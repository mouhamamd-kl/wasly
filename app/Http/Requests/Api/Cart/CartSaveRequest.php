<?php

namespace App\Http\Requests\Api\Cart;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CartSaveRequest extends ApiRequest
{
    /**
     * Define the validation rules for the product update request.
     *
     * @return array
     */
    public function rules()
    {
        return
            [
                'product_id' => 'required|exists:products,id',
                'count' => 'required|integer|min:1',
            ];
    }
}
