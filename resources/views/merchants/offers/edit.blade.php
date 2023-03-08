@extends('admin.layouts.app')
@section('page_title', $page_title)

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        {{ __('Edit Offer')}}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('merchant-offers.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                {!! Form::model($offer_data, ['method' => 'PATCH','route' => ['merchant-offers.update', $offer_data->offer_uniid], 'class'=>'w-100 position-relative mb-3', 'enctype'=>'multipart/form-data', 'id'=>'offer-form']) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="offer-edit" data-toggle="tab" href="{{ route('merchant-offers.edit',$offer_data->offer_uniid)}}">Offer Update</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="offer-promo-edit" data-toggle="tab" href="{{ route('merchant-offers.promo-edit',$offer_data->offer_uniid)}}">Coupon Update</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in English')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name', null, array('placeholder' => __('Offer Name in English'),'class' => 'form-control')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in Arabic')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name_arabic', null, array('placeholder' => __('Offer Name in Arabic'),'class' => 'form-control')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Main Offer Description:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description', null, array('placeholder' =>__('Main Offer Description in English'),'class' => 'form-control', 'rows'=>'2')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description_arabic', null, array('placeholder' => 'وصف العرض الرئيسي','class' => 'form-control', 'rows'=>'2')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Offer Desc Details:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description', null, array('placeholder' => __('Offer Desc Details in English'),'class' => 'form-control', 'rows'=>'2')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description_arabic', null, array('placeholder' => 'وصف العرض الرئيسي','class' => 'form-control', 'rows'=>'2')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Images')}} ({{ __('Max 2 MB')}}) :</label>
                            <!--end::Label-->
                            <input class="col-12 nova-file-selector" type="file" name="offer_image_link[]" id="offer_image_link" accept="image/*" multiple />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="offer_price_div" @if($offer_data->offer_coupon_type == 1) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Original Price')}}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_price', null, array('class' => 'form-control', 'placeholder'=> __('Original Price'), 'step'=> '0.001', 'min'=> '0', 'oninput'=>'!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Discount')}} %:</label>
                            <!--end::Label-->
                            {!! Form::number('offer_discount', null, array('placeholder' => __('Offer Discount'),'class' => 'form-control', 'min'=>'1', 'max'=>'100')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Duration of Offer')}} :</label>
                        <!--end::Label-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('From')}} :</label>
                            <!--end::Label-->
                            {!! Form::date('offer_from', null, array('class' => 'form-control')) !!}
                        </div>
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('To')}}:</label>
                            <!--end::Label-->
                            {!! Form::date('offer_to', null, array('class' => 'form-control')) !!}
                        </div>
                        <!--begin::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Categories')}}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="offer_categories[]" id="offer_categories" data-control="select2" multiple="multiple">
                                @foreach ($categories_data as $category)
                                <option value="{{$category['id']}}" @foreach($offer_categories as $categories) @if($category["id"]==$categories["category"]) selected @endif @endforeach>{{$category['category_name']}}</option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <div class="checkbox-list">
                                    <br>
                                    <label class="checkbox">
                                        <input type="checkbox" id="categories_checkbox" />
                                        <span></span>
                                        Select All Categories
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Comments')}}</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_comments', null, array('placeholder' => __('Comments'),'class' => 'form-control', 'rows'=>'3')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--end::Input group-->
                    <div class="row">
                        <div class="col-12 fv-row">
                            <div class="form-group ">
                                <!--begin::Label-->
                                <label class="col-12 col-form-label fw-bold fs-6">{{ __('Branches')}}</label>
                                <!--end::Label-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="branches_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">{{ __('Select Branch')}}</th>
                                            <th>{{ __('Promo Count')}}</th>
                                            <th style="width:5%"><button type="button" id="add_row" class="btn btn-dark"><i class="fas fa-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $x = 1; ?>
                                        @foreach ($offer_branches as $branches)
                                        <tr id="row_<?php echo $x ?>">
                                            <td>
                                                <span class="text-danger" id="text-danger_<?php echo $x ?>"></span>
                                                <select class="form-control offer_branches" data-row-id="offer_branches_<?php echo $x ?>" id="offer_branches_<?php echo $x ?>" name="offer_branches[]" data-control="select2" required>
                                                    <option selected disabled value="0">--Please Select--</option>
                                                    @foreach ($branches_data as $branch)
                                                    <option value="{{$branch['id']}}" @if($branch['id']==$branches->branch) selected @endif>{{$branch['branch_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <span class="text-danger" id="text-count-danger"></span>
                                                <input type="number" data-row-id="offer_branches_count_<?php echo $x ?>" id="offer_branches_count_<?php echo $x ?>" name="offer_branches_count[]" class="form-control" autocomplete="off" required value="{{ $branches->coupons }}" min="0" onchange="countTotalPromo()">
                                            </td>
                                            <td><button type="button" onclick="removeRow(<?php echo $x ?>)" id="remove_row" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>
                                        </tr>
                                        <?php $x++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer d-flex justify-content-start py-6 px-9">
                    <input type="hidden" id="selected-merchant">
                    <button type="submit" class="btn btn-primary px-6">{{ __('Save Changes') }}</button>
                </div>
                <!--end::Card footer-->
                {!! Form::close() !!}

                <!--begin::Cashier Card-->
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            {{ __('Offer Gallery')}}
                        </div>
                        <!--End::Card title-->
                    </div>
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-12 fv-row">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="offers_gallery_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-dark fw-bolder fs-7 text-uppercase gs-0">
                                            <th>{{ __('Image')}}</th>
                                            <th>{{ __('Image Order')}}</th>
                                            <th>{{ __('Action')}}</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="text-dark-600 fw-bold">
                                        @foreach ($gallery_data as $image)
                                        <tr>
                                            <td><img src="{{ asset($image['image'])}}" alt="Gallery Image" style="height: 60px;"></td>
                                            <td><input type="number" name="order" min="1" max="5" id="order_{{ $image['id']}}" value="{{ $image['image_order']}}"></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="updateOrder('{{$image['id']}}')">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(13.000000, 6.000000) rotate(-450.000000) translate(-13.000000, -6.000000) " x="12" y="8.8817842e-16" width="2" height="12" rx="1" />
                                                                <path d="M9.79289322,3.79289322 C10.1834175,3.40236893 10.8165825,3.40236893 11.2071068,3.79289322 C11.5976311,4.18341751 11.5976311,4.81658249 11.2071068,5.20710678 L8.20710678,8.20710678 C7.81658249,8.59763107 7.18341751,8.59763107 6.79289322,8.20710678 L3.79289322,5.20710678 C3.40236893,4.81658249 3.40236893,4.18341751 3.79289322,3.79289322 C4.18341751,3.40236893 4.81658249,3.40236893 5.20710678,3.79289322 L7.5,6.08578644 L9.79289322,3.79289322 Z" fill="#000000" fill-rule="nonzero" transform="translate(7.500000, 6.000000) rotate(-270.000000) translate(-7.500000, -6.000000) " />
                                                                <rect fill="#000000" opacity="0.3" transform="translate(11.000000, 18.000000) scale(1, -1) rotate(90.000000) translate(-11.000000, -18.000000) " x="10" y="12" width="2" height="12" rx="1" />
                                                                <path d="M18.7928932,15.7928932 C19.1834175,15.4023689 19.8165825,15.4023689 20.2071068,15.7928932 C20.5976311,16.1834175 20.5976311,16.8165825 20.2071068,17.2071068 L17.2071068,20.2071068 C16.8165825,20.5976311 16.1834175,20.5976311 15.7928932,20.2071068 L12.7928932,17.2071068 C12.4023689,16.8165825 12.4023689,16.1834175 12.7928932,15.7928932 C13.1834175,15.4023689 13.8165825,15.4023689 14.2071068,15.7928932 L16.5,18.0857864 L18.7928932,15.7928932 Z" fill="#000000" fill-rule="nonzero" transform="translate(16.500000, 18.000000) scale(1, -1) rotate(270.000000) translate(-16.500000, -18.000000) " />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </button>
                                                <button type="button" class="btn btn-icon btn-sm btn-color-dark" onclick="removeImage('{{$image['id']}}')">
                                                    <!--begin::Svg Icon | path: icons/duotune/general/gen027.svg-->
                                                    <span class="svg-icon svg-icon-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="black" />
                                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="black" />
                                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="black" />
                                                        </svg>
                                                    </span>
                                                    <!--end::Svg Icon-->
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <!--end::Table body-->
                                </table>
                                <!--end::Table-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--End::Cashier Card-->
            </div>
            <!--end::Card-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<script>
    var merchantBranches = new Array();
    var merchant = "{{$offer_data['merchant_id']}}";
    LoadPreMerchantBranches(merchant);

    $(document).ready(function() {
        $("#offerNav").addClass('active');
        $("#offer-edit").addClass('active');
        $('input').attr('autocomplete', 'off');

        var createOfferForm = $('#offer-form');
        createOfferForm.validate({
            rules: {
                offer_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                offer_name_arabic: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                offer_description: {
                    required: true,
                },
                offer_description_arabic: {
                    required: true,
                },
                offer_desc_description: {
                    required: true,
                },
                offer_desc_description_arabic: {
                    required: true,
                },
                offer_discount: {
                    required: true,
                    number: true,
                    max: 100,
                },
                offer_from: {
                    required: true,
                    date: true,
                },
                offer_to: {
                    required: true,
                    date: true,
                },
                'offer_categories[]': {
                    required: true,
                },
                'offer_branches[]': {
                    required: true,
                },
                'offer_branches_count[]': {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $('input[type=radio][name=offer_type]').change(function() {
            if (this.value == '1') {
                $('#coupon_type_div').show();
            } else if (this.value == '2') {
                $('#coupon_type_div').hide();
            }
            $('#coupons_csv_div').hide();
            $('#code_generation_div').hide();
            $('#offer_price_div').hide();
        });
        $('input[type=radio][name=offer_coupon_type]').change(function() {
            if (this.value == '2') {
                $('#code_generation_div').show();
                $('#offer_price_div').show();
            } else if (this.value == '1') {
                $('#code_generation_div').hide();
                $('#offer_price_div').hide();
            }
            $('#coupons_csv_div').hide();
        });
        $('input[type=radio][name=offer_code_generation]').change(function() {
            if (this.value == '1') {
                $('#coupons_csv_div').show();
            } else if (this.value == '2') {
                $('#coupons_csv_div').hide();
            }
        });
        $('input[type=file][id=offer_image_link]').change(function() {
            var fileUpload = $("input[type=file][id=offer_image_link");
            if (parseInt(fileUpload.get(0).files.length) > 5) {
                fileUpload.val('');
                alert("You can only upload a maximum of 5 files");
            }
        });

        $("#categories_checkbox").click(function() {
            if ($("#categories_checkbox").is(':checked')) {
                $("#offer_categories > option").prop("selected", "selected");
                $("#offer_categories").trigger("change");
            } else {
                $("#offer_categories > option").removeAttr("selected");
                $("#offer_categories").trigger("change");
            }
        });

        $("#branches_checkbox").click(function() {
            if ($("#branches_checkbox").is(':checked')) {
                $("#offer_branches > option").prop("selected", "selected");
                $("#offer_branches").trigger("change");
            } else {
                $("#offer_branches > option").removeAttr("selected");
                $("#offer_branches").trigger("change");
            }
        });

        $("#add_row").unbind('click').bind('click', function() {
            var count_table_tbody_tr = $("#branches_table tbody tr").length;
            let offer_branches_val = $("#offer_branches_" + count_table_tbody_tr).val();
            if (count_table_tbody_tr >= 1 && offer_branches_val != 0 && offer_branches_val != null) {
                var row_id = count_table_tbody_tr + 1;
                $("#text-danger_" + count_table_tbody_tr).text(" ");
                var html = '<tr id="row_' + row_id + '">';
                html +=
                    '<td>' +
                    '<span class="text-danger" id="text-danger_' + row_id + '"></span>' +
                    '<select class="form-control offer_branches" data-row-id="offer_branches_' + row_id + '" id="offer_branches_' + row_id + '" name="offer_branches[]" data-control="select2">' +
                    '<option selected disabled value="0">--Please Select--</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" data-row-id="offer_branches_count_' + row_id + '" id="offer_branches_count_' + row_id + '" name="offer_branches_count[]" class="form-control" autocomplete="off" value="0" min="0" onchange="countTotalPromo()">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id + '\')" id="remove_row_\'' + row_id + '\'" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#branches_table tbody tr:last").after(html);
                } else {
                    $("#branches_table tbody").html(html);
                }
                var offer_branches = $('#offer_branches_' + row_id).empty();
                offer_branches.append('<option selected disabled value="0">--Please Select--</option>');
                merchantBranches.forEach(element => {
                    offer_branches.append('<option value=' + element.id + '>' + element.branch_name + '</option>');
                });
            } else if (count_table_tbody_tr < 1) {
                $("#text-danger_" + count_table_tbody_tr).text(" ");
                var row_id = count_table_tbody_tr + 1;
                var html = '<tr id="row_' + row_id + '">';
                html +=
                    '<td>' +
                    '<span class="text-danger" id="text-danger_' + row_id + '"></span>' +
                    '<select class="form-control offer_branches" data-row-id="offer_branches_' + row_id + '" id="offer_branches_' + row_id + '" name="offer_branches[]" data-control="select2">' +
                    '<option selected disabled value="0">--Please Select--</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" data-row-id="offer_branches_count_' + row_id + '" id="offer_branches_count_' + row_id + '" name="offer_branches_count[]" class="form-control" autocomplete="off" onchange="countTotalPromo()">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id + '\')" id="remove_row_\'' + row_id + '\'" class="btn btn-outline-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#branches_table tbody tr:last").after(html);
                } else {
                    $("#branches_table tbody").html(html);
                }
                var offer_branches = $('#offer_branches_' + row_id).empty();
                offer_branches.append('<option selected disabled value="0">--Please Select--</option>');
                merchantBranches.forEach(element => {
                    offer_branches.append('<option value=' + element.id + '>' + element.branch_name + '</option>');
                });
            } else if (offer_branches_val == "" || offer_branches_val == null || offer_branches_val == 0) {
                $("#text-danger_" + count_table_tbody_tr).text("Please select an option first");
            }
            countTotalPromo();
        });
    });

    function LoadPreMerchantBranches(val) {
        if (val != 0) {
            var url = '{{ route("merchant-branches.branches", ":id") }}';
            url = url.replace(':id', val);
            $.ajax({
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                dataType: "json",
                url: url,
                type: "GET",
                success: function(response) {
                    let x = 0;
                    merchantBranches = [];
                    response.forEach(element => {
                        merchantBranches[x] = {
                            'id': element.id,
                            'branch_name': element.branch_name
                        };
                        x++;
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log('Error');
                }
            });
        }
    }

    function removeImage(id) {
        if (id) {
            console.log(id);
            Swal.fire({
                text: "Are you sure you want to Delete?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, Delete!",
                cancelButtonText: "No, Cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var url = '{{ route("merchant-offers.destroy-image", ":id") }}';
                    url = url.replace(':id', id);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: url,
                        type: "GET",
                        success: function(response) {
                            window.location.reload();
                            Swal.fire("Done!", "Deleted Successfully.", "success");
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire("Error Deleting!", "Please, Try Again", "error");
                        }
                    });
                }
            });
        }
    }

    function updateOrder(id) {
        if (id) {
            Swal.fire({
                text: "Are you sure you want to Update?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, Update!",
                cancelButtonText: "No, Cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var order = $('#order_' + id).val();
                    if (order && order >= 1 && order <= 5) {
                        var url = '{{ route("offers.update-image-order") }}';
                        $.ajax({
                            headers: {
                                "X-CSRF-Token": "{{ csrf_token() }}",
                            },
                            url: url,
                            type: "POST",
                            data: {
                                'id': id,
                                'order': order,
                            },
                            success: function(response) {
                                if (response.data) {
                                    $("#offers_gallery_table").load(window.location + " #offers_gallery_table");
                                    Swal.fire("Done!", "Updated Successfully.", "success");
                                } else {
                                    Swal.fire("Error Updating!", "Parameters Missing", "error");
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                Swal.fire("Error Updating!", "Please, Try Again", "error");
                            }
                        });
                    } else {
                        Swal.fire("Error Updating!", "Please, Enter Order Number between 1 to 5", "error");
                    }
                }
            });
        }
    }

    function removeRow(tr_id) {
        $("#branches_table tbody tr#row_" + tr_id).remove();
        countTotalPromo();
    }

    function countTotalPromo() {
        var total_count_allow = parseInt($("#offer_coupons").val());
        var count_table_tbody_tr = parseInt($("#branches_table tbody tr").length);
        branch_count = 0;
        for (let index = 1; index <= count_table_tbody_tr; index++) {
            branch_count += parseInt($('#offer_branches_count_' + index).val());
        }
        if (branch_count > total_count_allow) {
            $("#text-count-danger").text("Coupons count exceed total coupons value.");
            $(':input[type="submit"]').prop('disabled', true);
        } else {
            $("#text-count-danger").text("");
            $(':input[type="submit"]').prop('disabled', false);
        }
    }
</script>
@endsection