<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use App\Models\MerchantDetail;
use App\Http\Traits\FileUploadTrait;
use App\Jobs\MerchantWelcomeMailJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\MerchantWelcomeMail;
use App\Mail\NewMember;
use App\Models\Branch;
use App\Models\Offer;

class MerchantController extends Controller
{
    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading = 'Merchant Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!Auth::user()->can('admin-merchant-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        return view('admin.merchants.index', compact('merchants_data', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('admin-merchant-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        return view('admin.merchants.create', compact('page_title'));
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
            // Auth Details
            'name' => 'required|max:50|min:3',
            'email' => 'required|email|min:5|max:50|unique:users|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
            'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',

            //Form Attributes
            'merchant_brand' => 'required|max:50|min:3',
            'merchant_brand_arabic' => 'required|max:50|min:3',
            'merchant_gov_id' => 'required|max:20|min:5|regex:/^[0-9]+$/',
            'merchant_website' => 'required|url|max:255',
            'merchant_number' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'business_owner' => 'required|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            'merchant_contact_person' => 'required|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            'merchant_contact_number' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'merchant_building_address' => 'required|max:255',
            'merchant_str_address' => 'required|max:255',
            'merchant_com_address' => 'required',
            'merchant_commercial_activity' => 'required',
            'merchant_tax_number' => 'required|max:30|min:5|regex:/^[0-9]+$/',
            'merchant_logo' => 'mimes:jpeg,jpg,png|required|max:2048',
            'merchant_gov_letter' => 'mimes:doc,pdf,docx|required|max:2048',
            'merchant_tax_letter' => 'mimes:doc,pdf,docx|required|max:2048',
            'arabic_business_owner' => 'required',
            'arabic_contact_person_name' => 'required',
        ], [
            'email.regex' => 'Email should be a valid Email.',
            'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
            'confirm-password.same' => 'Password Confirmation should match the Password',
            'merchant_logo.mimes' => 'Logo must be a Image',
            'merchant_gov_letter.mimes' => 'Merchant Reg. Gov letter must be PDF or Word.',
            'merchant_tax_letter.mimes' => 'Tax Registration letter must be PDF or Word.',
            'merchant_logo.max' => 'Image should be 2 MB max.',
            'merchant_gov_letter.max' => 'Merchant Reg. Gov letter should be 2 MB max.',
            'merchant_tax_letter.max' => 'Tax Registration letter be 2 MB max.',
        ]);
        $input = $request->all();
        $userData = [
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ];
        $user = User::create($userData);
        $user->assignRole('Merchant');
        if ($user) {
            $merchant_logo = $request->merchant_logo;
            if ($merchant_logo) {
                $file_path = $this->ImageUpload($merchant_logo, 'uploads/merchants/logos/');
                $merchant_logo = $file_path;
            } else {
                $merchant_logo = '';
            }
            $merchant_gov_letter = $request->merchant_gov_letter;
            if ($merchant_gov_letter) {
                $file_path = $this->DocUpload($merchant_gov_letter, 'uploads/merchants/docs/gov/');
                $merchant_gov_letter = $file_path;
            } else {
                $merchant_gov_letter = '';
            }
            $merchant_tax_letter = $request->merchant_tax_letter;
            if ($merchant_tax_letter) {
                $file_path = $this->DocUpload($merchant_tax_letter, 'uploads/merchants/docs/tax/');
                $merchant_tax_letter = $file_path;
            } else {
                $merchant_tax_letter = '';
            }
            $merchantData = [
                'merchant_id' => $user['id'],
                'merchant_uniid' => Str::uuid()->toString(),
                'merchant_brand' => $input['merchant_brand'] ? $input['merchant_brand'] : 'No Brand',
                'merchant_brand_arabic' => $input['merchant_brand_arabic'],
                'merchant_iban' => $input['merchant_iban'] ? $input['merchant_iban'] : 'No IBAN',
                'merchant_gov_id' => $input['merchant_gov_id'],
                'merchant_website' => $input['merchant_website'],
                'merchant_number' => $input['merchant_number'],
                'business_owner' => $input['business_owner'],
                'merchant_contact_person' => $input['merchant_contact_person'],
                'merchant_contact_number' => $input['merchant_contact_number'],
                'merchant_building_address' => $input['merchant_building_address'],
                'merchant_str_address' => $input['merchant_str_address'],
                'merchant_com_address' => $input['merchant_com_address'],
                'merchant_commercial_activity' => $input['merchant_commercial_activity'],
                'merchant_tax_number' => $input['merchant_tax_number'],
                'arabic_business_owner' => $input['arabic_business_owner'],
                'arabic_contact_person_name' => $input['arabic_contact_person_name'],
                'merchant_logo' => $merchant_logo,
                'merchant_gov_letter' => $merchant_gov_letter,
                'merchant_tax_letter' => $merchant_tax_letter,
            ];
            $merchant = MerchantDetail::create($merchantData);
            if ($merchant) {
                $email_data = [
                    'name' => $input['name'],
                    'email' => $input['email'],
                    'password' => $input['password'],
                    'url' => route('login'),
                ];
                $queueData = [];
                $queueData['email'] = $input['email'];
                $queueData['data'] = $email_data;
                dispatch(new MerchantWelcomeMailJob($queueData));
            }
            return redirect()->route('merchants.index')
                ->with('success', 'Merchant Created Successfully');
        } else {
            return redirect()->route('merchants.create')
                ->with('error', 'Error Occur. Please Try Again.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        if (!Auth::user()->can('admin-merchant-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchant = User::find($id);
        $merchant_roles = $merchant->getRoleNames();
        if ($merchant_roles[0] == 'Merchant') {
            $merchant_details = User::find($id)->merchant;
            return view('admin.merchants.show', compact('merchant', 'merchant_details', 'page_title'));
        } else {
            return redirect()->route('merchants.index')
                ->with('error', 'Selected User is not a merchant');
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
        if (!Auth::user()->can('admin-merchant-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchant = User::find($id);
        $merchant_roles = $merchant->getRoleNames();
        if ($merchant_roles[0] == 'Merchant') {
            $merchant_details = User::find($id)->merchant;
            return view('admin.merchants.edit', compact('merchant', 'merchant_details', 'page_title'));
        } else {
            return redirect()->route('merchants.index')
                ->with('error', 'Selected User is not a merchant');
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
            return redirect()->route('merchants');
        }
        $this->validate($request, [
            // Auth Details
            'name' => 'required|max:50|min:3',
            'email' => 'required|email|min:5|max:50|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $id,

            //Form Attributes
            'merchant_brand' => 'required|max:50|min:3',
            'merchant_brand_arabic' => 'required|max:50|min:3',

            'merchant_gov_id' => 'required|max:20|min:5|regex:/^[0-9]+$/',
            'merchant_website' => 'required|url|max:255',
            'merchant_number' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'business_owner' => 'required|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            'merchant_contact_person' => 'required|min:3|max:50|regex:/^[a-zA-Z ]*$/',
            'merchant_contact_number' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'merchant_building_address' => 'required|max:255',
            'merchant_str_address' => 'required|max:255',
            'merchant_com_address' => 'required',
            'merchant_commercial_activity' => 'required',
            'merchant_tax_number' => 'required|min:5|max:30|regex:/^[0-9]+$/',
            'arabic_business_owner' => 'required',
            'arabic_contact_person_name' => 'required',
            'merchant_status' => 'required',
        ], [
            'email.regex' => 'Email should be a valid Email.',
        ]);
        $input = $request->all();
        if (!empty($input['password'])) {
            $this->validate($request, [
                // Auth Details
                'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            ], [
                'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
                'confirm-password.same' => 'Password Confirmation should match the Password',
            ]);
        }
        if (!empty($input['merchant_logo']) || !empty($input['merchant_gov_letter']) || !empty($input['merchant_gov_letter'])) {
            $this->validate($request, [
                // Auth Details
                'merchant_logo' => 'mimes:jpeg,jpg,png|max:2048',
                'merchant_gov_letter' => 'mimes:doc,pdf,docx|max:2048',
                'merchant_gov_letter' => 'mimes:doc,pdf,docx|max:2048'
            ], [
                'merchant_logo.mimes' => 'Logo must be a Image',
                'merchant_gov_letter.mimes' => 'Merchant Reg. Gov letter must be PDF or Word.',
                'merchant_tax_letter.mimes' => 'Tax Registration letter must be PDF or Word.',
                'merchant_logo.max' => 'Image should be 2 MB max.',
                'merchant_gov_letter.max' => 'Merchant Reg. Gov letter should be 2 MB max.',
                'merchant_tax_letter.max' => 'Tax Registration letter be 2 MB max.',
            ]);
        }
        $userData = [];
        if (!empty($input['password'])) {
            $userData = [
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ];
        } else {
            $userData = [
                'name' => $input['name'],
                'email' => $input['email'],
            ];
        }
        $user = User::find($id);
        $user->update($userData);
        $merchant_logo = $request->merchant_logo;
        if ($merchant_logo) {
            $file_path = $this->ImageUpload($merchant_logo, 'uploads/merchants/logos/');
            $merchant_logo = $file_path;
        } else {
            $merchant_logo = '';
        }
        $merchant_gov_letter = $request->merchant_gov_letter;
        if ($merchant_gov_letter) {
            $file_path = $this->DocUpload($merchant_gov_letter, 'uploads/merchants/docs/gov/');
            $merchant_gov_letter = $file_path;
        } else {
            $merchant_gov_letter = '';
        }
        $merchant_tax_letter = $request->merchant_tax_letter;
        if ($merchant_tax_letter) {
            $file_path = $this->DocUpload($merchant_tax_letter, 'uploads/merchants/docs/tax/');
            $merchant_tax_letter = $file_path;
        } else {
            $merchant_tax_letter = '';
        }
        $merchantData = [
            'merchant_brand' => $input['merchant_brand'] ? $input['merchant_brand'] : 'No Brand',
            'merchant_brand_arabic' => $input['merchant_brand_arabic'],
            'merchant_iban' => $input['merchant_iban'] ? $input['merchant_iban'] : 'No IBAN',
            'merchant_gov_id' => $input['merchant_gov_id'],
            'merchant_website' => $input['merchant_website'],
            'merchant_number' => $input['merchant_number'],
            'business_owner' => $input['business_owner'],
            'merchant_contact_person' => $input['merchant_contact_person'],
            'merchant_contact_number' => $input['merchant_contact_number'],
            'merchant_building_address' => $input['merchant_building_address'],
            'merchant_str_address' => $input['merchant_str_address'],
            'merchant_com_address' => $input['merchant_com_address'],
            'merchant_commercial_activity' => $input['merchant_commercial_activity'],
            'merchant_tax_number' => $input['merchant_tax_number'],
            'arabic_business_owner' => $input['arabic_business_owner'],
            'arabic_contact_person_name' => $input['arabic_contact_person_name'],
            'merchant_status' => $input['merchant_status'],
            'merchant_logo' => $merchant_logo,
            'merchant_gov_letter' => $merchant_gov_letter,
            'merchant_tax_letter' => $merchant_tax_letter,
        ];

        if (empty($merchant_logo)) {
            $merchantData = Arr::except($merchantData, array('merchant_logo'));
        }
        if (empty($merchant_gov_letter)) {
            $merchantData = Arr::except($merchantData, array('merchant_gov_letter'));
        }
        if (empty($merchant_tax_letter)) {
            $merchantData = Arr::except($merchantData, array('merchant_tax_letter'));
        }
        MerchantDetail::where('merchant_id', '=', $id)->update($merchantData);
        return redirect()->route('merchants.index')
            ->with('success', 'Merchant Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if (MerchantDetail::where('merchant_id', '=', $id)->exists()) {
            MerchantDetail::where('merchant_id', '=', $id)->update(['merchant_status' => 2]);
            Branch::where('merchant_id', '=', $id)->update(['branch_status' => 2]);
            Offer::where('merchant_id', '=', $id)->update(['offer_status' => 1]);
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function fetchMerchantsData(Request $request)
    {
        $result = array('data' => array());
        $merchants = [];
        $input = $request->merchant_id;
        if ($input == '0') {
            $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        } else if ($input != '0') {
            $merchants = User::where('merchant_id', '=', $input)->role('Merchant')->orderBy('id', 'DESC')->get();
        }
        foreach ($merchants as $key => $value) {
            $merchant_data = MerchantDetail::where('merchant_id', '=', $value->id)->first();
            $buttons = '';
            $buttons .= '<a href="' . route("merchants.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Show">
            <i class="far fa-eye"></i>
            </a>';
            if (Auth::user()->can('admin-merchant-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Edit" href="' . route("merchants.edit", $value->id) . '">
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
            if (Auth::user()->can('admin-merchant-delete')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Delete" onclick="removeFunc(' . $value->id . ')">
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
            $merchant_status = '';
            if ($merchant_data['merchant_status'] == 1) {
                $merchant_status = '<div class="badge badge-light-success fw-bolder">' . __('Active') . '</div>';
            } else {
                $merchant_status = '<div class="badge badge-light-danger fw-bolder">' . __('Inactive') . '</div>';
            }
            $result['data'][$key] = array(
                $merchant_data['merchant_brand'] ? $merchant_data['merchant_brand'] : 'No Brand',
                $value['name'] ? $value['name'] : 'No Name',
                $value['email'] ? $value['email'] : 'No Email',
                $merchant_data['merchant_number'] ? $merchant_data['merchant_number'] : 'No Number',
                $merchant_data['merchant_gov_id'] ? $merchant_data['merchant_gov_id'] : 'No Gov ID',
                $merchant_status,
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function profile($id)
    {
        $curUserId = Auth::user()->id;
        if ($curUserId == $id) {
            $user = User::find($id);
            $roles = Role::pluck('name', 'name')->all();
            $userRole = $user->roles->pluck('name', 'name')->all();
            return view('admin.merchants.profile', compact('user'));
        } else {
            return redirect()->route('dashboard')
                ->with('error', 'Something went wrong. Try again...');
        }
    }
}
