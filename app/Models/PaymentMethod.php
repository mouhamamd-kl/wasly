<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
     use HasFactory;
     public static function findOrFailWithResponse(int $id)
     {
          $paymentMethod = self::find($id);

          if (!$paymentMethod) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Payment Method Not Found')->throwResponse();
          }
          return $paymentMethod;
     }
}
