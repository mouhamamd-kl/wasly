<?php

namespace App\Http\Requests\Api\Delivery;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CustomerUpdateRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'first_name' => 'string|max:255',
            'last_name' => 'string|max:255',
            'birth_date' => 'date',
            'gender' => 'in:male,female',
            'phone' => 'string|unique:deliveries,phone|max:15',
            'email' => 'email|unique:deliveries,email|max:255',
            'password' => 'string|min:8|confirmed',  // Make sure to confirm the password
            'photo' => 'file|mimes:jpg,jpeg,png,gif',  // Assuming it's a file path or URL
        ];
    }
    /**
     * Get custom attribute names for validation errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birth_date' => 'Birth Date',
            'gender' => 'Gender',
            'phone' => 'Phone Number',
            'email' => 'Email Address',
            'password' => 'Password',
            'photo' => 'Profile Photo',
        ];
    }
}
