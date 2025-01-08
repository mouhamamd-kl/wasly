<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     use HasFactory;
     /**
      * Get the customer associated with the cart.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function customer()
     {
          return $this->belongsTo(Customer::class);
     }

     /**
      * Get the product associated with the cart.
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
      */
     public function product()
     {
          return $this->belongsTo(Product::class);
     }

}
