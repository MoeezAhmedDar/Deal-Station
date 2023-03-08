<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends BaseController
{
    public function fetchAllAdvertisementsData(Request $request)
    {
        $advertisements = Advertisement::whereAdvertisementStatus(1)->orderBy('advertisement_type', 'ASC')->get();
        foreach ($advertisements as $advertisement) {
            $advertisements_data[] = [
                'advertisement_type' => $advertisement['advertisement_type'],
                'advertisement_name' => $advertisement['advertisement_name'],
                'advertisement_name_arabic' => $advertisement['advertisement_name_arabic'],
                'advertisement_text' => $advertisement['advertisement_text'],
                'advertisement_item' => $advertisement['advertisement_item'],
                'advertisement_item_id' => $advertisement['advertisement_item_id'],
                'advertisement_image' => url($advertisement['advertisement_image']),
            ];
        }
        return $this->responseApi($advertisements_data, true, 'Advertisements Data Fetched Successfully', 200);
    }
}
