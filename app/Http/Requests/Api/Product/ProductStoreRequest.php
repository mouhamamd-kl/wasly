<?php

namespace App\Http\Requests\Api\Product;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ProductStoreRequest extends ApiRequest
{
    /**
     * Define the validation rules for the product update request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'photo' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_string($value) && !request()->hasFile($attribute)) {
                        $fail('The :attribute must be a valid URL or a valid file.');
                    }
                    if (is_string($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                        $fail('The :attribute must be a valid URL.');
                    }
                },
                'mimes:jpg,jpeg,png,gif', // Optional product photo
            ],
            'description' => 'required|string',
            'stock_quantity' => 'required|integer|min:0', // Stock quantity must be a non-negative integer
            'price' => 'required|numeric|min:0', // Price must be a positive number
            'is_active' => 'required|boolean', // Ensure it's true/false
            'category_id' => 'required|exists:categories,id', // Ensure the category exists
            'store_id' => 'required|exists:stores,id', // Ensure the store exists
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
            'name' => 'Product Name',
            'photo' => 'Product Photo',
            'description' => 'Description',
            'stock_quantity' => 'Stock Quantity',
            'price' => 'Price',
            'is_active' => 'Active Status',
            'category_id' => 'Category',
            'store_id' => 'Store',
        ];
    }
    public function messages()
    {
        return [
            'photo.mimes' => 'The product photo must be a file of type: jpg, jpeg, png, gif.',
            'photo.url' => 'The product photo must be a valid URL.',
        ];
    }
}
