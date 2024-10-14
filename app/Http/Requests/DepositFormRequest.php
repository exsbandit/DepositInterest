<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositFormRequest extends FormRequest
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
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|in:TRY,USD,EUR',
            'duration' => 'required|integer|min:1',
        ];
    }

    /**
     * Hata mesajlarını özelleştirmek için kullanılır.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.required' => 'Ana para alanı zorunludur.',
            'amount.numeric' => 'Ana para sayısal olmalıdır.',
            'currency.required' => 'Para birimi zorunludur.',
            'currency.in' => 'Geçerli bir para birimi seçiniz (TRY, USD, EUR).',
            'duration.required' => 'Vade zorunludur.',
            'duration.integer' => 'Vade tam sayı olmalıdır.',
        ];
    }
}
