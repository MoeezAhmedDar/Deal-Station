<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Carbon\Carbon;
use App\Models\VisibleMembership;
use App\Models\Offer;
use App\Models\OfferWishlist;
use App\Models\User;

class CampaignController extends BaseController
{
    public function fetchAllCampaignsData(Request $request)
    {
        $campaigns = Campaign::whereCampaignStatus(1)->get();
        $campaign_data = array();
        foreach ($campaigns as $campaign) {
            $fromDate = date('d-m-Y', strtotime($campaign['campaign_from']));
            $from = Carbon::createFromFormat('d-m-Y', $fromDate);

            $toDate = date('d-m-Y', strtotime($campaign['campaign_to']));
            $to = Carbon::createFromFormat('d-m-Y', $toDate);

            $nowDate = Carbon::now()->format('d-m-Y');
            $now = Carbon::createFromFormat('d-m-Y', $nowDate);

            $offer_counts = Offer::where('offer_campaign', $campaign['id'])->count();
            if (($now->gte($from) && $now->lte($to))) {
                $campaign_data[] = [
                    'id' => $campaign['campaign_uniid'],
                    'campaign_name' => $campaign['campaign_name'],
                    'campaign_name' => $campaign['campaign_name'],
                    'campaign_name_arabic' => $campaign['campaign_name_arabic'],
                    'campaign_from' => date('d-m-Y', strtotime($campaign['campaign_from'])),
                    'campaign_to' => date('d-m-Y', strtotime($campaign['campaign_to'])),
                    'campaign_banner' => url($campaign['campaign_banner']),
                    'offers_count' => $offer_counts,
                ];
            }
        }
        return $this->responseApi($campaign_data, true, 'Campaigns Data Fetched Successfully', 200);
    }

    public function fetchCampaignOffersData(Request $request)
    {
        if ($request->user()) {
            $user_data = $request->user()->load(['latestMemberSubscription']);
            $id = $request->only('id');

            if ($id) {
                if (Campaign::where([
                    ['campaign_uniid', '=', $id],
                    ['campaign_status', '=', 1]
                ])->exists()) {
                    $campaign = Campaign::where('campaign_uniid', '=', $id)->first();
                    $fromDate = date('d-m-Y', strtotime($campaign['campaign_from']));
                    $from = Carbon::createFromFormat('d-m-Y', $fromDate);
                    $toDate = date('d-m-Y', strtotime($campaign['campaign_to']));
                    $to = Carbon::createFromFormat('d-m-Y', $toDate);
                    $nowDate = Carbon::now()->format('d-m-Y');
                    $now = Carbon::createFromFormat('d-m-Y', $nowDate);
                    $user_data = $request->user();
                    $offers = array();
                    if (($now->gte($from) && $now->lte($to))) {
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
                                    if (($now->gte($from) && $now->lte($to)) && $offer_data['offer']['offer_campaign'] == $campaign['id']) {
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
                                            'gallery' => $gallery_data[0],
                                            'wishlist' => $wishlist,
                                        ];
                                    }
                                }
                            }
                            return $this->responseApi($offers, true, 'Offers Fetched Successfully', 200);
                        } else {
                            return $this->responseApi([], false, __('User does not have any Subscription'), 417);
                        }
                    }
                } else {
                    return $this->responseApi([], false, __('Id is not correct'), 417);
                }
            } else {
                return $this->responseApi([], false, __('Campaign id is missing'), 417);
            }
        } else {
            return $this->responseApi([], false, __('User Token Missing'), 417);
        }
    }


    public function countCampaignOffers($id, $user_plan)
    {
        $offerCount = 0;
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
                if (($now->gte($from) && $now->lte($to)) && $offer_data['offer']['offer_campaign'] == $id) {
                    $offerCount++;
                }
            }
        }
        return  $offerCount;
    }
}
