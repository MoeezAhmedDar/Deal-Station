<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\City;
use App\Models\CouponRedeem;
use App\Models\MembershipSubscription;
use App\Models\MerchantDetail;
use App\Models\SubscriptionPlan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Models\Plan;
use App\Models\UserSubscription;

class MemberController extends Controller
{

    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading =  'Member Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-member-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        return view('admin.members.index', compact('page_title'));
    }

    public function fetchMembersData(Request $request)
    {
        $result = array('data' => array());
        $members = User::role('Member')->orderBy('id', 'DESC')->get();
        foreach ($members as $key => $value) {
            $status = '';
            if ($value['status'] == 'Active') {
                $status = 'Block';
            } else {
                $status = 'Activate';
            }
            $buttons = '';
            $button2 = '<a href="' . $value['id'] . '" class="btn btn-primary changeStatus">' . __($status) . '
            </a>';

            $buttons .= '<a href="' . route("members.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="show">
            <i class="far fa-eye"></i>
            </a>';

            if (Auth::user()->can('admin-member-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" href="' . route("members.edit", $value->id) . '" data-toggle="tooltip" data-placement="top" title="edit">
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
            if (Auth::user()->can('admin-member-delete')) {
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
            $result['data'][$key] = array(
                $value['name'],
                $value['email'],
                $value['phone'],
                $button2,
                $buttons
            );
        }
        echo json_encode($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('admin-member-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $city_data = City::all();
        $plans = Plan::wherePlanStatus('1')->get();
        $subscriptions = MembershipSubscription::with('subscription')->get();
        return view('admin.members.create', compact('city_data', 'page_title', 'plans'));
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
            'name' => 'required|max:50|min:5|regex:/^[a-zA-Z ]*$/',
            'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email',
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone',
            'dob' => 'required|before:today',
            'gender' => 'required',
            'city' => 'required|numeric',
        ], [
            'email.regex' => 'Email should be a valid Email.',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make('DealStation@@123');
        $user = User::create($input);
        $user->assignRole('Member');

        if (!empty($input['plan']) && !empty($input['subscription'])) {
            $user->latestMemberSubscription()->delete();
            $user_membership_data = [
                'plan' => $input['plan'],
                'subscription' => $input['subscription'],
                'user_id' =>  $user->id
            ];
            UserSubscription::create($user_membership_data);
        }

        return redirect()->route('members.index')
            ->with('success', __('Member has been added successully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('admin-member-list')) {
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        $city_data = City::all();
        if ($user->hasRole('Member')) {
            $user_subscription =  $user->latestMemberSubscription;
            $plans = Plan::wherePlanStatus('1')->get();
            $subscriptions = MembershipSubscription::with('subscription')->get();
            return view('admin.members.show', compact('user', 'city_data', 'user_subscription', 'plans', 'subscriptions'));
        } else {
            return redirect()->route('members.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('admin-member-edit')) {
            return redirect()->route('dashboard');
        }
        $user = User::find($id);
        $city_data = City::all();
        if ($user->hasRole('Member')) {
            $user_subscription =  $user->latestMemberSubscription;
            $plans = Plan::wherePlanStatus('1')->get();
            $subscriptions = MembershipSubscription::with('subscription')->get();
            return view('admin.members.edit', compact('user', 'city_data', 'user_subscription', 'plans', 'subscriptions'));
        } else {
            return redirect()->route('members.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$id) {
            return redirect()->route('members');
        }
        $input = $request->all();
        $this->validate($request, [
            //Form Attributes
            'name' => 'required|max:50|min:5|regex:/^[a-zA-Z ]*$/',
            'email' => 'required|email|min:5|max:50|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $id,
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone,' . $id,
            'dob' => 'required|before:today',
            'gender' => 'required',
            'city' => 'required|numeric',
        ], [
            'email.regex' => 'Email should be a valid Email.',
        ]);
        if (!empty($input['password'])) {
            $this->validate($request, [
                // Auth Details
                'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            ], [
                'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
                'confirm-password.same' => 'Password Confirmation should match the Password',
            ]);
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        if (!empty($input['plan']) && !empty($input['subscription'])) {
            $user = User::find($id);
            $user->latestMemberSubscription()->delete();
            $user_membership_data = [
                'plan' => $input['plan'],
                'subscription' => $input['subscription'],
                'user_id' =>  $id
            ];
            UserSubscription::create($user_membership_data);
        }

        $user = User::find($id);
        $user->update($input);
        return redirect()->route('members.index')
            ->with('success', __('Member has been edited successully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user->hasRole('Member') && !CouponRedeem::where('user_id', '=', $id)->exists()) {
            if (UserSubscription::where('user_id', '=', $id)->exists()) {
                UserSubscription::where('user_id', '=', $id)->delete();
            }
            User::find($id)->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function changeStatus(Request $request)
    {
        $status = '';
        $showStatus = '';
        if ($request->status == 'Activate') {
            $status = 'Active';
            $showStatus = 'Block';
        } else {
            $status = 'Block';
            $showStatus = 'Activate';
        }

        User::where('id', $request->id)->update([
            'status' => $status,
        ]);

        return response()->json($showStatus);
    }
}
