<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
     use HasFactory;
     public static function findOrFailWithResponse(int $id)
     {
         $paymentStatus = self::find($id);
 
         if (!$paymentStatus) {
             // Return the custom API response
             ApiResponse::sendResponse(404, 'Payment Status Not Found')->throwResponse();
         }
         return $paymentStatus;
     }
}
