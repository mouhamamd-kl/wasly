<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
     use HasFactory;
     protected $fillable = ['latitude', 'longitude', 'label', 'is_default'];

     public function customer()
     {
          return $this->belongsTo(Customer::class);
     }
     public static function findOrFailWithResponse(int $id)
     {
          $customerAddress = self::find($id);

          if (!$customerAddress) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Customer Address Not Found')->throwResponse();
          }
          return $customerAddress;
     }
}
