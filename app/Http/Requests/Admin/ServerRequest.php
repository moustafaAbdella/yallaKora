<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
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
            'name'      => 'required|unique:servers,name,' . $this->request->get('id'),
            'domain'    => 'nullable',
            'useragent' => 'nullable',
            'type'      => 'nullable',
            'player'    => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'the server name is required.',
            'name.unique'   => 'the server already exists.',
        ];
    }
}
