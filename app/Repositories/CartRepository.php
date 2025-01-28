<?php
// app/Repositories/CartRepository.php
namespace App\Repositories;

use App\Models\Product;

class CartRepository
{
    public function findProductById($id)
    {
        return Product::findOrFail($id);
    }
}
