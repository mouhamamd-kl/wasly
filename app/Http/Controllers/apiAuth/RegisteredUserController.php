<?php

namespace App\Http\Controllers\apiAuth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request)
    {
        // The request is automatically validated here
        if (!$request->validated()) {
            return ApiResponse::sendResponse(code: 422, msg: 'Registration Validation Errors', data: $request->messages());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
        ]);

        // event(new Registered($user));
        Auth::login($user, true);
        // Send verification email

        $user->sendEmailVerificationNotification();

        $data = [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'name' => $user->name,
            'email' => $user->email,
        ];
        return ApiResponse::sendResponse(code: 201, msg: 'User Account Created Successfully. Please check your email for verification.', data: $data);
    }
}
