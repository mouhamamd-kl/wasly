<?php

namespace App\Models;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Notifications\CustomPasswordNotification;
use App\Notifications\EmailVerification;

class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pivot'
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

    /**
     * Get the customer's addresses.
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
    // Define the relationship with products through the cart pivot table
    public function cartProducts()
    {
        return $this->belongsToMany(Product::class, 'carts')
            ->withPivot('count')
            ->withTimestamps();
    }
    public function favouriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favourite_products')
            ->withTimestamps();
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function cards()
    {
        return $this->hasMany(CustomerCard::class);
    }
    /**
     * Get nearby stores based on the customer's default address.
     *
     * @param int $radius The radius to search within (in kilometers)
     * @param int $limit The maximum number of stores to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNearbyStores($radius = 10, $limit = 10)
    {
        $defaultAddress = $this->addresses()->where('is_default', true)->first();

        if (!$defaultAddress) {
            return collect();
        }

        return Store::getNearby($defaultAddress->latitude, $defaultAddress->longitude, $radius, $limit);
    }


    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordNotification(token: $token, route: 'customer', email: $this->email));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->generateVerificationToken();
        $url = route('customer.verification.verify', [
            'id' => $this->getKey(),
            'token' => $this->verification_token,
        ]);
        $this->notify(new EmailVerification($url, $this->name));
    }

    /**
     * Generate a new email verification token.
     *
     * @return void
     */
    public function generateVerificationToken()
    {
        $this->verification_token = Str::random(40);
        $this->verification_token_till = now()->addMinutes(60);
        $this->save();
    }

    /**
     * Verify the customer's email using the verification token.
     *
     * @return void
     */
    public function verifyUsingVerificationToken()
    {
        $this->email_verified_at = now();
        $this->verification_token = null;
        $this->verification_token_till = null;
        $this->save();
    }

    /**
     * Determine if the customer has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null;
    }
    public static function findOrFailWithResponse(int $id)
    {
        $customer = self::find($id);

        if (!$customer) {
            // Return the custom API response
            ApiResponse::sendResponse(404, 'Customer Not Found')->throwResponse();
        }
        return $customer;
    }
}
