<?php

namespace App\Http\Requests\Api\Customer;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CustomerRegisterRequest extends ApiRequest
{
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'phone' => 'required|string|unique:customers,phone|max:15',
            'email' => 'required|email|unique:customers,email|max:255',
            'password' => 'required|string|min:8|confirmed',  // Make sure to confirm the password
            'photo' => 'required|file|mimes:jpg,jpeg,png,gif',  // Assuming it's a file path or URL
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
