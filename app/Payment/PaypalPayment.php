<?php

namespace App\Payment;

use App\Models\Payment;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;
use App\Notification\NotificationTe;

class PaypalPayment {

    private $paypal_client_id;
    private $paypal_secret;
    private $verify_route_name;


    public function __construct()
    {
        $this->paypal_client_id = config('nafezly-payments.PAYPAL_CLIENT_ID');
        $this->paypal_secret = config('nafezly-payments.PAYPAL_SECRET');
        $this->verify_route_name = config('nafezly-payments.verify_route_name');
        //$this->cancel_route_name = 'payment_cansel';
    }
    
    /**
     * @param $amount
     * @param null $user
     * @param null $plan
     * @return array|Application|RedirectResponse|Redirector
     */
    public function pay($payment,$price,$user){
        
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));

        // $fields = ['id', 'product_id', 'name', 'description'];
        // $plans = $provider->listPlans(1, 30, true, $fields);
        $sendChat = new NotificationTe;
        $sendChat->sendMsgToPayment(' يقوم  ' . $user->name . ' بالدفع  ' . ' باستخدام باي بال ');

        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route($this->verify_route_name) . '?type=paypal',
                "cancel_url" => route($this->verify_route_name) . '?status=cancel',
            ],
            "purchase_units" => [
                 [
                    "reference_id" => $payment->id,
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $price
                    ]
                ]
            ]
        ]);
        
        if (isset($response['id']) && $response['id'] != null) {
            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            
            return redirect()
                ->route('error-payment')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('error-payment')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }   

    }

    
    /**
     * payment_verify the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function verify($request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
        //return $response;

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
           
            $payment = Payment::find($response['purchase_units'][0]['reference_id']);
            $payment->success = 1; 
            $payment->status = 'completed';
            $payment->save();

            $plan = Subscription::find($payment->subscription_id);
            $user = User::find($payment->user_id );

            $start_at = Carbon::now();
            $ends_at = Carbon::now()->addDays($plan->duration);

            $newSubscription = SubscriptionHistory::create([
                'user_id' => $user->id,
                'subscription_id' => $plan->id,
                'payment_id' => $payment->id,
                'start_at' => $start_at,
                'ends_at'  =>  $ends_at,
                'duration' => $plan->duration,
                'currency' => $plan->currency,
                'price' => $plan->price,
                'type' => $payment->type,
                'active' => 1
            ]);

            $user->ends_at = $ends_at;
            $user->premuim = 1;
            $user->subscription_id= $newSubscription->id;
            $user->save();

            $sendChat = new NotificationTe;
            $sendChat->sendMsgToPayment(' اشترك ' . $user->name . ' بسعر ' . $plan->price . ' لمدة ' . $plan->duration . ' يوم ');

            return redirect( route('verify-payment') . '?status=success')
            ->with('success', 'Transaction complete.');

        } else if (isset($response['status']) && $request->status == 'cancel') {
            
            $payment = Payment::find($response['purchase_units'][0]['reference_id']);
            $payment->status = 'cancel';
            $payment->save();

            $user = User::find($payment->user_id );
            $sendChat = new NotificationTe;
            $sendChat->sendMsgToPayment(' قام ' . $user->name . ' بالغاء ' .  ' الدفع ' . ' id : ' . $payment->id);

            abort(404);

        } else {

            if(isset($response['status']) ) {
                $payment = Payment::find($response['purchase_units'][0]['reference_id']);
                $payment->status = 'fail';
                $payment->save();   
                  
                $user = User::find($payment->user_id );
                $sendChat = new NotificationTe;
                $sendChat->sendMsgToPayment(' لم يتمكن مستخدم ' . $user->name . ' بالدفع ' . ' id: '. $payment->id);
            } 

            return redirect( route('verify-payment') . '?status=fail')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

}