<?php

namespace App\Http\Controllers\StoreOwnerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\StoreOwnerResource;
use App\Models\Delivery;
use App\Models\StoreOwner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        
        if (!$request->validated()) {
            return ApiResponse::sendResponse(code: 422, msg: 'Login Validation Errors', data: $request->messages());
        }
        // Automatically validated by LoginRequest
        $credentials = $request->only(['email', 'password']);
        $user = StoreOwner::where('email', $credentials['email'])->first();
       
        // Check if the user exists
        if (!$user) {
            return ApiResponse::sendResponse(404, 'Account Cannot Be Found');
        }

        // If the user has not verified their email, prompt verification
        if (!$user->hasVerifiedEmail()) {
            Auth::guard(Constants::store_owner_guard)->login($user, true);  // Log in temporarily to send verification
            $user->sendEmailVerificationNotification();
            return ApiResponse::sendResponse(403, 'Your account is not verified. Please check your email for verification link.');
        }

        // Attempt authentication for verified users
        if (Auth::guard(Constants::store_owner_guard)->attempt($credentials)) {
            $data = [
                'token' => $user->createToken(Constants::store_owner_guard.'_auth_token',[Constants::store_owner_guard])->plainTextToken,
                'account' => new StoreOwnerResource($user)
            ];
            return ApiResponse::sendResponse(200, 'User Account Logged In Successfully', $data);
        }

        // Invalid credentials
        return ApiResponse::sendResponse(401, 'Invalid credentials');
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // return ApiResponse::sendResponse(code: 202, msg: 'test', data: []);
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(code: 200, msg: 'Logged Out Successfully', data: []);
    }
}
