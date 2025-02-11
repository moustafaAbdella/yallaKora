<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class LeagueRequest extends FormRequest
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
            'league.name' => 'required|unique:leagues,name,'.$this->request->get('id'),
            'league.name_ar' => 'unique:leagues,name_ar,'.$this->request->get('id'),
            'league.img' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'league.name.required' => 'رجاء كتابة اسم الفريق بالانجليزي.',
            'league.name.unique' => 'اسم الفريق بالانجليزي موجود بالفعل',
            'league.name_ar.unique' => 'اسم الفريق بالعربي موجود بالفعل',
            'league.img.required' => 'رجاء اضافة لوجو الفريق  .',   
        ];
    }
}
