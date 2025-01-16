<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionHistory;
use App\Models\User;
use App\Http\Requests\Admin\SubscriptionRequest;
use App\Http\Requests\Admin\SubscriptionHistoryRequest;
use Illuminate\Http\Request;

class SubscriptionHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(SubscriptionHistory::with('user')->orderByDesc('created_at')->paginate(6), 200);
    }

    // create a new genre in the database
    public function store(SubscriptionHistoryRequest $request)
    {
       $subscriptionHistory = new SubscriptionHistory();
        
       $subscriptionHistory->fill($request->subscription);
       if ($request->user) {
            $user = User::find($request->user['value']);
    
            if($user!= null){
                $subscriptionHistory->user_id  = $user->id;
                $user->premuim = $subscriptionHistory->active;
                $user->ends_at = $subscriptionHistory->ends_at;
                $user->save();
            }else {
                $data = [
                    'status' => 400,
                    'message' => 'تاكد من اختيار حساب مشترك  بشكل صحيح',
                ];
        
                return response()->json($data, $data['status']);
            }
       }
       $subscriptionHistory->save();
       
       $subscriptionHistory = SubscriptionHistory::with('user')->find($subscriptionHistory->id);
    
       $data = [
           'status' => 200,
           'message' => 'successfully created',
           'body' => $subscriptionHistory
       ];
    
       return response()->json($data, $data['status']);
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Subscription  $subscriptionHistory
    * @return \Illuminate\Http\Response
    */
    public function update(SubscriptionRequest $request, $subscriptionHistory)
    {
       $subscriptionHistory = SubscriptionHistory::find($subscriptionHistory);
       if($subscriptionHistory != null) {
    
            $subscriptionHistory->fill($request->subscription);
            $subscriptionHistory->save(); 
    
            $subscriptionHistory = SubscriptionHistory::find($subscriptionHistory->id);
    
            $user = User::find($subscriptionHistory->user_id);
            if($user!= null){
                $user->premuim = $subscriptionHistory->active;
                $user->ends_at = $subscriptionHistory->ends_at;
                $user->save();
            }

            $data = [
               'status' => 200,
               'message' => 'تم تحديث خطة بنجاح',
               'body' => $subscriptionHistory
            ];
        }else {
           $data = [
               'status' => 400,
               'message' => 'لم يتم ايجاد خطة',
               'body' => $subscriptionHistory
           ];   
        }
    
        return response()->json($data, $data['status']);
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Team  $team
     * @return \Illuminate\Http\Response
     */
    public function destroy( $subscriptionHistory)
    {
        $subscriptionHistory = SubscriptionHistory::find($subscriptionHistory);
        
        $user = User::find($subscriptionHistory->user_id);
       
        if ($subscriptionHistory != null) {

            $subscriptionHistory->delete();
            
            if($user!= null){
                $user->subscription_id = null;
                $user->premuim = 0;
                $user->ends_at = null;
                $user->save();
            } 
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
}

