<?php

namespace App\Http\Requests\Api\Category;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CategorySaveRequest extends ApiRequest
{
    /**
     * Define the validation rules for the product update request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'string|max:255',
            'photo' => 'file|mimes:jpg,jpeg,png,gif', // Optional product photo
            'description' => 'string',
            'stock_quantity' => 'integer|min:0', // Stock quantity must be a non-negative integer
            'price' => 'numeric|min:0', // Price must be a positive number
            'is_active' => 'boolean', // Ensure it's true/false
            'category_id' => 'exists:categories,id', // Ensure the category exists
            'store_id' => 'exists:stores,id', // Ensure the store exists
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
}
