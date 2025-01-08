<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;
     public function products()
     {
          return $this->hasMany(Product::class);
     }
     public static function findOrFailWithResponse(int $id)
     {
          $category = self::find($id);

          if (!$category) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Category Not Found')->throwResponse();
          }
          return $category;
     }
}
