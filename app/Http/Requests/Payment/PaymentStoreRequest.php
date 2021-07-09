<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'amount'      => 'required|integer',
            'currency'    => 'required|string',
            'user_id'     => 'required|integer',
            'description' => 'required|string',
            'message'     => 'nullable|string',
        ];
    }
}
