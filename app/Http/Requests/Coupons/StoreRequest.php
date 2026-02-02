<?php

namespace App\Http\Requests\Coupons;

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
            'id' => 'nullable|min:6|alpha_num|unique:coupons,id',
            'name' => 'required|string',
            'type' => 'required|in:percent_off,amount_off',
            'percent_off' => 'nullable|required_if:type,percent_off|integer',
            'discount_amount' => 'nullable|required_if:type,amount_off|integer',
            'duration' => 'required|in:forever,once,repeating',
            'number_of_months' => 'required_if:duration,repeating',
            'applies_to_checkbox' => 'required|boolean',
            'applies_to' => 'exclude_if:applies_to_checkbox,false|required|exists:products,stripe_product'
        ];
    }

    function messages()
    {
        return [
            'applies_to.required' => 'Select a product to create a product-specific coupon.',
            'applies_to.exists' => 'The selected product is invalid.',
        ];
    }
}
