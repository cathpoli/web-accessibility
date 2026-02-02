<?php

namespace App\Http\Requests\Domains;

use Illuminate\Foundation\Http\FormRequest;

class SetWidgetRequest extends FormRequest
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
            'triggerColor' => 'required',
            'triggerPositionX' => 'required',
            'triggerPositionY' => 'required',
            'triggerIcon' => 'required',
            'triggerIconColor' => 'required',
            'widgetHeroTitleColor' => 'required',
            'themeColor' => 'required',
        ];
    }
}
