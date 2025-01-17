<?php

namespace app\Http\Requests\Api\CustomerCard;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Helpers\ApiResponse;
use App\Http\Requests\Api\ApiRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class CustomerCardStoreRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customers,id',
            'card_number' => 'required|string|min:13|max:19', // Adjust based on card type
            'expiration_date' => 'required|string|regex:/^(0[1-9]|1[0-2])\/\d{2}$/', // Format: m/y
            'card_type' => 'required|string|max:50',
            'cvv' => 'required|string|min:3|max:4',
        ];
    }

    /**
     * Custom messages for validation.
     */
    public function messages()
    {
        return [
            'expiration_date.regex' => 'The expiration date must be in the format m/y.',
        ];
    }
}
