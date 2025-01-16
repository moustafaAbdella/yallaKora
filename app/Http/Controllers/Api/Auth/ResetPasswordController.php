<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\ResetCodePassword;
use Illuminate\Foundation\Auth\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends BaseController
{
    use IssueTokenTrait;

    /**
     * Change the password (Setp 3)
     *
     * @param  mixed $request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $rules = [
            'code' => 'required|string|exists:reset_code_passwords',
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
        ];
        $customMessages = [
            'password.required' =>  'رجاء تاكد من كتابة كلمة سر',
            'password.confirmed' =>  'كلمة سر غير متطابقة',

        ];
        //Create a validator, unlike $this->validate(), this does not automatically redirect on failure, leaving the final control to you :)
        $validated = Validator::make($request->all(), $rules,$customMessages);
    
        //Check if the validation failed, return your custom formatted code here.
        if($validated->fails())
        {
            $errors =  $validated->messages();
            $error =  '';
            foreach ($validated->messages()->toArray() as $key => $value) { 
               $error = $value[0];
            }
            return $this->sendError($error,$errors); 
        }
        
        // find the code
        $passwordReset = ResetCodePassword::where('code', $request->code)->where('email', $request->email)->first();
        if(!$passwordReset){
            return $this->sendError('حدث خطا');
        }
        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return $this->sendError('انتهت صلاحية رمز تحقق'); 
        }

        // find user's email 
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
       // $user->update($request->only('password'));
        $user->password = bcrypt($request['password']);
        $user->save();
        // delete current code 
        $passwordReset->delete();


        $response = $this->issueToken($request, 'password');
        if ( $response['status'] != 200)
        {
            return $this->sendError('اسم المستخدم أو كلمة السر غير صحيحة',$response); 
        }else{
            $user->expires_in = $response['expires_in'] ;
            $user->token = $response['access_token'] ;
            $user->refresh_token = $response['refresh_token'] ;
            return $this->sendResponse($user,null);
        }
        
        // return $this->sendResponse([],' تم اعادة تعين كلمة سر بنجاح ');

    }
}