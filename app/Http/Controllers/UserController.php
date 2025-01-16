<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Admin\UserRequestStore;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Http\Requests\Admin\ChangePasswordRequest;
use App\Models\User;
use Auth;




class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    ////عاعخع
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $query = $request->q;

        if(isset($query)) {
           $users = User::where('name', 'LIKE', "%$query%")->orWhere('email', 'LIKE', "%$query%")->limit(6)->get();
        }else{
            return [];
        }
        return response()->json($users, 200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(User::orderByDesc('created_at')
        ->paginate(6), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        return response()->json(['count'=>User::count()], 200);
    }

    // create a new genre in the database
    public function store(UserRequestStore $request)
    {
        $user = new User();
         
        $user->fill($request->user);
          
        $user->save();
        $user = User::find($user->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $user
        ];

        return response()->json($data, $data['status']);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $user)
    {
        $user = User::find($user);
        if($user != null) {
            $user->fill($request->user);
            $user->save(); 
            $data = [
                'status' => 200,
                'message' => 'تم تحديث حساب بنجاح',
                'body' => $user
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد حساب',
                'body' => $user
            ];   
        }

        return response()->json($data, $data['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($user)
    {
        $user = User::find($user);

        if ($user != null) {

            $user->delete();

            $data = [
                'status' => 200,
                'message' => 'successfully removed',
            ];
        } else {
            $data = [
                'status' => 400,
                'message' => 'could not be deleted',
            ];
        }

        return response()->json($data, $data['status']);

    }

    /**
     * update admin
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateAdmin(Request $request)
    {
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        flash('تم تحديث بروفايل بنجاح')->success();
        return back();
    }

    /**
     * update password admin
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updatePasswordAdmin(ChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = bcrypt($request['password']);
        $user->save();
        flash('تم تحديث كلمة سر بنجاح')->success();
        return back();
    }
}
