<?php

namespace App\Http\Requests\Api\CustomerAddress;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can check if the user is authorized to perform this action
        return true; // Assuming all authenticated users can add or update their addresses
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => 'required|in:Home,Office,Other', // Only allows the three defined labels
            'longitude' => 'required|numeric', // Longitude must be a valid number
            'latitude' => 'required|numeric', // Latitude must be a valid number
            'is_default' => 'boolean', // Should be a boolean value
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'label.required' => 'The address label is required.',
            'label.in' => 'The address label must be one of the following: Home, Office, Other.',
            'longitude.required' => 'The longitude is required.',
            'longitude.numeric' => 'The longitude must be a valid number.',
            'latitude.required' => 'The latitude is required.',
            'latitude.numeric' => 'The latitude must be a valid number.',
            'is_default.boolean' => 'The default address flag must be true or false.',
        ];
    }
}
