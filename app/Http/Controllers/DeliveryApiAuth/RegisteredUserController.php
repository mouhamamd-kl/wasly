<?php

namespace App\Http\Controllers\DeliveryApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Delivery\DeliveryRegisterRequest;
use App\Models\Delivery;
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
    public function store(DeliveryRegisterRequest $request)
    {
        // Validate the incoming request data
        $validated = $request->validated();

        // Handle photo upload
        $photoData = null;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Get the file content
            $file = $request->file('photo');
            $photoData = base64_encode(file_get_contents($file));
        }
        // If no photo is uploaded, use the default user image and encode it
        if ($photoData === null) {
            $defaultImagePath = public_path('defaults/images/defaultUserImage.png');
            $photoData = base64_encode(file_get_contents($defaultImagePath));
        }
        // Create the delivery record in the database
        $delivery = Delivery::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],  // Nullable field
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'photo' => $photoData,  // Store the file path in the database
        ]);

        // Log in the delivery
        // Auth::guard('delivery')->login($delivery, true);
        auth()->guard(Constants::delivery_guard)->setUser($delivery);

        // Send email verification notification (if applicable)
        $delivery->sendEmailVerificationNotification();

        // Generate the API token for the delivery
        $data = [
            'token' => $delivery->createToken(Constants::delivery_guard.'auth_token', [Constants::delivery_guard])->plainTextToken,
            'first_name' => $delivery->first_name,
            'last_name' => $delivery->last_name,
            'email' => $delivery->email,
            'phone' => $delivery->phone,
            'photo' => $photoData,  // Return the photo URL if available
        ];

        return ApiResponse::sendResponse(201, 'Delivery Account Created Successfully. Please check your email for verification.', $data);
    }
}
