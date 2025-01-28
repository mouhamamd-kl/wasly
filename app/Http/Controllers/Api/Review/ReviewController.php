<?php
namespace App\Http\Controllers\Api\Review;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Models\Store;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    private $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function addReview(Request $request, $productId)
    {
        $validatedData = $request->validate([
            'rating_id' => 'required|exists:ratings,id',
            'description' => 'required|string|max:255',
        ]);

        $customer = $request->user();
        $product = Product::findOrFail($productId);

        $review = $this->reviewService->addReview($customer->id, $product, $validatedData);

        return ApiResponse::sendResponse(201, 'Review added successfully.', new ReviewResource($review));
    }

    public function updateReview(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rating_id' => 'required|numeric|min:1|max:5',
            'description' => 'required|string|max:255',
        ]);

        $customer = $request->user();
        $review = Review::findOrFail($id);

        if ($review->customer_id !== $customer->id) {
            return ApiResponse::sendResponse(403, 'You are not authorized to update this review.');
        }

        $updatedReview = $this->reviewService->updateReview($review, $validatedData);

        return ApiResponse::sendResponse(200, 'Review updated successfully.', new ReviewResource($updatedReview));
    }

    public function removeReview(Request $request, $id)
    {
        $customer = $request->user();
        $review = Review::findOrFail($id);

        if ($review->customer_id !== $customer->id) {
            return ApiResponse::sendResponse(403, 'You are not authorized to delete this review.');
        }

        $this->reviewService->deleteReview($review);

        return ApiResponse::sendResponse(200, 'Review deleted successfully.');
    }

    public function getProductReviewsApi(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $reviews = $this->reviewService->getProductReviews($product);

        return ApiResponse::sendResponse(
            200,
            'Product reviews retrieved successfully',
            ReviewResource::collection($reviews)
        );
    }

    public function getStoreReviews(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        $reviews = $this->reviewService->getStoreReviews($store);

        return ApiResponse::sendResponse(
            200,
            'Store reviews retrieved successfully',
            ReviewResource::collection($reviews)
        );
    }
}
