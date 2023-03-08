<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\MerchantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth Routes
Route::group(
    ['middleware' => 'localization'],
    function () {
        Route::group(['prefix' => 'v1/auth'], function () {
            Route::post('member-login', [AuthController::class, 'authenticateMember']);
            Route::post('member-signup', [AuthController::class, 'memberRegister'])->name('member.signup');
            Route::post('cashier-login', [AuthController::class, 'authenticateCashier']);
            Route::post('phone-status', [AuthController::class, 'phoneNumberStatus']);

            Route::post('otp-send', [AuthController::class, 'sendOtpVerification']);
            Route::post('otp-verify', [AuthController::class, 'verifyOtpVerification']);


            Route::get('cities', [AuthController::class, 'fetchCities']);
            Route::get('settings', [AuthController::class, 'fetchSettings']);
            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('logout', [AuthController::class, 'logoutUser']);
                Route::get('member-data', [AuthController::class, 'authenticatedMemberData']);
                Route::post('member-update', [AuthController::class, 'updateMemberData']);
                Route::post('member-profile-image', [AuthController::class, 'updateMemberImageData'])->name('member.profile-image');
            });
        });


        Route::group(['prefix' => 'v1/member'], function () {
            Route::get('guest-offers', [OfferController::class, 'fetchGuestOffersData']);
            Route::get('offers', [OfferController::class, 'fetchAllOffersData']);
            Route::get('member-campaigns', [CampaignController::class, 'fetchAllCampaignsData']);
            Route::post('branches', [MerchantController::class, 'fetchAllMerchantBranches']);
            Route::get('discover-offers', [OfferController::class, 'discoverAllOffersData']);
            Route::post('merchants', [MerchantController::class, 'fetchAllMerchant']);

            Route::group(['middleware' => 'auth:api'], function () {
                Route::get('membership-plans', [MembershipController::class, 'fetchMembershipPlans']);
                Route::get('membership-subscription', [MembershipController::class, 'fetchMembershipSubscriptionPlans']);
                Route::post('member-subscription', [MembershipController::class, 'memberSubscription']);
                Route::get('promo-offers', [OfferController::class, 'fetchPromoOffersData']);
                Route::post('offer', [OfferController::class, 'fetchOfferData']);
                Route::post('get-promo', [OfferController::class, 'fetchOfferPromoData']);
                Route::post('redeem-promo', [OfferController::class, 'RedeemPromoOffer']);
                Route::get('promo-details/{promo}', [OfferController::class, 'fetchPromoData']);
                // Route::get('member-campaigns', [CampaignController::class, 'fetchAllCampaignsData']);
                Route::post('campaign-offers', [CampaignController::class, 'fetchCampaignOffersData']);
                Route::post('branch-offers', [OfferController::class, 'fetchBranchOffers']);
                Route::post('category-offers', [OfferController::class, 'fetchCategoryOffersData']);
                Route::post('search-offers', [OfferController::class, 'fetchSearchOffersData']);
                Route::get('wishlist-offers', [OfferController::class, 'fetchWishlistOffersData']);
                Route::post('wishlist-offer', [OfferController::class, 'wishlistOfferData']);
                Route::get('advertisements', [AdvertisementController::class, 'fetchAllAdvertisementsData']);

                Route::get('offer-report', [OfferController::class, 'fetchOffersReportData']);

                // Route::post('branches', [MerchantController::class, 'fetchAllMerchantBranches']);
                Route::get('offer-notifications', [OfferController::class, 'fetchOffersNotificationsData']);

                Route::get('offer-branch-code/{promo}', [OfferController::class, 'fetchBranchCode']);
            });
        });
    }
);
