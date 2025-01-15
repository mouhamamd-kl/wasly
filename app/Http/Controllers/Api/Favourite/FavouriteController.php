<?php

namespace App\Http\Controllers\Api\Favourite;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    use AuthorizesRequests;
    public function addToFavourites(Request $request, $productId)
    {
        // Assuming customer is authenticated
        $customer = $request->user(); // Or fetch by ID if needed
        $product = Product::find($productId);

        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product Not Found');
        }
        // Attach the product to the customer's favourites (if not already added)
        if (!$customer->favouriteProducts->contains($product)) {
            $customer->favouriteProducts()->attach($product);
            return ApiResponse::sendResponse(200, 'Product added to favourites!');
        }

        return ApiResponse::sendResponse(400, 'Product is already in favourites.');
    }

    public function removeFromFavourites(Request $request, $productId)
    {
        // Assuming customer is authenticated
        $customer = $request->user(); // Or fetch by ID if needed
        $product = Product::find($productId);
        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product Not Found');
        }
        // Detach the product from the customer's favourites (if it exists)
        if ($customer->favouriteProducts->contains($product)) {
            $customer->favouriteProducts()->detach($product);
            return ApiResponse::sendResponse(200, 'Product removed from favourites.');
        }

        return ApiResponse::sendResponse(400, 'Product not found in favourites.');
    }

    public function getUserFavouriteProducts(Request $request)
    {
        // Assuming the customer is authenticated
        $customer = $request->user();

        // Retrieve the customer's favorite products
        $favouriteProducts = $customer->favouriteProducts();
        return $favouriteProducts;
        // Return the favorite products using the ApiResponse helper
    }
    public function getUserFavouriteProductsApi(Request $request)
    {


        // Retrieve the customer's favorite products query
        $favouriteProductsQuery = $this->getUserFavouriteProducts($request);
        // Paginate the query results
        $favouriteProducts = $favouriteProductsQuery->get(); // Adjust the per-page limit as needed
        // Return the favorite products using the ApiResponse helper
        return ApiResponse::sendResponse(200, 'sucess', ProductResource::collection($favouriteProducts));
    }


    public function clearUserFavouriteProducts(Request $request)
    {
        // Assuming the customer is authenticated
        $customer = $request->user();

        // Detach all favorite products
        $customer->favouriteProducts()->detach();

        // Return a success response
        return ApiResponse::sendResponse(200, 'All favourite products have been cleared.');
    }
}
