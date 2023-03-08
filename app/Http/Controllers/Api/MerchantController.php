<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use App\Models\OfferBranch;
use App\Models\VisibleMembership;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class MerchantController extends BaseController
{
    public function fetchAllMerchant(Request $request)
    {
        $merchants_data = [];
        $filter = strtolower($request->filter);
        $merchants = User::has('merchantOffer', '>', 0)->with('merchantOffer')->whereRaw('Lower(name) LIKE ? ', "%$filter%")->whereHas("roles", function ($q) {
            $q->where("name", "Merchant");
        })->get();
        $merchants_data = $merchants->map(function ($merchant) {
            if ($merchant->merchantOffer()->count() > 0) {
                return collect($merchant->toArray())
                    ->only(['id', 'name', 'email'])
                    ->all();
            }
        });
        return $this->responseApi($merchants_data, true, 'Merchants Fetched Successfully', 200);
    }

    public function fetchAllMerchantBranches(Request $request)
    {
        if ($request->bearerToken()) {
            if (!Auth::guard('api')->check()) {
                return $this->responseApi([], false, __('Sorry, This member does not exist'), 401);
            }
            $user_data = auth('api')->user()->load(['latestMemberSubscription']);
            if ($user_data->latestMemberSubscription) {
                $user_plan = $user_data->latestMemberSubscription->plan;
                $branches_data = array();
                $input = $request->merchant;
                $lang = $request->lang;
                $lat = $request->lat;
                $distance = $request->distance;

                if (!$input || $input == 0) {
                    if (($lat && $lang) && ($lat != 0.0 && $lang != 0.0)) {
                        $branches = DB::table("branches")
                            ->select(
                                "branches.*",
                                DB::raw("(6371 *
                        acos(cos(radians(" . $lat . ")) *
                        cos(radians(branches.branch_latitude)) *
                        cos(radians(branches.branch_longitude) -
                        radians(" . $lang . ")) +
                        sin(radians(" . $lat . ")) *
                        sin(radians(branches.branch_latitude)))) AS distance")
                            )->get();
                        foreach ($branches as $branch) {
                            if ($branch->branch_status == 1 && $branch->distance <= $distance) {
                                if ($this->countBranchOffers($branch->id, $user_plan)) {
                                    $branches_data[] = [
                                        'id' =>  $branch->branch_uniid,
                                        'branch_name' =>  $branch->branch_name,
                                        'branch_name_arabic' =>  $branch->branch_name_arabic,
                                        'branch_latitude' =>  $branch->branch_latitude,
                                        'branch_longitude' =>  $branch->branch_longitude,
                                        'branch_image' => url($branch->branch_image),
                                    ];
                                }
                            }
                        }
                    } else {
                        $branches = Branch::orderBy('id', 'DESC')->get();
                        foreach ($branches as $branch) {
                            if ($branch['branch_status'] == 1) {
                                $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                                if ($this->countBranchOffers($branch->id, $user_plan)) {
                                    $branches_data[] = [
                                        'id' =>  $branch['branch_uniid'],
                                        'branch_name' =>  $branch['branch_name'],
                                        'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                        'branch_latitude' =>  $branch['branch_latitude'],
                                        'branch_longitude' =>  $branch['branch_longitude'],
                                        'branch_image' => url($branch['branch_image']),
                                    ];
                                }
                            }
                        }
                    }
                } else if ($input != 0) {
                    $branches = Branch::where('merchant_id', '=', $input)->orderBy('id', 'DESC')->get();
                    foreach ($branches as $branch) {
                        if ($branch['branch_status'] == 1) {
                            $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                            if ($this->countBranchOffers($branch->id, $user_plan)) {
                                $branches_data[] = [
                                    'id' =>  $branch['branch_uniid'],
                                    'branch_name' =>  $branch['branch_name'],
                                    'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                    'branch_latitude' =>  $branch['branch_latitude'],
                                    'branch_longitude' =>  $branch['branch_longitude'],
                                    'branch_image' => url($branch['branch_image']),
                                ];
                            }
                        }
                    }
                }
                return $this->responseApi($branches_data, true, 'Branches Fetched Successfully', 200);
            } else {
                $branches_data = array();
                $input = $request->merchant;
                $lang = $request->lang;
                $lat = $request->lat;
                $distance = $request->distance;
                if (!$input || $input == 0) {
                    if (($lat && $lang) && ($lat != 0.0 && $lang != 0.0)) {
                        $branches = DB::table("branches")
                            ->select(
                                "branches.*",
                                DB::raw("(6371 *
                        acos(cos(radians(" . $lat . ")) *
                        cos(radians(branches.branch_latitude)) *
                        cos(radians(branches.branch_longitude) -
                        radians(" . $lang . ")) +
                        sin(radians(" . $lat . ")) *
                        sin(radians(branches.branch_latitude)))) AS distance")
                            )->get();
                        foreach ($branches as $branch) {
                            if ($branch->branch_status == 1 && $branch->distance <= $distance) {
                                $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                                if ($this->countBranchOffers($branch->id, 0)) {
                                    $branches_data[] = [
                                        'id' =>  $branch->branch_uniid,
                                        'branch_name' =>  $branch->branch_name,
                                        'branch_name_arabic' =>  $branch->branch_name_arabic,
                                        'branch_latitude' =>  $branch->branch_latitude,
                                        'branch_longitude' =>  $branch->branch_longitude,
                                        'branch_image' => url($branch->branch_image),
                                    ];
                                }
                            }
                        }
                    } else {
                        $branches = Branch::orderBy('id', 'DESC')->get();
                        foreach ($branches as $branch) {
                            if ($branch['branch_status'] == 1) {
                                $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                                if ($this->countBranchOffers($branch->id, 0)) {
                                    $branches_data[] = [
                                        'id' =>  $branch['branch_uniid'],
                                        'branch_name' =>  $branch['branch_name'],
                                        'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                        'branch_latitude' =>  $branch['branch_latitude'],
                                        'branch_longitude' =>  $branch['branch_longitude'],
                                        'branch_image' => url($branch['branch_image']),
                                    ];
                                }
                            }
                        }
                    }
                } else if ($input != 0) {
                    $branches = Branch::where('merchant_id', '=', $input)->orderBy('id', 'DESC')->get();
                    foreach ($branches as $branch) {
                        if ($branch['branch_status'] == 1) {
                            $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                            if ($this->countBranchOffers($branch->id, 0)) {
                                $branches_data[] = [
                                    'id' =>  $branch['branch_uniid'],
                                    'branch_name' =>  $branch['branch_name'],
                                    'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                    'branch_latitude' =>  $branch['branch_latitude'],
                                    'branch_longitude' =>  $branch['branch_longitude'],
                                    'branch_image' => url($branch['branch_image']),
                                ];
                            }
                        }
                    }
                }
                return $this->responseApi($branches_data, true, 'Branches Fetched Successfully', 200);
            }
        } else {
            $branches_data = array();
            $input = $request->merchant;
            $lang = $request->lang;
            $lat = $request->lat;
            $distance = $request->distance;
            if (!$input || $input == 0) {
                if (($lat && $lang) && ($lat != 0.0 && $lang != 0.0)) {
                    $branches = DB::table("branches")
                        ->select(
                            "branches.*",
                            DB::raw("(6371 *
                        acos(cos(radians(" . $lat . ")) *
                        cos(radians(branches.branch_latitude)) *
                        cos(radians(branches.branch_longitude) -
                        radians(" . $lang . ")) +
                        sin(radians(" . $lat . ")) *
                        sin(radians(branches.branch_latitude)))) AS distance")
                        )->get();
                    foreach ($branches as $branch) {
                        if ($branch->branch_status == 1 && $branch->distance <= $distance) {
                            $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                            if ($this->countBranchOffers($branch->id, 0)) {
                                $branches_data[] = [
                                    'id' =>  $branch->branch_uniid,
                                    'branch_name' =>  $branch->branch_name,
                                    'branch_name_arabic' =>  $branch->branch_name_arabic,
                                    'branch_latitude' =>  $branch->branch_latitude,
                                    'branch_longitude' =>  $branch->branch_longitude,
                                    'branch_image' => url($branch->branch_image),
                                ];
                            }
                        }
                    }
                } else {
                    $branches = Branch::orderBy('id', 'DESC')->get();
                    foreach ($branches as $branch) {
                        if ($branch['branch_status'] == 1) {
                            $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                            if ($this->countBranchOffers($branch->id, 0)) {
                                $branches_data[] = [
                                    'id' =>  $branch['branch_uniid'],
                                    'branch_name' =>  $branch['branch_name'],
                                    'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                    'branch_latitude' =>  $branch['branch_latitude'],
                                    'branch_longitude' =>  $branch['branch_longitude'],
                                    'branch_image' => url($branch['branch_image']),
                                ];
                            }
                        }
                    }
                }
            } else if ($input != 0) {
                $branches = Branch::where('merchant_id', '=', $input)->orderBy('id', 'DESC')->get();
                foreach ($branches as $branch) {
                    if ($branch['branch_status'] == 1) {
                        $offers_count = OfferBranch::where('branch', '=', $branch->id)->count();
                        if ($this->countBranchOffers($branch->id, 0)) {
                            $branches_data[] = [
                                'id' =>  $branch['branch_uniid'],
                                'branch_name' =>  $branch['branch_name'],
                                'branch_name_arabic' =>  $branch['branch_name_arabic'],
                                'branch_latitude' =>  $branch['branch_latitude'],
                                'branch_longitude' =>  $branch['branch_longitude'],
                                'branch_image' => url($branch['branch_image']),
                            ];
                        }
                    }
                }
            }
            return $this->responseApi($branches_data, true, 'Branches Fetched Successfully', 200);
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
}
