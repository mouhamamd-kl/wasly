<?php
// app/Http/Controllers/Api/Store/StoreController.php
namespace App\Http\Controllers\Api\Store;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Store\StoreUpdateRequest;
use App\Http\Resources\StoreResource;
use App\Repositories\StoreRepository;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private $storeService;
    private $storeRepository;

    public function __construct(StoreService $storeService, StoreRepository $storeRepository)
    {
        $this->storeService = $storeService;
        $this->storeRepository = $storeRepository;
    }

    public function index()
    {
        $stores = $this->storeRepository->searchByName()->get();
        return ApiResponse::sendResponse(200, 'Success', StoreResource::collection($stores));
    }

    public function fetchStoreOrderItemsByStatus(Request $request, $storeId)
    {
        $status = $request->get('status', 'pending');
        $orderItems = $this->storeService->getStoresByStatus($storeId, $status);

        return ApiResponse::sendResponse(200, 'Success', StoreResource::collection($orderItems));
    }

    public function update(StoreUpdateRequest $request, $id)
    {
        $store = $this->storeRepository->findById($id);
        if (!$store) {
            return ApiResponse::sendResponse(404, 'Store not found');
        }

        $validatedData = $request->validated();
        $updateResult = $this->storeService->updateStoreData($store, $validatedData);

        $message = !empty($updateResult['changes'])
            ? "{$updateResult['updatedFields']} updated successfully"
            : "Nothing Changed";

        return ApiResponse::sendResponse(200, $message, new StoreResource($store));
    }

    public function nearbyApi(Request $request)
    {
        $customer = $request->user();
        if (!$customer) {
            return ApiResponse::sendResponse(404, 'Customer not found');
        }

        $address = $customer->addresses()->where('is_default', true)->first();
        if (!$address) {
            return ApiResponse::sendResponse(404, 'No default address found');
        }

        $nearbyStores = $this->storeService->getNearbyStores($address->latitude, $address->longitude, $request->input('radius', 10), $request->input('limit', 10));

        return ApiResponse::sendResponse(200, 'Nearby stores retrieved successfully', StoreResource::collection($nearbyStores));
    }

    public function popularByOrdersApi()
    {
        $popularStores = $this->storeService->getPopularByOrders();
        return ApiResponse::sendResponse(200, 'Success', StoreResource::collection($popularStores));
    }

    public function popularByRatingsApi()
    {
        $popularStores = $this->storeService->getPopularByRatings();
        return ApiResponse::sendResponse(200, 'Success', StoreResource::collection($popularStores));
    }
}
