<?php
// app/Services/ProductService.php
namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Store;

class ProductService
{
    public function getProducts()
    {
        return Product::withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function getLatestProducts($limit = 10)
    {
        return Product::withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function getMostPopularProducts($limit = 10)
    {
        return Product::withAvg('ratings as average_rating', 'rating')
            ->with(['store', 'category'])
            ->withCount(['orderItems', 'reviews as reviews_count'])
            ->orderBy('order_items_count', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function getStoreProducts(Store $store)
    {
        return $store->products()
            ->withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->ByActive()
            ->latest()
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function getCategoryProducts(Category $category)
    {
        return $category->products()
            ->withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->latest()
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }

    public function searchProducts($filters)
    {
        return Product::withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->filterByStore($filters['store_id'] ?? null)
            ->filterByCategory($filters['category_id'] ?? null)
            ->filterByName($filters['name'] ?? null)
            ->filterByPriceRange($filters['min_price'] ?? null, $filters['max_price'] ?? null)
            ->sortByPrice($filters['sort'] ?? null)
            ->where('is_active', true)
            ->get()
            ->map(function ($product) {
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    }
}
