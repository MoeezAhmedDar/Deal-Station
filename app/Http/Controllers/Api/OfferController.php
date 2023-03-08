<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\CouponRedeem;
use App\Models\MerchantDetail;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Models\VisibleMembership;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\OfferCategory;
use App\Models\Advertisement;
use App\Models\Branch;
use App\Models\OfferNotification;
use App\Models\OfferWishlist;
use App\Models\City;
use App\Models\OfferBranch;
use App\Models\BranchRedeem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class OfferController extends BaseController
{
    public function fetchAllOffersData(Request $request)
    {
        if ($request->bearerToken()) {
            if (!Auth::guard('api')->check()) {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }

            $user_data = auth('api')->user()->load(['latestMemberSubscription']);
            $offers = array();
            $categories_data = array();
            $categoriesCount = 0;

            $user_subscribed = false;
            $user_subscription = [];
            $now = Carbon::now();

            if ($user_data->latestMemberSubscription != null) {
                $sub_ex = Carbon::parse($user_data->latestMemberSubscription->user_subscriptions_expiry);
                $user_subscription = $user_data->latestMemberSubscription;
                if ($now->lt($sub_ex)) {
                    $user_subscribed = true;
                }
            }

            if ($user_subscription) {
                $user_plan = $user_subscription->plan;
                $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                    return $q->where([
                        ['offer_status', '!=', 1],
                        ['offer_type', '!=', 2],
                    ]);
                }])->where('plan', '=', $user_plan)->get();

                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });

                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);

                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        if (($now->gte($from) && $now->lte($to))) {
                            $offer = Offer::find($offer_data['offer']['id']);
                            $gallery = $offer->gallery;
                            $gallery_data = array();
                            foreach ($gallery as $image) {
                                $gallery_data[] = url($image->image);
                            }
                            $wishlist = OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists();
                            $offers[] =  [
                                'id' => $offer_data['offer']['offer_uniid'],
                                'offer_name' => $offer_data['offer']['offer_name'],
                                'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                'offer_description' => $offer_data['offer']['offer_description'],
                                'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                'offer_discount' => $offer_data['offer']['offer_discount'],
                                'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                'offer_status' => $offer_data['offer']['offer_status'],
                                'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                'wishlist' => $wishlist,
                            ];
                        }
                    }
                }

                $categories = Category::all();
                foreach ($categories as $category) {
                    $categories_data[$categoriesCount] = [
                        'id' => $category['id'],
                        'category_name' => $category['category_name'],
                        'category_name_arabic' => $category['category_name_arabic'],
                        'category_icon' => url($category['category_icon']),
                    ];
                    $categoriesCount++;
                }
                $advertisements_data = array();
                $banners_data = array();
                $advertisements = Advertisement::orderBy('advertisement_type', 'ASC')->get();
                foreach ($advertisements as $advertisement) {
                    if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] <= 5) {
                        $advertisements_data[] = [
                            'advertisement_type' => $advertisement['advertisement_type'],
                            'advertisement_name' => $advertisement['advertisement_name'],
                            'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                            'advertisement_text' => $advertisement['advertisement_text'],
                            'advertisement_image' => url($advertisement['advertisement_image']),
                            'advertisement_item' => $advertisement['advertisement_item'],
                            'advertisement_item_id' => $advertisement['advertisement_item_id'],
                        ];
                    } else if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] > 5) {
                        $banners_data[] = [
                            'advertisement_type' => $advertisement['advertisement_type'],
                            'advertisement_name' => $advertisement['advertisement_name'],
                            'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                            'advertisement_text' => $advertisement['advertisement_text'],
                            'advertisement_image' => url($advertisement['advertisement_image']),
                            'advertisement_item' => $advertisement['advertisement_item'],
                            'advertisement_item_id' => $advertisement['advertisement_item_id'],
                        ];
                    }
                }
                $myResponse = [
                    'advertisements' => $advertisements_data,
                    'banners' => $banners_data,
                    'categories' => $categories_data,
                    'offers' => $offers,
                    'is_profile_completed' => $user_data->is_completed,
                    'subscribed' => $user_subscribed,
                    'subscription_expire' => $user_subscription ? date('d-m-Y i:s', strtotime($user_subscription->user_subscriptions_expiry)) : '',
                ];
                return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
            } else {
                $offers = array();
                $categories_data = array();
                $categoriesCount = 0;
                $offers_data = Offer::all();
                foreach ($offers_data as $offer_data) {
                    $fromDate = date('d-m-Y', strtotime($offer_data['offer_from']));
                    $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                    $toDate = date('d-m-Y', strtotime($offer_data['offer_to']));
                    $to = Carbon::createFromFormat('d-m-Y', $toDate);

                    $nowDate = Carbon::now()->format('d-m-Y');
                    $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                    if ($offer_data['offer_status'] != 1 && $offer_data['offer_type'] != 2 && ($now->gte($from) && $now->lte($to))) {
                        $offer = Offer::find($offer_data['id']);
                        $gallery = $offer->gallery;
                        $gallery_data = array();
                        foreach ($gallery as $image) {
                            $gallery_data[] = url($image->image);
                        }
                        $offers[] =  [
                            'id' => $offer_data['offer_uniid'],
                            'offer_name' => $offer_data['offer_name'],
                            'offer_name_arabic' => $offer_data['offer_name_arabic'],
                            'offer_description' => $offer_data['offer_description'],
                            'offer_description_arabic' => $offer_data['offer_description_arabic'],
                            'offer_desc_description' => $offer_data['offer_desc_description'],
                            'offer_desc_description_arabic' => $offer_data['offer_desc_description_arabic'],
                            'offer_discount' => $offer_data['offer_discount'],
                            'offer_to' => date('d-m-Y', strtotime($offer_data['offer_to'])),
                            'offer_from' => date('d-m-Y', strtotime($offer_data['offer_from'])),
                            'offer_status' => $offer_data['offer_status'],
                            'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                            'wishlist' => false,
                        ];
                    }
                }
                $categories = Category::all();
                foreach ($categories as $category) {
                    $categories_data[$categoriesCount] = [
                        'id' => $category['id'],
                        'category_name' => $category['category_name'],
                        'category_name_arabic' => $category['category_name_arabic'],
                        'category_icon' => url($category['category_icon']),
                    ];
                    $categoriesCount++;
                }
                $advertisements_data = array();
                $banners_data = array();
                $advertisements = Advertisement::orderBy('advertisement_type', 'ASC')->get();
                foreach ($advertisements as $advertisement) {
                    if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] <= 5) {
                        $advertisements_data[] = [
                            'advertisement_type' => $advertisement['advertisement_type'],
                            'advertisement_name' => $advertisement['advertisement_name'],
                            'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                            'advertisement_text' => $advertisement['advertisement_text'],
                            'advertisement_image' => url($advertisement['advertisement_image']),
                            'advertisement_item' => $advertisement['advertisement_item'],
                            'advertisement_item_id' => $advertisement['advertisement_item_id'],
                        ];
                    } else if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] > 5) {
                        $banners_data[] = [
                            'advertisement_type' => $advertisement['advertisement_type'],
                            'advertisement_name' => $advertisement['advertisement_name'],
                            'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                            'advertisement_text' => $advertisement['advertisement_text'],
                            'advertisement_image' => url($advertisement['advertisement_image']),
                            'advertisement_item' => $advertisement['advertisement_item'],
                            'advertisement_item_id' => $advertisement['advertisement_item_id'],
                        ];
                    }
                }
                $myResponse = [
                    'advertisements' => $advertisements_data,
                    'banners' => $banners_data,
                    'categories' => $categories_data,
                    'offers' => $offers,
                    'is_profile_completed' => $user_data->is_completed,
                    'subscribed' => $user_subscribed,
                    'subscription_expire' => $user_subscription ? date('d-m-Y i:s', strtotime($user_subscription->user_subscriptions_expiry)) : '',
                ];
                return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
            }
        } else {
            $offers = array();
            $categories_data = array();
            $categoriesCount = 0;
            $offers_data = Offer::all();
            foreach ($offers_data as $offer_data) {
                $fromDate = date('d-m-Y', strtotime($offer_data['offer_from']));
                $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                $toDate = date('d-m-Y', strtotime($offer_data['offer_to']));
                $to = Carbon::createFromFormat('d-m-Y', $toDate);

                $nowDate = Carbon::now()->format('d-m-Y');
                $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                if ($offer_data['offer_status'] != 1 && $offer_data['offer_type'] != 2 && ($now->gte($from) && $now->lte($to))) {
                    $offer = Offer::find($offer_data['id']);
                    $gallery = $offer->gallery;
                    $gallery_data = array();
                    foreach ($gallery as $image) {
                        $gallery_data[] = url($image->image);
                    }
                    $offers[] =  [
                        'id' => $offer_data['offer_uniid'],
                        'offer_name' => $offer_data['offer_name'],
                        'offer_name_arabic' => $offer_data['offer_name_arabic'],
                        'offer_description' => $offer_data['offer_description'],
                        'offer_description_arabic' => $offer_data['offer_description_arabic'],
                        'offer_desc_description' => $offer_data['offer_desc_description'],
                        'offer_desc_description_arabic' => $offer_data['offer_desc_description_arabic'],
                        'offer_discount' => $offer_data['offer_discount'],
                        'offer_to' => date('d-m-Y', strtotime($offer_data['offer_to'])),
                        'offer_from' => date('d-m-Y', strtotime($offer_data['offer_from'])),
                        'offer_status' => $offer_data['offer_status'],
                        'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                        'wishlist' => false,
                    ];
                }
            }


            $categories = Category::all();
            foreach ($categories as $category) {
                $categories_data[$categoriesCount] = [
                    'id' => $category['id'],
                    'category_name' => $category['category_name'],
                    'category_name_arabic' => $category['category_name_arabic'],
                    'category_icon' => url($category['category_icon']),
                ];
                $categoriesCount++;
            }
            $advertisements_data = array();
            $banners_data = array();
            $advertisements = Advertisement::orderBy('advertisement_type', 'ASC')->get();
            foreach ($advertisements as $advertisement) {
                if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] <= 5) {
                    $advertisements_data[] = [
                        'advertisement_type' => $advertisement['advertisement_type'],
                        'advertisement_name' => $advertisement['advertisement_name'],
                        'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                        'advertisement_text' => $advertisement['advertisement_text'],
                        'advertisement_image' => url($advertisement['advertisement_image']),
                        'advertisement_item' => $advertisement['advertisement_item'],
                        'advertisement_item_id' => $advertisement['advertisement_item_id'],
                    ];
                } else if ($advertisement['advertisement_status'] == 1 && $advertisement['advertisement_type'] > 5) {
                    $banners_data[] = [
                        'advertisement_type' => $advertisement['advertisement_type'],
                        'advertisement_name' => $advertisement['advertisement_name'],
                        'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                        'advertisement_text' => $advertisement['advertisement_text'],
                        'advertisement_image' => url($advertisement['advertisement_image']),
                        'advertisement_item' => $advertisement['advertisement_item'],
                        'advertisement_item_id' => $advertisement['advertisement_item_id'],
                    ];
                }
            }
            $myResponse = [
                'advertisements' => $advertisements_data,
                'banners' => $banners_data,
                'categories' => $categories_data,
                'offers' => $offers,
                'is_profile_completed' => 'true',
                'subscribed' => true,
            ];
            return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
        }
    }

    public function fetchGuestOffersData(Request $request)
    {
        $offers = array();
        $categories_data = array();
        $offersCount = 0;
        $categoriesCount = 0;
        $offers_data = Offer::all();
        foreach ($offers_data as $offer_data) {
            $fromDate = date('d-m-Y', strtotime($offer_data['offer_from']));
            $from = Carbon::createFromFormat('d-m-Y', $fromDate);

            $toDate = date('d-m-Y', strtotime($offer_data['offer_to']));
            $to = Carbon::createFromFormat('d-m-Y', $toDate);

            $nowDate = Carbon::now()->format('d-m-Y');
            $now = Carbon::createFromFormat('d-m-Y', $nowDate);
            if ($offer_data['offer_status'] != 1 && ($now->gte($from) && $now->lte($to))) {
                $offer = Offer::find($offer_data['id']);
                $gallery = $offer->gallery;
                $gallery_data = array();
                foreach ($gallery as $image) {
                    $gallery_data[] = url($image->image);
                }
                $offers[] =  [
                    'id' => $offer_data['offer']['offer_uniid'],
                    'offer_name' => $offer_data['offer']['offer_name'],
                    'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                    'offer_description' => $offer_data['offer']['offer_description'],
                    'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                    'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                    'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                    'offer_discount' => $offer_data['offer']['offer_discount'],
                    'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                    'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                    'offer_status' => $offer_data['offer']['offer_status'],
                    'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                    'wishlist' => false,
                ];
            }
        }
        $categories = Category::all();
        foreach ($categories as $category) {
            $categories_data[$categoriesCount] = [
                'category_name' => $category['category_name'],
                'category_name_arabic' => $category['category_name_arabic'],
                'category_icon' => url($category['category_icon']),
            ];
            $categoriesCount++;
        }
        $myResponse = [
            'categories' => $categories_data,
            'offers' => $offers,
        ];
        return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
    }

    public function fetchOfferData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            $id = $request->only('id');
            if ($id) {
                $result = array();
                if (Offer::where('offer_uniid', '=', $id)->exists()) {
                    $offer_data = Offer::where('offer_uniid', '=', $id)->first();
                    if ($offer_data['offer_status'] != 1) {
                        $offer = Offer::find($offer_data['id']);

                        $gallery = $offer->gallery;
                        $gallery_data = array();
                        foreach ($gallery as $image) {
                            $gallery_data[] = url($image->image);
                        }
                        $promo_blur = '';
                        if ($offer_data['offer_type'] == 1 && $offer_data['offer_coupon_type'] == 1) {
                            $promo_blur = url('admin/assets/media/qr.png');
                        } else  if ($offer_data['offer_type'] == 1 && $offer_data['offer_coupon_type'] == 2) {
                            $promo_blur = url('admin/assets/media/code.png');
                        }
                        $merchant_data = MerchantDetail::where('merchant_id', '=', $offer_data['merchant_id'])->first();
                        $wishlist = OfferWishlist::where([
                            ['offer_id', '=', $offer_data['id']],
                            ['user_id', '=', $user_data['id']]
                        ])->exists();
                        $result =  [
                            'id' => $offer_data['offer_uniid'],
                            'offer_name' => $offer_data['offer_name'],
                            'offer_name_arabic' => $offer_data['offer_name_arabic'],
                            'offer_description' => $offer_data['offer_description'],
                            'offer_description_arabic' => $offer_data['offer_description_arabic'],
                            'offer_desc_description' => $offer_data['offer_desc_description'],
                            'offer_desc_description_arabic' => $offer_data['offer_desc_description_arabic'],
                            'offer_discount' => $offer_data['offer_discount'],
                            'offer_to' => date('d-m-Y', strtotime($offer_data['offer_to'])),
                            'offer_from' => date('d-m-Y', strtotime($offer_data['offer_from'])),
                            'offer_blur' =>  $promo_blur,
                            'gallery' => $gallery_data,
                            'merchant_logo' => url($merchant_data['merchant_logo']),
                            'merchant_brand' => $merchant_data['merchant_brand'],
                            'merchant_brand_arabic' => $merchant_data['merchant_brand_arabic'],
                            'wishlist' => $wishlist,
                        ];
                    }
                    return $this->responseApi($result, true, 'Offer Fetched Successfully', 200);
                } else {
                    return $this->responseApi([], false, __('Id is not correct'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Offer id is missing'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchOfferPromoData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            $id = $request->only('id');
            if ($id) {
                $result = array();
                if (Offer::where('offer_uniid', '=', $id)->exists()) {
                    $offer_data = Offer::where('offer_uniid', '=', $id)->first();
                    $duration_filter = '';
                    $coupon_data = '';
                    $promo_data = '';
                    $redeemCount = '';
                    if ($offer_data['offer_status'] != 1) {
                        $offer = Offer::find($offer_data['id']);
                        if ($offer['offer_per_user'] != 0) {
                            $redeem_duration = $offer['offer_usage_duration'];
                            if ($redeem_duration == 1) {
                                $redeemCount = CouponRedeem::where([
                                    ['user_id', '=', $user_data['id']],
                                    ['offer_id', '=', $offer['id']],
                                ])->whereMonth('created_at', '=', Carbon::now()->month)->count();
                            } else if ($redeem_duration == 2) {
                                $redeemCount = CouponRedeem::where([
                                    ['user_id', '=', $user_data['id']],
                                    ['offer_id', '=', $offer['id']],
                                ])->whereYear('created_at', '=', Carbon::now()->year)->count();
                            } else if ($redeem_duration == 3) {
                                $redeemCount = CouponRedeem::where([
                                    ['user_id', '=', $user_data['id']],
                                    ['offer_id', '=', $offer['id']],
                                ])->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
                            }
                        }
                    }
                    if ($offer['offer_per_user'] != 0 && $redeemCount < $offer['offer_per_user']) {
                        if (Coupon::where([
                            ['coupon_status', '=', 1],
                            ['offer_id', '=', $offer['id']]
                        ])->exists()) {
                            $coupon_data = Coupon::where([
                                ['coupon_status', '=', 1],
                                ['offer_id', '=', $offer['id']]
                            ])->first();
                        } else {
                            return $this->responseApi([], false, __('No Promo exits against this offer'), 417);
                        }
                    } else if ($offer['offer_per_user'] != 0 && $redeemCount >= $offer['offer_per_user']) {
                        return $this->responseApi([], false, __('User Limit is exceeded'), 417);
                    } else if ($offer['offer_per_user'] == 0) {
                        if (Coupon::where([
                            ['coupon_status', '=', 1],
                            ['offer_id', '=', $offer['id']]
                        ])->exists()) {
                            $coupon_data = Coupon::where([
                                ['coupon_status', '=', 1],
                                ['offer_id', '=', $offer['id']]
                            ])->first();
                        } else {
                            return $this->responseApi([], false, __('No Promo exits against this offer'), 417);
                        }
                    }
                    if ($offer['offer_type'] == 1 && $offer['offer_coupon_type'] == 1) {
                        $file_name = $coupon_data['coupon_code'] . '.png';
                        QrCode::size(500)
                            ->format('png')
                            ->backgroundColor(255, 255, 255)
                            ->generate($coupon_data['coupon_code'], public_path('uploads/offers/qrcodes/' . $file_name));
                        $promo_data = [
                            'promo' => url('uploads/offers/qrcodes/' . $file_name),
                            'promo_type' => 'qr',
                            'promo_blur' => url('admin/assets/media/qr.png'),
                        ];
                    } else if ($offer['offer_type'] == 1 && $offer['offer_coupon_type'] == 2) {
                        $promo_data = [
                            'promo' => $coupon_data['coupon_code'],
                            'promo_type' => 'code',
                            'promo_blur' => url('admin/assets/media/code.png'),
                        ];
                    } else if ($offer['offer_type'] == 2 && $offer['offer_coupon_type'] == 0) {
                        $file_name = $coupon_data['coupon_code'] . '.png';
                        QrCode::size(500)
                            ->format('png')
                            ->backgroundColor(255, 255, 255)
                            ->generate($coupon_data['coupon_code'], public_path('uploads/offers/qrcodes/' . $file_name));
                        $promo_data = [
                            'promo' => url('uploads/offers/qrcodes/' . $file_name),
                            'promo_type' => 'qr',
                            'promo_blur' => url('admin/assets/media/qr.png'),
                        ];
                    }
                    return $this->responseApi($promo_data, true, 'Offer Promo Fetched Successfully', 200);
                } else {
                    return $this->responseApi([], false, __('Id is not correct'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Offer id is missing'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function RedeemPromoOffer(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            $inputs = $request->only('promo', 'offer', 'price', 'city');
            if ($request->user()->hasRole('Cashier')) {
                $city_data = $user_data->cashierBranch;
                $inputs['city'] =  $city_data ? $city_data['branch_city'] : 0;
            }
            if (Offer::where('offer_uniid', '=', $inputs['offer'])->exists()) {
                $offer_data = Offer::where('offer_uniid', '=', $inputs['offer'])->first();
                if (Coupon::where([
                    ['coupon_status', '=', 1],
                    ['offer_id', '=', $offer_data['id']],
                    ['coupon_code', '=', $inputs['promo']]
                ])->exists()) {
                    $offer_price = 0;
                    $discount_price = 0;
                    if ($offer_data['offer_type'] == 1 && $offer_data['offer_coupon_type'] == 1) {
                        $cashier_branch = $user_data->cashierBranch;
                        $allow_coupons = OfferBranch::select('coupons')->where([
                            ['branch', '=', $cashier_branch['id']],
                            ['offer', '=', $offer_data['id']]
                        ])->first();
                        $branch_redeems = BranchRedeem::where([
                            ['branch_id', '=', $cashier_branch['id']],
                            ['offer_id', '=', $offer_data['id']]
                        ])->count();
                        if ($branch_redeems >= $allow_coupons['coupons']) {
                            return $this->responseApi([], false, __('Branch Redeem Limit Exceeded'), 417);
                        }
                        $offer_price = $inputs['price'];
                        $discount_price = ($offer_price / 100) * $offer_data['offer_discount'];
                    } else if ($offer_data['offer_type'] == 1 && $offer_data['offer_coupon_type'] == 2) {
                        $offer_price = $offer_data['offer_price'];
                        $discount_price = ($offer_price / 100) * $offer_data['offer_discount'];
                    } else if ($offer_data['offer_type'] == 2) {
                        $offer_price = $inputs['price'];
                        $discount_price = ($offer_price / 100) * $offer_data['offer_discount'];
                    }
                    $couponDta = Coupon::where([
                        ['coupon_status', '=', 1],
                        ['offer_id', '=', $offer_data['id']],
                        ['coupon_code', '=', $inputs['promo']]
                    ])->first();
                    $redeem_data = [
                        'offer_id' => $offer_data['id'],
                        'coupons_id' => $couponDta['id'],
                        'user_id' => $user_data['id'],
                        'discount' => $offer_data['offer_discount'],
                        'price' => $offer_price,
                        'city_id' => $inputs['city'],
                        'discount_price' => $discount_price,
                    ];
                    CouponRedeem::create($redeem_data);
                    if ($offer_data['offer_type'] == 1 && $offer_data['offer_coupon_type'] == 1) {
                        $cashier_branch = $user_data->cashierBranch;
                        $redeem_data = [
                            'offer_id' => $offer_data['id'],
                            'branch_id' => $cashier_branch['id'],
                        ];
                        BranchRedeem::create($redeem_data);
                    }
                    if ($offer_data['offer_type'] != 2) {
                        Coupon::where([
                            ['coupon_status', '=', 1],
                            ['offer_id', '=', $offer_data['id']],
                            ['coupon_code', '=', $inputs['promo']]
                        ])->update(['coupon_status' => 2]);
                    }
                    return $this->responseApi([], true, 'Coupon Redeemed', 200);
                } else {
                    return $this->responseApi([], false, __('Coupon is Incorrect'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Offer not exists'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchPromoData($id)
    {
        if ($id) {
            $result = array();
            if (Coupon::where('coupon_code', '=', $id)->exists()) {
                $coupon_data = Coupon::where('coupon_code', '=', $id)->first();
                if ($coupon_data['coupon_status'] == 1) {
                    $offer = Coupon::find($coupon_data['id'])->offer;
                    $gallery = $offer->gallery;
                    $gallery_data = array();
                    foreach ($gallery as $image) {
                        $gallery_data[] = url($image->image);
                    }
                    $result =  [
                        'id' => $offer['offer_uniid'],
                        'offer_name' => $offer['offer_name'],
                        'offer_name_arabic' => $offer['offer_name_arabic'],
                        'offer_description' => $offer['offer_description'],
                        'offer_description_arabic' => $offer['offer_description_arabic'],
                        'offer_desc_description' => $offer['offer_desc_description'],
                        'offer_desc_description_arabic' => $offer['offer_desc_description_arabic'],
                        'offer_discount' => $offer['offer_discount'],
                        'offer_to' => date('d-m-Y', strtotime($offer['offer_to'])),
                        'offer_from' => date('d-m-Y', strtotime($offer['offer_from'])),
                        'gallery' => $gallery_data,
                    ];
                    return $this->responseApi($result, true, 'Offer Fetched Successfully', 200);
                } else {
                    return $this->responseApi([], false, __('Promo already Used'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Id is not correct'), 417);
            }
        } else {
            return $this->responseApi([], false, __('Offer id is missing'), 417);
        }
    }

    public function fetchPromoOffersData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $offers = array();

            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                    return $q->where([
                        ['offer_status', '!=', 1],
                        ['offer_type', '!=', 2],
                    ]);
                }])->where('plan', '=', $user_plan)->get();
                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });
                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);

                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        $type = $request->only('type');
                        $cou_type = '';
                        if ($type['type'] == 'qr') {
                            $cou_type = 1;
                        } else if ($type['type'] == 'code') {
                            $cou_type = 2;
                        }
                        if (($now->gte($from) && $now->lte($to)) && $offer_data['offer']['offer_coupon_type'] == $cou_type) {
                            $offer = Offer::find($offer_data['offer']['id']);
                            $gallery = $offer->gallery;
                            $gallery_data = array();
                            foreach ($gallery as $image) {
                                $gallery_data[] = url($image->image);
                            }
                            $wishlist = OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists();
                            $offers[] =  [
                                'id' => $offer_data['offer']['offer_uniid'],
                                'offer_name' => $offer_data['offer']['offer_name'],
                                'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                'offer_description' => $offer_data['offer']['offer_description'],
                                'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                'offer_discount' => $offer_data['offer']['offer_discount'],
                                'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                'offer_status' => $offer_data['offer']['offer_status'],
                                'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                'wishlist' => $wishlist,
                            ];
                        }
                    }
                }
                return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
            } else {
                return $this->responseApi([], false, __('User does not have any Subscription'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchCategoryOffersData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $id = $request->only('id');
            if ($id) {
                if (OfferCategory::where('category', '=', $id)->exists()) {
                    $categories = OfferCategory::where('category', '=', $id)->get();
                    $category_offers = array();
                    foreach ($categories as $category) {
                        $category_offers[] = $category['offer'];
                    }

                    $offers = array();
                    if ($user_data->latestMemberSubscription) {
                        $user_plan = $user_data->latestMemberSubscription->plan;
                        $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                            return $q->where([
                                ['offer_status', '!=', 1],
                                ['offer_type', '!=', 2],
                            ]);
                        }])->where('plan', '=', $user_plan)->get();
                        $offers_data = $visible_offers->map(function ($visible_offer) {
                            return collect($visible_offer->toArray())
                                ->only(['offer'])
                                ->all();
                        });
                        foreach ($offers_data as $offer_data) {
                            if ($offer_data['offer']) {
                                $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                                $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                                $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                                $to = Carbon::createFromFormat('d-m-Y', $toDate);
                                $nowDate = Carbon::now()->format('d-m-Y');
                                $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                                if (($now->gte($from) && $now->lte($to)) && in_array($offer_data['offer']['id'], $category_offers)) {
                                    $offer = Offer::find($offer_data['offer']['id']);
                                    $gallery = $offer->gallery;
                                    $gallery_data = array();
                                    foreach ($gallery as $image) {
                                        $gallery_data[] = url($image->image);
                                    }
                                    $wishlist = OfferWishlist::where([
                                        ['offer_id', '=', $offer_data['offer']['id']],
                                        ['user_id', '=', $user_data['id']]
                                    ])->exists();
                                    $offers[] = [
                                        'id' => $offer_data['offer']['offer_uniid'],
                                        'offer_name' => $offer_data['offer']['offer_name'],
                                        'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                        'offer_description' => $offer_data['offer']['offer_description'],
                                        'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                        'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                        'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                        'offer_discount' => $offer_data['offer']['offer_discount'],
                                        'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                        'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                        'offer_status' => $offer_data['offer']['offer_status'],
                                        'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                        'wishlist' => $wishlist,
                                    ];
                                }
                            }
                        }
                        return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
                    } else {
                        return $this->responseApi([], false, __('User does not have any Subscription'), 417);
                    }
                } else {
                    return $this->responseApi([], false, __('Id is not correct'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Category id is missing'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function discoverAllOffersData(Request $request)
    {
        if ($request->bearerToken()) {
            $user_data = auth('api')->user()->load(['latestMemberSubscription']);
            if (!$user_data) {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }
            $offers = array();
            $offersCount = 0;
            $offers = array();


            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                    return $q->where([
                        ['offer_status', '!=', 1],
                        ['offer_type', '!=', 2],
                    ]);
                }])->where('plan', '=', $user_plan)->get();
                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });
                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);
                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        if (($now->gte($from) && $now->lte($to))) {
                            $offer = Offer::find($offer_data['offer']['id']);
                            $gallery = $offer->gallery;
                            $gallery_data = array();
                            foreach ($gallery as $image) {
                                $gallery_data[] = url($image->image);
                            }
                            $wishlist = OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists();
                            $offers[] =  [
                                'id' => $offer_data['offer']['offer_uniid'],
                                'offer_name' => $offer_data['offer']['offer_name'],
                                'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                'offer_description' => $offer_data['offer']['offer_description'],
                                'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                'offer_discount' => $offer_data['offer']['offer_discount'],
                                'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                'offer_status' => $offer_data['offer']['offer_status'],
                                'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                'wishlist' => $wishlist,
                            ];
                        }
                    }
                }
                return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
            } else {
                return $this->responseApi([], false, __('User does not have any Subscription'), 417);
            }
        } else {
            $offers = array();
            $offers_data = Offer::all();
            foreach ($offers_data as $offer_data) {
                $fromDate = date('d-m-Y', strtotime($offer_data['offer_from']));
                $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                $toDate = date('d-m-Y', strtotime($offer_data['offer_to']));
                $to = Carbon::createFromFormat('d-m-Y', $toDate);

                $nowDate = Carbon::now()->format('d-m-Y');
                $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                if ($offer_data['offer_status'] != 1 && $offer_data['offer_type'] != 2 && ($now->gte($from) && $now->lte($to))) {
                    $offer = Offer::find($offer_data['id']);
                    $gallery = $offer->gallery;
                    $gallery_data = array();
                    foreach ($gallery as $image) {
                        $gallery_data[] = url($image->image);
                    }
                    $offers[] =  [
                        'id' => $offer_data['offer_uniid'],
                        'offer_name' => $offer_data['offer_name'],
                        'offer_name_arabic' => $offer_data['offer_name_arabic'],
                        'offer_description' => $offer_data['offer_description'],
                        'offer_description_arabic' => $offer_data['offer_description_arabic'],
                        'offer_desc_description' => $offer_data['offer_desc_description'],
                        'offer_desc_description_arabic' => $offer_data['offer_desc_description_arabic'],
                        'offer_discount' => $offer_data['offer_discount'],
                        'offer_to' => date('d-m-Y', strtotime($offer_data['offer_to'])),
                        'offer_from' => date('d-m-Y', strtotime($offer_data['offer_from'])),
                        'offer_status' => $offer_data['offer_status'],
                        'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                        'wishlist' => false,
                    ];
                }
            }
            return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
        }
    }

    public function fetchSearchOffersData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $offers = array();

            $offersCount = 0;
            $filter = strtolower($request->filter);

            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $visible_offers = VisibleMembership::with(['offer' => function ($q) use ($filter) {
                    return $q->where([
                        ['offer_name', 'like', '%' . $filter . '%'],
                        ['offer_status', '!=', 1],
                        ['offer_type', '!=', 2],
                    ])->orWhere('offer_name_arabic', 'like', '%' . $filter . '%');
                }])->where('plan', '=', $user_plan)->get();
                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });
                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);

                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        if (($now->gte($from) && $now->lte($to)) && $offer_data['offer']['offer_name']) {
                            $offer = Offer::find($offer_data['offer']['id']);
                            $gallery = $offer->gallery;
                            $gallery_data = array();
                            foreach ($gallery as $image) {
                                $gallery_data[] = url($image->image);
                            }
                            $wishlist = OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists();
                            $offers[] =  [
                                'id' => $offer_data['offer']['offer_uniid'],
                                'offer_name' => $offer_data['offer']['offer_name'],
                                'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                'offer_description' => $offer_data['offer']['offer_description'],
                                'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                'offer_discount' => $offer_data['offer']['offer_discount'],
                                'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                'offer_status' => $offer_data['offer']['offer_status'],
                                'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                'wishlist' => $wishlist,
                            ];
                        }
                    }
                }
                return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
            } else {
                return $this->responseApi([], false, __('User does not have any Subscription'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchWishlistOffersData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $offers = array();

            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                    return $q->where([
                        ['offer_status', '!=', 1],
                        ['offer_type', '!=', 2],
                    ]);
                }])->where('plan', '=', $user_plan)->get();
                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });
                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);

                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);

                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        if (
                            ($now->gte($from) && $now->lte($to))
                            && OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists()
                        ) {
                            $offer = Offer::find($offer_data['offer']['id']);
                            $gallery = $offer->gallery;
                            $gallery_data = array();
                            foreach ($gallery as $image) {
                                $gallery_data[] = url($image->image);
                            }
                            $wishlist = OfferWishlist::where([
                                ['offer_id', '=', $offer_data['offer']['id']],
                                ['user_id', '=', $user_data['id']]
                            ])->exists();
                            $offers[] =  [
                                'id' => $offer_data['offer']['offer_uniid'],
                                'offer_name' => $offer_data['offer']['offer_name'],
                                'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                'offer_description' => $offer_data['offer']['offer_description'],
                                'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                'offer_discount' => $offer_data['offer']['offer_discount'],
                                'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                'offer_status' => $offer_data['offer']['offer_status'],
                                'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                'wishlist' => $wishlist,
                            ];
                        }
                    }
                }
                return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
            } else {
                return $this->responseApi([], false, __('User does not have any Subscription'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function wishlistOfferData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            $offer = $request->offer;
            $status = $request->status;
            if (Offer::where('offer_uniid', '=', $offer)->exists()) {
                $offer_data = Offer::where('offer_uniid', '=', $offer)->first();
                if ($status == true) {
                    $wishlistData = [
                        'offer_id' => $offer_data['id'],
                        'user_id' => $user_data['id'],
                    ];
                    OfferWishlist::updateOrCreate($wishlistData);
                } else if ($status == false) {
                    $wishlistData = [
                        'offer_id' => $offer_data['id'],
                        'user_id' => $user_data['id'],
                    ];
                    OfferWishlist::where($wishlistData)->delete();
                }
                return $this->responseApi([], true, 'Wishlist Updated', 200);
            } else {
                return $this->responseApi([], false, __('Id is not correct'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchOffersReportData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user();
            $per_year_saving = 0;
            $per_month_saving = 0;
            $total_saving = 0;
            $redeemsCount = 0;
            $monthRedeemsCount = 0;
            $yearRedeemsCount = 0;
            $offersRedeemed = 0;
            $city_data = City::find($user_data['city']);

            if (CouponRedeem::where('user_id', '=', $user_data['id'])->exists()) {
                // General Savings
                $coupons_redeemed = CouponRedeem::where('user_id', '=', $user_data['id'])->get();
                $redeemsCount = $coupons_redeemed->count();
                foreach ($coupons_redeemed as $coupon_redeemed) {
                    $total_saving += $coupon_redeemed['discount_price'];
                }

                // Per  Month Savings
                $monthRedeems = CouponRedeem::where('user_id', '=', $user_data['id'])->whereMonth('created_at', '=', Carbon::now()->month)->get();
                $monthRedeemsCount = $monthRedeems->count();
                foreach ($monthRedeems as $monthRedeem) {
                    $per_month_saving += $monthRedeem['discount_price'];
                }

                // Per  Year Savings
                $yearRedeems = CouponRedeem::where('user_id', '=', $user_data['id'])->whereYear('created_at', '=', Carbon::now()->year)->get();
                $yearRedeemsCount = $yearRedeems->count();
                foreach ($yearRedeems as $yearRedeem) {
                    $per_year_saving += $yearRedeem['discount_price'];
                }

                $offersRedeemed = CouponRedeem::where('user_id', '=', $user_data['id'])->distinct('offer_id')->count('id');
            }
            $myResponse = [
                'name' => $user_data['name'],
                'city' => $city_data['city_name'] . ', Saudi Arabia',

                'total_savings' => $total_saving ? $total_saving : 0,
                'coupons_redeemed' => $redeemsCount ? $redeemsCount : 0,
                'offers_redeemed' => $offersRedeemed ? $offersRedeemed : 0,

                'per_year_savings' => $per_year_saving ? $per_year_saving : 0,
                'per_year_redeems' => $yearRedeemsCount ? $yearRedeemsCount : 0,

                'per_months_savings' => $per_month_saving ? $per_month_saving : 0,
                'per_month_redeems' => $monthRedeemsCount ? $monthRedeemsCount : 0,
            ];
            return $this->responseApi($myResponse, true, 'Member Report Fetched Successfully', 200);
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchOffersNotificationsData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $user_notify = array();

            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $user_notifications = OfferNotification::where('plan', '=', $user_plan)->orderBy('id', 'DESC')->get();
                foreach ($user_notifications as $user_notification) {
                    $user_notify[] = [
                        'message' => $user_notification['message'],
                        'offer' => $user_notification['offer'],
                        'date' => date('d-m-Y', strtotime($user_notification['created_at'])),
                    ];
                }
                return $this->responseApi($user_notify, true, 'Offer Notifications Fetched Successfully', 200);
            } else {
                return $this->responseApi([], false, __('User does not have any Subscription'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function fetchBranchOffers(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);

            $branch = $request->only('branch');
            $myResponse = [];
            if ($branch) {
                if (Branch::where('branch_uniid', '=', $branch)->exists()) {
                    $branch = Branch::where('branch_uniid', '=', $branch)->first();
                    $offers = OfferBranch::where('branch', '=', $branch->id)->get();
                    if (!$offers->isEmpty()) {
                        $branches_offers = array();
                        foreach ($offers as $offer) {
                            $branches_offers[] = $offer['offer'];
                        }
                        $offers = array();
                        if ($user_data->latestMemberSubscription) {
                            $user_plan = $user_data->latestMemberSubscription->plan;
                            $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                                return $q->where([
                                    ['offer_status', '!=', 1],
                                    ['offer_type', '!=', 2],
                                ]);
                            }])->where('plan', '=', $user_plan)->get();
                            $offers_data = $visible_offers->map(function ($visible_offer) use ($branch, $user_plan) {
                                if ($this->countBranchOffers($branch->id, $user_plan)) {
                                    return collect($visible_offer->toArray())
                                        ->only(['offer'])
                                        ->all();
                                }
                            });
                            foreach ($offers_data as $offer_data) {
                                if ($offer_data['offer']) {
                                    $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                                    $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                                    $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                                    $to = Carbon::createFromFormat('d-m-Y', $toDate);
                                    $nowDate = Carbon::now()->format('d-m-Y');
                                    $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                                    if (($now->gte($from) && $now->lte($to)) && in_array($offer_data['offer']['id'], $branches_offers)) {
                                        $offer = Offer::find($offer_data['offer']['id']);
                                        $gallery = $offer->gallery;
                                        $gallery_data = array();
                                        foreach ($gallery as $image) {
                                            $gallery_data[] = url($image->image);
                                        }
                                        $wishlist = OfferWishlist::where([
                                            ['offer_id', '=', $offer_data['offer']['id']],
                                            ['user_id', '=', $user_data['id']]
                                        ])->exists();
                                        $offers[] = [
                                            'id' => $offer_data['offer']['offer_uniid'],
                                            'offer_name' => $offer_data['offer']['offer_name'],
                                            'offer_name_arabic' => $offer_data['offer']['offer_name_arabic'],
                                            'offer_description' => $offer_data['offer']['offer_description'],
                                            'offer_description_arabic' => $offer_data['offer']['offer_description_arabic'],
                                            'offer_desc_description' => $offer_data['offer']['offer_desc_description'],
                                            'offer_desc_description_arabic' => $offer_data['offer']['offer_desc_description_arabic'],
                                            'offer_discount' => $offer_data['offer']['offer_discount'],
                                            'offer_to' => date('d-m-Y', strtotime($offer_data['offer']['offer_to'])),
                                            'offer_from' => date('d-m-Y', strtotime($offer_data['offer']['offer_from'])),
                                            'offer_status' => $offer_data['offer']['offer_status'],
                                            'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                            'wishlist' => $wishlist,
                                        ];
                                    }
                                    $myResponse = [
                                        'branch_icon' => url($branch['branch_image']),
                                        'branch_name' => $branch['branch_name'],
                                        'branch_name_arabic' => $branch['branch_name_arabic'],
                                        'offers' => $offers,
                                    ];
                                }
                            }
                            return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
                        } else {
                            $offers_data = Offer::where([
                                ['offer_status', '!=', 1],
                                ['offer_type', '!=', 2],
                            ])->get();
                            foreach ($offers_data as $offer_data) {
                                if ($offer_data) {
                                    $fromDate = date('d-m-Y', strtotime($offer_data['offer_from']));
                                    $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                                    $toDate = date('d-m-Y', strtotime($offer_data['offer_to']));
                                    $to = Carbon::createFromFormat('d-m-Y', $toDate);
                                    $nowDate = Carbon::now()->format('d-m-Y');
                                    $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                                    if (($now->gte($from) && $now->lte($to)) && in_array($offer_data['id'], $branches_offers)) {
                                        $offer = Offer::find($offer_data['id']);
                                        $gallery = $offer->gallery;
                                        $gallery_data = array();
                                        foreach ($gallery as $image) {
                                            $gallery_data[] = url($image->image);
                                        }
                                        $wishlist = OfferWishlist::where([
                                            ['offer_id', '=', $offer_data['id']],
                                            ['user_id', '=', $user_data['id']]
                                        ])->exists();
                                        $offers[] = [
                                            'id' => $offer_data['offer_uniid'],
                                            'offer_name' => $offer_data['offer_name'],
                                            'offer_name_arabic' => $offer_data['offer_name_arabic'],
                                            'offer_description' => $offer_data['offer_description'],
                                            'offer_description_arabic' => $offer_data['offer_description_arabic'],
                                            'offer_desc_description' => $offer_data['offer_desc_description'],
                                            'offer_desc_description_arabic' => $offer_data['offer_desc_description_arabic'],
                                            'offer_discount' => $offer_data['offer_discount'],
                                            'offer_to' => date('d-m-Y', strtotime($offer_data['offer_to'])),
                                            'offer_from' => date('d-m-Y', strtotime($offer_data['offer_from'])),
                                            'offer_status' => $offer_data['offer_status'],
                                            'gallery' => $gallery_data ? $gallery_data[0] : ' ',
                                            'wishlist' => $wishlist,
                                        ];
                                    }
                                    $myResponse = [
                                        'branch_icon' => url($branch['branch_image']),
                                        'branch_name' => $branch['branch_name'],
                                        'branch_name_arabic' => $branch['branch_name_arabic'],
                                        'offers' => $offers,
                                    ];
                                }
                            }
                            return $this->responseApi($myResponse, true, 'Offers Fetched Successfully', 200);
                            // return $this->responseApi([], false, 'User does not have any Subscription', 417);
                        }
                    } else {
                        return $this->responseApi([], false, '', 418);
                    }
                } else {
                    return $this->responseApi([], false, __('Id is not correct'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Branch id is missing'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }

    public function countBranchOffers($id, $plan_id)
    {
        $countOffers = 0;
        $response = false;
        if (Branch::where('id', '=', $id)->exists()) {
            $branch = Branch::where('id', '=', $id)->first();
            $offers = OfferBranch::where('branch', '=', $branch->id)->get();
            if (!$offers->isEmpty()) {
                $branches_offers = array();
                foreach ($offers as $offer) {
                    $branches_offers[] = $offer['offer'];
                }
                $offers = array();
                $user_plan = $plan_id;
                $visible_offers = [];
                if ($user_plan != 0) {
                    $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                        return $q->where([
                            ['offer_status', '!=', 1],
                            ['offer_type', '!=', 2],
                        ]);
                    }])->where('plan', '=', $user_plan)->get();
                } else {
                    $visible_offers = VisibleMembership::with(['offer' => function ($q) {
                        return $q->where([
                            ['offer_status', '!=', 1],
                            ['offer_type', '!=', 2],
                        ]);
                    }])->get();
                }
                $offers_data = $visible_offers->map(function ($visible_offer) {
                    return collect($visible_offer->toArray())
                        ->only(['offer'])
                        ->all();
                });
                foreach ($offers_data as $offer_data) {
                    if ($offer_data['offer']) {
                        $fromDate = date('d-m-Y', strtotime($offer_data['offer']['offer_from']));
                        $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                        $toDate = date('d-m-Y', strtotime($offer_data['offer']['offer_to']));
                        $to = Carbon::createFromFormat('d-m-Y', $toDate);
                        $nowDate = Carbon::now()->format('d-m-Y');
                        $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                        if (($now->gte($from) && $now->lte($to)) && in_array($offer_data['offer']['id'], $branches_offers)) {
                            $countOffers++;
                        }
                    }
                }
                $response = $countOffers > 0 ? true : false;
            }
        }
        return $response;
    }

    public function fetchBranchCode($code)
    {
        if (Coupon::where([
            ['coupon_status', '=', 1],
            ['coupon_code', '=', $code]
        ])->exists()) {
            $file_name = $code . '.png';
            $promo_data = [
                'promo' => url('uploads/offers/qrcodes/' . $file_name),
                'promo_type' => 'qr',
                'promo_blur' => url('admin/assets/media/qr.png'),
            ];
            return $this->responseApi($promo_data, true, 'QR Generated', 200);
        } else {
            return $this->responseApi([], false, __('Coupon is Incorrect'), 417);
        }
    }
}
