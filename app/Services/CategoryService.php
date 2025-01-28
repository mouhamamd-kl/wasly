<?php
// app/Services/CategoryService.php
namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public function getAllCategories()
    {
        return Category::latest()->get();
    }

    public function paginateCategories($perPage)
    {
        return Category::latest()->paginate($perPage);
    }

    public function createCategory(array $validatedData)
    {
        return Category::create($validatedData);
    }

    public function updateCategory(Category $category, array $validatedData)
    {
        $category->update($validatedData);
        return $category;
    }

    public function deleteCategory(Category $category)
    {
        $category->delete();
    }
}
