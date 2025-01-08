<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
     use HasFactory;
     public static function findOrFailWithResponse(int $id)
     {
          $rating = self::find($id);

          if (!$rating) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Rating Not Found')->throwResponse();
          }

          return $rating;
     }
}
