<?php

namespace App\Http\Requests\Whitelists;

use App\Rules\ValidateIfHasScheme;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'domain' => [
                'bail',
                'required',
                new ValidateIfHasScheme,
                'regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
                'unique:domains,name',
            ],
        ];
    }

    public function messages()
    {
        return [
            'domain.unique' => 'Domain is either already whitelisted or currently subscribed by a client.'
        ];
    }
}
