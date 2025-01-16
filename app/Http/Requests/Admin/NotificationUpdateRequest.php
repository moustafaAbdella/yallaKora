<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class NotificationUpdateRequest extends FormRequest
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
            'key' => 'required|unique:notifications,key,'.$this->request->get('id'),
            'value' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'key.required' => 'the key is required.',
            'key.unique' => 'the key already exists.',
            'value.required' => 'the value is required.',
        ];
    }
}
