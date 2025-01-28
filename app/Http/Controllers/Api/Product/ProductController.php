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
    public function index(Request $request)
    {
        $products = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->get()
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Products retrieved successfully',
            ProductResource::collection($products)
        );
    }
    

    public function latestApi(Request $request)
    {
        $products = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->latest() // Order by latest products
            ->take(10) // Limit to the latest 10 products
            ->get()
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Latest products retrieved successfully',
            ProductResource::collection($products)
        );
    }
    

    // public function latestApi(Request $request)
    // {
    //     $products = $this->latest($request);

    //     return ApiResponse::sendResponse(200, 'products received successfully', ProductResource::collection($products));
    // }

    // public function getMostPopularProducts()
    // {
    //     $popularProducts = Product::withAvg('ratings as average_rating', 'rating')
    //         ->with(['store', 'category']) // Include related models
    //         ->withCount('orderItems') // Count related order items
    //         ->orderBy('order_items_count', 'desc') // Sort by the count in descending order
    //         ->take(10) // Limit to the top 10 most popular products
    //         ->get()
    //         ->map(function ($product) {
    //             // Ensure `average_rating` is 0 if null
    //             $product->average_rating = $product->average_rating ?? 0;
    //             return $product;
    //         });

    //     return ApiResponse::sendResponse(
    //         200,
    //         'Most popular products retrieved successfully',
    //         ProductResource::collection($popularProducts)
    //     );
    // }
    public function getMostPopularProducts()
    {
        $popularProducts = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['store', 'category']) // Include related models
            ->withCount('orderItems') // Count related order items
            ->withCount('reviews as reviews_count') // Count related reviews
            ->orderBy('order_items_count', 'desc') // Sort by the count in descending order
            ->take(10) // Limit to the top 10 most popular products
            ->get()
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Most popular products retrieved successfully',
            ProductResource::collection($popularProducts)
        );
    }
    



    public function searchApi(Request $request)
    {
        $products = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->filterByStore($request->input('store_id'))
            ->filterByCategory($request->input('category_id'))
            ->filterByName($request->input('name'))
            ->filterByPriceRange($request->input('min_price'), $request->input('max_price'))
            ->sortByPrice($request->input('sort'))
            ->where('is_active', true) // Filter active products
            ->get() // Retrieve results
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Products retrieved successfully',
            ProductResource::collection($products)
        );
    }
    

    // public function searchApi(Request $request)
    // {
    //     $products = $this->searchProducts($request)->get();

    //     return ApiResponse::sendResponse(200, 'success', ProductResource::collection($products));
    // }

    public function show($id)
    {
        $product = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->find($id); // Find the product by ID
    
        if ($product) {
            // Ensure average_rating is 0 if null
            $product->average_rating = $product->average_rating ?? 0;
    
            return ApiResponse::sendResponse(
                200,
                'Product retrieved successfully',
                new ProductResource($product)
            );
        }
    
        return ApiResponse::sendResponse(404, 'Product not found', []);
    }
    

    public function getStoreProductsApi($storeId)
    {
        // Ensure the store exists or return a 404 response
        $store = Store::findOrFailWithResponse($storeId);
    
        $products = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->ByStore($storeId) // Filter by store ID
            ->ByActive() // Filter active products
            ->latest() // Order by latest
            ->get()
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Products for the store retrieved successfully',
            ProductResource::collection($products)
        );
    }
    

    // public function getStoreProductsApi(Request $request, $storeId)
    // {
    //     $productsQuery = $this->getStoreProducts($storeId);

    //     $products = $productsQuery->get();

    //     return ApiResponse::sendResponse(200, 'success', ProductResource::collection($products));
    // }

    public function getCategoryProductsApi($categoryId)
    {
        // Ensure the category exists or return a 404 response
        $category = Category::findOrFailWithResponse($categoryId);
    
        $products = Product::withAvg('ratings as average_rating', 'rating') // Include average rating
            ->with(['category', 'store']) // Include related models
            ->withCount('reviews as reviews_count') // Count related reviews
            ->withCount('orderItems') // Count related order items
            ->ByCategory($categoryId) // Filter by category ID
            ->latest() // Order by latest
            ->get()
            ->map(function ($product) {
                // Ensure average_rating is 0 if null
                $product->average_rating = $product->average_rating ?? 0;
                return $product;
            });
    
        return ApiResponse::sendResponse(
            200,
            'Products for the category retrieved successfully',
            ProductResource::collection($products)
        );
    }
    

    // public function getCategoryProductsApi(Request $request, $storeId)
    // {
    //     $productsQuery = $this->getCategoryProducts($storeId);

    //     $products = $productsQuery->get();

    //     return ApiResponse::sendResponse(200, 'success', ProductResource::collection($products));
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


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
        $this->authorize('manage', $product, $authCustomer);
        $product->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Product Deleted Successfully', data: []);
    }
    /**
     * Get products for a specific store.
     *
     * @param  int  $storeId
     * @return \Illuminate\Http\Response
     */
}
