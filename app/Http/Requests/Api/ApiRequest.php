<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected function failedValidation(Validator $validator)
    {
        $response = ApiResponse::sendResponse(422, 'Validation Errors', $validator->errors()->all());
        throw new ValidationException($validator, $response);
    }

    public function authorize(): bool
    {
        return true;
    }

}
