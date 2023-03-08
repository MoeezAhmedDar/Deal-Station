<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coupon;
use App\Models\CouponRedeem;
use App\Models\Offer;
use App\Models\OfferBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PDF;


class ReportController extends Controller
{
  private $page_heading;

  public function __construct()
  {
    $this->page_heading =  'Reports';
  }


  public function redeemedCoupons()
  {
    if (!Auth::user()->can('admin-report-list')) {
      return redirect()->route('dashboard');
    }
    $page_title = __($this->page_heading);
    $merchants = User::role('Merchant')->orderBy('id', 'DESC')->get();
    $merchants_data = $merchants->map(function ($merchant) {
      return collect($merchant->toArray())
        ->only(['id', 'name'])
        ->all();
    });
    return view('admin.reports.redeem_coupons', compact('merchants_data', 'page_title'));
  }

  public function fetchRedeemedCoupons(Request $request)
  {
    $result = array('data' => array());
    $redeemed_coupons = array();
    $merchant = $request->merchant_id;
    $branch = $request->branch_id;
    if ($merchant == '0' && $branch == '0') {
      $redeemed_coupons = CouponRedeem::with('offer')->orderBy('id', 'DESC')->get();
    } else if ($merchant != '0' && $branch == '0') {
      $redeemed_coupons = CouponRedeem::with(['offer' => function ($query) use ($merchant) {
        return $query->where('merchant_id', '=', $merchant);
      }])->get();
    }
    foreach ($redeemed_coupons as $key => $redeemed_coupon) {
      if ($redeemed_coupon['offer'] != null) {
        $promo = '';
        $coupon_data = Coupon::find($redeemed_coupon['coupons_id']);
        if ($redeemed_coupon['offer']['offer_type'] == 1) {
          if ($redeemed_coupon['offer']['offer_coupon_type'] == 1) {
            $file_name = $coupon_data['coupon_code'] . '.png';
            $promo = '<img src="' . url('uploads/offers/qrcodes/' . $file_name) . '" class="align-self-end" alt="Promo" style="height: 60px;">';
          } else if ($redeemed_coupon['offer']['offer_coupon_type'] == 2) {
            $promo = $coupon_data['coupon_code'];
          }
        } else if ($redeemed_coupon['offer']['offer_type'] == 2) {
          $file_name = $coupon_data['coupon_code'] . '.png';
          $promo = '<img src="' . url('uploads/offers/qrcodes/' . $file_name) . '" class="align-self-end" alt="Promo" style="height: 60px;" >';
        }
        $merchant_data = User::find($redeemed_coupon['offer']['merchant_id']);
        $member_data = User::find($redeemed_coupon['user_id']);
        $result['data'][$key] = array(
          $redeemed_coupon['offer']['offer_name'],
          $promo,
          $merchant_data['name'],
          $member_data['name']
        );
      }
    }
    echo json_encode($result);
  }

  public function ExportPDFRedeemedCoupons(Request $request)
  {
    $result = array();
    $redeemed_coupons = array();
    $merchant = $request->merchant_id;
    $branch = $request->branch_id;
    if ($merchant == '0' && $branch == '0') {
      $redeemed_coupons = CouponRedeem::with('offer')->orderBy('id', 'DESC')->get();
    } else if ($merchant != '0' && $branch == '0') {
      $redeemed_coupons = CouponRedeem::with(['offer' => function ($query) use ($merchant) {
        return $query->where('merchant_id', '=', $merchant);
      }])->get();
    }
    foreach ($redeemed_coupons as $key => $redeemed_coupon) {
      if ($redeemed_coupon['offer'] != null) {
        $promo = '';
        $coupon_data = Coupon::find($redeemed_coupon['coupons_id']);
        if ($redeemed_coupon['offer']['offer_type'] == 1) {
          if ($redeemed_coupon['offer']['offer_coupon_type'] == 1) {
            $file_name = $coupon_data['coupon_code'] . '.png';
            $promo = '<img src="' . url('uploads/offers/qrcodes/' . $file_name) . '" class="align-self-end" alt="Promo" style="height: 60px;" >';
          } else if ($redeemed_coupon['offer']['offer_coupon_type'] == 2) {
            $promo = $coupon_data['coupon_code'];
          }
        } else if ($redeemed_coupon['offer']['offer_type'] == 2) {
          $file_name = $coupon_data['coupon_code'] . '.png';
          $promo = '<img src="' . url('uploads/offers/qrcodes/' . $file_name) . '" class="align-self-end" alt="Promo" style="height: 60px;" >';
        }
        $merchant_data = User::find($redeemed_coupon['offer']['merchant_id']);
        $member_data = User::find($redeemed_coupon['user_id']);
        $result[$key] = [
          'offer' => $redeemed_coupon['offer']['offer_name'],
          'promo' => $promo,
          'merchant' => $merchant_data['name'],
          'member' => $member_data['name']
        ];
      }
    }
    $html = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Redeemed Coupons</title>
            <style>
              @page {
                margin: 100px 25px;
              }
        
              header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                font-size: 20px !important;
        
                /** Extra personal styles **/
                background-color: #fff;
                color: #000;
                text-align: left;
                line-height: 35px;
              }
        
              footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                font-size: 20px !important;
        
                /** Extra personal styles **/
                background-color: #fff;
                color: #000;
                text-align: left;
                line-height: 35px;
              }
        
              table,
              td {
                border: 1px solid #000000;
                background-color: #ffffff;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
              }

              table,
              th {
                border: 1px solid #000000;
                background-color: #ffffff;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
              }
            </style>
          </head>
          <body>
            <header></header>
            <footer></footer>
            <main>
              <h4>Redeemed Coupons</h4>
              <table>
                <thead>
                  <th>Offer</th>
                  <th>Promo</th>
                  <th>Merchant</th>
                  <th>Member</th>
                </thead>
                <tbody>';
    foreach ($result as $key) {
      $html .= '<tr>
                    <td>' . $key['offer'] . '</td>
                    <td>' . $key['promo'] . '</td>
                    <td>' . $key['merchant'] . '</td>
                    <td>' . $key['member'] . '</td>
                  </tr>';
    }
    $html .= '</tbody>
              </table>
            </main>
          </body>
        </html>';
    $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
    $fileName = 'Redeemed-Coupons-' . time() . '.pdf';
    $pdfUpload = Storage::put('public/pdf/' . $fileName, $pdf->output());
    $pdfLink = Storage::url('public/pdf/' . $fileName);
    $pdfDownloadLink = url($pdfLink);
    $response['status'] = true;
    $response['link'] = $pdfDownloadLink;
    return response()->json($response);
  }

  public function ExportPDFMembers(Request $request)
  {
    $result = array();
    $members = User::role('Member')->orderBy('id', 'DESC')->get();
    foreach ($members as $key => $value) {
      $city = City::find($value['city']);
      $result[$key] = [
        'name' => $value['name'],
        'email' => $value['email'],
        'phone' =>  $value['phone'],
        'dob' => date('d-m-Y', strtotime($value['dob'])),
        'gender' => $value['gender'],
        'city' => $city ? $city->city_name : ''
      ];
    }
    $html = '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
            <title>Members - Deal Station</title>
            <style>
              @page {
                margin: 100px 25px;
              }
        
              header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                font-size: 20px !important;
        
                /** Extra personal styles **/
                background-color: #fff;
                color: #000;
                text-align: left;
                line-height: 35px;
              }
        
              footer {
                position: fixed;
                bottom: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                font-size: 20px !important;
        
                /** Extra personal styles **/
                background-color: #fff;
                color: #000;
                text-align: left;
                line-height: 35px;
              }
        
              table,
              td {
                border: 1px solid #000000;
                background-color: #ffffff;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
              }

              table,
              th {
                border: 1px solid #000000;
                background-color: #ffffff;
                width: 100%;
                text-align: left;
                border-collapse: collapse;
              }
            </style>
          </head>
          <body>
            <header></header>
            <footer></footer>
            <main>
              <h4>Members</h4>
              <table>
                <thead>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>DOB</th>
                  <th>Gender</th>
                  <th>City</th>
                </thead>
                <tbody>';
    foreach ($result as $key) {
      $html .= '<tr>
                    <td>' . $key['name'] . '</td>
                    <td>' . $key['email'] . '</td>
                    <td>' . $key['phone'] . '</td>
                    <td>' . $key['dob'] . '</td>
                    <td>' . $key['gender'] . '</td>
                    <td>' . $key['city'] . '</td>
                  </tr>';
    }
    $html .= '</tbody>
              </table>
            </main>
          </body>
        </html>';
    $pdf = PDF::loadHTML($html)->setPaper('a4', 'portrait');
    $fileName = 'Members-' . time() . '.pdf';
    $pdfUpload = Storage::put('public/pdf/' . $fileName, $pdf->output());
    $pdfLink = Storage::url('public/pdf/' . $fileName);
    $pdfDownloadLink = url($pdfLink);
    $response['status'] = true;
    $response['link'] = $pdfDownloadLink;
    return response()->json($response);
  }
}
