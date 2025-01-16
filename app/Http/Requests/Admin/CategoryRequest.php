<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required|unique:categories,name,'.$this->request->get('id')
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the categorie name is required.',
            'name.unique' => 'the categorie already exists.',
        ];
    }
}
