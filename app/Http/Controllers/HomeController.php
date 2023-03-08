<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use App\Models\City;
use App\Models\SubscriptionPlan;
use App\Models\Plan;
use App\Models\Offer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $merchant_count = 0;
        $branch_count = 0;
        $category_count = 0;
        $city_count = 0;
        $subscriptions_count = 0;
        $memberships_count = 0;
        $offers_count = 0;
        $members_count = 0;
        $user = User::find(Auth::user()->id);
        if (!$user->hasRole('Merchant')) {
            $merchant_count = User::role('Merchant')->count();
            $branch_count = Branch::all()->count();
            $category_count = Category::all()->count();
            $city_count = City::all()->count();
            $subscriptions_count = SubscriptionPlan::all()->count();
            $memberships_count = Plan::all()->count();
            $offers_count = Offer::all()->count();
            $members_count = User::role('Member')->count();
        } else if ($user->hasRole('Merchant')) {
            $branch_count = Branch::where('merchant_id', '=', Auth::user()->id)->count();
            $offers_count = Offer::where('merchant_id', '=', Auth::user()->id)->count();
        }
        return view('admin.home', compact(
            'merchant_count',
            'branch_count',
            'category_count',
            'city_count',
            'subscriptions_count',
            'memberships_count',
            'offers_count',
            'members_count'
        ));
    }

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
