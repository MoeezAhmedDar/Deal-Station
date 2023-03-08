<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\MerchantDetail;
use App\Models\City;
use Illuminate\Support\Facades\Mail;
use App\Mail\CashierWelcomeMail;
use App\Mail\NewBranchMail;
use App\Models\OfferBranch;
use Illuminate\Support\Facades\Hash;
use App\Jobs\CashierWelcomeMailJob;

class BranchController extends Controller
{
    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading =  'Branch Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-branch-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        return view('admin.branches.index', compact('merchants_data', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->can('admin-branch-create')) {
            return redirect()->route('dashboard');
        }
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $page_title = __($this->page_heading);
        $city_data = City::all();
        return view('admin.branches.create', compact('page_title', 'merchants_data', 'city_data'));
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
            'email' => 'required|email|min:5|max:50|unique:users|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
            'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            'branch_name' => 'required|max:50|min:3|unique:branches,branch_name',
            'branch_name_arabic' => 'required|max:50|min:3|unique:branches,branch_name_arabic',
            'branch_latitude' => 'required|max:255',
            'branch_longitude' => 'required|max:255',
            'branch_phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'branch_city' => 'required',
            'merchant_id' => 'required',
            'branch_building_address' => 'required|max:255',
            'branch_str_address' => 'required|max:255',
            'branch_com_address' => 'required|max:255',
            'branch_image' => 'mimes:jpeg,jpg,png,tif,lzw|required|max:2048',
        ], [
            'merchant_id.required' => 'Merchant is required.',
            'email.regex' => 'Email should be a valid Email.',
            'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
            'confirm-password.same' => 'Password Confirmation should match the Password',
            'branch_image.mimes' => 'Logo must be a Image',
            'branch_image.max' => 'Image should be 2 MB max.',
        ]);
        $input = $request->all();
        $userData = [
            'name' => $input['branch_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ];
        $cashier = User::create($userData);
        $cashier->assignRole('Cashier');
        if ($cashier) {
            $merchant_id = $input['merchant_id'];
            $branch_status = $input['branch_status'];
            $branch_image = $request->branch_image;
            if ($branch_image) {
                $file_path = $this->ImageUpload($branch_image, 'uploads/branches/');
                $branch_image = $file_path;
            } else {
                $branch_image = '';
            }
            $branchData = [
                'merchant_id' => $merchant_id,
                'branch_uniid' => Str::uuid()->toString(),
                'branch_name' => $input['branch_name'],
                'branch_name_arabic' => $input['branch_name_arabic'],
                'branch_city' => $input['branch_city'],
                'branch_phone' => $input['branch_phone'],
                'branch_building_address' => $input['branch_building_address'],
                'branch_str_address' => $input['branch_str_address'],
                'branch_com_address' => $input['branch_com_address'],
                'branch_latitude' => $input['branch_latitude'],
                'branch_longitude' => $input['branch_longitude'],
                'branch_status' => $branch_status,
                'branch_cashier' => $cashier->id,
                'branch_image' => $branch_image,
            ];
            $branch = Branch::create($branchData);
            if ($branch) {
                $merchant = User::find($merchant_id);
                $email_data = [
                    'name' => $input['branch_name'],
                    'email' => $input['email'],
                    'password' => $input['password'],
                    'url' => route('login'),
                ];

                $queueData = [];
                $queueData['email'] = $input['email'];
                $queueData['m_email'] = $merchant->email;
                $queueData['data'] = $email_data;
                dispatch(new CashierWelcomeMailJob($queueData));
            }
            return redirect()->route('branches.index')
                ->with('success', __('Branch has been added successfully'));
        } else {
            return redirect()->route('branches.create')
                ->with('error', 'Error Occur. Please Try Again.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('admin-branch-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $branch = Branch::find($id);
        $cashier = Branch::find($id)->cashier;
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $city_data = City::all();
        return view('admin.branches.show', compact('branch', 'merchants_data', 'page_title', 'cashier', 'city_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('admin-branch-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $branch = Branch::find($id);
        $cashier = Branch::find($id)->cashier;
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $city_data = City::all();
        return view('admin.branches.edit', compact('branch', 'merchants_data', 'cashier', 'page_title', 'city_data'));
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
            return redirect()->route('branches');
        }
        $input = $request->all();
        $this->validate($request, [
            //Form Attributes
            'email' => 'required|email|min:5|max:50|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $input['cashier'],
            'branch_name' => 'required|max:50|min:3',
            'branch_name_arabic' => 'required|max:50|min:3',
            'branch_latitude' => 'required|max:255',
            'branch_longitude' => 'required|max:255',
            'branch_phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'branch_city' => 'required',
            'branch_building_address' => 'required|max:255',
            'branch_str_address' => 'required|max:255',
            'branch_com_address' => 'required|max:255',
            'branch_status' => 'required',
        ], [
            // 'merchant_id.required' => 'Please Select a Merchant',
            'branch_status.required' => 'Please Select Branch Status',
        ]);
        if (!empty($input['password'])) {
            $this->validate($request, [
                // Auth Details
                'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            ], [
                'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
                'confirm-password.same' => 'Password Confirmation should match the Password',
            ]);
        }
        $cashierData = [];
        if (!empty($input['password'])) {
            $cashierData = [
                'name' => $input['branch_name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ];
        } else {
            $cashierData = [
                'name' => $input['branch_name'],
                'email' => $input['email'],
            ];
        }
        $cashier = Branch::find($id)->cashier;
        $cashier->update($cashierData);
        if (!empty($input['branch_image'])) {
            $this->validate($request, [
                'branch_image' => 'mimes:jpeg,jpg,png,tif,lzw|max:2048',
            ], [
                'branch_image.mimes' => 'Logo must be a Image',
                'branch_image.max' => 'Image should be 2 MB max.',
            ]);
        }
        $branch_image = $request->branch_image;
        if ($branch_image) {
            $file_path = $this->ImageUpload($branch_image, 'uploads/branches/');
            $branch_image = $file_path;
        } else {
            $branch_image = '';
        }
        $branchData = [
            'branch_name' => $input['branch_name'],
            'branch_name_arabic' => $input['branch_name_arabic'],
            'branch_city' => $input['branch_city'],
            'branch_phone' => $input['branch_phone'],
            'branch_building_address' => $input['branch_building_address'],
            'branch_str_address' => $input['branch_str_address'],
            'branch_com_address' => $input['branch_com_address'],
            'branch_latitude' => $input['branch_latitude'],
            'branch_longitude' => $input['branch_longitude'],
            'branch_status' => $input['branch_status'],
            'branch_image' => $branch_image,
        ];
        if (empty($branch_image)) {
            $branchData = Arr::except($branchData, array('branch_image'));
        }
        Branch::where('id', '=', $id)->update($branchData);
        return redirect()->route('branches.index')
            ->with('success', __('Branch has been edited successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!OfferBranch::where('branch', '=', $id)->exists()) {
            $branch = Branch::where('id', '=', $id)->first();
            User::find($branch->branch_cashier)->delete();
            Branch::where('id', '=', $id)->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function fetchBranchesData(Request $request)
    {
        $result = array('data' => array());
        $branches = [];
        $input = $request->merchant_id;
        if ($input == '0') {
            $branches = Branch::orderBy('id', 'DESC')->get();
        } else if ($input != '0') {
            $branches = Branch::where('merchant_id', '=', $input)->orderBy('id', 'DESC')->get();
        }
        foreach ($branches as $key => $value) {
            $merchant_data = MerchantDetail::where('merchant_id', '=', $value->merchant_id)->first();
            $buttons = '';
            $buttons .= '<a href="' . route("branches.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Show">
            <i class="far fa-eye"></i>
            </a>';
            if (Auth::user()->can('admin-branch-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Edit" href="' . route("branches.edit", $value->id) . '">
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
            if (Auth::user()->can('admin-branch-delete')) {
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
            if ($value['branch_status'] == 1) {
                $branch_status = '<div class="badge badge-light-success fw-bolder">' . __('Active') . '</div>';
            } else {
                $branch_status = '<div class="badge badge-light-danger fw-bolder">' . __('Inactive') . '</div>';
            }
            $result['data'][$key] = array(
                $value['branch_name'],
                $merchant_data ? $merchant_data['merchant_brand'] : '',
                $value['branch_phone'],
                $branch_status,
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function fetchMerchantBranchesData($id)
    {
        if ($id) {
            $branches = Branch::where('merchant_id', '=', $id)->get();
            $branches_data = $branches->map(function ($branch) {
                return collect($branch->toArray())
                    ->only(['id', 'branch_name'])
                    ->all();
            });
            return json_encode($branches_data);
        } else {
            return false;
        }
    }
}
