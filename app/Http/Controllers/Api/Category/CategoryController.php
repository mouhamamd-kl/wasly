<?php
// app/Http/Controllers/Api/Category/CategoryController.php
namespace App\Http\Controllers\Api\Category;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategorySaveRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private $categoryService;
    use AuthorizesRequests;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function indexApi(Request $request)
    {
        $categories = $this->categoryService->getAllCategories();
        return ApiResponse::sendResponse(200, 'Categories retrieved successfully', CategoryResource::collection($categories));
    }

    public function indexPaginateApi(Request $request)
    {
        $perPage = getPaginate($request);
        $categories = $this->categoryService->paginateCategories($perPage);
        return PaginationHelper::paginateResponse($categories, CategoryResource::class, Category::class);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return ApiResponse::sendResponse(200, 'Category retrieved successfully', new CategoryResource($category));
    }

    public function create(CategorySaveRequest $request)
    {
        $this->authorize('create', Category::class);

        $validatedData = $request->validated();
        $category = $this->categoryService->createCategory($validatedData);

        return ApiResponse::sendResponse(201, 'Category created successfully', new CategoryResource($category));
    }

    public function update(CategorySaveRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('update', $category);

        $validatedData = $request->validated();
        $updatedCategory = $this->categoryService->updateCategory($category, $validatedData);

        return ApiResponse::sendResponse(200, 'Category updated successfully', new CategoryResource($updatedCategory));
    }

    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $this->authorize('delete', $category);

        $this->categoryService->deleteCategory($category);

        return ApiResponse::sendResponse(200, 'Category deleted successfully');
    }
}
