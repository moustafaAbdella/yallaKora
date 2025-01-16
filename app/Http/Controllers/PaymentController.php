<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Payment\PaypalPaymentV2;
use Illuminate\Http\Request;
use App\Notification\NotificationTe;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Models\User;
use App\Payment\PaypalPayment;

class PaymentController extends Controller
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
        // session()->put('user_id', $request['user_id']);
        // session()->put('plan_id', $request->order_id);
        //  return $request->user_id;
        // $sendChat = new NotificationTe;
        // $sendChat->sendMsgToAdmin('dsadsadsadassdas');
        if ($request->type != null && $request->user_id != null && $request->plan_id) {

            $plan = Subscription::find($request->plan_id);
            $user = User::find($request->user_id);

            if ($request->type == 'paypal' && $plan && $user && $user->premuim != 1) {

                $payment_history = Payment::create([
                    'user_id'         => $user->id,
                    'subscription_id' => $plan->id,
                    'type'            => $request->type,
                    'success'         => 0,
                    'status'          => 'padding',
                ]);

                $payment = new PayPalPayment();
                return $payment->pay($payment_history, $plan->price, $user);
            } else {
                abort(404);
            }

        } else if ($request->user_id != null && $request->plan_id) {
            $plan = Subscription::find($request->plan_id);
            $user = User::find($request->user_id);


            if ($plan && $user) {
                return view('payment.pay', [ 'user_id' => $request->user_id, 'plan' => $plan ]);
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    public function makePayment(Request $request)
    {
        if ( $request->user_id != null && $request->plan_id) {
            // try {


            $plan            = Subscription::find($request->plan_id);
            $user            = User::find($request->user_id);
            $payment_history = Payment::create([
                'user_id'         => $user->id,
                'subscription_id' => $plan->id,
                'type'            => 'paypal',
                'success'         => 0,
                'status'          => 'padding',
            ]);
            $payment         = new PaypalPaymentV2();
            $order           = $payment->createOrder($payment_history, $plan->price, $user);
            return response()->json([ 'id' => $order]);

            // } catch (\Exception $e) {
            // }
        }
        abort(404);
    }

    /**
     * payment_verify the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function executePayment(Request $request){
        // if ($request->status == 'fail' || $request->status == 'success') {
        //     abort(404);
        // }
        if ($request->type == 'paypal') {
            $payment = new PayPalPaymentV2();
            return $payment->verify($request);
        } else {
            abort(404);
        }
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

        if ($request->type == 'paypal') {
            $payment = new PayPalPayment();
            return $payment->verify($request);
        } else {
            abort(404);
        }

    }

    public function errorPayment(Request $request)
    {
        return $request->error;
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
