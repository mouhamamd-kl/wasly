<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryStatus extends Model
{
     use HasFactory;
     public static function findOrFailWithResponse(int $id)
     {
          $deliveryStatus = self::find($id);

          if (!$deliveryStatus) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'DeliveryStatus Found')->throwResponse();
          }
          return $deliveryStatus;
     }
}
