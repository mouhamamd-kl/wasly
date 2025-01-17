<?php

namespace App\Http\Controllers\StoreOwnerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Store\StoreSaveRequest;
use App\Http\Requests\Api\StoreOwner\StoreOwnerRegisterRequest;
use App\Http\Resources\StoreOwnerResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Models\StoreOwner;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        // Extract `storeOwner` and `store` data
        $storeOwnerData = $request->input('storeOwner', []);
        $storeData = $request->input('store', []);

        // Validate StoreOwner data using StoreOwnerRegisterRequest rules
        $storeOwnerValidator = Validator::make($storeOwnerData, (new StoreOwnerRegisterRequest)->rules(), [], (new StoreOwnerRegisterRequest)->attributes());
        $storeOwnerValidated = $storeOwnerValidator->validated();
        $storeOwnerPhoto = ImagePath(
            request: $request,               // Pass the full request object
            photoKey: 'storeOwner.photo',     // Pass the specific photo field key
            ImageReplacePath: 'defualts/images/defaultUserImage.jpg'  // Default image path
        );
        $StoreOwner = StoreOwner::create([
            'first_name' => $storeOwnerValidated['first_name'],
            'last_name' => $storeOwnerValidated['last_name'],
            'birth_date' => $storeOwnerValidated['birth_date'],  // Nullable field
            'gender' => $storeOwnerValidated['gender'],
            'phone' => $storeOwnerValidated['phone'],
            'email' => $storeOwnerValidated['email'],
            'password' => Hash::make($storeOwnerValidated['password']),
            'photo' => $storeOwnerPhoto,  // Store the file path in the database
        ]);


        $storeData['store_owner_id'] = $StoreOwner->id; // Ensure store_owner_id is included
        $storeValidator = Validator::make($storeData, (new StoreSaveRequest())->rules(), [], (new StoreSaveRequest)->attributes());
        $storeValidated = $storeValidator->validated();
        
        // Add store_owner_id to the validated data
        $storeValidated['store_owner_id'] = $StoreOwner->id;
        
        // Handle the store photo upload
        $storePhoto = ImagePath(
            $request,               // Pass the full request object
            'defualts/images/defaultStoreImage.png', // Default image path
            'store.photo',          // Pass the specific photo field key
        );
        $storeValidated['photo'] = $storePhoto;
        
        // Create the store record
        $store = Store::create($storeValidated);
        // Log in the StoreOwner
        // Auth::guard('StoreOwner')->login($StoreOwner, true);
        auth()->guard(Constants::store_owner_guard)->setUser($StoreOwner);

        // Send email verification notification (if applicable)
        $StoreOwner->sendEmailVerificationNotification();

        // Generate the API token for the StoreOwner
        $data = [
            'token' => $StoreOwner->createToken(Constants::store_owner_guard . 'auth_token', [Constants::store_owner_guard])->plainTextToken,
            'account'=>new StoreOwnerResource($StoreOwner),
        ];

        return ApiResponse::sendResponse(201, 'Account Created Successfully. Please check your email for verification.', $data);
    }
}
