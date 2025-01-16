<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Http\Requests\Admin\SubscriptionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{

         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data()
    {
        return response()->json(Subscription::orderByDesc('created_at')->get(), 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function count()
    {
        $users = DB::table('subscription_histories')
            ->where('start_at', '>', DB::raw('DATE_ADD(CURDATE(), INTERVAL -30 DAY)'))
            ->select(DB::raw('count(*) as user_count, SUM(price) as total'))->get();
        return  $users[0];
    }

    // create a new genre in the database
    public function store(SubscriptionRequest $request)
    {
        $subscription = new Subscription();
         
        $subscription->fill($request->plan);

        $subscription->save();
        
        $subscription = Subscription::find($subscription->id);

        $data = [
            'status' => 200,
            'message' => 'successfully created',
            'body' => $subscription
        ];

        return response()->json($data, $data['status']);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function changeActive(Request $request, $subscription)
    {
        
        $subscription = Subscription::find($subscription);

        $active = $request->active;
        if($active && ($active === 'true' || $active === 'false')) {
            $active = $active === 'true'? 1: 0;
        }
        if($subscription != null) {
            $subscription->active = $active;
            $subscription->save();
    
            $data = [
                'status' => 200,
                'message' => 'تم تغير بنجاح',
                'body' => $subscription
            ];
        }else{
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد بطولة',
                'body' => $subscription
            ];
        }
        return response()->json($data, $data['status']);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriptionRequest $request, $subscription)
    {
        $subscription = Subscription::find($subscription);
        if($subscription != null) {

            $subscription->fill($request->plan);
            $subscription->save(); 

            $subscription = Subscription::find($subscription->id);

            $data = [
                'status' => 200,
                'message' => 'تم تحديث خطة بنجاح',
                'body' => $subscription
            ];
        }else {
            $data = [
                'status' => 400,
                'message' => 'لم يتم ايجاد خطة',
                'body' => $subscription
            ];   
        }

        return response()->json($data, $data['status']);
    }
    
}
