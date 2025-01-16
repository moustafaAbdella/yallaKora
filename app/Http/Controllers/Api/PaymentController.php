<?php

namespace App\Http\Controllers\Api;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Notification\NotificationTe;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Models\User;
use App\Payment\PaypalPayment;
use App\Http\Controllers\Api\BaseController as BaseController;

class PaymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * create payment
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function payment(Request $request)
    {
        $plan = Subscription::find($request->plan_id);
        $user = auth()->user();

        if(!$plan){
            return $this->sendError('لم يتم ايجاد خطة'); 
        }

        if ($user->premuim == 1) {
            return $this->sendError('انت مشترك بالفعل'); 
        }

        if($request->type == 'paypal'){

            // $payment_history = Payment::create([
            //     'user_id' => $user->id,
            //     'subscription_id' => $plan->id,
            //     'type' => $request->type,
            //     'success' => 0,
            //     'status' => 'padding',
            // ]);

            // $payment = new PayPalPayment();
            // $sendPayment = $payment->pay($payment_history,$plan->price,$user);
            // if($sendPayment['status'] == 'done'){
                return $this->sendResponse([
                    'status'     => 'done',
                    'link'       => env('APP_URL') . '/buy?user_id='. $user->id .'&plan_id=' . $plan->id,
                    'successUrl' => route('verify-payment') . '?status=success',
                    'cancelUrl'  => route('error-payment') . '?status=fail',
                ],null);
            // }else{
            //     return $this->sendError($sendPayment['error']); 
            // }

        }else{
            return $this->sendError('حدث خطا'); 
        }
        return $this->sendError('حدث خطا'); 
    }
    
    /**
     * payment_verify the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        if ($request->status == 'fail' || $request->status == 'success') {
            abort(404);
        }

        if ( $request->type == 'paypal' ) {
            $payment = new PayPalPayment();
            return $payment->verify($request);
        } else {
            abort(404);
        }
        
    }

    public function errorPayment(Request $request)
    {
        // return $request->error;
        $payment = new PayPalPayment();
        return $payment->verify($request);
    }

    public function test(Request $request)
    {
        $payment = new PayPalPayment();
        return $payment->webhock($request);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
