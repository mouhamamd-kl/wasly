<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public static function findOrFailWithResponse(int $id)
    {
        $orderStatus = self::find($id);

        if (!$orderStatus) {
            // Return the custom API response
            ApiResponse::sendResponse(404, 'Order Status Not Found')->throwResponse();
        }
        return $orderStatus;
    }
}
