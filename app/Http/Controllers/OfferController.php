<?php

namespace App\Http\Controllers;

use App\Exports\CouponsExport;
use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Imports\CouponsImport;
use App\Jobs\NewOfferJob;
use App\Mail\NewOffer;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\Models\Branch;
use App\Models\Category;
use App\Models\City;
use App\Models\Offer;
use App\Models\OfferBranch;
use App\Models\OfferCategory;
use App\Models\Plan;
use App\Models\TargetedMembership;
use App\Models\VisibleMembership;
use Illuminate\Support\Facades\Hash;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\CouponRedeem;
use App\Models\Image;
use App\Models\MerchantDetail;
use App\Models\OfferNotification;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as FacadeResponse;

class OfferController extends Controller
{
    private $page_heading;
    use FileUploadTrait;

    public function __construct()
    {
        $this->page_heading = 'Offer Management';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-offer-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);

        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });

        $branches = Branch::all();
        $branches_data = $branches->map(function ($branch) {
            return collect($branch->toArray())
                ->only(['id', 'branch_name'])
                ->all();
        });

        return view('admin.offers.index', compact('merchants_data', 'page_title', 'branches_data'));
    }

    public function offerRequestsIndex()
    {
        if (!Auth::user()->can('admin-offer-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);

        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });

        $branches = Branch::all();
        $branches_data = $branches->map(function ($branch) {
            return collect($branch->toArray())
                ->only(['id', 'branch_name'])
                ->all();
        });

        return view('admin.offers.offer-request', compact('merchants_data', 'page_title', 'branches_data'));
    }

    public function fetchOffersData(Request $request)
    {
        $result = array('data' => array());
        $offers = [];
        $merchant_id = $request->merchant_id;
        $branch_id = $request->branch_id;
        if ($merchant_id == '0' && $branch_id == '0') {
            $offers = Offer::where('offer_request', '!=', 1)->orderBy('id', 'DESC')->get();
        } else if ($merchant_id != '0' && $branch_id != '0') {
            $offers = Offer::with('offerBranches')->where([
                ['offer_request', '!=', 1],
                ['merchant_id', '=', $merchant_id]
            ])->whereHas("offerBranches", function ($q) use ($branch_id) {
                $q->where('branch', $branch_id);
            })->orderBy('id', 'DESC')->get();
        } else if ($merchant_id != '0' && $branch_id == '0') {
            $offers = Offer::where([
                ['offer_request', '!=', 1],
                ['merchant_id', '=', $merchant_id]
            ])->orderBy('id', 'DESC')->get();
        } else if ($merchant_id == '0' && $branch_id != '0') {
            $offers = Offer::with('offerBranches')->where('offer_request', '!=', 1)
                ->whereHas("offerBranches", function ($q) use ($branch_id) {
                    $q->where('branch', $branch_id);
                })->orderBy('id', 'DESC')->get();
        }

        foreach ($offers as $key => $value) {
            $buttons = '';
            if ($value['offer_type'] == 2) {
                $buttons .= '<a href="' . route("offers.download-qr", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" title="Download Coupons">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            } else if ($value['offer_coupon_type'] == 2) {
                $buttons .= '<a href="' . route("offers.download-promos", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" title="Download Coupons">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            }
            $buttons .= '<a href="' . route("offers.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="show">
            <i class="far fa-eye"></i>
            </a>';
            if (Auth::user()->can('admin-offer-create')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Duplicate Offer" href="' . route("offers.clone", $value->id) . '">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
                <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            }
            if (Auth::user()->can('admin-offer-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" data-toggle="tooltip" data-placement="top" title="Edit" href="' . route("offers.edit", $value->id) . '">
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
            if (Auth::user()->can('admin-offer-delete')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark"  data-toggle="tooltip" data-placement="top" title="Delete    " onclick="removeFunc(' . $value->id . ')">
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
            $offer_status = "";
            if ($value['offer_status'] == 2) {
                $offer_status = '<a href="' . route("offers.edit", $value->id) . '"><div class="badge badge-light-success fw-bolder">' . __('Approved') . '</div></a>';
            } else if ($value['offer_status'] == 1) {
                $offer_status = '<a href="' . route("offers.edit", $value->id) . '"><div class="badge badge-light-danger fw-bolder">' . __('Unapproved') . '</div></a>';
            }

            $total_coupons_count = Offer::find($value->id)->coupons()->count();
            $total_re_coupons_count = Offer::find($value->id)->redeemCoupons()->count();
            $total_available = $total_coupons_count - $total_re_coupons_count;

            $couponsStats = '';
            if ($value['offer_type'] != 2) {
                $couponsStats = __('Total Coupons:') . $total_coupons_count . '</br>';
            }
            $couponsStats = __('Total Redeemed:') . $total_re_coupons_count . '</br>';
            if ($value['offer_type'] != 2) {
                $couponsStats = __('Total Available:') . $total_available . '</br>';
            }

            $result['data'][$key] = array(
                $value['offer_name'],
                $value['offer_to'],
                $value['offer_from'],
                $couponsStats,
                $offer_status,
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function fetchOfferRequestsData(Request $request)
    {
        $result = array('data' => array());
        $offers = [];
        $merchant_id = $request->merchant_id;
        $branch_id = $request->branch_id;
        if ($merchant_id == '0' && $branch_id == '0') {
            $offers = Offer::where([
                ['offer_status', '!=', 2],
                ['offer_request', '=', 1]
            ])->orderBy('id', 'DESC')->get();
        } else if ($merchant_id != '0' && $branch_id != '0') {
            $offers = Offer::with('offerBranches')->where([
                ['offer_status', '!=', 2],
                ['offer_request', '=', 1],
                ['merchant_id', '=', $merchant_id]
            ])->whereHas("offerBranches", function ($q) use ($branch_id) {
                $q->where('branch', $branch_id);
            })->orderBy('id', 'DESC')->get();
        } else if ($merchant_id != '0' && $branch_id == '0') {
            $offers = Offer::where([
                ['offer_status', '!=', 2],
                ['offer_request', '=', 1],
                ['merchant_id', '=', $merchant_id]
            ])->orderBy('id', 'DESC')->get();
        } else if ($merchant_id == '0' && $branch_id != '0') {
            $offers = Offer::with('offerBranches')->where([
                ['offer_status', '!=', 2],
                ['offer_request', '=', 1]
            ])->whereHas("offerBranches", function ($q) use ($branch_id) {
                $q->where('branch', $branch_id);
            })->orderBy('id', 'DESC')->get();
        }

        foreach ($offers as $key => $value) {
            $buttons = '';
            if ($value['offer_type'] == 2) {
                $buttons .= '<a href="' . route("offers.download-qr", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" title="Download Coupons">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            } else if ($value['offer_coupon_type'] == 2) {
                $buttons .= '<a href="' . route("offers.download-promos", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark" title="Download Coupons">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            }
            $buttons .= '<a href="' . route("offers.show", $value->id) . '" class="btn btn-icon btn-sm btn-color-dark">
            <i class="far fa-eye"></i>
            </a>';
            if (Auth::user()->can('admin-offer-create')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" href="' . route("offers.clone", $value->id) . '">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <rect x="0" y="0" width="24" height="24"/>
                <path d="M6,9 L6,15 C6,16.6568542 7.34314575,18 9,18 L15,18 L15,18.8181818 C15,20.2324881 14.2324881,21 12.8181818,21 L5.18181818,21 C3.76751186,21 3,20.2324881 3,18.8181818 L3,11.1818182 C3,9.76751186 3.76751186,9 5.18181818,9 L6,9 Z" fill="#000000" fill-rule="nonzero"/>
                <path d="M10.1818182,4 L17.8181818,4 C19.2324881,4 20,4.76751186 20,6.18181818 L20,13.8181818 C20,15.2324881 19.2324881,16 17.8181818,16 L10.1818182,16 C8.76751186,16 8,15.2324881 8,13.8181818 L8,6.18181818 C8,4.76751186 8.76751186,4 10.1818182,4 Z" fill="#000000" opacity="0.3"/>
                </g>
                </svg>
                </span>
                <!--end::Svg Icon-->
                </a>';
            }
            if (Auth::user()->can('admin-offer-edit')) {
                $buttons .= ' <a class="btn btn-icon btn-sm btn-color-dark" href="' . route("offers.edit", $value->id) . '">
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
            if (Auth::user()->can('admin-offer-delete')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="removeFunc(' . $value->id . ')">
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
            $offer_status = "";
            if ($value['offer_status'] == 2) {
                $offer_status = '<a href="' . route("offers.edit", $value->id) . '"><div class="badge badge-light-success fw-bolder">Approved</div></a>';
            } else if ($value['offer_status'] == 1) {
                $offer_status = '<a href="' . route("offers.edit", $value->id) . '"><div class="badge badge-light-danger fw-bolder">Unapproved</div></a>';
            }

            $total_coupons_count = Offer::find($value->id)->coupons()->count();
            $total_re_coupons_count = Offer::find($value->id)->redeemCoupons()->count();
            $total_available = $total_coupons_count - $total_re_coupons_count;

            $couponsStats = '';
            if ($value['offer_type'] != 2) {
                $couponsStats .= 'Total Coupons: ' . $total_coupons_count . '</br>';
            }
            $couponsStats .= 'Total Redeemed: ' . $total_re_coupons_count . '</br>';
            if ($value['offer_type'] != 2) {
                $couponsStats .= 'Total Available: ' . $total_available . '</br>';
            }

            $result['data'][$key] = array(
                $value['offer_name'],
                $value['offer_to'],
                $value['offer_from'],
                $couponsStats,
                $offer_status,
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
        if (!Auth::user()->can('admin-offer-create')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $campaigns_data = Campaign::all();
        $categories_data = Category::all();
        $plans_data = Plan::all();
        return view('admin.offers.create', compact('page_title', 'campaigns_data', 'merchants_data', 'categories_data', 'plans_data'));
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
            'offer_name' => 'required|max:50|min:3',
            'offer_name_arabic' => 'required|max:50|min:3',
            'offer_description' => 'required',
            'offer_description_arabic' => 'required',
            'offer_desc_description' => 'required',
            'offer_desc_description_arabic' => 'required',
            'offer_discount' => 'required|numeric|max:100',
            'offer_type' => 'required|numeric',
            'offer_coupons' => 'required|numeric',
            'offer_from' => 'required|date',
            'offer_to' => 'required|date',
            'offer_categories' => 'required',
            'offer_branches' => 'required',
            'offer_branches_count' => 'required',
            'targeted_membership' => 'required',
            'visible_membership' => 'required',
            'offer_status' => 'required',
            'merchant_id' => 'required',
            'offer_image_link.*' => 'mimes:png,jpeg,jpg|file|required|max:2048',
        ], [
            'merchant_id.required' => 'Please Select Merchant',
            'offer_image_link.*.mimes' => 'Gallery Image must be a Image',
            'offer_image_link.*.max' => 'Image should be 2 MB max.',
        ]);
        $input = $request->all();
        $offer_coupon_type = 0;
        $offer_code_generation = 0;
        $offer_price = 0;
        $couponCodesData = array();
        $fileCodesData = array();

        $offer_per_user = $request->offer_per_user;
        if (!is_null($offer_per_user) && !empty($offer_per_user)) {
            $this->validate($request, [
                'offer_usage_duration' => 'required|numeric',
            ]);
        }

        if ($input['offer_type'] == 1) {
            $this->validate($request, [
                'offer_coupon_type' => 'required|numeric',
            ]);
            $offer_coupon_type = $input['offer_coupon_type'];
            if ($input['offer_coupon_type'] == 2) {
                $this->validate($request, [
                    'offer_code_generation' => 'required|numeric',
                    'offer_price' => 'required|numeric',
                ]);
                $offer_code_generation = $input['offer_code_generation'];
                $offer_price = $input['offer_price'];
                if ($input['offer_code_generation'] == 1) {
                    $this->validate($request, [
                        'coupons_csv' => 'mimes:csv,txt,xlsx,xls|required|max:4096',
                    ]);
                    $file_path = $this->DocUpload($request->file('coupons_csv'), 'uploads/offers/csv/');
                    $rows = \Excel::toArray(new CouponsImport,  $file_path);
                    foreach ($rows[0] as $key => $value) {
                        $couponCodesData[] = $value[0];
                    }
                    unlink($file_path);
                    foreach ($couponCodesData as $code) {
                        if (!Coupon::where('coupon_code', '=', $code)->exists() && strlen($code) == 11) {
                            $fileCodesData[] = $code;
                        }
                    }
                    $fileCodesCount = count($fileCodesData);
                    $input['offer_coupons'] = $fileCodesCount;
                }
            }
        } else if ($input['offer_type'] == 2) {
            $offer_coupon_type = 0;
            $offer_code_generation = 0;
        }

        $offer_campaign = $request->offer_campaign;
        if (!is_null($offer_campaign) && !empty($offer_campaign)) {
            $offer_campaign = $input['offer_campaign'];
        } else {
            $offer_campaign = '0';
        }

        $offer_per_user = $request->offer_per_user;
        if (!is_null($offer_per_user) && !empty($offer_per_user)) {
            $offer_per_user = $input['offer_per_user'];
        } else {
            $offer_per_user = '0';
        }

        $offer_usage_duration = $request->offer_usage_duration;
        if (!is_null($offer_per_user) && !empty($offer_per_user)) {
            $offer_usage_duration = $input['offer_usage_duration'];
        } else {
            $offer_usage_duration = '0';
        }

        $offerData = [
            'offer_uniid' => Str::uuid()->toString(),
            'offer_name' => $input['offer_name'],
            'offer_name_arabic' => $input['offer_name_arabic'],
            'offer_description' => $input['offer_description'],
            'offer_description_arabic' => $input['offer_description_arabic'],
            'offer_desc_description' => $input['offer_desc_description'],
            'offer_desc_description_arabic' => $input['offer_desc_description_arabic'],
            'offer_image_link' => 'No Link',
            'offer_discount' => $input['offer_discount'],
            'offer_price' => $offer_price,
            'offer_coupons' => $input['offer_coupons'],
            'offer_from' => $input['offer_from'],
            'offer_to' => $input['offer_to'],
            'offer_type' => $input['offer_type'],
            'offer_status' => $input['offer_status'],
            'offer_campaign' => $offer_campaign,
            'offer_per_user' => $offer_per_user,
            'offer_usage_duration' => $offer_usage_duration,
            'offer_code_generation' => $offer_code_generation,
            'offer_coupon_type' => $offer_coupon_type,
            'offer_comments' => $input['offer_comments'],
            'merchant_id' => $input['merchant_id'],
        ];

        $offer = Offer::create($offerData);
        if ($input['offer_type'] == 1 && $offer_coupon_type == 1) {
            $offer_coupons = $input['offer_coupons'];
            for ($x = 0; $x < $offer_coupons; $x++) {
                $coupon_data = [
                    'offer_id' => $offer->id,
                    'coupon_code' => $this->generateRandomString(11),
                    'coupon_per_user' => $offer_per_user,
                    'coupon_usage_duration' => $offer_usage_duration,
                    'coupon_status' => 1,
                ];
                Coupon::create($coupon_data);
            }
        } else if ($input['offer_type'] == 1 && $offer_coupon_type == 2) {
            if ($offer_code_generation == 2) {
                $offer_coupons = $input['offer_coupons'];
                $coupons[] = $this->generateRandomString(11);
                for ($x = 0; $x < $offer_coupons; $x++) {
                    $coupons[$x] = $this->generateRandomString(11);
                    $coupon_data = [
                        'offer_id' => $offer->id,
                        'coupon_code' => $coupons[$x],
                        'coupon_per_user' => $offer_per_user,
                        'coupon_usage_duration' => $offer_usage_duration,
                        'coupon_status' => 1,
                    ];
                    Coupon::create($coupon_data);
                }
            } else if ($offer_code_generation == 1) {
                for ($x = 0; $x < $fileCodesCount; $x++) {
                    $coupon_data = [
                        'offer_id' => $offer->id,
                        'coupon_code' => $fileCodesData[$x],
                        'coupon_per_user' => $offer_per_user,
                        'coupon_usage_duration' => $offer_usage_duration,
                        'coupon_status' => 1,
                    ];
                    Coupon::create($coupon_data);
                }
            }
        } else if ($input['offer_type'] == 2) {
            $coupon_data = [
                'offer_id' => $offer->id,
                'coupon_code' => $this->generateRandomString(11),
                'coupon_per_user' => $offer_per_user,
                'coupon_usage_duration' => $offer_usage_duration,
                'coupon_status' => 1,
            ];
            Coupon::create($coupon_data);
        }

        $offer_categories = $request->input('offer_categories');
        $offer_branches = $request->input('offer_branches');
        $offer_branches_count = $request->input('offer_branches_count');
        $targeted_membership = $request->input('targeted_membership');
        $visible_membership = $request->input('visible_membership');
        $offer_image_link = $request->file('offer_image_link');
        $countCategories = 0;
        if ($offer_categories != null) {
            $countCategories = count($offer_categories);
        }
        $countBranches = 0;
        if ($offer_branches != null) {
            $countBranches = count($offer_branches);
        }
        $countTargeted = 0;
        if ($targeted_membership != null) {
            $countTargeted = count($targeted_membership);
        }
        $countVisible = 0;
        if ($visible_membership != null) {
            $countVisible = count($visible_membership);
        }
        $countImages = 0;
        if ($offer_image_link != null) {
            $countImages = count($offer_image_link);
        }
        $categories = array();
        $targeted = array();
        $visible = array();
        $images = array();
        $order = 0;
        for ($x = 0; $x < $countImages; $x++) {
            $key = $offer_image_link[$x] ?? null;
            if ($key != null) {
                $file_path = $this->ImageUpload($offer_image_link[$x], 'uploads/offers/gallery/');
                $images = new Image;
                $images->image =  $file_path;
                $images->image_order =  ++$order;
                $offer->gallery()->save($images);
            }
        }
        for ($x = 0; $x < $countCategories; $x++) {
            $key = $offer_categories[$x] ?? null;
            if ($key != null) {
                $categories['offer'] = $offer->id;
                $categories['category'] = $offer_categories[$x];
                OfferCategory::create($categories);
            }
        }

        for ($y = 0; $y < $countBranches; $y++) {
            $key = $offer_branches[$y] ?? null;
            $keyCount = $offer_branches_count[$y] ?? null;
            if ($key != null && $keyCount != null) {
                $branches['offer'] = $offer->id;
                $branches['branch'] = $offer_branches[$y];
                $branches['coupons'] = $offer_branches_count[$y];
                OfferBranch::create($branches);
            }
        }
        for ($y = 0; $y < $countTargeted; $y++) {
            $key = $targeted_membership[$y] ?? null;
            if ($key != null) {
                $targeted['offer'] = $offer->id;
                $targeted['plan'] = $targeted_membership[$y];
                TargetedMembership::create($targeted);
            }
        }
        for ($y = 0; $y < $countVisible; $y++) {
            $key = $visible_membership[$y] ?? null;
            if ($key != null) {
                $visible['offer'] = $offer->id;
                $visible['plan'] = $visible_membership[$y];
                VisibleMembership::create($visible);

                $merchant_data = MerchantDetail::whereMerchantId($offer->merchant_id)->first();
                $notify['message'] = 'New Offer:' . $offer->offer_name . ' Added. $#$#' . $merchant_data->merchant_brand;
                $notify['offer'] = $offer->offer_uniid;
                $notify['plan'] = $visible_membership[$y];
                OfferNotification::create($notify);
            }
        }
        if ($offer) {
            $merchant = User::find($input['merchant_id']);
            $email_data = [
                'merchant_name' => $merchant->name,
                'merchant_email' => $merchant->email,
                'offer_name' =>  $input['offer_name'],
                'url' => route('merchant-offers.show', $offer->offer_uniid),
            ];
            $queueData = [];
            $queueData['email'] = $merchant->email;
            $queueData['data'] = $email_data;
            dispatch(new NewOfferJob($queueData));
        }
        return redirect()->route('offers.index')
            ->with('success', __('Offer has been added sucessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::user()->can('admin-offer-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $offer_data = Offer::find($id);
        $offer_categories = $offer_data->offerCategories;
        $offer_branches = $offer_data->offerBranches;
        $offer_merchant = $offer_data->merchant;
        $categories_data = Category::all();
        $plans_data = Plan::all();
        $targeted_plans = $offer_data->targetPlans;
        $visible_plans = $offer_data->visiblePlans;
        $gallery = $offer_data->gallery;
        $gallery_data = $gallery->map(function ($image) {
            return collect($image->toArray())
                ->only(['id', 'image'])
                ->all();
        });
        $campaigns_data = Campaign::all();
        $branches_data = Branch::where('merchant_id', '=', $offer_merchant->id)->orderBy('id', 'DESC')->get();
        return view('admin.offers.show', compact(
            'page_title',
            'campaigns_data',
            'plans_data',
            'targeted_plans',
            'visible_plans',
            'offer_merchant',
            'offer_branches',
            'merchants_data',
            'offer_categories',
            'offer_data',
            'categories_data',
            'branches_data',
            'gallery_data'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::user()->can('admin-offer-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $offer_data = Offer::find($id);
        $offer_categories = $offer_data->offerCategories;
        $offer_branches = $offer_data->offerBranches;
        $offer_merchant = $offer_data->merchant;
        $categories_data = Category::all();
        $plans_data = Plan::all();
        $targeted_plans = $offer_data->targetPlans;
        $visible_plans = $offer_data->visiblePlans;
        $gallery = $offer_data->gallery;
        $gallery_data = $gallery->map(function ($image) {
            return collect($image->toArray())
                ->only(['id', 'image', 'image_order'])
                ->all();
        });
        $campaigns_data = Campaign::all();
        $branches_data = Branch::where('merchant_id', '=', $offer_merchant->id)->orderBy('id', 'DESC')->get();
        return view('admin.offers.edit', compact(
            'page_title',
            'campaigns_data',
            'plans_data',
            'targeted_plans',
            'visible_plans',
            'offer_merchant',
            'offer_branches',
            'merchants_data',
            'offer_categories',
            'offer_data',
            'categories_data',
            'branches_data',
            'gallery_data'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPromo($id)
    {
        if (!Auth::user()->can('admin-offer-edit')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $offer_data = Offer::find($id);
        $offer_categories = $offer_data->offerCategories;
        $offer_branches = $offer_data->offerBranches;
        $offer_merchant = $offer_data->merchant;
        $categories_data = Category::all();
        $plans_data = Plan::all();
        $targeted_plans = $offer_data->targetPlans;
        $visible_plans = $offer_data->visiblePlans;
        $gallery = $offer_data->gallery;
        $gallery_data = $gallery->map(function ($image) {
            return collect($image->toArray())
                ->only(['id', 'image', 'image_order'])
                ->all();
        });
        $campaigns_data = Campaign::all();
        $branches_data = Branch::where('merchant_id', '=', $offer_merchant->id)->orderBy('id', 'DESC')->get();
        return view('admin.offers.promo-edit', compact(
            'page_title',
            'campaigns_data',
            'plans_data',
            'targeted_plans',
            'visible_plans',
            'offer_merchant',
            'offer_branches',
            'merchants_data',
            'offer_categories',
            'offer_data',
            'categories_data',
            'branches_data',
            'gallery_data'
        ));
    }

    public function clone($id)
    {
        $page_title = __($this->page_heading);
        $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
        $merchants_data = $merchants->map(function ($merchant) {
            return collect($merchant->toArray())
                ->only(['id', 'name'])
                ->all();
        });
        $offer_data = Offer::find($id);
        $offer_categories = $offer_data->offerCategories;
        $offer_branches = $offer_data->offerBranches;
        $offer_merchant = $offer_data->merchant;
        $categories_data = Category::all();
        $plans_data = Plan::all();
        $targeted_plans = $offer_data->targetPlans;
        $visible_plans = $offer_data->visiblePlans;
        $gallery = $offer_data->gallery;
        $gallery_data = $gallery->map(function ($image) {
            return collect($image->toArray())
                ->only(['id', 'image', 'image_order'])
                ->all();
        });
        $campaigns_data = Campaign::all();
        $branches_data = Branch::where('merchant_id', '=', $offer_merchant->id)->orderBy('id', 'DESC')->get();
        return view('admin.offers.clone', compact(
            'page_title',
            'campaigns_data',
            'plans_data',
            'targeted_plans',
            'visible_plans',
            'offer_merchant',
            'offer_branches',
            'merchants_data',
            'offer_categories',
            'offer_data',
            'categories_data',
            'branches_data',
            'gallery_data'
        ));
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
        if (!$id) {
            return redirect()->route('offers');
        }
        $this->validate($request, [
            //Form Attributes
            'offer_name' => 'required|max:50|min:3',
            'offer_name_arabic' => 'required|max:50|min:3',
            'offer_description' => 'required',
            'offer_description_arabic' => 'required',
            'offer_desc_description' => 'required',
            'offer_desc_description_arabic' => 'required',
            'offer_discount' => 'required|numeric|max:100',
            'offer_from' => 'required|date',
            'offer_to' => 'required|date',
            'offer_categories' => 'required',
            'offer_branches' => 'required',
            'offer_branches_count' => 'required',
            'targeted_membership' => 'required',
            'visible_membership' => 'required',
            'offer_status' => 'required',
            'merchant_id' => 'required'
        ], [
            'merchant_id.required' => 'Please Select Merchant'
        ]);
        $input = $request->all();
        if (!empty($request->file('offer_image_link'))) {
            $this->validate($request, [
                //Form Attributes
                'offer_image_link.*' => 'mimes:png,jpeg,jpg|file|required|max:2048',
            ], [
                'offer_image_link.*.mimes' => 'Gallery Image must be a Image',
                'offer_image_link.*.max' => 'Image should be 2 MB max.',
            ]);
        }

        $offer_campaign = $request->offer_campaign;
        if (!is_null($offer_campaign) && !empty($offer_campaign)) {
            $offer_campaign = $input['offer_campaign'];
        } else {
            $offer_campaign = '0';
        }

        $offer_price = $request->offer_price;
        if (!is_null($offer_price) && !empty($offer_price)) {
            $offer_price = $input['offer_price'];
        } else {
            $offer_price = '0';
        }

        $offerData = [
            'offer_name' => $input['offer_name'],
            'offer_name_arabic' => $input['offer_name_arabic'],
            'offer_description' => $input['offer_description'],
            'offer_description_arabic' => $input['offer_description_arabic'],
            'offer_desc_description' => $input['offer_desc_description'],
            'offer_desc_description_arabic' => $input['offer_desc_description_arabic'],
            'offer_image_link' => 'No Link',
            'offer_discount' => $input['offer_discount'],
            'offer_price' => $input['offer_price'],
            'offer_from' => $input['offer_from'],
            'offer_to' => $input['offer_to'],
            'offer_status' => $input['offer_status'],
            'offer_campaign' => $offer_campaign,
            'offer_comments' => $input['offer_comments'],
            'merchant_id' => $input['merchant_id'],
            'offer_request' => 2,
        ];
        $offer = Offer::where('id', '=', $id)->update($offerData);
        $offer = Offer::find($id);
        $offer_categories = $request->input('offer_categories');
        $offer_branches = $request->input('offer_branches');
        $offer_branches_count = $request->input('offer_branches_count');
        $targeted_membership = $request->input('targeted_membership');
        $visible_membership = $request->input('visible_membership');
        $countCategories = 0;
        if ($offer_categories != null) {
            $countCategories = count($offer_categories);
        }
        $countBranches = 0;
        if ($offer_branches != null) {
            $countBranches = count($offer_branches);
        }
        $countTargeted = 0;
        if ($targeted_membership != null) {
            $countTargeted = count($targeted_membership);
        }
        $countVisible = 0;
        if ($visible_membership != null) {
            $countVisible = count($visible_membership);
        }
        $categories = array();
        $targeted = array();
        $visible = array();
        if (!empty($request->file('offer_image_link'))) {
            $this->validate($request, [
                //Form Attributes
                'offer_image_link.*' => 'mimes:jpeg,jpg,png,tif,lzw|required|max:2048',
            ], [
                'offer_image_link.*.mimes' => 'Gallery Image must be a Image',
                'offer_image_link.*.max' => 'Image should be 2 MB max.',
            ]);
            $offer_image_link = $request->file('offer_image_link');
            $countImages = count($offer_image_link);
            $images = array();
            $order = 0;
            for ($x = 0; $x < $countImages; $x++) {
                $key = $offer_image_link[$x] ?? null;
                if ($key != null) {
                    $file_path = $this->ImageUpload($offer_image_link[$x], 'uploads/offers/gallery/');
                    $images = new Image;
                    $images->image =  $file_path;
                    $images->image_order =  ++$order;
                    $offer->gallery()->save($images);
                }
            }
        }
        $offer->offerCategories()->delete();
        for ($x = 0; $x < $countCategories; $x++) {
            $key = $offer_categories[$x] ?? null;
            if ($key != null) {
                $categories['offer'] = $offer->id;
                $categories['category'] = $offer_categories[$x];
                OfferCategory::create($categories);
            }
        }
        $offer->offerBranches()->delete();
        for ($y = 0; $y < $countBranches; $y++) {
            $key = $offer_branches[$y] ?? null;
            $keyCount = $offer_branches_count[$y] ?? null;
            if ($key != null && $keyCount != null) {
                $branches['offer'] = $offer->id;
                $branches['branch'] = $offer_branches[$y];
                $branches['coupons'] = $offer_branches_count[$y];
                OfferBranch::create($branches);
            }
        }
        $offer->targetPlans()->delete();
        for ($y = 0; $y < $countTargeted; $y++) {
            $key = $targeted_membership[$y] ?? null;
            if ($key != null) {
                $targeted['offer'] = $offer->id;
                $targeted['plan'] = $targeted_membership[$y];
                TargetedMembership::create($targeted);
            }
        }
        $offer->visiblePlans()->delete();
        for ($y = 0; $y < $countVisible; $y++) {
            $key = $visible_membership[$y] ?? null;
            if ($key != null) {
                $visible['offer'] = $offer->id;
                $visible['plan'] = $visible_membership[$y];
                VisibleMembership::create($visible);
            }
        }
        return redirect()->route('offers.index')
            ->with('success', __('Offer has been edited sucessfully'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePromo(Request $request)
    {
        $input = $request->all();
        $id = $input['xxyyz'];
        if (!$id) {
            return redirect()->route('offers');
        }
        $this->validate($request, [
            //Form Attributes
            'offer_type' => 'required|numeric',
            'offer_coupons' => 'required|numeric',
        ]);
        $input = $request->all();
        $offer_coupon_type = 0;
        $offer_code_generation = 0;
        $couponCodesData = array();
        $fileCodesData = array();
        Coupon::where([
            ['offer_id', '=', $id],
            ['coupon_status', '=', 1],
        ])->delete();
        $pre_coupons_count = Coupon::where('offer_id', '=', $id)->count();
        $new_offer_coupons = $input['offer_coupons'];
        $input['offer_coupons'] = $pre_coupons_count + $input['offer_coupons'];

        if ($input['offer_type'] == 1) {
            $this->validate($request, [
                'offer_coupon_type' => 'required|numeric',
            ]);
            $offer_coupon_type = $input['offer_coupon_type'];
            if ($input['offer_coupon_type'] == 2) {
                $this->validate($request, [
                    'offer_code_generation' => 'required|numeric',
                ]);
                $offer_code_generation = $input['offer_code_generation'];
                if ($input['offer_code_generation'] == 1) {
                    $this->validate($request, [
                        'coupons_csv' => 'mimes:csv,txt,xlsx,xls|required|max:4096',
                    ]);
                    $file_path = $this->DocUpload($request->file('coupons_csv'), 'uploads/offers/csv/');
                    $rows = \Excel::toArray(new CouponsImport,  $file_path);
                    foreach ($rows[0] as $key => $value) {
                        $couponCodesData[] = $value[0];
                    }
                    unlink($file_path);
                    foreach ($couponCodesData as $code) {
                        if (!Coupon::where('coupon_code', '=', $code)->exists() && strlen($code) == 11) {
                            $fileCodesData[] = $code;
                        }
                    }
                    $fileCodesCount = count($fileCodesData);
                    $pre_coupons_count = Coupon::where('offer_id', '=', $id)->count();
                    $input['offer_coupons'] = $pre_coupons_count + $fileCodesCount;
                }
            }
        } else if ($input['offer_type'] == 2) {
            $offer_coupon_type = 0;
            $offer_code_generation = 0;
        }

        $offer_per_user = $request->offer_per_user;
        if (!is_null($offer_per_user) && !empty($offer_per_user)) {
            $offer_per_user = $offer_per_user;
        } else {
            $offer_per_user = '0';
        }

        $offer_usage_duration = $request->offer_usage_duration;
        if (!is_null($offer_usage_duration) && !empty($offer_usage_duration)) {
            $offer_usage_duration = $offer_usage_duration;
        } else {
            $offer_usage_duration = '0';
        }

        $offerData = [
            'offer_coupons' => $input['offer_coupons'],
            'offer_type' => $input['offer_type'],
            'offer_per_user' => $offer_per_user,
            'offer_usage_duration' => $offer_usage_duration,
            'offer_code_generation' => $offer_code_generation,
            'offer_coupon_type' => $offer_coupon_type,
        ];

        $offer = Offer::where('id', '=', $id)->update($offerData);
        $offer = Offer::find($id);
        if ($input['offer_type'] == 1 && $offer_coupon_type == 1) {
            for ($x = 0; $x < $new_offer_coupons; $x++) {
                $coupon_data = [
                    'offer_id' => $offer->id,
                    'coupon_code' => $this->generateRandomString(11),
                    'coupon_per_user' => $offer_per_user,
                    'coupon_usage_duration' => $offer_usage_duration,
                    'coupon_status' => 1,
                ];
                Coupon::create($coupon_data);
            }
        } else if ($input['offer_type'] == 1 && $offer_coupon_type == 2) {
            if ($offer_code_generation == 2) {
                $coupons[] = $this->generateRandomString(11);
                for ($x = 0; $x < $new_offer_coupons; $x++) {
                    $coupons[$x] = $this->generateRandomString(11);
                    $coupon_data = [
                        'offer_id' => $offer->id,
                        'coupon_code' => $coupons[$x],
                        'coupon_per_user' => $offer_per_user,
                        'coupon_usage_duration' => $offer_usage_duration,
                        'coupon_status' => 1,
                    ];
                    Coupon::create($coupon_data);
                }
            } else if ($offer_code_generation == 1) {
                for ($x = 0; $x < $fileCodesCount; $x++) {
                    $coupon_data = [
                        'offer_id' => $offer->id,
                        'coupon_code' => $fileCodesData[$x],
                        'coupon_per_user' => $offer_per_user,
                        'coupon_usage_duration' => $offer_usage_duration,
                        'coupon_status' => 1,
                    ];
                    Coupon::create($coupon_data);
                }
            }
        } else if ($input['offer_type'] == 2) {
            $coupon_data = [
                'offer_id' => $offer->id,
                'coupon_code' => $this->generateRandomString(11),
                'coupon_per_user' => $offer_per_user,
                'coupon_usage_duration' => $offer_usage_duration,
                'coupon_status' => 1,
            ];
            Coupon::create($coupon_data);
        }

        return redirect()->route('offers.index')
            ->with('success', 'Offer Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$id) {
            return redirect()->route('offers');
        }
        $offer = Offer::find($id);
        if ($offer->redeemCoupons()->count() == 0) {
            $offer->offerNotification()->delete();
            $offer->offerCategories()->delete();
            $offer->offerBranches()->delete();
            $offer->targetPlans()->delete();
            $offer->visiblePlans()->delete();
            $offer->gallery()->delete();
            $offer->coupons()->delete();
            $offer->wishList()->delete();
            $offer->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }

    public function destroyImage($id)
    {
        if (!$id) {
            return redirect()->back();
        }
        if (Image::find($id)->delete()) {
            return true;
        } else {
            return false;
        }
    }

    function generateRandomString($length = 11)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
    }

    public function readCSV($csvFile, $array)
    {
        $file_handle = fopen($csvFile, 'r');
        while (!feof($file_handle)) {
            $line_of_text[] = fgetcsv($file_handle, 0, $array['delimiter']);
        }
        fclose($file_handle);
        return $line_of_text;
    }

    public function updateImageOrder(Request $request)
    {
        $order = $request->order;
        $id = $request->id;
        if ($order && $id) {
            Image::where('id', '=', $id)->update(['image_order' => $order]);
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function downloadPromos($id)
    {
        if (!$id) {
            return redirect()->back();
        }
        $offer_data = Offer::find($id);
        $csv_coupons = array();
        if ($offer_data['offer_coupon_type'] == 2) {
            $offer_coupons = $offer_data->coupons;
            foreach ($offer_coupons as  $offer_coupon) {
                if ($offer_coupon['coupon_status'] == 1) {
                    $csv_coupons[] = [
                        'promo' => $offer_coupon['coupon_code'],
                    ];
                }
            }
            return \Excel::download(new CouponsExport($csv_coupons), $offer_data['offer_name'] . '-promos.xlsx');
        } else {
            return redirect()->back();
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function downloadQr($id)
    {
        if (!$id) {
            return redirect()->back();
        }
        $offer_data = Offer::find($id);
        if ($offer_data['offer_type'] == 2) {
            $offer_coupons = $offer_data->coupons;
            foreach ($offer_coupons as  $offer_coupon) {
                $file_name = $offer_coupon['coupon_code'] . '.png';
                if (!file_exists('uploads/offers/qrcodes/' . $file_name)) {
                    QrCode::size(500)
                        ->format('png')
                        ->backgroundColor(255, 255, 255)
                        ->generate($offer_coupon['coupon_code'], public_path('uploads/offers/qrcodes/' . $file_name));
                }
                return FacadeResponse::download('uploads/offers/qrcodes/' . $file_name);
            }
        } else {
            return redirect()->back();
        }
    }
}
