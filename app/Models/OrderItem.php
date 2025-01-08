<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
     use HasFactory;

     /**
      * Get the order that owns the item.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function status()
     {
          return $this->belongsTo(OrderStatus::class, 'order_status_id');
     }
     public function order()
     {
          return $this->belongsTo(Order::class);
     }
     public function payment()
     {
          return $this->hasOne(Payment::class);
     }
     /**
      * Get the product associated with the item.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function product()
     {
          return $this->belongsTo(Product::class);
     }
     public static function findOrFailWithResponse(int $id)
     {
          $orderItem = self::find($id);

          if (!$orderItem) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Order Item Not Found')->throwResponse();
          }
          return $orderItem;
     }
}
