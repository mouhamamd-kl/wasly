<?php
// app/Repositories/CategoryRepository.php
namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function findById($id)
    {
        return Category::findOrFail($id);
    }
}
