<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    use FileUploadTrait;
    private $page_heading;

    public function __construct()
    {
        $this->page_heading = 'Settings';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = __($this->page_heading);
        $settings_data = Setting::find(1);
        return view('admin.settings.index', compact('page_title', 'settings_data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!$id) {
            return redirect()->route('settings');
        }
        $this->validate($request, [
            'app_name' => 'required|max:50|min:3',
            'app_name_arabic' => 'required|max:50|min:3',
            'app_phone' => 'required|min:8|max:15|regex:/^[\+?\d\s]*$/',
            'app_email' => 'required|email|min:5|max:50|regex:/^[A-z0-9_.]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z.]{1,4}$/',
            'app_building_address' => 'required|max:255',
            'app_str_address' => 'required|max:255',
            'app_com_address' => 'required|max:255',
            'app_facebook' => 'required|url|max:255',
            'app_insta' => 'required|url|max:255',
            'app_twitter' => 'required|url|max:255',
            'app_pinterest' => 'required|url|max:255',
            'app_privacy' => 'required',
            'app_privacy_arabic' => 'required',
            'app_about' => 'required',
            'app_about_arabic' => 'required',
            'default_trial_days' => 'required|numeric|min:1|max:365',
        ]);
        $input = $request->all();

        if (!empty($request->file('app_logo_ltr'))) {
            $this->validate($request, [
                'app_logo_ltr' => 'mimes:jpeg,jpg,png,tif,lzw|max:2048',
            ], [
                'app_logo_ltr.mimes' => 'Logo must be a Image',
                'app_logo_ltr.max' => 'Image should be 2 MB max.',
            ]);
        }

        $app_logo_ltr = $request->app_logo_ltr;
        if ($app_logo_ltr) {
            $file_path = $this->ImageUpload($app_logo_ltr, 'uploads/logos/');
            $app_logo_ltr = $file_path;
        } else {
            $app_logo_ltr = '';
        }

        if (!empty($input['app_logo_rtl'])) {
            $this->validate($request, [
                'app_logo_rtl' => 'mimes:jpeg,jpg,png,tif,lzw|max:2048',
            ], [
                'app_logo_rtl.mimes' => 'Logo must be a Image',
                'app_logo_rtl.max' => 'Image should be 2 MB max.',
            ]);
        }

        $app_logo_rtl = $request->app_logo_rtl;
        if ($app_logo_rtl) {
            $file_path = $this->ImageUpload($app_logo_rtl, 'uploads/logos/');
            $app_logo_rtl = $file_path;
        } else {
            $app_logo_rtl = '';
        }

        $settingsData = [
            'app_name' => $input['app_name'],
            'app_name_arabic' => $input['app_name_arabic'],
            'app_phone' => $input['app_phone'],
            'app_email' => $input['app_email'],
            'app_building_address' => $input['app_building_address'],
            'app_str_address' => $input['app_str_address'],
            'app_com_address' => $input['app_com_address'],
            'app_facebook' => $input['app_facebook'],
            'app_insta' => $input['app_insta'],
            'app_twitter' => $input['app_twitter'],
            'app_pinterest' => $input['app_pinterest'],
            'app_privacy' => $input['app_privacy'],
            'app_privacy_arabic' => $input['app_privacy_arabic'],
            'app_about' => $input['app_about'],
            'app_about_arabic' => $input['app_about_arabic'],
            'app_logo_ltr' => $app_logo_ltr,
            'app_logo_rtl' => $app_logo_rtl,
            'default_trial_days' => $input['default_trial_days'],
        ];
        if (empty($request->file('app_logo_ltr'))) {
            $settingsData = Arr::except($settingsData, array('app_logo_ltr'));
        }

        if (empty($request->file('app_logo_rtl'))) {
            $settingsData = Arr::except($settingsData, array('app_logo_rtl'));
        }
        Setting::where('id', '=', $id)->update($settingsData);
        return redirect()->route('settings.index')
            ->with('success', 'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
