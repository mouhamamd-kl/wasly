<?php
// app/Http/Controllers/Api/Order/OrderController.php
namespace App\Http\Controllers\Api\Order;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderItemResource;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function createOrder(Request $request)
    {
        $customer = $request->user();
        $cartItems = $customer->cartProducts;

        if ($cartItems->isEmpty()) {
            return ApiResponse::sendResponse(400, 'No items in cart');
        }

        $validated = $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'customer_address_id' => 'required|exists:customer_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $orders = $this->orderService->createOrder(
            $customer,
            $cartItems,
            $validated['delivery_id'],
            $validated['customer_address_id'],
            $validated['payment_method_id']
        );

        return ApiResponse::sendResponse(200, 'Orders created successfully', $orders);
    }

    public function processPayment(Request $request, $orderItemId)
    {
        $validated = $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
            'card_id' => 'nullable|exists:cards,id',
        ]);

        $orderItem = $this->orderService->findOrderItemById($orderItemId);
        $payment = $this->orderService->processPayment($orderItem, $validated['payment_method_id'], $validated['card_id']);

        return ApiResponse::sendResponse(200, 'Payment initiated', $payment);
    }

    public function updateOrderItemStatus(Request $request, $orderItemId)
    {
        $validated = $request->validate(['status' => 'required|in:Accepted,Rejected,Cancelled']);
        $orderItem = $this->orderService->findOrderItemById($orderItemId);

        $this->orderService->updateOrderItemStatus($orderItem, $validated['status']);

        return ApiResponse::sendResponse(200, "Order item status updated to {$validated['status']}", $orderItem);
    }

    public function cancelOrderItem(Request $request, $orderItemId)
    {
        $orderItem = $this->orderService->findOrderItemById($orderItemId);

        if ($orderItem->order->customer_id !== $request->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized to cancel this order');
        }

        $this->orderService->updateOrderItemStatus($orderItem, 'Cancelled');

        return ApiResponse::sendResponse(200, 'Order item cancelled successfully', $orderItem);
    }
}
