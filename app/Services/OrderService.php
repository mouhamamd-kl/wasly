<?php
// app/Services/OrderService.php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Notification;

class OrderService
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    public function findOrderItemById($id)
    {
        return $this->orderRepository->findOrderItemById($id);
    }
    public function createOrder($customer, $cartItems, $deliveryId, $customerAddressId, $paymentMethodId)
    {
        $orderStatus = OrderStatus::where('name', 'Pending')->first();
        $paymentStatus = PaymentStatus::where('name', 'Pending')->first();
        $orders = [];

        // Group cart items by store
        $groupedByStore = $cartItems->groupBy(fn($cartItem) => $cartItem->product->store_id);

        foreach ($groupedByStore as $storeId => $storeCartItems) {
            // Create order
            $order = Order::create([
                'store_id' => $storeId,
                'customer_id' => $customer->id,
                'delivery_id' => $deliveryId,
                'order_status_id' => $orderStatus->id,
                'order_placed_at' => now(),
                'customer_address_id' => $customerAddressId,
            ]);

            // Create order items
            foreach ($storeCartItems as $cartItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product->id,
                    'quantity' => $cartItem->count,
                    'price' => $cartItem->product->price,
                    'order_status_id' => $orderStatus->id,
                ]);

                Payment::create([
                    'order_item_id' => $orderItem->id,
                    'payment_status_id' => $paymentStatus->id,
                    'payment_method_id' => $paymentMethodId,
                    'amount' => $orderItem->price,
                ]);
            }

            // Notify the store
            Notification::create([
                'store_id' => $storeId,
                'order_id' => $order->id,
                'status' => 'sent',
            ]);

            $orders[] = $order;
        }

        return $orders;
    }

    public function processPayment(OrderItem $orderItem, $paymentMethodId, $cardId = null)
    {
        $payment = new Payment();
        $payment->order_item_id = $orderItem->id;
        $payment->payment_status_id = PaymentStatus::where('name', 'Pending')->first()->id;
        $payment->payment_method_id = $paymentMethodId;
        $payment->amount = $orderItem->price;
        $payment->card_id = $cardId;
        $payment->save();

        return $payment;
    }

    public function updateOrderItemStatus(OrderItem $orderItem, $status)
    {
        $orderStatus = OrderStatus::where('name', ucfirst($status))->first();
        if (!$orderStatus) {
            throw new \Exception("Invalid status: $status");
        }

        $orderItem->order_status_id = $orderStatus->id;
        $orderItem->save();

        if ($status === 'Accepted') {
            $paymentStatus = PaymentStatus::where('name', 'Completed')->first();
            $orderItem->payment->update(['payment_status_id' => $paymentStatus->id]);

            Notification::create([
                'customer_id' => $orderItem->order->customer_id,
                'order_item_id' => $orderItem->id,
                'status' => 'Order Accepted',
            ]);
        } elseif (in_array($status, ['Rejected', 'Cancelled'])) {
            $paymentStatus = PaymentStatus::where('name', 'Cancelled')->first();
            $orderItem->payment->update(['payment_status_id' => $paymentStatus->id]);

            Notification::create([
                'customer_id' => $orderItem->order->customer_id,
                'order_item_id' => $orderItem->id,
                'status' => $status === 'Rejected' ? 'Order Rejected' : 'Order Cancelled',
            ]);
        }

        $this->updateOrderStatus($orderItem->order);
    }

    public function updateOrderStatus(Order $order)
    {
        $orderItems = $order->orderItems;

        $allAccepted = $orderItems->every(fn($item) => $item->order_status->name === 'Accepted');
        $allRejected = $orderItems->every(fn($item) => $item->order_status->name === 'Rejected');

        if ($allAccepted) {
            $order->order_status_id = OrderStatus::where('name', 'Accepted')->first()->id;
        } elseif ($allRejected) {
            $order->order_status_id = OrderStatus::where('name', 'Rejected')->first()->id;
        } else {
            $order->order_status_id = OrderStatus::where('name', 'Partially Accepted')->first()->id;
        }

        $order->save();
    }
}
