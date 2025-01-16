<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\ResetCodePassword;
use App\Mail\SendCodeResetPassword;
use App\Mail\Subscribe;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;

class ForgotPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        // Delete all old code that user send before.
        ResetCodePassword::where('email', $request->email)->delete();

        // Generate random code
        $data['code'] = mt_rand(100000, 999999);

        // Create a new code
        $codeData = ResetCodePassword::create($data);

        // Send email to user
        
        Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

        return $this->sendResponse([],'تم  ارسال رمز تحقق علي بريد الاكتروني  ');
    }
}
