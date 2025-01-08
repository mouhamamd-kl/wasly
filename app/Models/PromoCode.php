<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
     use HasFactory;
     public static function findOrFailWithResponse(int $id)
     {
          $promoCode = self::find($id);

          if (!$promoCode) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Promo Code Not Found')->throwResponse();
          }

          return $promoCode;
     }
}
