<?php

namespace App\Http\Controllers\Api\Customer;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdRequest;
use App\Http\Requests\Api\ApiRequest;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Requests\Api\Customer\CustomerUpdateRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CustomerResource;
use App\Models\Ad;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::latest()->paginate(10);
        if (request()->is('api/*')) {
            return PaginationHelper::paginateResponse($customer, CustomerResource::class, Customer::class);
        }
        return view('admin.auth.master', get_defined_vars());
    }

    public function latest()
    {
        $customer = Customer::latest()->take(10)->get();
        if (request()->is('api/*')) {
            if (count($customer) > 0) {
                return ApiResponse::sendResponse(code: 200, msg: 'latest Customers retrived successfully', data: CustomerResource::collection($customer));
            }
            return ApiResponse::sendResponse(code: 200, msg: 'no Customers found', data: []);
        }
        return view('admin.auth.master', get_defined_vars());
    }


    public function search(Request $request)
    {
        $word = $request->has('search') ? $request->input('search') : null;
        $ads = Customer::when($word != null, function ($q) use ($word) {
            $q->where('title', 'like', '%' . $word . '%');
        })->latest()->paginate(1);
        if ($request->is('api/*')) {
            return  PaginationHelper::paginateResponse($ads, CustomerResource::class, Customer::class);
        }
        return view('admin.auth.master', get_defined_vars());
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customer = Customer::find($id);
        if ($id) {
            return ApiResponse::sendResponse(code: 404, msg: 'customer retrived Successfully', data: new CustomerResource($customer));
        }
        return ApiResponse::sendResponse(code: 404, msg: 'customer Not Found', data: []);
    }

    public function info(Request $request)
    {
        return ApiResponse::sendResponse(code: 200, msg: 'Account Retrieved Successfully', data: new CustomerResource($request->user()));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, $id)
    {
        return ApiResponse::sendResponse(code: 200, msg: $request->user('sanctum'));
        // Authenticate and authorize the customer
        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(
                code: 403,
                msg: 'Unauthorized access. You can only update your own account.',
                data: []
            );
        }

        // Fetch the customer
        $customer = $this->findCustomerById($id);
        if (!$customer) {
            return ApiResponse::sendResponse(
                code: 404,
                msg: 'Customer not found',
                data: []
            );
        }

        // Process and validate the request
        $validatedData = $this->processValidatedData($request);

        // Update customer details
        return $this->updateCustomerData($customer, $validatedData);
    }

    /**
     * Check if the request is authorized for the customer.
     */
    private function isAuthorized($request, $id)
    {
        $authCustomer = $request->user();
        $isAdmin = Auth::guard('admin')->check();

        return ($authCustomer && $authCustomer->id == $id) || $isAdmin;
    }

    /**
     * Find a customer by ID.
     */
    private function findCustomerById($id)
    {
        return Customer::find($id);
    }

    /**
     * Process validated data for special fields like password and photo.
     */
    private function processValidatedData($request)
    {
        $validatedData = $request->validated();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        if (!empty($validatedData['photo'])) {
            $validatedData['photo'] = profileImagePath($request);
        }

        return $validatedData;
    }

    /**
     * Update customer data and return the appropriate response.
     */
    private function updateCustomerData($customer, $validatedData)
    {
        $customer->update($validatedData);

        $changes = $customer->getChanges();
        if (!empty($changes)) {
            $changedKeys = array_keys($changes);
            $updatedFields = implode(', ', array_diff($changedKeys, ['updated_at']));

            return ApiResponse::sendResponse(
                code: 200,
                msg: $updatedFields . ' updated successfully',
                data: new CustomerResource($customer)
            );
        }

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Nothing Changed',
            data: []
        );
    }

    // public function update(CustomerUpdateRequest $request, $id)
    // {
    //     // Authenticate the customer

    //     $authCustomer = $request->user();
    //     if ((!$authCustomer || ($authCustomer->id != $id)) && !Auth::guard('admin')->check()) {
    //         return ApiResponse::sendResponse(code: 403, msg: 'Unauthorized access. You can only update your own account.', data: []);
    //     }

    //     // Find the customer by ID
    //     $customer = Customer::find($id);

    //     if (!$customer) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Customer not found',
    //         ], 404);
    //     }

    //     // Validate the incoming request

    //     $validatedData = $request->validated();

    //     // Update the customer's attributes dynamically
    //     if (isset($validatedData['password'])) {
    //         $validatedData['password'] = Hash::make($validatedData['password']);
    //     }
    //     if (isset($validatedData['photo'])) {
    //         $validatedData['photo'] = profileImagePath($request);
    //     }

    //     // Save the updated customer
    //     $customer->update($validatedData);
    //     $changes = $customer->getChanges();
    //     $changedKeys = array_keys($changes);
    //     if (!empty($changes)) {
    //         $string = json_encode($changedKeys);
    //         $result = str_replace(array('[', ']', '\\', '"', "updated_at"), '', $string);
    //         return ApiResponse::sendResponse(code: 200, msg: $result . ' updated successfully', data: new CustomerResource($customer));
    //     }
    //     return ApiResponse::sendResponse(code: 200, msg: 'Nothing Changed', data: []);
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $authCustomer = $request->user();
        return ApiResponse::sendResponse(code: 200, msg: $request->user());
        if (!$this->isAuthorized($request, $id)) {
            return ApiResponse::sendResponse(code: 403, msg: 'Unauthorized access. You can only Delete your own account.', data: []);
        }
        // Find the customer by ID
        $customer = Customer::find($id);
        if (!$customer)
            return ApiResponse::sendResponse(code: 404, msg: 'Account not found', data: []);
        $customer->delete();
        return ApiResponse::sendResponse(code: 201, msg: 'Account Deleted Successfully', data: []);
    }
}
