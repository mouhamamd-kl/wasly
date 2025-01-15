<?php

namespace App\Http\Controllers\CustomerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\CustomerPasswordResetRequest;
use App\Http\Requests\Customer\PasswordResetRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CustomerPasswordResetRequest $request): JsonResponse
    {
        $request->validated();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::broker(Constants::custumer_broker)->sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            return ApiResponse::sendResponse(code:200,msg:__($status));
            // throw ValidationException::withMessages([
            //     'email' => [__($status)],
            // ]);
        }
        return ApiResponse::sendResponse(code:200,msg:__($status));
        // return response()->json(['status' => __($status)]);
    }
   
}
