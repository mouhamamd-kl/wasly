<?php

namespace App\Http\Controllers\apiAuth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
        $user = User::where('email', $credentials['email'])->first();

        // Check if the user exists
        if (!$user) {
            return ApiResponse::sendResponse(404, 'Account Cannot Be Found');
        }

        // If the user has not verified their email, prompt verification
        if (!$user->hasVerifiedEmail()) {
            Auth::login($user, true);  // Log in temporarily to send verification
            $user->sendEmailVerificationNotification();
            return ApiResponse::sendResponse(403, 'Your account is not verified. Please check your email for verification link.');
        }

        // Attempt authentication for verified users
        if (Auth::attempt($credentials)) {
            $data = [
                'token' => $user->createToken('auth_token')->plainTextToken,
                'name' => $user->name,
                'email' => $user->email,
            ];
            return ApiResponse::sendResponse(200, 'User Account Logged In Successfully', $data);
        }

        // Invalid credentials
        return ApiResponse::sendResponse(401, 'Invalid credentials');
    }
    // public function store(LoginRequest $request)
    // {
    //     // The request is automatically validated here
    //     if (!$request->validated()) {
    //         return ApiResponse::sendResponse(code: 422, msg: 'Login Validation Errors', data: $request->messages());
    //     }

    //     $credentials = $request->only(['email', 'password']);

    //     // First, check if the user exists and is verified
    //     $user = User::where('email', $credentials['email'])->first();
    //     if(!$user){
    //         return ApiResponse::sendResponse(code: 404, msg: 'Account Cannot Be Found', data: []);
    //     }
    //     if ($user && $user->hasVerifiedEmail()) {
    //         // Only attempt authentication if the user exists and is verified
    //         if (Auth::attempt($credentials)) {
    //             $data['token'] = $user->createToken('auth_token')->plainTextToken;
    //             $data['name'] = $user->name;
    //             $data['email'] = $user->email;
    //             return ApiResponse::sendResponse(code: 200, msg: 'User Account Logged In Successfully', data: $data);
    //         } else {
    //             return ApiResponse::sendResponse(code: 401, msg: 'Invalid credentials', data: []);
    //         }
    //     } else {
    //         // event(new Registered($user));
    //         Auth::login($user, true);
    //         $user->sendEmailVerificationNotification();
    //         return ApiResponse::sendResponse(code: 403, msg: 'Your account is not verified. Please check your email for verification link.', data: []);
    //     }
    // }


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
