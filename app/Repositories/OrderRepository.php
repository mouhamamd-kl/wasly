<?php
// app/Repositories/OrderRepository.php
namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;

class OrderRepository
{
    public function findOrderItemById($id)
    {
        return OrderItem::findOrFail($id);
    }

    public function findOrderById($id)
    {
        return Order::findOrFail($id);
    }
}
