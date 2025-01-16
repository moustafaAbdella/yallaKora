<?php

namespace App\Payment;

use App\Models\Payment as DBPayment;
use App\Models\User;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use App\Notification\NotificationTe;
use Carbon\Carbon;
use Predis\Command\Traits\BloomFilters\Expansion;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentV2
{

    protected $paypal;

    public function __construct()
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        // $provider = \Srmklive\PayPal\Services\PayPal::setProvider();
        $this->paypal = $provider;
    }
    public function createOrder($payment2, $price, $user)
    {

        $sendChat = new NotificationTe;

        $msg = "💸 عملية دفع جديدة 💸

        ☉ الحالة: جاري الدفع
        ☉ أيدي المستخدم: $user->id
        ☉ اسم المستخدم: $user->name
        ☉ أميل المستخدم: $user->email
        ☉ المبلغ بالدولار:  $price$ 
        🪔 اشعار تلقائي من بوت #سهرة  
        
        ✅ البوت الرسمي @admin yalakora";

        $order = $this->paypal->createOrder([
            'intent'              => 'CAPTURE',
            'purchase_units'      => [
                [
                    'reference_id' => $payment2->id,
                    'amount'       => [
                        'currency_code' => 'USD',
                        'value'         => $price,
                    ],
                ],
            ],
            'application_context' => [
                'reference_id' => $payment2->id
            ]
        ]);

        return $order['id'];
    }

    public function verify($request)
    {
        try {
            $response = $this->paypal->capturePaymentOrder($request['orderId']);
            // return $response;
            // Check if payment was successful
            if ($response['status'] === 'COMPLETED') {

                // Payment was successful
                // You can update your database, send notifications, etc.
                $transaction = $response['purchase_units'][0];
                $amount      = $transaction['payments']['captures'][0]['amount']['value'];
                // $description = $transaction->getDescription();
                // $referenceId = $transaction->getCustom();
                $referenceId = $transaction['reference_id'];

                $payment          = DBPayment::find($referenceId);
                $payment->success = 1;
                $payment->status  = 'completed';
                $payment->save();

                $plan = Subscription::find($payment->subscription_id);
                $user = User::find($payment->user_id);

                // $start_at = Carbon::now();
                // $ends_at = Carbon::now()->addDays($plan->duration)->format('Y-m-d H:i:s');
                // $duration = 1000000; // duration in days
                $start_at = Carbon::now();
                $ends_at  = $start_at->copy()->addDays($plan->duration);

                $newSubscription = SubscriptionHistory::create([
                    'user_id'         => $user->id,
                    'subscription_id' => $plan->id,
                    'payment_id'      => $payment->id,
                    'start_at'        => $start_at,
                    'ends_at'         => $ends_at,
                    'duration'        => $plan->duration,
                    'currency'        => $plan->currency,
                    'price'           => $plan->price,
                    'type'            => $payment->type,
                    'active'          => 1
                ]);

                $user->ends_at         = $ends_at;
                $user->premuim         = 1;
                $user->subscription_id = $newSubscription->id;
                $user->save();

                $sendChat = new NotificationTe;
                $msg      = "💸 عملية اشتراك جديدة 💸

            ☉ الحالة: تم الدفع بنجاح
            ☉ أيدي المستخدم: $user->id
            ☉ اسم المستخدم: $user->name
            ☉ أميل المستخدم: $user->email
            ☉ المبلغ بالدولار:  $plan->price $ 
            ☉  لمدة:  $plan->duration
            ☉  تاريخ انتهاء اشتراك:  $ends_at
            🪔 اشعار تلقائي من بوت #سهرة  
            
            ✅ البوت الرسمي @admin yalakora";
                $sendChat->sendMsgToPayment($msg);

               
                return redirect(route('verify-payment') . '?status=success')
                    ->with('success', 'Transaction complete.');
            } else {
                $transaction = $response['purchase_units'][0];
                $referenceId = $transaction['reference_id'];

                $payment         = DBPayment::find($referenceId);
                $payment->status = 'fail';
                $payment->save();

                $user     = User::find($payment->user_id);
                $sendChat = new NotificationTe;
                $sendChat->sendMsgToPayment(' لم يتمكن مستخدم ' . $user->name . ' بالدفع ' . ' id: ' . $payment->id);

                return redirect(route('verify-payment') . '?status=fail')
                    ->with('error', 'Payment was not approved.');
                // Payment was not successful
                // You can handle this case accordingly
            }
        } catch (\Exception $e) {
            return redirect(route('verify-payment') . '?status=fail')
                ->with('error', 'Payment was not approved.');
        }
    }
}