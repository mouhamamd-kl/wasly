<?php
// app/Http/Controllers/Api/Customer/CustomerController.php
namespace App\Http\Controllers\Api\Customer;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerService;
    use AuthorizesRequests;
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = $this->customerService->getAllCustomers();
        return ApiResponse::sendResponse(200, 'Customers retrieved successfully', CustomerResource::collection($customers));
    }

    public function latest()
    {
        $customers = $this->customerService->getLatestCustomers();
        $message = count($customers) > 0 ? 'Latest customers retrieved successfully' : 'No customers found';
        return ApiResponse::sendResponse(200, $message, CustomerResource::collection($customers));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $customers = $this->customerService->searchCustomers($searchTerm);
        return ApiResponse::sendResponse(200, 'Search results retrieved successfully', CustomerResource::collection($customers));
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return ApiResponse::sendResponse(404, 'Customer not found');
        }

        return ApiResponse::sendResponse(200, 'Customer retrieved successfully', new CustomerResource($customer));
    }

    public function info(Request $request)
    {
        return ApiResponse::sendResponse(200, 'Account retrieved successfully', new CustomerResource($request->user()));
    }

    public function update(CustomerUpdateRequest $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $this->authorize('update', $customer);

        $updatedCustomer = $this->customerService->updateCustomer($customer, $request->validated());

        return ApiResponse::sendResponse(200, 'Customer updated successfully', new CustomerResource($updatedCustomer));
    }

    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $this->authorize('delete', $customer);

        $this->customerService->deleteCustomer($customer);

        return ApiResponse::sendResponse(200, 'Customer deleted successfully');
    }
}
