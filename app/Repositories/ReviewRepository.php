<?php
// app/Repositories/ReviewRepository.php
namespace App\Repositories;

use App\Models\Product;
use App\Models\Review;

class ReviewRepository
{
    public function findReviewById($id)
    {
        return Review::find($id);
    }

    public function getReviewsForProduct(Product $product)
    {
        return $product->reviews()->with(['rating', 'customer'])->get();
    }
}
