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
use App\Models\City;

class UserController extends Controller
{
    private $page_heading;

    public function __construct()
    {
        $this->page_heading =  'User Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if (!Auth::user()->can('admin-user-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        return view('admin.users.index', compact('page_title'));
    }

    public function fetchUsersData(Request $request)
    {
        $result = array('data' => array());
        $users = User::with('roles')->whereHas("roles", function ($q) {
            $q->where([
                ["name", '!=', "Admin"],
                ["name", '!=', "Client"],
                ["name", '!=', "Merchant"],
                ["name", '!=', "Cashier"],
                ["name", '!=', "Member"],
            ]);
        })->get();
        foreach ($users as $key => $value) {
            $buttons = '';
            $buttons .= '<a href="' . route("users.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="show">
            <i class="far fa-eye"></i>
            </a>';
            if (Auth::user()->can('admin-user-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" href="' . route("users.edit", $value->id) . '" data-toggle="tooltip" data-placement="top" title="edit">
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
            if (Auth::user()->can('admin-user-delete')) {
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
            $user_role = '<span class="badge badge-light-info fw-bold me-1">' . $value['roles'][0]['name'] . '</span>';
            $result['data'][$key] = array(
                $value['name'],
                $value['email'],
                $value['phone'],
                $user_role,
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
        if (!Auth::user()->can('admin-user-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $city_data = City::all();
        $roles = Role::where('id', '>', 5)->orderBy('id', 'DESC')->get();
        return view('admin.users.create', compact('city_data', 'roles', 'page_title'));
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
            'name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
            'email' => 'required||max:50|min:5|email:filter|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email',
            'password' => 'required|min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone',
            'dob' => 'required|before:today',
            'gender' => 'required',
            'roles' => 'required',
            'city' => 'required|numeric',
        ], [
            'email.regex' => 'Email should be a valid Email.',
            'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
            'confirm-password.same' => 'Password Confirmation should match the Password',
        ]);
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', __('User Created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        if (!Auth::user()->can('admin-user-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $city_data = City::all();
        $roles = Role::where('id', '>', 5)->orderBy('id', 'DESC')->get();
        $user = User::find($id);
        $userRole = $user->roles->all();
        if ($userRole[0]['id'] > 5) {
            return view('admin.users.show', compact('user', 'city_data', 'roles', 'page_title', 'userRole'));
        } else {
            return redirect()->back();
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
        if (!Auth::user()->can('admin-user-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $city_data = City::all();
        $roles = Role::where('id', '>', 5)->orderBy('id', 'DESC')->get();
        $user = User::find($id);
        $userRole = $user->roles->all();
        if ($userRole[0]['id'] > 5) {
            return view('admin.users.edit', compact('user', 'city_data', 'roles', 'page_title', 'userRole'));
        } else {
            return redirect()->back();
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
        $this->validate($request, [
            //Form Attributes
            'name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
            'email' => 'required||max:50|min:5|email:filter|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $id,
            'phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/|unique:users,phone,' . $id,
            'dob' => 'required|before:today',
            'gender' => 'required',
            'roles' => 'required',
            'city' => 'required|numeric',
        ], [
            'email.regex' => 'Email should be a valid Email.',
        ]);
        $input = $request->all();
        if (!empty($input['password'])) {
            $this->validate($request, [
                'password' => 'min:8|max:20|regex:/^.*(?=.*?[A-Z])(?=(.*[a-z]){1,})(?=(.*[\d]){1,})(?=(.*[\W]){1,})(?!.*\s).{8,}$/|same:confirm-password',
            ], [
                'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
                'confirm-password.same' => 'Password Confirmation should match the Password',
            ]);
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')
            ->with('success', __('User Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if (User::find($id)->delete()) {
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function profile()
    {
        $user = User::find(Auth::user()->id);
        return view('admin.users.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $input = $request->all();
        $id = $input['xxzyzz'];
        if (!empty($id)) {
            $this->validate($request, [
                'name' => 'required|max:50|min:3|regex:/^[a-zA-Z ]*$/',
                'email' => 'required|max:50|min:5|email|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/|unique:users,email,' . $id,
            ]);
            if (!empty($input['password'])) {
                $this->validate($request, [
                    'password' => 'max:20|min:8|regex:/^.*(?=.{8,})(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).*$/|same:confirm-password',
                ], [
                    'password.regex' => 'Password must contain at least 1 Uppercase, 1 Lowercase, 1 Number and 1 Special Character',
                    'confirm-password.same' => 'Password Confirmation should match the Password',
                ]);
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $user = User::find($id);
            $user->update($input);
            return redirect()->route('my-profile')
                ->with('success', __('Profile Updated Successfully'));
        } else {
            return redirect()->route('my-profile')
                ->with('error', __('Something went wrong. Try again...'));
        }
    }
}
