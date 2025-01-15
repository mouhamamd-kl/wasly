<?php

namespace App\Http\Controllers\Api\Review;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Review;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use AuthorizesRequests;
    public function addReview(Request $request, $productId)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rating_id' => 'required|exists:ratings,id',
            'description' => 'required|string|max:255',
        ]);

        // Retrieve the authenticated customer
        $customer =  $request->user();

        // Check if the product exists
        $product = Product::findOrFail($productId);

        // Create or retrieve the rating
        $rating = Rating::first(['rating' => $validatedData['rating']]);

        // Create the review
        $review = Review::create([
            'customer_id' => $customer->id,
            'product_id' => $product->id,
            'rating_id' => $rating->id,
            'description' => $validatedData['description'],
        ]);

        // Return a success response
        return ApiResponse::sendResponse(201, 'Review added successfully.', $review);
    }
    public function updateReview(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'rating_id' => 'required|numeric|min:1|max:5',
            'description' => 'required|string|max:255',
        ]);

        // Retrieve the authenticated customer
        $customer = $request->user();

        // Find the review by ID
        $review = Review::find($id);

        // Check if the review exists
        if (!$review) {
            return ApiResponse::sendResponse(404, 'Review not found.');
        }

        // Check if the authenticated customer is the owner of the review
        if ($review->customer_id !== $customer->id) {
            return ApiResponse::sendResponse(403, 'You are not authorized to update this review.');
        }

        // Update the review with the validated data
        $review->update([
            'rating' => $validatedData['rating'],
            'description' => $validatedData['description'],
        ]);

        // Return a success response with the updated review
        return ApiResponse::sendResponse(200, 'Review updated successfully.', $review);
    }
    public function removeReview(Request $request, $id)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();

        // Find the review by ID
        $review = Review::find($id);

        // Check if the review exists
        if (!$review) {
            return ApiResponse::sendResponse(404, 'Review not found.');
        }

        // Check if the authenticated customer is the owner of the review
        if ($review->customer_id !== $customer->id) {
            return ApiResponse::sendResponse(403, 'You are not authorized to delete this review.');
        }

        // Delete the review
        $review->delete();

        // Return a success response
        return ApiResponse::sendResponse(200, 'Review deleted successfully.');
    }


    public function getProductReviewsApi(Request $request, $productId)
    {
        // Ensure the product exists
        $product = Product::findOrFail($productId);

        // Retrieve all reviews for the product with necessary relationships
        $reviews = $product->reviews()
            ->with(['rating', 'product', 'customer']) // Eager load relationships
            ->get();

        // Return the reviews using the ReviewResource
        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Product reviews retrieved successfully',
            data: ReviewResource::collection($reviews)
        );
    }

    public function getStoreReviews(Request $request, $storeId)
    {
        // Ensure the store exists
        $store = Store::findOrFail($storeId);

        // Retrieve all reviews for the store's products
        $reviews = $store->products()
            ->with(['reviews.rating', 'reviews.product', 'reviews.customer']) // Eager load relationships
            ->get()
            ->pluck('reviews') // Extract reviews from each product
            ->flatten(); // Flatten the collection of reviews

        // Return the reviews using the ReviewResource
        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Store reviews retrieved successfully',
            data: ReviewResource::collection($reviews)
        );
    }




    // public function getProductReviews(Request $request, $productId)
    // {
    //     // Ensure the product exists
    //     $product = Product::findOrFail($productId);
    //     // Retrieve reviews with related models
    //     $reviews = $product->reviews()
    //         ->with(['rating', 'product', 'customer']);

    //     // Return the reviews using the ReviewResource collection
    //     return $reviews;
    // }
    // public function getProductReviewsApi(Request $request, $productId)
    // {
    //     $paginate = getPaginate($request);
    //     $reviews = $this->getProductReviews(request: $request, productId: $productId)->paginate($paginate);
    //     return PaginationHelper::paginateResponse(originData: $reviews, resourceClass: ReviewResource::class, modelClass: Review::class);
    // }
    // public function getStoreReviews(Request $request, $storeId)
    // {
    //     // Ensure the store exists
    //     $store = Store::findOrFail($storeId);

    //     // Retrieve reviews related to products of the store with related models
    //     $reviews = $store->products()
    //         ->with('reviews.rating', 'reviews.product', 'reviews.customer') // Eager load reviews and related data
    //         ->get()
    //         ->pluck('reviews') // Extract reviews from products
    //         ->flatten(); // Flatten the reviews collection

    //     // Return the reviews using a suitable resource collection
    //     return $reviews;
    // }
    // public function getStoreReviewsApi(Request $request, $storeId)
    // {

    //     $paginate = getPaginate($request);
    //     $reviews = $this->getStoreReviews(request: $request, storeId: $storeId)->paginate($paginate);
    //     return PaginationHelper::paginateResponse(originData: $reviews, resourceClass: ReviewResource::class, modelClass: Review::class);
    // }
}
