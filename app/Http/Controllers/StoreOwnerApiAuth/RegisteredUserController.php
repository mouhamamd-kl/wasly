<?php

namespace App\Http\Controllers\StoreOwnerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreOwner\StoreOwnerRegisterRequest;
use App\Models\StoreOwner;
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
    public function store(StoreOwnerRegisterRequest $request)
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
        // Create the StoreOwner record in the database
        $StoreOwner = StoreOwner::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'birth_date' => $validated['birth_date'],  // Nullable field
            'gender' => $validated['gender'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'photo' => $photoData,  // Store the file path in the database
        ]);

        // Log in the StoreOwner
        // Auth::guard('StoreOwner')->login($StoreOwner, true);
        auth()->guard(Constants::delivery_guard)->setUser($StoreOwner);

        // Send email verification notification (if applicable)
        $StoreOwner->sendEmailVerificationNotification();

        // Generate the API token for the StoreOwner
        $data = [
            'token' => $StoreOwner->createToken(Constants::delivery_guard.'auth_token', [Constants::delivery_guard])->plainTextToken,
            'first_name' => $StoreOwner->first_name,
            'last_name' => $StoreOwner->last_name,
            'email' => $StoreOwner->email,
            'phone' => $StoreOwner->phone,
            'photo' => $photoData,  // Return the photo URL if available
        ];

        return ApiResponse::sendResponse(201, 'Account Created Successfully. Please check your email for verification.', $data);
    }
}
