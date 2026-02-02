<?php

namespace App\Http\Requests\Coupons;

use Illuminate\Foundation\Http\FormRequest;

class StorePeriodOffRequest extends FormRequest
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
            'id' => 'nullable|min:6|alpha_num|unique:coupons,id',
            'name' => 'required|string',
            'type' => 'required|string',
            'period_off' => 'required|integer',
        ];
    }
}
