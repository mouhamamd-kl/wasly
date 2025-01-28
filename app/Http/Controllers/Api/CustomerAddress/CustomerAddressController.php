<?php
// app/Http/Controllers/Api/CustomerAddress/CustomerAddressController.php
namespace App\Http\Controllers\Api\CustomerAddress;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerAddress\CustomerAddressRequest;
use App\Http\Resources\CustomerAddressResource;
use App\Models\CustomerAddress;
use App\Services\CustomerAddressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    use AuthorizesRequests;
    private $customerAddressService;

    public function __construct(CustomerAddressService $customerAddressService)
    {
        $this->customerAddressService = $customerAddressService;
    }

    public function getUserAdresses(Request $request)
    {
        $customer = $request->user();
        $addresses = $this->customerAddressService->getUserAddresses($customer);

        return ApiResponse::sendResponse(200, 'Success', CustomerAddressResource::collection($addresses));
    }

    public function show($id)
    {
        $customerAddress = CustomerAddress::findOrFail($id);
        $this->authorize('view', $customerAddress);

        return ApiResponse::sendResponse(
            200,
            'Customer Address retrieved successfully',
            new CustomerAddressResource($customerAddress)
        );
    }

    public function create(CustomerAddressRequest $request)
    {
        $customer = $request->user();
        $this->authorize('create', CustomerAddress::class);

        $validatedData = $request->validated();
        $validatedData['customer_id'] = $customer->id;

        $customerAddress = $this->customerAddressService->createCustomerAddress($validatedData);

        return ApiResponse::sendResponse(
            201,
            'Customer Address created successfully',
            new CustomerAddressResource($customerAddress)
        );
    }

    public function update(CustomerAddressRequest $request, $id)
    {
        $customerAddress = CustomerAddress::findOrFail($id);
        $this->authorize('update', $customerAddress);

        $updatedAddress = $this->customerAddressService->updateCustomerAddress($customerAddress, $request->validated());

        return ApiResponse::sendResponse(
            201,
            'Customer Address updated successfully',
            new CustomerAddressResource($updatedAddress)
        );
    }

    public function destroy(Request $request, $id)
    {
        $customerAddress = CustomerAddress::findOrFail($id);
        $this->authorize('delete', $customerAddress);

        $this->customerAddressService->deleteCustomerAddress($customerAddress);

        return ApiResponse::sendResponse(201, 'Customer Address deleted successfully');
    }
}
