<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Jobs\CashierWelcomeMailJob;
use App\Jobs\NewBranchMailJob;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Branch;
use App\Models\City;
use Illuminate\Support\Facades\Hash;
use App\Models\OfferBranch;
use Illuminate\Support\Facades\Mail;
use App\Mail\CashierWelcomeMail;
use App\Mail\NewBranchMail;

class MerchantBranchController extends Controller
{
    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading = 'Branch Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->role('Merchant') && !Auth::user()->can('branch-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $branches = "";
        $branches = Branch::where('merchant_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->get();
        $count = 1;
        return view('merchants.branches.index', compact('branches', 'count', 'page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::user()->role('Merchant') && !Auth::user()->can('branch-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $city_data = City::all();
        return view('merchants.branches.create', compact('page_title', 'city_data'));
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
            'email' => 'required|email|min:5|max:50|unique:users|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
            'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            //Form Attributes
            'branch_name' => 'required|max:50|min:3',
            'branch_name_arabic' => 'required|max:50|min:3',
            'branch_latitude' => 'required|max:255',
            'branch_longitude' => 'required|max:255',
            'branch_phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'branch_city' => 'required',
            'branch_building_address' => 'required|max:255',
            'branch_str_address' => 'required|max:255',
            'branch_com_address' => 'required|max:255',
            'branch_image' => 'mimes:jpeg,jpg,png,tif,lzw|required|max:2048',
        ], [
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
            $merchant_id = Auth::user()->id;
            $branch_status = 1;
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
                'branch_image' => $branch_image,
                'branch_cashier' => $cashier->id,
            ];
            $branch =  Branch::create($branchData);
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

                $admins = User::role('Admin')->orderBy('id', 'DESC')->get();
                $admins_data = $admins->map(function ($admin) {
                    return collect($admin->toArray())
                        ->only('email')
                        ->all();
                });
                $admin_email_data = [
                    'merchant_name' => $merchant->name,
                    'merchant_email' => $merchant->email,
                    'branch_name' => $input['branch_name'],
                    'branch_email' => $input['email'],
                    'url' => route('branches.show', $branch->id),
                ];
                $queueData = [];
                $queueData['email'] = $admins_data;
                $queueData['data'] = $admin_email_data;
                dispatch(new NewBranchMailJob($queueData));
            }
            return redirect()->route('merchant-branches.index')
                ->with('success', 'Branch Created Successfully');
        } else {
            return redirect()->route('merchant-branches.create')
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
        if (!Auth::user()->role('Merchant') && !Auth::user()->can('branch-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $branch = Branch::where('branch_uniid', '=', $id)->first();
        $cashier = Branch::find($branch->id)->cashier;
        $city_data = City::all();
        return view('merchants.branches.show', compact('branch', 'page_title', 'cashier', 'city_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->role('Merchant') && !Auth::user()->can('branch-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $branch = Branch::where('branch_uniid', '=', $id)->first();
        $cashier = Branch::find($branch->id)->cashier;
        $city_data = City::all();
        return view('merchants.branches.edit', compact('branch', 'page_title', 'cashier', 'city_data'));
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
            return redirect()->route('merchant-branches');
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
        $branch = Branch::where('branch_uniid', '=', $id)->first();
        $cashier = Branch::find($branch->id)->cashier;
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
            'branch_image' => $branch_image,
        ];
        if (empty($branch_image)) {
            $branchData = Arr::except($branchData, array('branch_image'));
        }
        Branch::where('branch_uniid', '=', $id)->update($branchData);
        return redirect()->route('merchant-branches.index')
            ->with('success', 'Branch Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        $branch = Branch::where('branch_uniid', '=', $id)->first();
        if (!OfferBranch::where('branch', '=', $branch->id)->exists()) {
            $branch = Branch::where('id', '=', $branch->id)->first();
            User::find($branch->branch_cashier)->delete();
            Branch::where('id', '=', $branch->id)->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }
}
