<?php
// app/Http/Controllers/Api/Product/ProductController.php
namespace App\Http\Controllers\Api\Product;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Product\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Services\ProductService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use AuthorizesRequests;
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getProducts();
        return ApiResponse::sendResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
    }

    public function latestApi()
    {
        $products = $this->productService->getLatestProducts();
        return ApiResponse::sendResponse(200, 'Latest products retrieved successfully', ProductResource::collection($products));
    }

    public function getMostPopularProducts()
    {
        $popularProducts = $this->productService->getMostPopularProducts();
        return ApiResponse::sendResponse(200, 'Most popular products retrieved successfully', ProductResource::collection($popularProducts));
    }

    public function getStoreProductsApi($storeId)
    {
        $store = Store::findOrFail($storeId);
        $products = $this->productService->getStoreProducts($store);
        return ApiResponse::sendResponse(200, 'Products for the store retrieved successfully', ProductResource::collection($products));
    }

    public function getCategoryProductsApi($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $products = $this->productService->getCategoryProducts($category);
        return ApiResponse::sendResponse(200, 'Products for the category retrieved successfully', ProductResource::collection($products));
    }

    public function searchApi(Request $request)
    {
        $filters = $request->only(['store_id', 'category_id', 'name', 'min_price', 'max_price', 'sort']);
        $products = $this->productService->searchProducts($filters);
        return ApiResponse::sendResponse(200, 'Products retrieved successfully', ProductResource::collection($products));
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return ApiResponse::sendResponse(404, 'Product not found');
        }

        return ApiResponse::sendResponse(200, 'Product retrieved successfully', new ProductResource($product));
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('manage', $product);

        $validatedData = $request->validated();
        $product->update($validatedData);

        return ApiResponse::sendResponse(200, 'Product updated successfully', new ProductResource($product));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $this->authorize('manage', $product);

        $product->delete();
        return ApiResponse::sendResponse(200, 'Product deleted successfully');
    }
}
