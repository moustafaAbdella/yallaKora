<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class LivetvUpdateRequest extends FormRequest
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
            'livetv.name' => 'required',
            'livetv.logo' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'livetv.name.required' => 'رجاء كتابة اسم الفريق بالانجليزي.',
            'livetv.logo.required' => 'رجاء اضافة لوجو الفريق  .',   
        ];
    }
}
