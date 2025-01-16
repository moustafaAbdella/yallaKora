<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class TeamRequest extends FormRequest
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
            'team.name' => 'required|unique:teams,name,'.$this->request->get('id'),
            'team.name_ar' => 'unique:teams,name_ar,'.$this->request->get('id'),
            'team.img' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'team.name.required' => 'رجاء كتابة اسم الفريق بالانجليزي.',
            'team.name.unique' => 'اسم الفريق بالانجليزي موجود بالفعل',
            'team.name_ar.unique' => 'اسم الفريق بالعربي موجود بالفعل',
            'team.img.required' => 'رجاء اضافة لوجو الفريق  .',   
        ];
    }
}
