<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Models\User;
use App\Http\Requests\Api\UserRequest;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Http\Requests\Api\RemoveUserRequest;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class UserController extends BaseController
{
    use IssueTokenTrait;

    public function __construct()
    {
        //$this->client = Client::find('9772767f-86f3-423f-b4d4-72b3205cdc26');
    }

    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ];
        $customMessages = [
            'name.required' => 'رجاء ادخال اسم ',
            'email.required' => 'رجاء ادخال بريد الاكتروني',
            'email.email' => 'تاكد من كتابة بريد الاكتروني بشكل صحيح',
            'email.unique' => 'بريد الاكتروني مسجل بالفعل',
            'password.required' => 'رجاء ادخال باسورد',


        ];
        //Create a validator, unlike $this->validate(), this does not automatically redirect on failure, leaving the final control to you :)
        $validated = Validator::make($request->all(), $rules,$customMessages);
        if($validated->fails())
        {
            $errors =  $validated->messages();
            $error =  '';
            foreach ($validated->messages()->toArray() as $key => $value) {
               $error = $value[0];
            }
            return $this->sendError($error,$errors);
        }
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'premuim' => false,
            'password' => bcrypt(request('password'))
        ]);

        $response = $this->issueToken($request, 'password');
        if ( $response['status'] != 200)
        {
            return $this->sendError('اسم المستخدم أو كلمة السر غير صحيحة',$response);
        }else{

            return $this->sendResponse($response,null);
        }
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $rules = [
                'username' => 'required|email',
                'password' => 'required'
        ];
        $customMessages = [
            'username.required' =>  'رجاء تاكد من كتابة بريد الاكتروني',
            'password.required' =>  'رجاء تاكد من كتابة كلمة سر',
            'username.email' =>  'رجاء تاكد من كتابة بريد الاكتروني بشكل صحيح',

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
        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];
        $response = $this->issueToken($request, 'password');
        if ( $response['status'] != 200)
        {
            return $this->sendError('اسم المستخدم أو كلمة السر غير صحيحة',$response);
        }else{

            return $this->sendResponse($response,null);
        }
    }

    public function userInfo()
    {

     $user = auth()->user();
     return $this->sendResponse($user,null);
    }

    public function updateUser(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
        ];
        $customMessages = [
            'name.required' => 'رجاء ادخال اسم ',
            'email.required' => 'رجاء ادخال بريد الاكتروني',
            'email.email' => 'تاكد من كتابة بريد الاكتروني بشكل صحيح',
        ];
        //Create a validator, unlike $this->validate(), this does not automatically redirect on failure, leaving the final control to you :)
        $validated = Validator::make($request->all(), $rules,$customMessages);
        if($validated->fails())
        {
            $errors =  $validated->messages();
            $error =  '';
            foreach ($validated->messages()->toArray() as $key => $value) {
               $error = $value[0];
            }
            return $this->sendError($error,$errors);
        }
        $user = Auth::user();
        $user->name = $request->name;
        if($user->email != $request->email){
            $user = User::where('email',$request->email)->first();;
            if($user){
                return $this->sendError('بريد الاكتروني مسجل بالفعل');
            }
            $user->email = $request->email;
        }
        $user->save();
        return $this->sendResponse($user,null);
    }

    public function updatePassUser(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();
        return $this->sendResponse($user,null);
    }

    public function removeUser(RemoveUserRequest $request)
    {
        $user = Auth::user();
        $user->delete();
//        $user->remove = 1;
//        $date = Carbon::now();
//        $user->remove_at = $date->addDay(15);
//        $user->save();
        return $this->sendResponse($user,null);
    }

    public function logout(Request $request) {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();
        return $this->sendResponse([],null);
    }
}
