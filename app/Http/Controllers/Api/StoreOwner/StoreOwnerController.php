<?php
namespace App\Http\Controllers\Api\StoreOwner;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOwner\StoreOwnerUpdateRequest;
use App\Http\Resources\StoreOwnerResource;
use App\Models\StoreOwner;
use App\Repositories\StoreOwnerRepository;
use App\Services\StoreOwnerService;
use Illuminate\Http\Request;

class StoreOwnerController extends Controller
{
    private $storeOwnerService;
    private $storeOwnerRepository;

    public function __construct(StoreOwnerService $storeOwnerService, StoreOwnerRepository $storeOwnerRepository)
    {
        $this->storeOwnerService = $storeOwnerService;
        $this->storeOwnerRepository = $storeOwnerRepository;
    }

    public function index()
    {
        $storeOwners = $this->storeOwnerRepository->getPaginated(10);
        return PaginationHelper::paginateResponse($storeOwners, StoreOwnerResource::class, StoreOwner::class);
    }

    public function latest()
    {
        $storeOwners = $this->storeOwnerService->getLatestStoreOwners(10);
        $message = count($storeOwners) > 0 ? 'Latest StoreOwners retrieved successfully' : 'No StoreOwners found';
        return ApiResponse::sendResponse(200, $message, StoreOwnerResource::collection($storeOwners));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search', null);
        $storeOwners = $this->storeOwnerService->searchStoreOwners($searchTerm)->paginate(10);
        return PaginationHelper::paginateResponse($storeOwners, StoreOwnerResource::class, StoreOwner::class);
    }

    public function show($id)
    {
        $storeOwner = $this->storeOwnerRepository->findById($id);
        if (!$storeOwner) {
            return ApiResponse::sendResponse(404, 'StoreOwner not found');
        }
        return ApiResponse::sendResponse(200, 'StoreOwner retrieved successfully', new StoreOwnerResource($storeOwner));
    }

    public function info(Request $request)
    {
        return ApiResponse::sendResponse(200, 'Account retrieved successfully', new StoreOwnerResource($request->user()));
    }

    public function update(StoreOwnerUpdateRequest $request, $id)
    {
        $storeOwner = $this->storeOwnerRepository->findById($id);
        if (!$storeOwner) {
            return ApiResponse::sendResponse(404, 'StoreOwner not found');
        }

        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(403, 'Unauthorized access. You can only update your own account.');
        }

        $validatedData = $request->validated();
        $updateResult = $this->storeOwnerService->updateStoreOwnerData($storeOwner, $validatedData);

        $message = !empty($updateResult['changes'])
            ? "{$updateResult['updatedFields']} updated successfully"
            : "Nothing Changed";

        return ApiResponse::sendResponse(200, $message, new StoreOwnerResource($storeOwner));
    }

    public function destroy(Request $request, $id)
    {
        $storeOwner = $this->storeOwnerRepository->findById($id);
        if (!$storeOwner) {
            return ApiResponse::sendResponse(404, 'StoreOwner not found');
        }

        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(403, 'Unauthorized access. You can only delete your own account.');
        }

        $this->storeOwnerService->deleteStoreOwner($storeOwner);
        return ApiResponse::sendResponse(200, 'Account deleted successfully');
    }

    private function isAuthorized(Request $request, $id)
    {
        $authStoreOwner = $request->user();
        $isAdmin = auth()->guard('admin')->check();
        return ($authStoreOwner && $authStoreOwner->id == $id) || $isAdmin;
    }
}
