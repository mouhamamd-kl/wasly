<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     use HasFactory;
     /**
      * Get the order associated with the payment.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function orderItem()
     {
          return $this->belongsTo(OrderItem::class);
     }

     /**
      * Get the payment status associated with the payment.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function paymentStatus()
     {
          return $this->belongsTo(PaymentStatus::class);
     }

     /**
      * Get the payment method associated with the payment.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function paymentMethod()
     {
          return $this->belongsTo(PaymentMethod::class);
     }
     public static function findOrFailWithResponse(int $id)
     {
          $payment = self::find($id);

          if (!$payment) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Payment Not Found')->throwResponse();
          }
          return $payment;
     }
}
