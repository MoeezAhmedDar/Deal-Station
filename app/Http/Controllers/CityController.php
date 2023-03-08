<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Models\Branch;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Models\City;
use App\Models\Plan;

class CityController extends Controller
{
    use FileUploadTrait;
    private $page_heading;

    public function __construct()
    {
        $this->page_heading = 'City Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-city-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        return view('admin.cities.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = array();
        $validator = Validator::make($request->all(), [
            //Form Attributes
            'city_name' => 'required|max:50|min:3|unique:cities,city_name',
            'city_name_arabic' => 'required|max:50|min:3',
        ]);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->all();
            $response['status'] = false;
        } else {
            $input = $request->all();
            $cityData = [
                'city_uniid' => Str::uuid()->toString(),
                'city_name' => $input['city_name'],
                'city_name_arabic' => $input['city_name_arabic'],
            ];
            City::create($cityData);
            $response['status'] = true;
            $response['messages'] = __('City has been added sucessfully');
        }
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (
            !Branch::where('branch_city', $id)->exists() &&
            !Plan::where('plan_city', '=', $id)->exists() &&
            !User::where('city', '=', $id)->exists()
        ) {
            City::where('id', '=', $id)->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }


    public function fetchCitiesData(Request $request)
    {
        $result = array('data' => array());
        $cities = City::orderBy('id', 'DESC')->get();
        foreach ($cities as $key => $value) {
            $buttons = '';

            if (Auth::user()->can('admin-city-list')) {
                $buttons .= '<button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="showFunc(' . $value->id . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_show_category" data-toggle="tooltip" data-placement="top" title="show">
                <i class="far fa-eye"></i>
                </button>';
            }
            if (Auth::user()->can('admin-city-edit')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="editFunc(' . $value->id . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_city" data-toggle="tooltip" data-placement="top" title="Edit">
                <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                <span class="svg-icon svg-icon-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="black" />
                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="black" />
                </svg>
                </span>
                <!--end::Svg Icon-->
                </button>';
            }
            if (Auth::user()->can('admin-city-delete')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="removeFunc(' . $value->id . ')" data-toggle="tooltip" data-placement="top" title="Delete">
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
            $result['data'][$key] = array(
                $value['city_name'],
                $value['city_name_arabic'],
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function fetchCityData($id)
    {
        if ($id) {
            $city = City::where('id', '=', $id)->first();
            echo json_encode($city);
        }
    }

    public function updateCityData(Request $request)
    {
        $response = array();
        $input = $request->all();
        $id =  $input['xxyyzz'];
        $validator = Validator::make($request->all(), [
            //Form Attributes
            'city_name' => 'required|max:50|min:3|unique:cities,city_name,' . $id,
            'city_name_arabic' => 'required|max:50|min:3',
        ]);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->all();
            $response['status'] = false;
        } else {
            $input = $request->all();
            $cityData = [
                'city_name' => $input['city_name'],
                'city_name_arabic' => $input['city_name_arabic']
            ];
            City::where('id', '=', $input['xxyyzz'])->update($cityData);
            $response['status'] = true;
            $response['messages'] = __('City has been edited sucessfully');
        }
        return response()->json($response);
    }
}
