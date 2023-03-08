<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Plan;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use App\Models\City;
use App\Models\MembershipSubscription;
use Carbon\Carbon;
use Illuminate\Support\Str;

class MembershipController extends BaseController
{
    public function fetchMembershipPlans(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $result = array();
            $plans = Plan::where([
                ['plan_status', '=', 1],
                ['plan_name', '!=', 'Trail Plan']
            ])->orderBy('id', 'DESC')->get();
            foreach ($plans as $plan) {
                $user_subscription = false;
                if ($user_data->latestMemberSubscription) {
                    $user_subscription = $plan->id == $user_data->latestMemberSubscription->plan ? true : false;
                }
                $planData = [
                    'id' => $plan->id,
                    'plan_name' => $plan->plan_name,
                    'plan_name_arabic' => $plan->plan_name_arabic,
                    'plan_icon' => url($plan->plan_icon),
                    'plan_terms' => $plan->plan_terms,
                    'plan_description' => $plan->plan_description,
                    'plan_select' => $user_subscription
                ];
                $result[] = $planData;
            }
            return $this->responseApi($result, true, 'Plans Data Fetched', 200);
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        };
    }

    public function fetchMembershipSubscriptionPlans(Request $request)
    {
        if ($request->user()) {
            $input = $request->only('id');
            $id = $input['id'];
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $subscriptionData = array();
            $plan = Plan::find($id);
            $city = City::find($plan->plan_city);
            $plan_subscriptions = Plan::find($id)->planSubscriptions;
            foreach ($plan_subscriptions as $plan_subscription) {
                $subscription = SubscriptionPlan::find($plan_subscription->subscription_id);
                $user_subscription = false;
                if ($user_data->latestMemberSubscription) {
                    $user_subscription = $plan_subscription->id == $user_data->latestMemberSubscription->subscription ? true : false;
                }
                $subscriptionData[] = [
                    'id' => $plan_subscription->id,
                    'subscription_name' => $subscription->subscription_name,
                    'subscription_arabic' => $subscription->subscription_name_arabic,
                    'subscription_duration' => $subscription->subscription_duration,
                    'subscription_price' => (float)$plan_subscription->subscription_price,
                    'subscription_description' => $subscription->subscription_description,
                    'city_name' => $city->city_name,
                    'city_name_arabic' => $city->city_name_arabic,
                    'subscription_select' => $user_subscription
                ];
            }
            return $this->responseApi($subscriptionData, true, 'Membership Subscription Data Fetched', 200);
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        };
    }

    public function memberSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan' => 'required',
            'subscription' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $user_data = $request->user();
            if ($user_data->is_completed == 'true') {
                $inputs = $request->all();
                $membership_data = MembershipSubscription::with('subscription')->find($inputs['subscription']);

                if ($membership_data->subscription_price == 0) {
                    $user_membership_data = [
                        'transaction_id' => Str::uuid()->toString(),
                        'plan' => $inputs['plan'],
                        'subscription' => $inputs['subscription'],
                        'user_id' =>  $user_data['id'],
                        'user_subscriptions_expiry' =>  Carbon::now()->addMonths($membership_data->subscription->subscription_duration),
                        'user_subscriptions_payment_status' =>  'Paid',
                    ];
                    $us_date = UserSubscription::create($user_membership_data);
                    return $this->responseApi([
                        'checkout_url' => 'automatically_subscribed',
                    ], true, 'Subscription Created', 200);
                } else {
                    $user_membership_data = [
                        'transaction_id' => Str::uuid()->toString(),
                        'plan' => $inputs['plan'],
                        'subscription' => $inputs['subscription'],
                        'user_id' =>  $user_data['id'],
                        'user_subscriptions_expiry' =>  Carbon::now()->addMonths($membership_data->subscription->subscription_duration),
                        'user_subscriptions_payment_status' =>  'Pending',
                    ];
                    $us_date = UserSubscription::create($user_membership_data);
                    $checkout_page = route('payment.checkout', $us_date->transaction_id);
                    return $this->responseApi([
                        'checkout_url' => $checkout_page,
                    ], true, 'Member Subscription Created', 200);
                }
            } else {
                return $this->responseApi([], false, __('Please, Complete your profile first.'), 417);
            }
        }
    }
}
