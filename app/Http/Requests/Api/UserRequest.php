<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;


class UserRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'رجاء ادحال اسم ',
            'email.required' => 'رجاء ادخال بريد الاكتروني',
            'email.email' => 'تاكد من كتابة بريد الاكتروني بشكل صحيح',
            'email.unique' => 'بريد الاكتروني مسجل بالفعل',
            'password.required' => 'رجاء ادخال باسورد',

        ];
    }
}
