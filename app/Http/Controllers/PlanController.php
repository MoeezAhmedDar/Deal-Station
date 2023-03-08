<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Models\City;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\MembershipSubscription;
use App\Models\TargetedMembership;
use App\Models\UserSubscription;
use App\Models\VisibleMembership;

class PlanController extends Controller
{
    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading =  'Plan Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-plans-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        // $plans = Plan::orderBy('id', 'DESC')->get();
        return view('admin.plans.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('admin-plans-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $subscription_plans = SubscriptionPlan::where('subscription_name', '!=', 'Trail Subscription')->orderBy('id', 'DESC')->get();

        $city_data = City::all();
        return view('admin.plans.create', compact('page_title', 'subscription_plans', 'city_data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            //Form Attributes
            'plan_name' => 'required|max:50|min:3',
            'plan_name_arabic' => 'required|max:50|min:3',
            'plan_city' => 'required',
            'plan_terms' => 'required|url|max:255',
            'plan_icon' => 'mimes:jpeg,jpg,png,tif,lzw|required|max:2048',
            'plan_subscription' => 'required',
        ], [
            'plan_icon.mimes' => 'Logo must be a Image',
            'plan_icon.max' => 'Image should be 2 MB max.',
            'plan_subscription.required' => 'You have to select at least one Subscription Plan.',
            'plan_subscription_price.required' => 'You have to enter price for Subscription Plan.',
        ]);
        $input = $request->all();
        $plan_icon = $request->plan_icon;
        if ($plan_icon) {
            $file_path = $this->ImageUpload($plan_icon, 'uploads/plans/');
            $plan_icon = $file_path;
        } else {
            $plan_icon = '';
        }
        $planData = [
            'plan_uniid' => Str::uuid()->toString(),
            'plan_name' => $input['plan_name'],
            'plan_name_arabic' => $input['plan_name_arabic'],
            'plan_city' => $input['plan_city'],
            'plan_terms' => $input['plan_terms'],
            'plan_description' => $input['plan_description'],
            'plan_icon' => $plan_icon,
        ];
        $plan = Plan::create($planData);
        $plan_subscription = $request->input('plan_subscription');
        $plan_subscription_price = $request->input('plan_subscription_price');
        $count = count($plan_subscription);
        $items = array();
        $x = 0;
        for ($x = 0; $x < $count; $x++) {
            $key = $plan_subscription[$x] ?? null;
            if ($key != null) {
                $items['plan_id'] = $plan->id;
                $items['subscription_id'] = $plan_subscription[$x];
                $items['subscription_price'] = $plan_subscription_price[$x];
                MembershipSubscription::create($items);
            }
        }
        return redirect()->route('plans.index')
            ->with('success', __('Membership plan has been added successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('admin-plans-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $subscription_plans = SubscriptionPlan::orderBy('id', 'DESC')->get();
        $city_data = City::all();
        $plan = Plan::find($id);
        $plan_subscriptions = Plan::find($id)->planSubscriptions;
        return view('admin.plans.show', compact('page_title', 'plan', 'plan_subscriptions', 'subscription_plans', 'city_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('admin-plans-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $subscription_plans = SubscriptionPlan::where('subscription_name', '!=', 'Trail Subscription')->orderBy('id', 'DESC')->get();
        $city_data = City::all();
        $plan = Plan::find($id);
        $plan_subscriptions = Plan::find($id)->planSubscriptions;
        return view('admin.plans.edit', compact('page_title', 'plan', 'plan_subscriptions', 'subscription_plans', 'city_data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$id) {
            return redirect()->route('plans');
        }
        $input = $request->all();
        $this->validate($request, [
            //Form Attributes
            'plan_name' => 'required|max:50|min:3',
            'plan_name_arabic' => 'required|max:50|min:3',
            'plan_city' => 'required',
            'plan_terms' => 'required|url|max:255',
            'plan_subscription' => 'required',
        ], [
            'plan_icon.mimes' => 'Logo must be a Image',
            'plan_icon.max' => 'Image should be 2 MB max.',
            'plan_subscription.required' => 'You have to select at least one Subscription Plan.',
            'plan_subscription_price.required' => 'You have to enter price for Subscription Plan.',
        ]);
        if (!empty($input['plan_icon'])) {
            $this->validate($request, [
                'plan_icon' => 'mimes:jpeg,jpg,png,tif,lzw|max:2048',
            ], [
                'plan_icon.mimes' => 'Logo must be a Image',
                'plan_icon.max' => 'Image should be 2 MB max.',
            ]);
        }
        $plan_icon = $request->plan_icon;
        if ($plan_icon) {
            $file_path = $this->ImageUpload($plan_icon, 'uploads/plans/');
            $plan_icon = $file_path;
        } else {
            $plan_icon = '';
        }
        $planData = [
            'plan_name' => $input['plan_name'],
            'plan_name_arabic' => $input['plan_name_arabic'],
            'plan_city' => $input['plan_city'],
            'plan_terms' => $input['plan_terms'],
            'plan_description' => $input['plan_description'],
            'plan_icon' => $plan_icon,
        ];
        if (empty($plan_icon)) {
            $planData = Arr::except($planData, array('plan_icon'));
        }
        Plan::where('id', '=', $id)->update($planData);
        $plan = Plan::find($id);
        $plan_subscription = $request->input('plan_subscription');
        $plan_subscription_price = $request->input('plan_subscription_price');
        $count = count($plan_subscription);
        $items = array();
        $x = 0;
        for ($x = 0; $x < $count; $x++) {
            $key = $plan_subscription[$x] ?? null;
            if ($key != null) {
                if (MembershipSubscription::where(
                    [
                        ['plan_id', $plan->id],
                        ['subscription_id', $plan_subscription[$x]],
                    ]
                )->exists()) {
                    $items['plan_id'] = $plan->id;
                    $items['subscription_id'] = $plan_subscription[$x];
                    $items['subscription_price'] = $plan_subscription_price[$x];
                    MembershipSubscription::where(
                        [
                            ['plan_id', $plan->id],
                            ['subscription_id', $plan_subscription[$x]],
                        ]
                    )->update($items);
                } else {
                    $items['plan_id'] = $plan->id;
                    $items['subscription_id'] = $plan_subscription[$x];
                    $items['subscription_price'] = $plan_subscription_price[$x];
                    MembershipSubscription::create($items);
                }
            }
        }
        return redirect()->route('plans.index')
            ->with('success', __('Membership plan has been edited successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plan  $plan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plan = Plan::find($id);
        if ($plan->userSubscriptions()->count() == 0) {
            $plan->planSubscriptions()->delete();
            $plan->targetedMembership()->delete();
            $plan->visibleMembership()->delete();
            $plan->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function fetchPlansData(Request $request)
    {
        $result = array('data' => array());
        $plans = Plan::where('plan_name', '!=', 'Trail Plan')->orderBy('id', 'DESC')->get();
        foreach ($plans as $key => $value) {
            $buttons = '';
            if (Auth::user()->can('admin-plans-list')) {
                $buttons .= '<a href="' . route("plans.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="show">
                <i class="far fa-eye"></i>
                </a>';
            }
            if (Auth::user()->can('admin-plans-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" href="' . route("plans.edit", $value->id) . '" data-toggle="tooltip" data-placement="top" title="edit">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black" />
                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black" />
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            }
            if (Auth::user()->can('admin-plans-delete')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="removeFunc(' . $value->id . ')" data-toggle="tooltip" data-placement="top" title="Delete">
                <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                </svg>
                </span>
                <!--end::Svg Icon-->
                </button>';
            }
            $plan_icon = '<!--begin::Symbol-->
            <div class="symbol symbol-40 symbol-light-primary mr-5">
            <span class="symbol-label">
            <img src="' . asset($value->plan_icon) . '" class="h-75 align-self-end" alt="category icon">
            </span>
            </div>
            <!--end::Symbol-->';
            $result['data'][$key] = array(
                $plan_icon,
                $value['plan_name'],
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function fetchMembershipSubscriptionPlans($id)
    {
        if ($id) {
            $subscriptions = MembershipSubscription::with('subscription')->where('plan_id', '=', $id)->get();
            echo json_encode($subscriptions);
        }
    }
}
