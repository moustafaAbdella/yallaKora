<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionHistoryRequest extends FormRequest
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
            'subscription.duration' => 'required',
            'subscription.currency' => 'required',
            'subscription.price' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'subscription.duration.required' => '  رجاء اختيار المدة  .',
            'subscription.currency.required' => ' رجاء اختيار العملة    .',
            'subscription.price.required' => ' رجاء كتابة سعر اشتراك .',
        ];
    }
}
