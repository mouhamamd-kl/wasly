<?php
// app/Repositories/ProductRepository.php
namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function findById($id)
    {
        return Product::withAvg('ratings as average_rating', 'rating')
            ->with(['category', 'store'])
            ->withCount(['reviews as reviews_count', 'orderItems'])
            ->find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
