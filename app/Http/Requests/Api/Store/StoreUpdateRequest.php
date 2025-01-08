<?php

namespace App\Http\Requests\Api\Store;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreUpdateRequest extends ApiRequest
{
    /**
     * Define the validation rules for the store update request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255', // Store name
            'photo' => 'file|mimes:jpg,jpeg,png,gif', // Optional store photo
            'latitude' => 'required|numeric', // Latitude must be a numeric value
            'longitude' => 'required|numeric', // Longitude must be a numeric value
            'phone' => 'required|string|max:15', // Store phone number (make sure it's not too long)
            'store_owner_id' => 'exists:store_owners,id', // Ensure the store owner exists
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
            'name' => 'Store Name',
            'photo' => 'Store Photo',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'phone' => 'Phone Number',
            'store_owner_id' => 'Store Owner',
        ];
    }
}
