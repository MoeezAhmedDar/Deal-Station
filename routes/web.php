<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\MerchantController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\Merchant\MerchantBranchController;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\Merchant\MerchantOfferController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PrivacyPolicy;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Merchant\MerchantReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\LocalizationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Front End Routes
// Route::get('/', function () {
//     return view('welcome');
// })->name('welcome');


Auth::routes();

Route::get('lang/{locale}', [LocalizationController::class, 'langSwitcher'])->name('lang');

Route::get('users/account-banned',  function () {
    return view("admin.account-ban");
})->name('users.account-ban');



// Web Routes
// checkMerchantStatus
Route::group(['middleware' => ['auth']], function () {
    // General Routes
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/my-profile', [UserController::class, 'profile'])->name('my-profile');
    Route::post('users/profileUpdate', [UserController::class, 'profileUpdate'])->name('update-profile');
    Route::get('language/{locale}', [HomeController::class, 'langSwitcher'])->name('lang-switcher');
    Route::post('offers/update-image-order', [OfferController::class, 'updateImageOrder'])->name('offers.update-image-order');
    Route::get('branches/merchant-branches/{id}', [BranchController::class, 'fetchMerchantBranchesData'])->name('branches.merchant-branches');

    //Notification Routes
    Route::get('/notification.index', [NotificationController::class, 'index'])->name('notification.index');
    Route::post('/save-push-notification-token', [NotificationController::class, 'savePushNotificationToken'])->name('save-push-notification-token');
    Route::post('/send-push-notification', [NotificationController::class, 'sendPushNotification'])->name('send.push-notification');

    // Admin Routes
    Route::group(['middleware' => ['permission:admin-role-list']], function () {
        Route::get('roles/fetch-roles', [RoleController::class, 'fetchRolesData'])->name('fetch-roles');
        Route::resource('roles', RoleController::class);
    });

    Route::group(['middleware' => ['permission:admin-user-list']], function () {
        Route::post('users/fetch-users', [UserController::class, 'fetchUsersData'])->name('fetch-users');
        Route::resource('settings', SettingController::class);
        Route::resource('users', UserController::class);
    });

    Route::group(['middleware' => ['permission:admin-merchant-list']], function () {
        Route::post('merchants/fetch-merchants', [MerchantController::class, 'fetchMerchantsData'])->name('fetch-merchants');
        Route::resource('merchants', MerchantController::class);
    });

    Route::group(['middleware' => ['permission:admin-category-list']], function () {
        Route::post('categories/fetch-categories', [CategoryController::class, 'fetchCategoriesData'])->name('fetch-categories');
        Route::get('categories/fetch-category/{id}', [CategoryController::class, 'fetchCategoryData'])->name('categories.fetch-category');
        Route::post('categories/update-category', [CategoryController::class, 'updateCategoryData'])->name('categories.update-category');
        Route::resource('categories', CategoryController::class);
    });

    Route::group(['middleware' => ['permission:admin-city-list']], function () {
        Route::post('cities/fetch-cities', [CityController::class, 'fetchCitiesData'])->name('fetch-cities');
        Route::get('cities/fetch-city/{id}', [CityController::class, 'fetchCityData'])->name('cities.fetch-city');
        Route::post('cities/update-city', [CityController::class, 'updateCityData'])->name('cities.update-city');
        Route::resource('cities', CityController::class);
    });

    Route::group(['middleware' => ['permission:admin-branch-list']], function () {
        Route::post('branches/fetch-branches', [BranchController::class, 'fetchBranchesData'])->name('fetch-branches');
        Route::resource('branches', BranchController::class);
    });

    Route::group(['middleware' => ['permission:admin-subscription-list']], function () {
        Route::post('subscription-plans/fetch-subscription-plans', [SubscriptionPlanController::class, 'fetchSubscriptionPlansData'])->name('fetch-subscription-plans');
        Route::get('subscription-plans/fetch-subscription-plan/{id}', [SubscriptionPlanController::class, 'fetchSubscriptionPlanData'])->name('subscription-plans.fetch-subscription-plan');
        Route::post('subscription-plans/update-subscription-plan', [SubscriptionPlanController::class, 'updateSubscriptionPlanData'])->name('subscription-plans.update-subscription-plan');
        Route::resource('subscription-plans', SubscriptionPlanController::class);
    });

    Route::group(['middleware' => ['permission:admin-plans-list']], function () {
        Route::resource('plans', PlanController::class);
        Route::post('plans/fetch-plans', [PlanController::class, 'fetchPlansData'])->name('fetch-plans');
    });

    Route::group(['middleware' => ['permission:admin-offer-list']], function () {
        Route::get('offers/offer-requests', [OfferController::class, 'offerRequestsIndex'])->name('offers.offer-requests');
        Route::get('offers/{id}/clone', [OfferController::class, 'clone'])->name('offers.clone');
        Route::get('offers/{id}/promo-edit', [OfferController::class, 'editPromo'])->name('offers.promo-edit');
        Route::post('offers/promo-update', [OfferController::class, 'updatePromo'])->name('offers.promo-update');
        Route::post('offers/fetch-offers', [OfferController::class, 'fetchOffersData'])->name('fetch-offers');
        Route::post('offers/fetch-offers-for-branches', [OfferController::class, 'fetchOffersDataForBranches'])->name('fetch-offers-for-branches');
        Route::post('offers/fetch-offer-requests', [OfferController::class, 'fetchOfferRequestsData'])->name('fetch-offer-requests');
        Route::get('offers/delete-image/{id}', [OfferController::class, 'destroyImage'])->name('offers.destroy-image');
        Route::get('offers/download-promos/{id}', [OfferController::class, 'downloadPromos'])->name('offers.download-promos');
        Route::get('offers/download-qr/{id}', [OfferController::class, 'downloadQr'])->name('offers.download-qr');
        Route::resource('offers', OfferController::class);
    });

    Route::group(['middleware' => ['permission:admin-member-list']], function () {
        Route::post('members/fetch-members', [MemberController::class, 'fetchMembersData'])->name('fetch-members');
        Route::post('members/change-status', [MemberController::class, 'changeStatus'])->name('change-status');
        Route::get('members/fetch-memberships/{id}', [PlanController::class, 'fetchMembershipSubscriptionPlans'])->name('members.fetch-memberships');
        Route::resource('members', MemberController::class);
    });

    Route::group(['middleware' => ['permission:admin-campaign-list']], function () {
        Route::post('campaigns/fetch-campaigns', [CampaignController::class, 'fetchCampaignsData'])->name('fetch-campaigns');
        Route::get('campaigns/fetch-campaign/{id}', [CampaignController::class, 'fetchCampaignData'])->name('campaigns.fetch-campaign');
        Route::post('campaigns/update-campaign', [CampaignController::class, 'updateCampaignData'])->name('campaigns.update-campaign');
        Route::resource('campaigns', CampaignController::class);
    });

    Route::group(['middleware' => ['permission:admin-advertisement-list']], function () {
        Route::post('advertisements/fetch-advertisements', [AdvertisementController::class, 'fetchAdvertisementsData'])->name('fetch-advertisements');
        Route::get('advertisements/fetch-advertisement/{id}', [AdvertisementController::class, 'fetchAdvertisementData'])->name('advertisements.fetch-advertisement');
        Route::post('advertisements/update-advertisement', [AdvertisementController::class, 'updateAdvertisementData'])->name('advertisements.update-advertisement');
        Route::get('advertisements/fetch-items/{item}', [AdvertisementController::class, 'fetchAdvertisingItems'])->name('advertisements.fetch-items');
        Route::resource('advertisements', AdvertisementController::class);
    });

    Route::group(['middleware' => ['permission:admin-report-list']], function () {
        Route::get('reports/redeemed-coupons', [ReportController::class, 'redeemedCoupons'])->name('reports.redeemed-coupons');
        Route::post('reports/fetch-re-coupons', [ReportController::class, 'fetchRedeemedCoupons'])->name('reports.fetch-re-coupons');
        Route::post('reports/export-pdf-coupons', [ReportController::class, 'ExportPDFRedeemedCoupons'])->name('reports.export-pdf-coupons');
        Route::get('reports/export-pdf-members', [ReportController::class, 'ExportPDFMembers'])->name('reports.export-pdf-members');
    });

    Route::group(['middleware' => ['role:Merchant']], function () {
        // Merchant Routes
        // Route::post('merchants/profileUpdate', [MerchantController::class, 'profileUpdate'])->name('merchant-update-profile');

        Route::get('merchant-branches/branches/{id}', [BranchController::class, 'fetchMerchantBranchesData'])->name('merchant-branches.branches');
        Route::resource('merchant-branches', MerchantBranchController::class);

        Route::post('merchant-offers/fetch-offers', [MerchantOfferController::class, 'fetchMerchantOffersData'])->name('fetch-merchant-offers');
        Route::get('merchant-offers/{id}/clone', [MerchantOfferController::class, 'clone'])->name('merchant-offers.clone');
        Route::get('merchant-offers/{id}/promo-edit', [MerchantOfferController::class, 'editPromo'])->name('merchant-offers.promo-edit');
        Route::post('merchant-offers/promo-update', [MerchantOfferController::class, 'updatePromo'])->name('merchant-offers.promo-update');
        Route::get('merchant-offers/download-promos/{id}', [MerchantOfferController::class, 'downloadPromos'])->name('merchant-offers.download-promos');
        Route::get('merchant-offers/download-qr/{id}', [MerchantOfferController::class, 'downloadQr'])->name('merchant-offers.download-qr');
        Route::get('merchant-offers/delete-image/{id}', [MerchantOfferController::class, 'destroyImage'])->name('merchant-offers.destroy-image');
        Route::resource('merchant-offers', MerchantOfferController::class);

        Route::get('merchant-reports/redeemed-coupons', [MerchantReportController::class, 'redeemedCoupons'])->name('merchant-reports.redeemed-coupons');
        Route::post('merchant-reports/fetch-re-coupons', [MerchantReportController::class, 'fetchRedeemedCoupons'])->name('merchant-reports.fetch-re-coupons');
        Route::post('merchant-reports/export-pdf-coupons', [MerchantReportController::class, 'ExportPDFRedeemedCoupons'])->name('merchant-reports.export-pdf-coupons');
    });
});

Route::get('privacy-policy', [PrivacyPolicy::class, 'index'])->name('privacy-policy');
Route::get('terms-conditions', [PrivacyPolicy::class, 'terms'])->name('terms-conditions');

//Payment Routes
Route::get('payment/checkout/{trans_id}', [PaymentController::class, 'prepareCheckout'])->name('payment.checkout');
Route::get('payment/proceed-payment/{trans_id}', [PaymentController::class, 'proceedPayment'])->name('payment.proceed-payment');

Route::get('payment/response/{mode}',  function () {
    return view("payment.response");
})->name('payment.response');

// Cache Clear Route
Route::get('cache-clear', function () {
    /* php artisan {Commands} */
    Artisan::call('route:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:cache');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    echo ("Cache Clear");
});

// Home Path Routes
Route::get(
    '/{path?}',
    [HomeController::class, 'index']
)->where(['path' => '.*'])->name('index');
