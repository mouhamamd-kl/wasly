<?php
// app/Services/FavouriteService.php
namespace App\Services;

use App\Models\Product;

class FavouriteService
{
    public function isFavourite($customerId, $productId)
    {
        return Product::whereHas('favouriteCustomers', function ($query) use ($customerId, $productId) {
            $query->where('customer_id', $customerId)->where('product_id', $productId);
        })->exists();
    }

    public function addToFavourites($customer, Product $product)
    {
        if (!$customer->favouriteProducts->contains($product)) {
            $customer->favouriteProducts()->attach($product);
            return true;
        }
        return false;
    }

    public function removeFromFavourites($customer, Product $product)
    {
        if ($customer->favouriteProducts->contains($product)) {
            $customer->favouriteProducts()->detach($product);
            return true;
        }
        return false;
    }

    public function getUserFavouriteProducts($customer)
    {
        return $customer->favouriteProducts()
            ->withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function clearUserFavouriteProducts($customer)
    {
        $customer->favouriteProducts()->detach();
    }
}
