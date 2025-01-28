<?php
// app/Http/Controllers/Api/CustomerCard/CustomerCardController.php
namespace App\Http\Controllers\Api\CustomerCard;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use app\Http\Requests\Api\CustomerCard\CustomerCardStoreRequest;
use App\Http\Resources\CustomerCardResource;
use App\Models\CustomerCard;
use App\Services\CustomerCardService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CustomerCardController extends Controller
{
    use AuthorizesRequests;
    private $customerCardService;

    public function __construct(CustomerCardService $customerCardService)
    {
        $this->customerCardService = $customerCardService;
    }

    public function getUserCards(Request $request)
    {
        $customer = $request->user();
        $customerCards = $this->customerCardService->getUserCards($customer);

        return ApiResponse::sendResponse(200, 'Success', CustomerCardResource::collection($customerCards));
    }

    public function show($id)
    {
        $customerCard = CustomerCard::findOrFail($id);
        $this->authorize('view', $customerCard);

        return ApiResponse::sendResponse(
            200,
            'Customer Card retrieved successfully',
            new CustomerCardResource($customerCard)
        );
    }

    public function create(CustomerCardStoreRequest $request)
    {
        $this->authorize('create', CustomerCard::class);

        $validatedData = $this->processValidatedData($request);
        $customerCard = $this->customerCardService->createCustomerCard($validatedData);

        return ApiResponse::sendResponse(
            201,
            'Customer Card created successfully',
            new CustomerCardResource($customerCard)
        );
    }

    public function update(CustomerCardStoreRequest $request, $id)
    {
        $customerCard = CustomerCard::findOrFail($id);
        $this->authorize('update', $customerCard);

        $updatedCard = $this->customerCardService->updateCustomerCard($customerCard, $request->validated());

        return ApiResponse::sendResponse(
            201,
            'Customer Card updated successfully',
            new CustomerCardResource($updatedCard)
        );
    }

    public function destroy(Request $request, $id)
    {
        $customerCard = CustomerCard::findOrFail($id);
        $this->authorize('delete', $customerCard);

        $this->customerCardService->deleteCustomerCard($customerCard);

        return ApiResponse::sendResponse(201, 'Customer Card deleted successfully');
    }

    private function processValidatedData($request)
    {
        $validatedData = $request->validated();
        $validatedData['card_number'] = encrypt($validatedData['card_number']);
        $validatedData['cvv'] = encrypt($validatedData['cvv']);
        return $validatedData;
    }
}
