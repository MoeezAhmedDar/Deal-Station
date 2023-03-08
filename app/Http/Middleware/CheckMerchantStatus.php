<?php

namespace App\Http\Middleware;

use App\Models\MerchantDetail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckMerchantStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('Admin')) {
            return $next($request);
        } elseif (Auth::check() && Auth::user()->hasRole('Merchant')) {
            $user = Auth::user();
            if (MerchantDetail::where([
                ['merchant_id', '=', $user->id],
                ['merchant_status', '=', 1]
            ])->exists()) {
                return $next($request);
            } else {
                return redirect()->route('users.account-ban');
            }
        }
        abort(403);
    }
}
