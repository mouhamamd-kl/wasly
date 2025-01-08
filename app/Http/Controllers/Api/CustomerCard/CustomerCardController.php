<?php

namespace App\Http\Controllers\Api\CustomerCard;

use App\Helpers\ApiResponse;
use App\Helpers\PaginationHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CustomerCard\CustomerCardRequest;
use App\Http\Requests\Api\Product\CustomerCardStoreRequest;
use App\Http\Resources\CustomerCardResource;
use App\Models\CustomerCard;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerCardController extends Controller
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
        $customerCard = CustomerCard::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('view', $customerCard);

        return ApiResponse::sendResponse(
            code: 200,
            msg: 'Customer Card retrieved successfully',
            data: new CustomerCardResource($customerCard)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CustomerCardRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerCardStoreRequest $request, $id)
    {
        $customerCard = CustomerCard::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('update', $customerCard);

        // Update the customer card
        $customerCard->update([
            'card_number' => encrypt($request->card_number),
            'expiration_date' => $request->expiration_date,
            'card_type' => $request->card_type,
            'cvv' => encrypt($request->cvv),
        ]);

        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Customer Card updated successfully',
            data: new CustomerCardResource($customerCard)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CustomerCardRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function create(CustomerCardStoreRequest $request)
    {
        $this->authorize('create', CustomerCard::class);

        // Process validated data and encrypt sensitive fields
        $validatedData = $this->processValidatedData($request);

        // Create a new customer card
        $customerCard = CustomerCard::create($validatedData);

        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Customer Card created successfully',
            data: new CustomerCardResource($customerCard)
        );
    }

    /**
     * Process validated data for sensitive fields like card number and CVV.
     */
    private function processValidatedData($request)
    {
        $validatedData = $request->validated();
        $validatedData['card_number'] = encrypt($validatedData['card_number']);
        $validatedData['cvv'] = encrypt($validatedData['cvv']);
        return $validatedData;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $customerCard = CustomerCard::findOrFailWithResponse($id);

        // Authorize the action using the policy
        $this->authorize('delete', $customerCard);

        // Delete the customer card
        $customerCard->delete();

        return ApiResponse::sendResponse(
            code: 201,
            msg: 'Customer Card deleted successfully',
            data: []
        );
    }
}
