<?php

namespace Database\Seeders;

use App\Models\MembershipSubscription;
use App\Models\Plan;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrialPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptionData = [
            'subscription_uniid' => Str::uuid()->toString(),
            'subscription_name' => 'Trail Subscription',
            'subscription_name_arabic' => 'اشتراك درب',
            'subscription_description' => 'Trail period subscription.',
        ];
        $subscription_plan_data = SubscriptionPlan::create($subscriptionData);

        $planData = [
            'plan_uniid' => Str::uuid()->toString(),
            'plan_name' => 'Trail Plan',
            'plan_name_arabic' => 'خطة درب',
            'plan_city' => 0,
            'plan_terms' => 'No Terms',
            'plan_icon' => 'No Icon'
        ];
        $plan_data = Plan::create($planData);

        $item = [
            'plan_id' => $plan_data->id,
            'subscription_id' => $subscription_plan_data->id,
            'subscription_price' => 0
        ];
        MembershipSubscription::create($item);
    }
}
