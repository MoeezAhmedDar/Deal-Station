<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PrivacyPolicy extends Controller
{
    public function index()
    {
        $settings = Setting::find(1);
        return view('privacy-policy', compact('settings'));
    }

    public function terms()
    {
        $settings = Setting::find(1);
        return view('terms-conditions', compact('settings'));
    }
}
