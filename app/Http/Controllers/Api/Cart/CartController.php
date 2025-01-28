<?php

namespace App\Http\Controllers\Api\Cart;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\Cart\CartSaveRequest;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Requests\Api\Product\ProductUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CartProductResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Ad;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class CartController extends Controller
{
    use AuthorizesRequests;
    public function addToCart(CartSaveRequest $request)
    {
        // Validate the request
        $validated = $request->validated();

        // Retrieve the authenticated customer
        $customer = $request->user();

        // Retrieve the product
        $product = Product::findOrFailWithResponse($validated['product_id']);

        // Check if there's enough stock
        if ($product->stock_quantity < $validated['count']) {
            return ApiResponse::sendResponse(400, 'Insufficient stock for the requested quantity.');
        }

        // Check if the product is already in the cart
        $productInCart = $customer->cartProducts()->where('product_id', $validated['product_id'])->first();

        if ($productInCart) {
            // Update the count if the product is already in the cart
            $newCount = $productInCart->pivot->count + $validated['count'];

            // Check again to ensure stock is sufficient for the updated count
            if ($product->stock_quantity < $newCount) {
                return ApiResponse::sendResponse(400, 'Insufficient stock for the total quantity in the cart.');
            }

            $customer->cartProducts()->updateExistingPivot($validated['product_id'], [
                'count' => $newCount,
            ]);
        } else {
            // Add the product to the cart with the specified count
            $customer->cartProducts()->attach($validated['product_id'], [
                'count' => $validated['count'],
            ]);
        }

        return ApiResponse::sendResponse(200, 'Product added to cart successfully.');
    }


    // public function removeFromCart(Request $request)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'product_id' => 'required|exists:products,id',
    //     ]);

    //     // Retrieve the authenticated customer
    //     $customer = $request->user();

    //     // Check if the product exists in the customer's cart
    //     $productInCart = $customer->cartProducts()->where('product_id', $validated['product_id'])->first();

    //     if (!$productInCart) {
    //         return ApiResponse::sendResponse(404, 'Product not found in your cart.');
    //     }

    //     // Detach the product from the cart
    //     $customer->cartProducts()->detach($validated['product_id']);

    //     return ApiResponse::sendResponse(200, 'Product removed from cart successfully.');
    // }
    public function removeFromCart(Request $request, $product_id)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();

        // Check if the product exists in the customer's cart
        $productInCart = $customer->cartProducts()->where('product_id', $product_id)->first();

        if (!$productInCart) {
            return ApiResponse::sendResponse(404, 'Product not found in your cart.');
        }

        // Detach the product from the cart
        $customer->cartProducts()->detach($product_id);

        return ApiResponse::sendResponse(200, 'Product removed from cart successfully.');
    }
    public function getCartUserProducts(Request $request)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();
    
        // Query to get the cart products with related models and computed fields
        $cartProductsQuery = $customer->cartProducts()
            ->withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount(['reviews as reviews_count']); // Count related reviews
    
        // Get all cart products
        $cartProducts = $cartProductsQuery->get();
    
        // Calculate the total price of all cart products
        $total = $cartProducts->sum(function ($product) {
            return $product->price * $product->pivot->count;
        });
    
        // Transform the cart products to the desired structure
        $transformedCartProducts = $cartProducts->map(function ($product) {
            return [
                "product_cart" => [
                    "product" => [
                        "id" => $product->id,
                        "name" => $product->name,
                        "photo" => $product->photo,
                        "description" => $product->description,
                        "stock_quantity" => $product->stock_quantity,
                        "price" => $product->price,
                        "is_active" => $product->is_active,
                        "category_id" => $product->category_id,
                        "store_id" => $product->store_id,
                        "created_at" => $product->created_at,
                        "updated_at" => $product->updated_at,
                        "average_rating" => $product->average_rating,
                        "reviews_count" => $product->reviews_count,
                        "category" => $product->category,
                        "store" => $product->store,
                    ],
                    "count" => $product->pivot->count,
                    "subtotal" => $product->price * $product->pivot->count,
                ],
            ];
        });
    
        // Return the transformed response using ApiResponse
        return ApiResponse::sendResponse(
            200,
            'Cart products retrieved successfully',
            [
                'cartProducts' => $transformedCartProducts,
                'total' => $total,
            ]
        );
    }
    
    // public function getCartUserProducts(Request $request)
    // {
    //     // Retrieve the authenticated customer
    //     $customer = $request->user();
    
    //     // Query to get the cart products with related models and computed fields
    //     $cartProductsQuery = $customer->cartProducts()
    //     ->withAvg('ratings as average_rating', 'rating') // Include average rating
    //     ->with(['category', 'store']) // Include related models
    //     ->withCount(['reviews as reviews_count']); // Count related reviews
    
    
    //     // Get all cart products (not paginated)
    //     $cartProducts = $cartProductsQuery->get();
    
    //     // Calculate the total price of all cart products
    //     $total = $cartProducts->sum(function ($product) {
    //         return $product->price * $product->pivot->count;
    //     });
    
    //     // Return the cart products and total
    //     return [
    //         'cartProducts' => $cartProducts,
    //         'total' => $total,
    //     ];
    // }
    
    // public function getCartUserProductsApi(Request $request)
    // {
    //     // Retrieve the data using getCartUserProducts
    //     $data = $this->getCartUserProducts($request);
    
    //     // Extract the variables
    //     $cartProducts = $data['cartProducts'];
    //     $total = $data['total'];
    
    //     // Build the response structure
    //     $responseArray = [
    //         'products' => CartProductResource::collection($cartProducts), // Use CartProductResource for product details
    //         'summary' => [
    //             'total' => $total, // Include total price in the summary
    //         ],
    //     ];
    
    //     // Return the API response
    //     return ApiResponse::sendResponse(200, 'Cart products retrieved successfully', $responseArray);
    // }
    



    // public function getCartUserProductsApi(Request $request)
    // {
    //     // Call getCartUserProducts to retrieve the data
    //     $data = $this->getCartUserProducts($request);

    //     // Extract the variables
    //     $cartProducts = $data['cartProductsQuery']->get();
    //     $total = $data['total'];

    //     // Convert the response to an array for modification
    //     $responseArray['product'] = $cartProducts;

    //     // Add the total to the response array
    //     $responseArray['data']['total'] = $total;

    //     // Return the modified response
    //     return ApiResponse::sendResponse(200, 'Cart products retrieved successfully', $responseArray);
    // }

    public function clearCart(Request $request)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();
        // Detach all products from the customer's cart
        $customer->cartProducts()->detach();
        return ApiResponse::sendResponse(200, 'Cart cleared successfully.');
    }
}
