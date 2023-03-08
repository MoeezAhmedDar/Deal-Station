<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class LocalizationController extends Controller
{
    public function langSwitcher($locale = null)
    {
        if (isset($locale) && in_array($locale, config('app.available_locales'))) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        }
        if ($locale == 'ar') {
            Config::set('dir', 'rtl');
        } else {
            Config::set('dir', 'ltr');
        }
        return redirect()->back();
    }
}
