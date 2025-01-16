<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'plan.duration' => 'required',
            'plan.currency' => 'required',
            'plan.price' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'plan.duration.required' => '  رجاء اختيار المدة  .',
            'plan.currency.required' => ' رجاء اختيار العملة    .',
            'plan.price.required' => ' رجاء كتابة سعر اشتراك .',
        ];
    }
}
