<?php

namespace App\Http\Controllers\Api\CustomerAddress;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\CustomerAddress\CustomerAddressRequest;
use App\Http\Resources\CustomerAddressResource;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerAddressController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $customerAddress = CustomerAddress::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('view', $customerAddress);

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Customer Address retrieved successfully',
            data: new CustomerAddressResource($customerAddress)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerAddressRequest $request, $id)
    {
        // Get the customer address by ID
        $address = CustomerAddress::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('update', $address);

        // Update the address
        $address->update([
            'label' => $request->label,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'is_default' => $request->is_default,
        ]);

        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Customer address updated successfully',
            data: new CustomerAddressResource($address)
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerAddressRequest $request)
    {
        // Authenticate and authorize the request
        /** @var StoreOwner $storeOwner */
        $storeOwner = $request->user(); // Assuming authenticated user has a store
        $this->authorize('create', CustomerAddress::class);  // This ensures that the 'create' policy is applied to the Product model
        // Process and validate the request
        $validatedData = $this->processValidatedData($request);
        $validatedData['photo'] = ImagePath(request: $request, ImageReplacePath: 'public\defaults\images\defaultProductImage.png');
        // Create a new product with the validated data
        $product = CustomerAddress::create($validatedData);
        // Return a success response with the newly created product
        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Category created successfully',
            data: new CustomerAddressResource($product)
        );
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
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $customerAddress = CustomerAddress::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('delete', $customerAddress);

        // Delete the customer address
        $customerAddress->delete();

        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Customer Address Deleted Successfully',
            data: []
        );
    }
}
