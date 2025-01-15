<?php

namespace App\Http\Controllers\Api\Product;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Requests\Api\Product\ProductUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreResource;
use App\Models\Ad;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class ProductController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::get();
        return ApiResponse::sendResponse(200, 'sucess', ProductResource::collection($products));
    }

    public function latest(Request $request)
    {
        $products = Product::latest()->take(10);
        return $products;
    }
    public function latestApi(Request $request)
    {
        $products = $this->latest($request)->get();
        // $productsPaginate = $products->paginate($paginate);
        // return PaginationHelper::paginateResponse($products, ProductResource::class, Product::class);
        return ApiResponse::sendResponse(200, 'products recived sucessfully', ProductResource::collection($products));
    }



    // In Controller

    public function searchProducts(Request $request)
    {
        return Product::query()
            ->filterByStore($request->input('store_id')) // Added store filter
            ->filterByCategory($request->input('category_id'))
            ->filterByName($request->input('name'))
            ->filterByPriceRange($request->input('min_price'), $request->input('max_price'))
            ->sortByPrice($request->input('sort'))
            ->where('is_active', true);
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'name' => 'nullable|string|max:255',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'sort' => 'nullable|in:high_to_low,low_to_high',
        ]);

        // $paginate = getPaginate($request);
        // $query = $this->searchProducts($request);
        // return $query->paginate($paginate);

    }
    public function searchApi(Request $request)
    {
        // return ApiResponse::sendResponse(code: 200, msg: $request->all());
        $products = $this->searchProducts($request)->get();
        return ApiResponse::sendResponse(200, 'sucess', ProductResource::collection($products));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with(['category', 'store'])->find($id);
        if ($id) {
            return ApiResponse::sendResponse(code: 404, msg: 'product retrived Successfully', data: new ProductResource($product));
        }
        return ApiResponse::sendResponse(code: 404, msg: 'product Not Found', data: []);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        // Authenticate and authorize the product
        $product = Product::findOrFailWithResponse($id);
        $this->authorize('manage', $product);
        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Update customer details
        return $this->updateProductData($product, $validatedData);
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
    private function updateProductData($product, $validatedData)
    {
        $product->update($validatedData);

        $changes = $product->getChanges();
        if (!empty($changes)) {
            $changedKeys = array_keys($changes);
            $updatedFields = implode(', ', array_diff($changedKeys, ['updated_at']));

            return ApiResponse::sendResponse(
                code: 200,
                msg: $updatedFields . ' updated successfully',
                data: new ProductResource($product)
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
    public function create(ProductUpdateRequest $request)
    {
        // Authenticate and authorize the request
        /** @var StoreOwner $storeOwner */
        $storeOwner = $request->user(); // Assuming authenticated user has a store
        // Process and validate the request
        $validatedData = $this->processValidatedData($request);
        $validatedData['photo'] = ImagePath(request: $request, ImageReplacePath: 'defaults\images\defaultProductImage.png');
        // Create a new product with the validated data
        $product = Product::create($validatedData + ['store_id' => $storeOwner->store->id]);

        // Return a success response with the newly created product
        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Product created successfully',
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
        $product = Product::findOrFailWithResponse($id);
        // Find the product by ID
        $this->authorize('manage', $product,$authCustomer);
        $product->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Product Deleted Successfully', data: []);
    }
    /**
     * Get products for a specific store.
     *
     * @param  int  $storeId
     * @return \Illuminate\Http\Response
     */
    public function getStoreProducts($storeId)
    {
        // Validate that the store exists
        $store = Store::findOrFailWithResponse($storeId);

        // Fetch the products for the specified store
        return Product::ByStore($storeId)->ByActive()->latest();
    }

    public function getStoreProductsApi(Request $request, $storeId)
    {
        // Get the products query
        $productsQuery = $this->getStoreProducts($storeId);

        // Determine pagination size
        // $paginate = getPaginate($request);

        // Paginate the products
        // $productsPaginate = $productsQuery->paginate($paginate);
        $products = $productsQuery->get();

        // Return a paginated response
        return ApiResponse::sendResponse(200, 'sucess', ProductResource::collection($products));
    }
    public function getCategoryProducts($categoryId)
    {
        // Validate that the store exists
        $category = Category::findOrFailWithResponse($categoryId);

        // Fetch the products for the specified store
        return Product::ByCategory($categoryId)->latest();
    }
    public function getCategoryProductsApi(Request $request, $storeId)
    {
        // Get the products query
        $productsQuery = $this->getStoreProducts($storeId);

        // Determine pagination size
        // $paginate = getPaginate($request);

        // Paginate the products
        // $productsPaginate = $productsQuery->paginate($paginate);
        $products = $productsQuery->get();

        // Return a paginated response
        return ApiResponse::sendResponse(200, 'success', ProductResource::collection($products));
    }
}
