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

        // Query to get the cart products with their related models
        $cartProductsQuery = $customer->cartProducts()->with('category', 'store');

        // Get all cart products (not paginated)
        $cartProducts = $cartProductsQuery->get();

        // Calculate the total price of all cart products
        $total = $cartProducts->sum(function ($product) {
            return $product->price * $product->pivot->count;
        });

        // Return the cart products and total
        return compact('cartProductsQuery', 'total');
    }




    public function getCartUserProductsApi(Request $request)
    {
        // Call getCartUserProducts to retrieve the data
        $data = $this->getCartUserProducts($request);

        // Extract the variables
        $paginate = getPaginate($request);
        $cartProducts = $data['cartProductsQuery']->paginate($paginate);
        $total = $data['total'];
        $response = PaginationHelper::paginateResponse($cartProducts, CartProductResource::class, Cart::class);

        // Convert the response to an array for modification
        $responseArray = $response->getData(true);

        // Add the total to the response array
        $responseArray['data']['total'] = $total;

        // Return the modified response
        return ApiResponse::sendResponse(200, 'Cart products retrieved successfully', $responseArray);
    }
    public function clearCart(Request $request)
    {
        // Retrieve the authenticated customer
        $customer = $request->user();
        // Detach all products from the customer's cart
        $customer->cartProducts()->detach();
        return ApiResponse::sendResponse(200, 'Cart cleared successfully.');
    }
}
