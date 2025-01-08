<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
     use HasFactory;
     protected $guarded = ['id'];
     // protected $fillable = ['name', 'photo', 'latitude', 'longitude','store'];
     public function products()
     {
          return $this->hasMany(Product::class);
     }
     public function owner()
     {
          return $this->belongsTo(StoreOwner::class, 'owner_id');
     }


     public function orders()
     {
          return $this->hasMany(Order::class);
     }

     public function reviews()
     {
          return $this->hasManyThrough(Review::class, Product::class);
     }
     
     public static function getNearby($lat, $lng, $radius = 10, $limit = 10)
     {
          return static::select(DB::raw("*,
                 (6371 * acos(cos(radians($lat))
                     * cos(radians(latitude))
                     * cos(radians(longitude) - radians($lng))
                     + sin(radians($lat))
                     * sin(radians(latitude))))
                 AS distance"))
               ->having('distance', '<=', $radius)
               ->orderBy('distance')
               ->limit($limit);
          // ->get();
     }
     public static function getPopularByOrders()
     {
          return static::withCount('orders')
               ->orderBy('orders_count', 'desc');
          // ->limit($limit)
          // ->get();
     }

     public static function getPopularByRatings()
     {
          return static::join('products', 'stores.id', '=', 'products.store_id')
               ->join('reviews', 'products.id', '=', 'reviews.product_id')
               ->join('ratings', 'reviews.rating_id', '=', 'ratings.id')
               ->select(
                    'stores.*',
                    DB::raw('AVG(ratings.rating) as average_rating'), // Calculate average rating
                    DB::raw('COUNT(reviews.id) as review_count') // Count reviews
               )
               ->groupBy('stores.id') // Group by store ID
               ->orderBy('average_rating', 'desc') // Order by average rating
               ->orderBy('review_count', 'desc'); // Order by review count
     }
     public static function findOrFailWithResponse(int $id)
     {
          $store = self::find($id);

          if (!$store) {
               // Return the custom API response
               ApiResponse::sendResponse(404, 'Store Not Found')->throwResponse();
          }

          return $store;
     }
}
