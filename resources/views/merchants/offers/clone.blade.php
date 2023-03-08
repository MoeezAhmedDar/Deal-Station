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
                        {{ __('Clone Offer')}}
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
                {!! Form::model($offer_data, ['method' => 'POST','route' => 'merchant-offers.store', 'class'=>'w-100 position-relative mb-3', 'enctype'=>'multipart/form-data','id'=>'offer-form']) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
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
                            <input class="col-12 nova-file-selector" type="file" name="offer_image_link[]" id="offer_image_link" accept="image/*" multiple required />
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

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Type')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_type" value="1" @if($offer_data->offer_type == 1) checked @endif />
                                    <span></span>
                                    {{ __('Digital Offer')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_type" value="2" @if($offer_data->offer_type == 2) checked @endif/>
                                    <span></span>
                                    {{ __('Branch Scannable Offer')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="coupon_type_div" @if($offer_data->offer_type == 2) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Coupon Type')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="1" @if($offer_data->offer_coupon_type == 1) checked @endif/>
                                    <span></span>
                                    {{ __('QR')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="2" @if($offer_data->offer_coupon_type == 2) checked @endif />
                                    <span></span>
                                    {{ __('Promo Code')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="code_generation_div" @if($offer_data->offer_coupon_type == 1 || $offer_data->offer_coupon_type == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Code Generation')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" value="1" @if($offer_data->offer_code_generation == 1) checked @endif />
                                    <span></span>
                                    {{ __('CSV Upload Link')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" value="2" @if($offer_data->offer_code_generation == 2) checked @endif/>
                                    <span></span>
                                    {{ __('Codes by DS')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row" id="coupons_csv_div" @if($offer_data->offer_code_generation == 2 || $offer_data->offer_code_generation == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Upload CSV File')}} :</label>
                            <!--end::Label-->
                            <br>
                            <input class="col-lg-6 nova-file-selector" type="file" name="coupons_csv" id="coupons_csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>

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
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Total Coupons')}}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_coupons', null, array('class' => 'form-control', 'placeholder'=> __('Total Coupons'))) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Per User Per Duration')}}:</label>
                            <!--end::Label-->
                            {!! Form::number('offer_per_user', null, array('placeholder' => __('Per User Per Duration'),'class' => 'form-control')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Usage Duration')}}:</label>
                            <!--end::Label-->
                            {!! Form::select('offer_usage_duration',['3' => 'Weekly','1' => 'Monthly','2' => 'Yearly'], null, array('class' => 'form-control', 'placeholder'=> __('Select an Option'))) !!}
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
                offer_type: {
                    required: true,
                    number: true,
                },
                offer_coupons: {
                    required: true,
                    number: true,
                },
                offer_from: {
                    required: true,
                    date: true,
                },
                offer_to: {
                    required: true,
                    date: true,
                },
                'offer_image_link[]': {
                    required: true,
                    extension: "jpg|jpeg|png",

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
                offer_coupon_type: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_type"][value=1]').is(":checked");
                        }
                    },
                },
                offer_code_generation: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(":checked");
                        }
                    },

                },
                coupons_csv: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_code_generation"][value=1]').is(":checked");
                        }
                    },
                },
                offer_price: {
                    required: {
                        depends: function(element) {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(":checked");
                        }
                    },
                },
                offer_usage_duration: {
                    required: {
                        depends: function(element) {
                            if ($('input[type="number"][name="offer_per_user"]').val() != '')
                                return true;
                            else
                                return false;
                        }
                    },
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