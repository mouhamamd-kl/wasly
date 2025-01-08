<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     use HasFactory;
     /**
      * Get the customer who placed the order.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function store()
     {
          return $this->belongsTo(Store::class);
     }
     public function items()
     {
          return $this->hasMany(OrderItem::class);
     }
     public function customer()
     {
          return $this->belongsTo(Customer::class);
     }

     /**
      * Get the delivery information for the order.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function delivery()
     {
          return $this->belongsTo(Delivery::class);
     }

     /**
      * Get the status of the order.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function status()
     {
          return $this->belongsTo(OrderStatus::class, 'order_status_id');
     }
     public static function findOrFailWithResponse(int $id)
     {
          $order = self::find($id);

          if (!$order) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Order Not Found')->throwResponse();
          }
          return $order;
     }
}
