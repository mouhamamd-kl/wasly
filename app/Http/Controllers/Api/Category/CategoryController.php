<?php

namespace App\Http\Controllers\Api\Category;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Category\CategorySaveRequest;
use App\Http\Requests\Api\Category\CategoryUpdateRequest;
use App\Http\Requests\Api\Product\ProductUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginateApi(Request $request)
    {
        $paginate = getPaginate($request);
        $products = Category::latest()->paginate($paginate);
        return PaginationHelper::paginateResponse($products, CategoryResource::class, Category::class);
    }
    public function indexApi(Request $request)
    {
        $categories = Category::latest()->get();
        return ApiResponse::sendResponse(200, 'Categories Recieved Successfully', CategoryResource::collection($categories));
    }
    public function latest(Request $request)
    {
        $products = Product::latest()->take(10);
        return $products;
    }
    public function latestApi(Request $request)
    {
        $paginate = getPaginate($request);
        $products = $this->latest($request);
        $productsPaginate = $products->paginate($paginate);
        return PaginationHelper::paginateResponse($productsPaginate, ProductResource::class, Product::class);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Category::findOrFailWithResponse($id);

        return ApiResponse::sendResponse(code: 404, msg: 'product retrived Successfully', data: new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategorySaveRequest $request, $id)
    {
        // Authenticate and authorize the product
        $category = Category::findOrFailWithResponse($id);
        $this->authorize('manage', $category);
        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Update customer details
        return $this->updateProductData($category, $validatedData);
    }
    /**
     * Process validated data for special fields like password and photo.
     */
    private function processValidatedData($request)
    {
        $validatedData = $request->validated();
        $validatedData['photo'] = ImagePath(request: $request, ImageReplacePath: 'defaults/images/defaultProductImage.png');
        return $validatedData;
    }

    /**
     * Update product data and return the appropriate response.
     */
    private function updateProductData($category, $validatedData)
    {
        $category->update($validatedData);

        $changes = $category->getChanges();
        if (!empty($changes)) {
            $changedKeys = array_keys($changes);
            $updatedFields = implode(', ', array_diff($changedKeys, ['updated_at']));

            return ApiResponse::sendResponse(
                code: 200,
                msg: $updatedFields . ' updated successfully',
                data: new CategoryResource($category)
            );
        }

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Nothing Changed',
            data: []
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CategorySaveRequest $request)
    {
        // Authenticate and authorize the request
        /** @var StoreOwner $storeOwner */
        $storeOwner = $request->user(); // Assuming authenticated user has a store
        $this->authorize('create', Category::class);  // This ensures that the 'create' policy is applied to the Product model
        // Process and validate the request
        $validatedData = $this->processValidatedData($request);
        // $validatedData['photo'] = ImagePath(request: $request, ImageReplacePath: 'public\defaults\images\defaultProductImage.png');
        // Create a new product with the validated data
        $product = Category::create($validatedData);
        // Return a success response with the newly created product
        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Category created successfully',
            data: new ProductResource($product)
        );
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $authCustomer = $request->user();
        $category = Category::findOrFailWithResponse($id);
        // Find the product by ID
        $this->authorize('manage', Category::class);
        $category->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Category Deleted Successfully', data: []);
    }
}
