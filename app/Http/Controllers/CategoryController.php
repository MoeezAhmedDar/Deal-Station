<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\FileUploadTrait;
use App\Models\Category;
use App\Models\OfferCategory;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use FileUploadTrait;
    private $page_heading;

    public function __construct()
    {
        $this->page_heading = 'Category Management';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth::user()->can('admin-category-list')) {
            return redirect()->route('dashboard');
        }
        $page_title = __($this->page_heading);
        return view('admin.categories.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }


    public function store(Request $request)
    {
        $response = array();
        $validator = Validator::make($request->all(), [
            //Form Attributes
            'category_name' => 'required|max:50|min:3|unique:categories,category_name',
            'category_name_arabic' => 'required|max:50|min:3',
            'category_icon' => 'mimes:jpeg,jpg,png,tif,lzw|required|max:2048',
        ], [
            'category_icon.mimes' => 'Logo must be a Image',
            'category_icon.max' => 'Image should be 2 MB max.',
        ]);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->all();
            $response['status'] = false;
        } else {
            $input = $request->all();
            $category_icon = $request->category_icon;
            if ($category_icon) {
                $file_path = $this->ImageUpload($category_icon, 'uploads/categories/');
                $category_icon = $file_path;
            } else {
                $category_icon = '';
            }
            $categoryData = [
                'category_uniid' => Str::uuid()->toString(),
                'category_name' => $input['category_name'],
                'category_name_arabic' => $input['category_name_arabic'],
                'category_icon' => $category_icon,
            ];
            Category::create($categoryData);
            $response['status'] = true;
            $response['messages'] = __('category has been added sucessfully');
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!OfferCategory::where('category', '=', $id)->exists()) {
            Category::where('id', '=', $id)->delete();
            return response()->json(['data' => true]);
        } else {
            return response()->json(['data' => false]);
        }
    }


    public function fetchCategoriesData(Request $request)
    {
        $result = array('data' => array());
        $categories = Category::orderBy('id', 'DESC')->get();
        foreach ($categories as $key => $value) {
            $buttons = '';

            if (Auth::user()->can('admin-category-list')) {
                $buttons .= '<button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="showFunc(' . $value->id . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_show_category" data-toggle="tooltip" data-placement="top" title="Show">
                <i class="far fa-eye"></i>
                </button>';
            }
            if (Auth::user()->can('admin-category-edit')) {
                $buttons .= ' <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="editFunc(' . $value->id . ')" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_category" data-toggle="tooltip" data-placement="top" title="edit">
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

            if (Auth::user()->can('admin-category-delete')) {
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
            $category_icon = '<!--begin::Symbol-->
            <div class="symbol symbol-40 symbol-light-primary mr-5">
            <span class="symbol-label">
            <img src="' . asset($value->category_icon) . '" class="h-75 align-self-end" alt="category icon">
            </span>
            </div>
            <!--end::Symbol-->';
            $result['data'][$key] = array(
                $category_icon,
                $value['category_name'],
                $buttons
            );
        }
        echo json_encode($result);
    }

    public function fetchCategoryData($id)
    {
        if ($id) {
            $category = Category::where('id', '=', $id)->first();
            echo json_encode($category);
        }
    }

    public function updateCategoryData(Request $request)
    {
        $response = array();
        $input = $request->all();
        $id =  $input['xxyyzz'];
        $validator = Validator::make($request->all(), [
            //Form Attributes
            'category_name' => 'required|max:50|min:3|unique:categories,category_name,' . $id,
            'category_name_arabic' => 'required|max:50|min:3',
            'category_icon' => 'mimes:jpeg,jpg,png,tif,lzw|max:2048',
        ], [
            'category_icon.mimes' => 'Logo must be a Image',
            'category_icon.max' => 'Image should be 2 MB max.',
        ]);
        if ($validator->fails()) {
            $response['message'] = $validator->messages()->all();
            $response['status'] = false;
        } else {
            $input = $request->all();
            $category_icon = $request->category_icon;
            if ($category_icon) {
                $file_path = $this->ImageUpload($category_icon, 'uploads/categories/');
                $category_icon = $file_path;
                $categoryData = [
                    'category_name' => $input['category_name'],
                    'category_name_arabic' => $input['category_name_arabic'],
                    'category_icon' => $category_icon,
                ];
                Category::where('id', '=', $id)->update($categoryData);
            } else {
                $categoryData = [
                    'category_name' => $input['category_name'],
                    'category_name_arabic' => $input['category_name_arabic']
                ];
                Category::where('id', '=', $input['xxyyzz'])->update($categoryData);
            }
            $response['status'] = true;
            $response['messages'] = __('category has been edited sucessfully');
        }
        return response()->json($response);
    }
}
