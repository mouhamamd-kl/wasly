<?php
// app/Services/CartService.php
namespace App\Services;

use App\Models\Product;

class CartService
{
    public function addToCart($customer, $productId, $count)
    {
        $product = Product::findOrFail($productId);

        if ($product->stock_quantity < $count) {
            throw new \Exception('Insufficient stock for the requested quantity.');
        }

        $productInCart = $customer->cartProducts()->where('product_id', $productId)->first();

        if ($productInCart) {
            $newCount = $productInCart->pivot->count + $count;

            if ($product->stock_quantity < $newCount) {
                throw new \Exception('Insufficient stock for the total quantity in the cart.');
            }

            $customer->cartProducts()->updateExistingPivot($productId, [
                'count' => $newCount,
            ]);
        } else {
            $customer->cartProducts()->attach($productId, [
                'count' => $count,
            ]);
        }
    }

    public function removeFromCart($customer, $productId)
    {
        $productInCart = $customer->cartProducts()->where('product_id', $productId)->first();

        if (!$productInCart) {
            throw new \Exception('Product not found in your cart.');
        }

        $customer->cartProducts()->detach($productId);
    }

    public function getCartProducts($customer)
    {
        $cartProducts = $customer->cartProducts()
            ->withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count'])
            ->get();

        $total = $cartProducts->sum(fn($product) => $product->price * $product->pivot->count);

        return [
            'cartProducts' => $cartProducts,
            'total' => $total,
        ];
    }

    public function clearCart($customer)
    {
        $customer->cartProducts()->detach();
    }
}
