<?php

namespace App\Http\Requests\Api;

use Auth;
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RemoveUserRequest
 * @package App\Http\Requests\Frontend\Access
 */
class RemoveUserRequest extends FormRequest
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
        // Validation rule for old password
        \Validator::extend('password', function ($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, current($parameters));
        });

        return [
            'password' => 'required|password:' . Auth::user()->password,
        ];
    }
}
