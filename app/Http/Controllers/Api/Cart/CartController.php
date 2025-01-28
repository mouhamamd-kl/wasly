<?php
// app/Http/Controllers/Api/Cart/CartController.php
namespace App\Http\Controllers\Api\Cart;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Cart\CartSaveRequest;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function addToCart(CartSaveRequest $request)
    {
        $customer = $request->user();
        $validated = $request->validated();

        try {
            $this->cartService->addToCart($customer, $validated['product_id'], $validated['count']);
            return ApiResponse::sendResponse(200, 'Product added to cart successfully.');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(400, $e->getMessage());
        }
    }

    public function removeFromCart(Request $request, $productId)
    {
        $customer = $request->user();

        try {
            $this->cartService->removeFromCart($customer, $productId);
            return ApiResponse::sendResponse(200, 'Product removed from cart successfully.');
        } catch (\Exception $e) {
            return ApiResponse::sendResponse(400, $e->getMessage());
        }
    }

    public function getCartUserProducts(Request $request)
    {
        $customer = $request->user();

        $cartDetails = $this->cartService->getCartProducts($customer);

        $transformedCartProducts = $cartDetails['cartProducts']->map(function ($product) {
            return [
                "product" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "photo" => $product->photo,
                    "description" => $product->description,
                    "stock_quantity" => $product->stock_quantity,
                    "price" => $product->price,
                    "is_active" => $product->is_active,
                    "created_at" => $product->created_at,
                    "updated_at" => $product->updated_at,
                    "average_rating" => $product->average_rating,
                    "reviews_count" => $product->reviews_count,
                    "category" => $product->category,
                    "store" => $product->store,
                ],
                "count" => $product->pivot->count,
                "subtotal" => $product->price * $product->pivot->count,
            ];
        });

        return ApiResponse::sendResponse(
            200,
            'Cart products retrieved successfully',
            [
                'cartProducts' => $transformedCartProducts,
                'total' => $cartDetails['total'],
            ]
        );
    }

    public function clearCart(Request $request)
    {
        $customer = $request->user();

        $this->cartService->clearCart($customer);

        return ApiResponse::sendResponse(200, 'Cart cleared successfully.');
    }
}
