<?php

namespace App\Http\Controllers\Api\Order;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\Cart\CartSaveRequest;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Requests\Api\Product\ProductUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CartProductResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderItemResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Ad;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\PaymentStatus;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class OrderController extends Controller
{
    use AuthorizesRequests;
    public function createOrder(Request $request)
    {
        $customer = $request->user(); // Get the currently logged-in customer
        $cartItems = $customer->cartProducts; // Get cart products

        // Get the 'Pending' status from the OrderStatus table
        $orderStatus = OrderStatus::where('name', 'Pending')->first(); // Default status: Pending

        // Initialize an array to hold order details
        $orders = [];

        // Group cart items by store (get store_id from the related Product model)
        $groupedByStore = $cartItems->groupBy(function ($cartItem) {
            return $cartItem->product->store_id; // Group by store_id from the product
        });

        // Loop through each store and create separate orders
        foreach ($groupedByStore as $storeId => $storeCartItems) {
            // Create the order for this store
            $order = Order::create([
                'store_id' => $storeId,
                'customer_id' => $customer->id,
                'delivery_id' => $request->delivery_id,
                'order_status_id' => $orderStatus->id,
                'order_placed_at' => now(),
                'order_delivered_at' => null, // Set to null initially
            ]);
            $paymentStatus = PaymentStatus::where('name', 'Pending')->first();

            // Create order items for this store
            foreach ($storeCartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id, // Use the product relationship
                    'quantity' => $cartItem->count, // Get the count from cart item
                    'price' => $cartItem->product->price, // Use the product's price
                    'order_status_id' => $orderStatus->id,
                ]);
                $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id); // Assuming a payment method is provided
                Payment::create([
                    'order_item_id' => $order->id,
                    'payment_status_id' => $paymentStatus->id,
                    'payment_method_id' => $paymentMethod->id,
                    'amount' => $order->total_amount, // Assuming total_amount is calculated
                ]);
            }

            // Create the payment with "Pending" status for the order


            // Send notification to the store about the new order
            Notification::create([
                'store_id' => $storeId,
                'order_id' => $order->id,
                'status' => 'sent', // Notification is sent to the store
            ]);

            // Add the order to the orders array
            $orders[] = $order;
        }

        return response()->json([
            'message' => 'Orders created successfully and notifications sent to stores',
            'orders' => $orders,
        ]);
    }
    public function processPayment(Request $request, $orderItemId)
    {
        $orderItem = OrderItem::findOrFail($orderItemId);

        $payment = new Payment();
        $payment->order_item_id = $orderItem->id;
        $payment->payment_status_id = PaymentStatus::where('name', 'Pending')->first()->id;
        $payment->payment_method_id = $request->payment_method_id;
        $payment->amount = $orderItem->price; // Assuming `price` exists
        $payment->card_id = $request->card_id;

        $payment->save();

        return ApiResponse::sendResponse(200, 'Payment initiated', $payment);
    }
    public function getUserItemOrders(Request $request)
    {
        $customer = $request->user();
        $orderItems = OrderItem::whereHas('order', function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
        })->get();
        return ApiResponse::sendResponse(200, 'success', OrderItemResource::collection($orderItems));
    }
    public function updateOrderItemStatus(Request $request, $orderItemId)
    {
        $orderItem = OrderItem::findOrFail($orderItemId);

        $status = $request->status; // e.g., Accepted, Rejected, Cancelled
        $orderStatus = OrderStatus::where('name', ucfirst($status))->first();

        if (!$orderStatus) {
            return ApiResponse::sendResponse(400, 'Invalid item status', null);
        }

        // Update OrderItem status
        $orderItem->order_status_id = $orderStatus->id;
        $orderItem->save();

        // Handle specific status actions
        if ($status === 'Accepted') {
            // Update payment to 'Completed'
            $paymentStatus = PaymentStatus::where('name', 'Completed')->first();
            $payment = $orderItem->payment;
            $payment->payment_status_id = $paymentStatus->id;
            $payment->save();

            // Notify the customer
            Notification::create([
                'customer_id' => $orderItem->order->customer_id,
                'order_item_id' => $orderItem->id,
                'status' => 'Order Accepted',
            ]);
        } else if ($status === 'Rejected' || $status === 'Cancelled') {
            // Update payment to 'Cancelled'
            $paymentStatus = PaymentStatus::where('name', 'Cancelled')->first();
            $payment = $orderItem->payment;
            $payment->payment_status_id = $paymentStatus->id;
            $payment->save();

            // Notify the customer
            Notification::create([
                'customer_id' => $orderItem->order->customer_id,
                'order_item_id' => $orderItem->id,
                'status' => $status === 'Rejected' ? 'Order Rejected' : 'Order Cancelled',
            ]);
        }

        // Recalculate Order Status
        $order = $orderItem->order;
        $this->updateOrderStatus($order);

        return ApiResponse::sendResponse(200, "Order item status updated to $status", [
            'order_item' => $orderItem,
            'order' => $order,
        ]);
    }


    private function updateOrderStatus(Order $order)
    {
        $orderItems = $order->orderItems;

        $acceptedStatusId = OrderStatus::where('name', 'Accepted')->first()->id;
        $rejectedStatusId = OrderStatus::where('name', 'Rejected')->first()->id;
        // $cancelledStatusId = OrderStatus::where('name', 'Cancelled')->first()->id;

        $allAccepted = $orderItems->every(fn($item) => $item->order_status_id === $acceptedStatusId);
        $allRejected = $orderItems->every(fn($item) => $item->order_status_id === $rejectedStatusId);
        // $allCancelled = $orderItems->every(fn($item) => $item->order_status_id === $cancelledStatusId);

        if ($allAccepted) {
            $order->order_status_id = $acceptedStatusId;
        } elseif ($allRejected) {
            $order->order_status_id = $rejectedStatusId;
        }
        //  else if ($allCancelled) {
        //     $order->order_status_id = $cancelledStatusId;
        // }
        else {
            $order->order_status_id = OrderStatus::where('name', 'Partially Accepted')->first()->id;
        }

        $order->save();
    }
    public function cancelOrderItem(Request $request, $orderItemId)
    {
        $orderItem = OrderItem::findOrFail($orderItemId);

        // Verify the customer owns this order
        if ($orderItem->order->customer_id !== $request->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized to cancel this order', null);
        }

        // Only allow cancellation of pending orders
        if ($orderItem->orderStatus->name !== 'Pending') {
            return ApiResponse::sendResponse(400, 'Can only cancel pending orders', null);
        }

        $cancelledStatus = OrderStatus::where('name', 'Cancelled')->first();
        $orderItem->order_status_id = $cancelledStatus->id;
        $orderItem->save();

        // Update payment status
        $payment = $orderItem->payment;
        $payment->payment_status_id = PaymentStatus::where('name', 'Cancelled')->first()->id;
        $payment->save();

        // Recalculate overall order status
        $this->updateOrderStatus($orderItem->order);

        return ApiResponse::sendResponse(200, 'Order item cancelled successfully', $orderItem);
    }
    // public function updateOrderStatus(Request $request, $orderId)
    // {
    //     $order = Order::findOrFail($orderId);
    //     $status = $request->status; // The new status: accepted, rejected, cancelled

    //     // Fetch the status from the OrderStatus table
    //     $orderStatus = OrderStatus::where('name', ucfirst($status))->first();

    //     if (!$orderStatus) {
    //         return response()->json(['message' => 'Invalid order status'], 400);
    //     }

    //     // Update order status
    //     $order->order_status_id = $orderStatus->id;
    //     $order->save();

    //     // Fetch the payment for the order
    //     $payment = Payment::where('order_id', $order->id)->first();

    //     // Handle accepted/rejected statuses
    //     if ($status == 'accepted') {
    //         // Update the payment status to "Completed" if the order is accepted
    //         $paymentStatus = PaymentStatus::where('name', 'Completed')->first();
    //         $payment->payment_status_id = $paymentStatus->id;
    //         $payment->save();

    //         // Optionally, send notification to the customer about successful payment processing
    //     } elseif ($status == 'rejected' || $status == 'cancelled') {
    //         // Update the payment status to "Cancelled" if the order is rejected or cancelled
    //         $paymentStatus = PaymentStatus::where('name', 'Cancelled')->first();
    //         $payment->payment_status_id = $paymentStatus->id;
    //         $payment->save();

    //         // Optionally, send notification to the customer about order cancellation
    //         $this->sendCancellationNotificationToCustomer($order);
    //     }

    //     return response()->json([
    //         'message' => "Order status updated to $status",
    //         'order' => $order,
    //     ]);
    // }


    protected function sendCancellationNotificationToCustomer(Order $order)
    {
        // Send notification to the customer (you can use email or SMS here)
        // Assuming the customer has an email for the notification
        $customer = $order->customer;

        // You can customize this message or send an email/SMS
        // Mail::to($customer->email)->send(new OrderCancelledMail($order));
    }
}
