<?php

namespace App\Http\Controllers\DeliveryApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->user(Constants::delivery_guard)->hasVerifiedEmail()) {
            return  ApiResponse::sendResponse(code: 200, msg: 'Email Already Verified', data: []);
        }

        $request->user(Constants::delivery_guard)->sendEmailVerificationNotification();
        return  ApiResponse::sendResponse(code: 200, msg: 'Email Verification Sent Successfully', data: []);
        // return response()->json(['status' => 'verification-link-sent']);
    }
}
