<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSubscriptionRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user' => 'required|integer|exists:users,id',
            'year' => 'required|integer|exists:years,id',
            'month' => 'required|string',
            'payment_account' => 'required|integer|exists:payment_accounts,id',
            'slip' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
