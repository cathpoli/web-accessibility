<?php

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
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
        /** Domain name regex validation: https://stackoverflow.com/a/26987741 */
        /** For domain and subdomain: https://stackoverflow.com/a/49134699 */
        return [
            'name' => 'required|regex:/^([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]([-a-zA-Z0-9]{0,61}[a-zA-Z0-9])?\.)?([a-zA-Z0-9]{1,2}([-a-zA-Z0-9]{0,252}[a-zA-Z0-9])?)\.([a-zA-Z]{2,63})$/',
            'plan_id' => 'required|exists:products,id',
            'coupon' => 'nullable|exists:coupons,code',
        ];
    }

    public function attributes()
    {
        return [
            'plan_id' => 'plan',
        ];
    }
}
