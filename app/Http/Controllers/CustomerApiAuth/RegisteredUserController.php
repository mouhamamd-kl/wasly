<?php

namespace App\Http\Controllers\CustomerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\CustomerRegisterRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CustomerRegisterRequest $request)
    {
        // Validate the incoming request data
        $validated = $request->validated();

        // Validate the incoming request data

        // Handle photo upload
        $photoData = ImagePath(request: $request, ImageReplacePath: 'defualts/images/defaultUserImage.jpg');
        // Create the customer record in the database
        $customer = Customer::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],  // Nullable field
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'photo' => $photoData,  // Store the file path in the database
        ]);

        // Log in the customer
        // Auth::guard('customer')->login($customer, true);
        auth()->guard(Constants::customer_guard)->setUser($customer);

        // Send email verification notification (if applicable)
        $customer->sendEmailVerificationNotification();

        // Generate the API token for the customer
        $data = [
            'token' => $customer->createToken(Constants::customer_guard . 'auth_token', [Constants::customer_guard])->plainTextToken,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'photo' => $photoData,  // Return the photo URL if available
        ];

        return ApiResponse::sendResponse(201, 'Customer Account Created Successfully. Please check your email for verification.', new CustomerResource($customer));
    }
}
