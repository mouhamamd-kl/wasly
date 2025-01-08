<?php

namespace App\Http\Controllers\Api\Store;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Requests\Api\Store\StoreUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\StoreResource;
use App\Models\Ad;
use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Store::latest()->paginate(10);
        if (request()->is('api/*')) {
            return PaginationHelper::paginateResponse($customer, StoreResource::class, Store::class);
        }
        return view('admin.auth.master', get_defined_vars());
    }

    public function latest(Request $request)
    {
        $stores = Store::latest()->take(10)->get();
        if (request()->is('api/*')) {
            if (count($stores) > 0) {
                return ApiResponse::sendResponse(code: 200, msg: 'latest Stores retrived successfully', data: StoreResource::collection($stores));
            }
            return ApiResponse::sendResponse(code: 200, msg: 'no Stores found', data: []);
        }
        return view('admin.auth.master', get_defined_vars());
    }


    public function searchStores(Request $request)
    {
        return Store::query()
            ->filterByName($request->input('name'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $paginate = getPaginate($request);
        $query = $this->searchStores($request);
        return $query->paginate($paginate);
    }
    public function searchApi(Request $request)
    {
        // return ApiResponse::sendResponse(code: 200, msg: $request->all());
        $stores = $this->search($request);
        return PaginationHelper::paginateResponse(originData: $stores, resourceClass: StoreResource::class, modelClass: Store::class);
    }
    // public function search(Request $request)
    // {
    //     $paginate = getPaginate($request);
    //     $query = $request->input('query');
    //     return Store::where('name', 'LIKE', "%{$query}%")->paginate($paginate);
    // }
    // public function searchApi(Request $request)
    // {
    //     $stores = $this->search($request);
    //     return PaginationHelper::paginateResponse(originData: $stores, resourceClass: StoreResource::class, modelClass: Store::class);
    // }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $store = Store::findOrFailWithResponse($id);
        return ApiResponse::sendResponse(code: 404, msg: 'store retrived Successfully', data: new StoreResource($store));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateRequest $request, $id)
    {
        // Authenticate and authorize the store
        $store = $this->findStoreById($id);
        if (!$store) {
            return ApiResponse::sendResponse(
                code: 404,
                msg: 'Store not found',
                data: []
            );
        }
        if (!$this->isAuthorized($request, $store)) {
            return ApiResponse::sendResponse(
                code: 403,
                msg: 'Unauthorized access. You can only update your own Store.',
                data: []
            );
        }

        // Fetch the customer


        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Update customer details
        return $this->updateStoreData($store, $validatedData);
    }

    /**
     * Check if the request is authorized for the customer.
     */
    private function isAuthorized(Request $request, Store $store)
    {
        $authCustomer = $request->user();
        $isAdmin = Auth::guard('admin')->check();

        return ($authCustomer && $authCustomer->id == $store->store->store_owner_id) || $isAdmin;
    }

    /**
     * Find a customer by ID.
     */
    private function findStoreById($id)
    {
        return Store::find($id);
    }

    /**
     * Process validated data for special fields like password and photo.
     */
    private function processValidatedData($request)
    {
        $validatedData = $request->validated();
        $validatedData['photo'] = ImagePath(request: $request, ImageReplacePath: 'defaults/images/defaultStoreImage.png');
        return $validatedData;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(StoreUpdateRequest $request)
    {
        // Authenticate and authorize the request
        /** @var StoreOwner $storeOwner */
        $storeOwner = $request->user(); // Assuming authenticated user has a store

        if (!$storeOwner) {
            return ApiResponse::sendResponse(
                code: 403,
                msg: 'Unauthorized access. You must be a store owner to create a Store.',
                data: []
            );
        }

        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Create a new product with the validated data
        $product = Store::create($validatedData + ['store_owner_id' => $storeOwner->id]);

        // Return a success response with the newly created product
        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Store created successfully',
            data: new StoreResource($product)
        );
    }
    /**
     * Update store data and return the appropriate response.
     */
    private function updateStoreData($store, $validatedData)
    {
        $store->update($validatedData);

        $changes = $store->getChanges();
        if (!empty($changes)) {
            $changedKeys = array_keys($changes);
            $updatedFields = implode(', ', array_diff($changedKeys, ['updated_at']));

            return ApiResponse::sendResponse(
                code: 200,
                msg: $updatedFields . ' updated successfully',
                data: new StoreResource($store)
            );
        }

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Nothing Changed',
            data: []
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
        $store = $this->findStoreById($id);
        if (!$store) {
            return ApiResponse::sendResponse(
                code: 404,
                msg: 'Store not found',
                data: []
            );
        }
        if (!$this->isAuthorized($request, $store)) {
            return ApiResponse::sendResponse(code: 403, msg: 'Unauthorized access. You can only Delete your own Store.', data: []);
        }
        // Find the store by ID
        $store->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Store Deleted Successfully', data: []);
    }
    public function nearby(Request $request)
    {
        $radius = $request->input('radius', 10);
        $limit = $request->input('limit', 10);
        $customer = $request->user();

        if (!$customer) {
            return ApiResponse::sendResponse(404, 'Customer not found');
        }

        $defaultAddress = $customer->addresses()->where('is_default', true)->first();

        if (!$defaultAddress) {
            return ApiResponse::sendResponse(404, 'No default address found');
        }
        $paginate = getPaginate($request);
        $nearStores = Store::getNearby($defaultAddress->latitude, $defaultAddress->longitude, $radius, $limit)->paginate($paginate);
        return $nearStores;
    }
    public function nearbyApi(Request $request)
    {
        $nearStores = $this->nearby($request);
        return ApiResponse::sendResponse(200, 'Nearby stores retrieved successfully', [
            'stores' => $nearStores,
            'count' => $nearStores->count(),
        ]);
    }
    public function popularByOrders(Request $request)
    {
        // With Limit
        // $limit = $request->input('limit', 10);
        // $popularStores = Store::getPopularByOrders($limit);
        $paginate = getPaginate($request);
        return  $popularStores = Store::getPopularByOrders()->paginate($paginate);
    }
    public function popularByOrdersApi(Request $request)
    {
        $popularStores = $this->popularByOrders($request);
        return  PaginationHelper::paginateResponse($popularStores, StoreResource::class, Store::class);
    }
    public function popularByRatings(Request $request)
    {
        // With Limit
        // $limit = $request->input('limit', 10);
        // $popularStores = Store::getPopularByRatings($limit);
        $paginate = getPaginate($request);
        $popularStores = Store::getPopularByRatings()->paginate($paginate);
        return $popularStores;
    }
    public function popularByRatingsApi(Request $request)
    {
        $popularStores = $this->popularByRatings($request);
        return  PaginationHelper::paginateResponse($popularStores, StoreResource::class, Store::class);
    }



    // public function search(Request $request)
    // {
    //     $paginate = getPaginate($request);
    //     $query = $request->input('query');
    //     return Store::where('name', 'LIKE', "%{$query}%")->paginate($paginate);
    // }
    // public function searchApi(Request $request)
    // {
    //     $stores = $this->search($request);
    //     return PaginationHelper::paginateResponse(originData: $stores, resourceClass: StoreResource::class, modelClass: Store::class);
    // }
}
