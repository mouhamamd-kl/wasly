<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;
use App\Helpers\ApiResponse;
use App\Helpers\verificationSwitcher;
use App\Notifications\CustomPasswordNotification;
use App\Notifications\EmailVerification;
use App\Notifications\OTPEmailVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class StoreOwner extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',                // Primary Key
    ];  
    public function store()
    {
         return $this->hasOne(Store::class);
    }
    public static function findOrFailWithResponse(int $id)
    {
         $storeOwner = self::find($id);

         if (!$storeOwner) {
              // Return the custom API response
              ApiResponse::sendResponse(404, 'Store Owner Not Found')->throwResponse();
         }

         return $storeOwner;
    }
        /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordNotification(token: $token, route: 'store-owner', email: $this->email));
    }
    public function sendEmailVerificationNotification()
    {
        // return ApiResponse::sendResponse(code:200,msg:'are you here',data:[]);
        $this->generateVerificationToken();
        $url = route('storeOwner.verification.verify', [
            'id' => $this->getKey(),
            'token' => $this->verification_token,
            // 'hash' => sha1($this->getEmailForVerification())
        ]);
        $this->notify(new EmailVerification($url, $this->name));
    }
    //==============================================================CUSTOM VERIFICATION TOKENS
    public function generateVerificationToken()
    {

        $this->verification_token = Str::random(40);
        $this->verification_token_till = now()->addMinutes(60);
        $this->save();
    }
    public function verifyUsingVerificationToken()
    {

        $this->email_verified_at = now();
        $this->verification_token = null;
        $this->verification_token_till = null;
        $this->save();
    }
    //==============================================================CUSTOM VERIFICATION TOKENS
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // In app/Models/User.php

    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }
}
