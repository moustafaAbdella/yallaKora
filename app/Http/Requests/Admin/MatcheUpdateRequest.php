<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Illuminate\Foundation\Http\FormRequest;

class MatcheUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'team_a' => 'required',
            'team_b' => 'required',
            'league' => 'required',
            'matche.startTime' => 'required|date',
            'matche.team_re_a' => 'nullable|numeric',
            'matche.team_re_b' => 'nullable|numeric',

        ];
    }

    public function messages()
    {
        return [
            'team_a.required' => 'رجاء اختيار الفريق الاول',
            'team_b.required' => 'رجاء اختيار الفريق الثاني',
            'league.required' => 'رجاء اختيار البطولة',
            'matche.startTime.required' => 'رجاء ادخال  تاريخ بداية المباراه',
            'matche.startTime.date' => 'رجاء تاكد من كتابة تاريخ بشكل صحيح  .',
            'matche.team_re_a.numeric' => '  رجاء كتابة نتيجة الفريق الاول رقم فقط ',
            'matche.team_re_b.numeric' => 'رجاء   تاكد من كتابة الفريق الثاني رقم فقط',

        ];
    }
}
