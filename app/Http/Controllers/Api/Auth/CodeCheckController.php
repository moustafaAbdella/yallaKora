<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;

class CodeCheckController extends BaseController
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:reset_code_passwords',
            'email' => 'required|email|exists:users',
        ]);

        // find the code
        $passwordReset = ResetCodePassword::where('code', $request->code)->where('email', $request->email)->first();

        if(!$passwordReset){
            return $this->sendError('حدث خطا',[],403);
        }
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return $this->sendError('انتهت صلاحية رمز تحقق',[],403);
        }
        return $this->sendResponse(['code' => $passwordReset->code],'رمز صحيح');
    }
}
