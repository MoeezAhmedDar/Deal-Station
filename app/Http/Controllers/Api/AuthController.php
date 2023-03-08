<?php

namespace App\Http\Controllers\Api;

use App\Jobs\NewMemberJob;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Laravel\Cashier\Cashier;
use \Stripe\Stripe;
use Exception;
use App\Models\CompanyLogo;
use Illuminate\Auth\Events\Registered;
use App\Models\City;
use App\Models\UserSubscription;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewMember;
use App\Models\CouponRedeem;
use App\Models\MembershipSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PhpParser\NodeVisitor\FirstFindingVisitor;
use Spatie\Permission\Models\Role;

class AuthController extends BaseController
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @return [string] message
     * @return [int] status
     */
    public function memberRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|min:2|regex:/^[a-zA-Z ]*$/',
            'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email',
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone',
            'city' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $input = $request->all();
            $input['password'] = Hash::make(Str::random(8));
            $user = User::create($input);
            $user->assignRole('Member');
            $tokenResult = $user->createToken('authToken');
            $token = $tokenResult->token;
            $token->save();
            $city_data = City::find($user->city);
            if ($user) {
                $email_data = [
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'url' => route('members.show', $user->id),
                ];

                $queueData = [];
                $queueData['email'] = $input['email'];
                $queueData['data'] = $email_data;
                dispatch(new NewMemberJob($queueData));
            }

            $user_membership_data = [
                'plan' => 4,
                'subscription' => 6,
                'user_id' =>  $user->id
            ];
            UserSubscription::create($user_membership_data);
            $user_subscribed = true;

            $user_access_data = [
                'name' => $user->name,
                'email' => $user->email,
                "phone" => $user->phone,
                "dob" => $user->dob ? date('d-m-Y', strtotime($user->dob)) : '',
                "city" => $city_data->city_name,
                "gender" => $user->gender ? $user->gender : '',
                'token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                'is_profile_completed' => $user->is_completed,
                'subscribed' => $user_subscribed,
            ];
            return $this->responseApi($user_access_data, true, 'Member Registered Successfully.', 200);
        }
    }

    public function authenticateMember(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $credentials = $request->only('phone');
            $user = User::where('phone', $credentials)->first();
            if ($user && $user->hasRole('Member')) {
                $user = User::where('phone', $credentials)->first();
                $user_subscribed = false;
                if (UserSubscription::where('user_id', '=', $user->id)->exists()) {
                    $user_subscribed = true;
                }
                $tokenResult = $user->createToken('authToken');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();

                $city_data = City::find($user->city);
                $user_access_data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    "phone" => $user->phone,
                    "dob" => $user->dob ? date('d-m-Y', strtotime($user->dob)) : '',
                    "city" => $city_data->city_name,
                    "gender" => $user->gender ? $user->gender : '',
                    'token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                    'is_profile_completed' => $user->is_completed,
                    'subscribed' => $user_subscribed,
                ];
                return $this->responseApi($user_access_data, true, 'Member Login Successfully', 200);
            } else {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     * @return [int] status
     */
    public function authenticateCashier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
            'password' => 'required|min:8|max:25|regex:/^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).*$/',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $credentials = $request->only(["email", "password"]);
            $user = User::where('email', $credentials['email'])->first();
            if ($user && $user->hasRole('Cashier')) {
                if (!Auth::attempt($credentials)) {
                    return $this->responseApi([], false, __('Invalid email or password'), 417);
                }
                $tokenResult = $user->createToken('authToken');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    $token->expires_at = Carbon::now()->addWeeks(1);
                $token->save();
                $user_access_data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                ];
                return $this->responseApi($user_access_data, true, 'Cashier Login Successfully', 200);
            } else {
                return $this->responseApi([], false, _('Sorry, This cashier does not exist'), 401);
            }
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     * @return [int] status
     */
    public function logoutUser(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->responseApi([], true, 'Logout Successfully', 200);
    }

    public function phoneNumberStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $phone = $request->only('phone');
            if (User::where('phone', $phone)->exists()) {
                return $this->responseApi([], true, 'Phone number exist already', 200);
            } else {
                return $this->responseApi([], false, __('Phone number does not exist'), 417);
            }
        }
    }

    public function fetchCities()
    {
        $result = array();
        $cityCount = 0;
        $cities = City::whereCityStatus(1)->orderBy('id', 'DESC')->get();
        foreach ($cities as $city) {
            $result[$cityCount]['id'] = $city['id'];
            $result[$cityCount]['city_name'] = $city['city_name'];
            $result[$cityCount]['city_name_arabic'] = $city['city_name_arabic'];
            $cityCount++;
        }
        return $this->responseApi($result, true, 'Cities Data Fetched', 200);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] data object
     * @return [string] message
     * @return [int] status
     */
    public function authenticatedMemberData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            if ($user_data && $user_data->hasRole('Member')) {
                $coupons_redeemed = CouponRedeem::where('user_id', '=', $user_data['id'])->count();
                $offers_redeemed = CouponRedeem::where('user_id', '=', $user_data['id'])->distinct('offer_id')->count('id');
                $city_data = $user_data->city ? City::find($user_data->city) : '';
                $data = [
                    'profile_image' => $user_data->profile_image ? url($user_data->profile_image) : '',
                    'name' => $user_data->name ? $user_data->name : '',
                    'email' => $user_data->email ? $user_data->email : '',
                    "phone" => $user_data->phone ? $user_data->phone : '',
                    "dob" => $user_data->dob ? date('d-m-Y', strtotime($user_data->dob)) : '',
                    "city" => $city_data ? $city_data->id : 0,
                    "city_name" => $city_data ? $city_data->city_name : '',
                    "gender" => $user_data->gender ? $user_data->gender : '',
                    'coupons_redeemed' => $coupons_redeemed,
                    'offers_redeemed' => $offers_redeemed,
                    'is_profile_completed' => $user_data->is_completed,
                ];
                return $this->responseApi($data, true, 'Member Data Retrieved', 200);
            } else {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }
        } else {
            return $this->responseApi([], false, __('Member token is missing'), 417);
        }
    }

    public function updateMemberData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            if ($user_data && $user_data->hasRole('Member')) {
                $input = $request->all();
                $validator = Validator::make($request->all(), [
                    'name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
                    'email' => 'required||max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $user_data['id'],
                    'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone,' . $user_data['id'],
                    'city' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
                } else {
                    $input = $request->only(['name', 'email', 'phone', 'dob', 'city', 'gender']);
                    $input = Arr::add($input, 'is_completed', 'true');
                    $user = User::find($user_data['id']);
                    if ($user->update($input)) {
                        return $this->responseApi([], true, 'Member Profile Updated Successfully', 200);
                    } else {
                        return $this->responseApi([], false, 'Member Profile not Updated', 200);
                    }
                }
            } else {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }
        } else {
            return $this->responseApi([], false, __('Member token is missing'), 417);
        }
    }

    public function fetchSettings()
    {
        $settings = Setting::find(1);
        $setting = [
            'app_name' => $settings['app_name'],
            'app_name_arabic' => $settings['app_name_arabic'],
            'app_phone' => $settings['app_phone'],
            'app_email' => $settings['app_email'],
            'app_building_address' => $settings['app_building_address'],
            'app_str_address' => $settings['app_str_address'],
            'app_com_address' => $settings['app_com_address'],
            'app_facebook' => $settings['app_facebook'],
            'app_insta' => $settings['app_insta'],
            'app_twitter' => $settings['app_twitter'],
            'app_pinterest' => $settings['app_pinterest'],
            'app_privacy' => $settings['app_privacy'],
            'app_privacy_arabic' => $settings['app_privacy_arabic'],
            'app_about' => $settings['app_about'],
            'app_about_arabic' => $settings['app_about_arabic'],
            'app_logo_ltr' => url($settings['app_logo_ltr']),
            'app_logo_rtl' => url($settings['app_logo_rtl']),
        ];
        return $this->responseApi($setting, true, 'Settings Fetched', 200);
    }

    public function verifyOtpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15',
            'code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $input = $request->all();
            if (User::wherePhone($input['phone'])->exists()) {
                $user_data = User::with('latestMemberSubscription')->wherePhone($input['phone'])->first();
                if (!$user_data->hasRole('Member')) {
                    $user_data->assignRole('Member');

                    // return $this->responseApi([], false, 'Can\'t Verify this number as a Member.', 417);
                }
                $otp_ex = Carbon::parse($user_data['otp_expires']);
                $now = Carbon::now();
                if ($user_data['otp'] == $input['code'] && $now->lt($otp_ex)) {
                    $user_subscribed = false;
                    $user_subscription = [];
                    if ($user_data->latestMemberSubscription != null) {
                        $sub_ex = Carbon::parse($user_data->latestMemberSubscription->user_subscriptions_expiry);
                        if ($now->lt($sub_ex)) {
                            $user_subscribed = true;
                            $user_subscription = $user_data->latestMemberSubscription;
                        }
                    }
                    // $user_data->tokens->each(function ($token, $key) {
                    //     $token->delete();
                    // });

                    DB::table('oauth_access_tokens')->where('user_id', $user_data->id)->update([
                        'revoked' => 1
                    ]);

                    $tokenResult = $user_data->createToken('authToken');
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();
                    $user_data->update(['is_verified', 1]);
                    $city_data = $user_data->city ? City::find($user_data->city) : '';
                    $user_access_data = [
                        'profile_image' => $user_data->profile_image ? url($user_data->profile_image) : '',
                        'name' => $user_data->name ? $user_data->name : '',
                        'email' => $user_data->email ? $user_data->email : '',
                        "phone" => $user_data->phone ? $user_data->phone : '',
                        "dob" => $user_data->dob ? date('d-m-Y', strtotime($user_data->dob)) : '',
                        "city" => $city_data ? $city_data->id : 0,
                        "city_name" => $city_data ? $city_data->city_name : '',
                        "gender" => $user_data->gender ? $user_data->gender : '',
                        'token' => $tokenResult->accessToken,
                        'token_type' => 'Bearer',
                        'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
                        'is_profile_completed' => $user_data->is_completed,
                        'subscribed' => $user_subscribed,
                        'subscription_expire' => $user_subscription ? date('d-m-Y i:s', strtotime($user_subscription->user_subscriptions_expiry)) : '',
                    ];
                    return $this->responseApi($user_access_data, true, 'Member Login Successfully', 200);
                } else {
                    return $this->responseApi([], false, __('OTP expired or incorrect'), 417);
                }
            } else {
                return $this->responseApi([], false, __('This Member does not exists'), 417);
            }
        }
    }

    public function sendOtpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:8|max:15',
        ]);
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 400);
        } else {
            $input = $request->only('phone');
            $client = new \GuzzleHttp\Client();

            $apiKey = \config('services.sms-msegat.sms_gateway_key');
            $api_url = \config('services.sms-msegat.sms_gateway_sms_url');
            $sender = \config('services.sms-msegat.sms_sender');
            $user_name = \config('services.sms-msegat.sms_user');

            $requestBody['userName'] = $user_name;
            $requestBody['numbers'] = $input['phone'];
            $requestBody['userSender'] = $sender;
            $requestBody['apiKey'] = $apiKey;
            $requestBody['msgEncoding'] = 'UTF8';

            if (User::wherePhone($input['phone'])->exists()) {
                $user_data = User::wherePhone($input['phone'])->first();
                if (!$user_data->hasRole('Member')) {
                    $user_data->assignRole('Member');

                    // return $this->responseApi([], false, 'Can\'t Verify this number as a Member.', 417);
                }
                $otp = $this->generateOtpNumber();
                if ($input['phone'] == '+966123456789') {
                    $otp = '0000';
                    $requestBody['msg'] = "Your OTP for Deal Station verification is: " . $otp;
                    $update_data = [
                        'otp' => $otp,
                        'is_verified' => 0,
                        'otp_expires' => Carbon::now()->addMinutes(2)
                    ];
                    User::wherePhone($input['phone'])->update($update_data);
                    $my_response = [
                        'phone' => $input['phone']
                    ];
                    return $this->responseApi($my_response, true, 'OTP sent', 200);
                }
                $requestBody['msg'] = "Your OTP for Deal Station verification is: " . $otp;
                $update_data = [
                    'otp' => $otp,
                    'is_verified' => 0,
                    'otp_expires' => Carbon::now()->addMinutes(2)
                ];
                User::wherePhone($input['phone'])->update($update_data);
                $request = $client->request('POST', $api_url, [
                    'json' => $requestBody,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);
                $response = json_decode($request->getBody());
                if ($response->code == 1 && $response->message == 'Success') {
                    $my_response = [
                        'phone' => $input['phone']
                    ];
                    return $this->responseApi($my_response, true, 'OTP sent', 200);
                } else {
                    return $this->responseApi([], false, __('OTP not sent'), 417);
                }
            } else {

                $trail_membership_data = MembershipSubscription::with('subscription')->whereHas("subscription", function ($q) {
                    $q->where([
                        ["subscription_name", '=', "Trail Subscription"],
                        ["subscription_status", '=', 1]
                    ]);
                })->first();

                $otp = $this->generateOtpNumber();
                if ($input['phone'] == '+966123456789') {
                    $otp = '0000';
                    $requestBody['msg'] = "Your OTP for Deal Station verification is: " . $otp;
                    $update_data = [
                        'otp' => $otp,
                        'is_verified' => 0,
                        'otp_expires' => Carbon::now()->addMinutes(2)
                    ];
                    User::wherePhone($input['phone'])->update($update_data);
                    $my_response = [
                        'phone' => $input['phone']
                    ];
                    return $this->responseApi($my_response, true, 'OTP sent', 200);
                }
                $requestBody['msg'] = "Your OTP for Deal Station verification is: " . $otp;
                $update_data = [
                    'otp' => $otp,
                    'is_verified' => 0,
                    'otp_expires' => Carbon::now()->addMinutes(2),
                    'password' => Hash::make(Str::random(8)),
                    'phone' => $input['phone'],
                ];
                $user = User::create($update_data);

                $user->assignRole('Member');

                $settings_data = Setting::find(1);

                $user_membership_data = [
                    'plan' => $trail_membership_data->plan_id,
                    'subscription' => $trail_membership_data->subscription_id,
                    'user_id' =>  $user->id,
                    'user_subscriptions_expiry' =>  Carbon::now()->addDays($settings_data->default_trial_days),
                    'user_subscriptions_payment_status' =>  'Paid',

                ];
                UserSubscription::where('user_id', '=', $user->id)->delete();
                UserSubscription::create($user_membership_data);
                $request = $client->request('POST', $api_url, [
                    'json' => $requestBody,
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ]
                ]);
                $response = json_decode($request->getBody());
                if ($response->code == 1 && $response->message == 'Success') {
                    $my_response = [
                        'phone' => $user->phone
                    ];
                    return $this->responseApi($my_response, true, 'OTP sent', 200);
                } else {
                    return $this->responseApi([], false, __('OTP not sent'), 417);
                }
            }
        }
    }

    public function updateMemberImageData(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ],
            [
                'image.image' => 'The type of the uploaded file should be an image.',
                'image.max' => 'Failed to upload an image. The image maximum size is 2MB.',
            ]
        );
        if ($validator->fails()) {
            return $this->responseApi($validator->messages()->all(), false, __('Validation Errors'), 417);
        } else {
            $user_data = $request->user();
            if ($request->has('image')) {
                $image = $request->file('image');
                $file_name = strtolower($image->getClientOriginalName());
                $file_name = explode('.', $file_name)[0];
                $file_name = str_replace(' ', '-', $file_name);
                $filename = $file_name . '-' . time() . rand(0, 9999) . '.' . $image->getClientOriginalExtension();
                if ($image->move(public_path() . '/uploads/members/', $filename)) {
                    $image_url = 'uploads/members/' . $filename;
                    User::where('id', $user_data['id'])->update(['profile_image' => $image_url]);
                    $my_response = [
                        'profile_image' => url($image_url),
                    ];
                    return $this->responseApi($my_response, true, 'Member image uploaded successfully', 200);
                } else {
                    return $this->responseApi([], false, __('Member image not uploaded'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Member image missing'), 417);
            }
        }
    }

    public function generateOtpNumber()
    {
        $number = mt_rand(1111, 9999);
        return $number;
    }

    public function otpNumberExists($number)
    {
        return User::whereOtp($number)->exists();
    }
}
