<?php
// app/Services/ReviewService.php
namespace App\Services;

use App\Models\Product;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Store;

class ReviewService
{
    public function addReview($customerId, Product $product, array $data)
    {
        $rating = Rating::find($data['rating_id']);
        if (!$rating) {
            throw new \Exception("Rating not found");
        }

        return Review::create([
            'customer_id' => $customerId,
            'product_id' => $product->id,
            'rating_id' => $rating->id,
            'description' => $data['description'],
        ]);
    }

    public function updateReview(Review $review, array $data)
    {
        $review->update([
            'rating_id' => $data['rating_id'],
            'description' => $data['description'],
        ]);

        return $review;
    }

    public function deleteReview(Review $review)
    {
        return $review->delete();
    }

    public function getProductReviews(Product $product)
    {
        return $product->reviews()
            ->with([
                'rating',
                'customer',
                'product' => fn ($query) => $query->withAvg('ratings as average_rating', 'rating')
                    ->with(['category', 'store'])
                    ->withCount(['reviews as reviews_count', 'orderItems']),
            ])
            ->get();
    }

    public function getStoreReviews(Store $store)
    {
        return $store->products()
            ->with(['reviews.rating', 'reviews.product', 'reviews.customer'])
            ->get()
            ->pluck('reviews')
            ->flatten();
    }
}
