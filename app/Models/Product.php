<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     use HasFactory;
     protected $guarded = ['id'];
     protected $hidden = ['pivot'];
     public function store()
     {
          return $this->belongsTo(Store::class);
     }
     public function category()
     {
          return $this->belongsTo(Category::class);
     }
     public function reviews()
     {
          return $this->hasMany(Review::class);
     }

     public function ratings()
     {
          return $this->hasManyThrough(Rating::class, Review::class, 'product_id', 'id', 'id', 'rating_id');
     }

     // Define the relationship with customers through the cart pivot table
     public function customers()
     {
          return $this->belongsToMany(Customer::class, 'carts')
               ->withPivot('count')
               ->withTimestamps();
     }
     public function orderItems()
     {
          return $this->hasMany(OrderItem::class);
     }
     // In Product model
     public function customersWhoFavourited()
     {
          return $this->belongsToMany(Customer::class, 'favourite_products')
               ->withTimestamps();
     }

     public function scopeFilterByCategory($query, $categoryId)
     {
          return $query->when($categoryId, function ($q) use ($categoryId) {
               $q->where('category_id', $categoryId);
          });
     }
     public function scopeFilterByStore($query, $storeId)
     {
          return $query->when($storeId, function ($q) use ($storeId) {
               $q->where('store_id', $storeId);
          });
     }
     public function scopeFilterByName($query, $name)
     {
          return $query->when($name, function ($q) use ($name) {
               $q->where('name', 'LIKE', '%' . $name . '%');
          });
     }

     public function scopeFilterByPriceRange($query, $minPrice, $maxPrice)
     {
          return $query->when($minPrice && $maxPrice, function ($q) use ($minPrice, $maxPrice) {
               $q->whereBetween('price', [$minPrice, $maxPrice]);
          });
     }

     public function scopeSortByPrice($query, $sort)
     {
          return $query->when($sort, function ($q) use ($sort) {
               if ($sort === 'high_to_low') {
                    $q->orderBy('price', 'desc');
               } elseif ($sort === 'low_to_high') {
                    $q->orderBy('price', 'asc');
               }
          });
     }
     public function scopeFilterByActive($query, $name)
     {
          return $query->when($name, function ($q) use ($name) {
               $q->where('is_active', true);
          });
     }
     /**
      * Find a product by ID or return a custom response.
      *
      * @param int $id
      * @return self
      */
     public static function findOrFailWithResponse(int $id)
     {
          $product = self::find($id);

          if (!$product) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Product Not Found')->throwResponse();
          }

          return $product;
     }
}
