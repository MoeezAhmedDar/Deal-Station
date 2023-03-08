<?php

namespace App\Http\Controllers;

use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function prepareCheckout($trans_id, Request $request)
    {
        $user_subscription = UserSubscription::with(['membership', 'member'])->where([
            ['transaction_id', '=', $trans_id],
            ['user_subscriptions_payment_status', '!=', 'Paid'],
            ['user_subscriptions_payment_status', '!=', 'Canceled'],
        ])->first();
        if ($user_subscription) {
            $ip = $request->ip();
            $payment_config_object = config('services.payment-hyperpay');
            $url = $payment_config_object['hyper_payment_url'];
            $data = '&paymentType=DB' .
                '&testMode=INTERNAL' .
                '&merchantTransactionId=' . $user_subscription->transaction_id .
                '&entityId=' . $payment_config_object['hyper_entity_id'] .
                '&amount=' . $user_subscription->membership->subscription_price .
                '&currency=' . $payment_config_object['hyper_currency'] .
                '&customer.email=' . $user_subscription->member->email .
                '&customer.mobile=' . $user_subscription->member->phone .
                '&customer.ip=' . $ip .
                '&customer.category=INDIVIDUAL';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer ' . $payment_config_object['hyper_access_token']
            ));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $payment_config_object['hyper_ssl_verifier']); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);
            $checkout_data = json_decode($responseData);
            return view('payment.checkout', compact('checkout_data', 'trans_id'));
        } else {
            echo 'Incorrect parameters, Already completed or broken link. Please try again.';
        }
    }

    public function proceedPayment($trans_id, Request $request,)
    {
        $user_subscription = UserSubscription::with(['membership', 'member'])->where([
            ['transaction_id', '=', $trans_id],
            ['user_subscriptions_payment_status', '!=', 'Paid'],
            ['user_subscriptions_payment_status', '!=', 'Canceled'],
        ])->first();
        if ($user_subscription) {
            $payment_config_object = config('services.payment-hyperpay');
            $url = $payment_config_object['hyper_payment_url'] . '/' . $request->id . "/payment";
            $url .= "?entityId=" . $payment_config_object['hyper_entity_id'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization:Bearer ' . $payment_config_object['hyper_access_token']
            ));

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $payment_config_object['hyper_ssl_verifier']); // this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $responseData = curl_exec($ch);
            if (curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);

            $response_data = json_decode($responseData);
            // 000.100.110
            // 000.000.000
            $message = $response_data->result->description;
            sleep(3);
            if ($response_data->result->code == "000.100.110" || $response_data->result->code == "000.000.000") {
                $user_subscription->update(['user_subscriptions_payment_status' => 'Paid']);
                $mode = 'success';
                return redirect()->route('payment.response', $mode)->with('success', $message);
            } else {
                $mode = 'error';
                return redirect()->route('payment.response', $mode)->with('error', $message);
            }
            return $response_data;
        } else {
            echo 'Incorrect parameters, Already completed or broken link. Please try again.';
        }
    }
}
