<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['email:rfc,dns', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'billing_address' => ['required','string', 'max:255'],
            'billing_address_2' => ['max:255'],
            'billing_address_3' => ['max:255'],
            'city' => ['max:255'],
            'state' => ['max:255'],
            'zip' => ['max:255'],
            'country' => ['max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
        ];
    }
}
