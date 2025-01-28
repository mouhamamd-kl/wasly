<?php
namespace App\Http\Controllers\Api\Favourite;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\FavouriteService;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    private $favouriteService;

    public function __construct(FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
    }

    public function isFavourite(Request $request, $productId)
    {
        $customer = $request->user();

        if (!$customer) {
            return ApiResponse::sendResponse(401, 'Unauthorized');
        }

        $isFavourite = $this->favouriteService->isFavourite($customer->id, $productId);

        return ApiResponse::sendResponse(200, 'Favourite status retrieved successfully', [
            'isFavourite' => $isFavourite,
        ]);
    }

    public function addToFavourites(Request $request, $productId)
    {
        $customer = $request->user();
        $product = Product::findOrFail($productId);

        $added = $this->favouriteService->addToFavourites($customer, $product);

        if ($added) {
            return ApiResponse::sendResponse(200, 'Product added to favourites!');
        }

        return ApiResponse::sendResponse(400, 'Product is already in favourites.');
    }

    public function removeFromFavourites(Request $request, $productId)
    {
        $customer = $request->user();
        $product = Product::findOrFail($productId);

        $removed = $this->favouriteService->removeFromFavourites($customer, $product);

        if ($removed) {
            return ApiResponse::sendResponse(200, 'Product removed from favourites.');
        }

        return ApiResponse::sendResponse(400, 'Product not found in favourites.');
    }

    public function getUserFavouriteProductsApi(Request $request)
    {
        $customer = $request->user();

        $favouriteProducts = $this->favouriteService->getUserFavouriteProducts($customer);

        return ApiResponse::sendResponse(
            200,
            'Favourite products retrieved successfully',
            ProductResource::collection($favouriteProducts)
        );
    }

    public function clearUserFavouriteProducts(Request $request)
    {
        $customer = $request->user();

        $this->favouriteService->clearUserFavouriteProducts($customer);

        return ApiResponse::sendResponse(200, 'All favourite products have been cleared.');
    }
}
