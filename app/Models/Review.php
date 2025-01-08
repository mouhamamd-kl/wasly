<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
     use HasFactory;
     public function customer()
     {
          return $this->belongsTo(Customer::class);
     }

     public function product()
     {
          return $this->belongsTo(Product::class);
     }

     public function rating()
     {
          return $this->belongsTo(Rating::class);
     }
     public static function findOrFailWithResponse(int $id)
     {
          $review = self::find($id);

          if (!$review) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Review Not Found')->throwResponse();
          }

          return $review;
     }
}
