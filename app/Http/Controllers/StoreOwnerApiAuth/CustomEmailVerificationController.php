<?php

namespace App\Http\Controllers\StoreOwnerApiAuth;

use App\Constants\Constants;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Merchant;
use App\Models\StoreOwner;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class CustomEmailVerificationController extends Controller
{
    public function notice(Request $request)
    {
        $user = StoreOwner::where('id', $request->id)->firstOrFail();
        $msg = $request->user(Constants::store_owner_guard)->hasVerifiedEmail()
            ? 'Account Already Verified'
            : 'Account is Now Verified';
        return view('api.email-verification', compact('msg'));
    }
    public function verify(Request $request)
    {
        $user = StoreOwner::where('verification_token', $request->token)->first();
        if (!$user) {
            $msg = 'Account is Already Verified';
            return view('api.email-verification', compact('msg'));
        }
        if ($user && !$user->hasVerifiedEmail()) {
            if (now() <= $user->verification_token_till) {
                $user->verifyUsingVerificationToken();
                $msg = 'Account is Now Verified';
                return view('api.email-verification', compact('msg'));
            } else {
                $msg = 'Please request a new verification';
                return view('api.email-verification', compact('msg'));
            };
        }
        return view('email-verification', compact('msg'));
    }
    public function resend(Request $request)
    {
        $user = StoreOwner::where('verification_token', $request->token)->firstOrFail();
        if ($user->hasVerifiedEmail()) {
            $msg = 'Account is Already Verified';
            return view('api.email-verification', compact('msg'));
        }
        $user->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    }
}
