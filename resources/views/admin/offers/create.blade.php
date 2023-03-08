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
                        {{ __('Create New Offer') }}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('offers.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                {!! Form::open([
                'route' => 'offers.store',
                'method' => 'POST',
                'class' => 'w-100 position-relative mb-3',
                'enctype' => 'multipart/form-data',
                'id' => 'offer-form',
                ]) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in English')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name', null, ['placeholder' => __('Offer Name in English'), 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in Arabic')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name_arabic', null, ['placeholder' => __('Offer Name in Arabic'), 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in English') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description', null, [
                            'placeholder' =>__('Main Offer Description in English'),
                            'class' => 'form-control',
                            'rows' => '2',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description_arabic', null, [
                            'placeholder' => __('Main Offer Description in Arabic'),

                            'class' => 'form-control',
                            'rows' => '2',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Desc Details in English') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description', null, [
                            'placeholder' => __('Offer Desc Details in English'),
                            'class' => 'form-control',
                            'rows' => '2',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Desc Details in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description_arabic', null, [
                            'placeholder' => __('Offer Desc Details in Arabic'),
                            'class' => 'form-control',
                            'rows' => '2',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Images') }}
                                ({{ __('Max 2 MB') }}) :</label>
                            <!--end::Label-->
                            <input class="col-12 nova-file-selector" type="file" name="offer_image_link[]" id="offer_image_link" accept="image/*" multiple />
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Discount') }}
                                %:</label>
                            <!--end::Label-->
                            {!! Form::number('offer_discount', null, [
                            'placeholder' => __('Offer Discount'),
                            'class' => 'form-control',
                            'min' => '1',
                            'max' => '100',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Type') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_type" id="offer_type_1" value="1" />
                                    <span></span>
                                    {{ __('Digital Offer') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_type" value="2" />
                                    <span></span>
                                    {{ __('Branch Scannable Offer') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="coupon_type_div" style="display: none;">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Coupon Type') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="1" />
                                    <span></span>
                                    {{ __('QR') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="2" />
                                    <span></span>
                                    {{ __('Promo Code') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="code_generation_div" style="display: none;">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Code Generation') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" id="offer_code_generation_1" value="1" />
                                    <span></span>
                                    {{ __('CSV Upload Link') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" id="offer_code_generation_2" value="2" />
                                    <span></span>
                                    {{ __('Codes by DS') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row" id="coupons_csv_div" style="display: none;">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Upload CSV File') }}
                                :</label>
                            <!--end::Label-->
                            <input class="col-lg-6 nova-file-selector" type="file" name="coupons_csv" id="coupons_csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="offer_price_div" style="display: none;">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Original Price') }}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_price', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Original Price'),
                            'step' => '0.001',
                            'min' => '0',
                            'oninput' => '!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Total Coupons') }}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_coupons', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Total Coupons'),
                            'id' => 'offer_coupons',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Offer Campaign') }} :</label>
                            <!--end::Label-->
                            <select class="form-control" name="offer_campaign" id="offer_campaign" data-control="select2">
                                <option selected disabled>--{{ __('Select an Option') }}--</option>
                                @foreach ($campaigns_data as $campaign)
                                <option value="{{ $campaign['id'] }}">{{ $campaign['campaign_name'] }} -
                                    {{ $campaign['campaign_from'] }}/{{ $campaign['campaign_to'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                            <!--end::Label-->
                            {!! Form::select('offer_status', ['1' => 'Unapproved', '2' => 'Approved'], null, [
                            'class' => 'form-control',
                            'placeholder' => __('Select an Option'),
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Per User Per Duration') }}:</label>
                            <!--end::Label-->
                            {!! Form::number('offer_per_user', null, [
                            'placeholder' => __('Per User Per Duration'),
                            'class' => 'form-control',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Usage Duration') }}:</label>
                            <!--end::Label-->
                            {!! Form::select('offer_usage_duration', ['3' => 'Weekly', '1' => 'Monthly', '2' => 'Yearly'], null, [
                            'class' => 'form-control',
                            'placeholder' => __('Select an Option'),
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Categories') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="offer_categories[]" id="offer_categories" data-control="select2" multiple="multiple">
                                @foreach ($categories_data as $category)
                                <option value="{{ $category['id'] }}">{{ $category['category_name'] }}</option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <div class="checkbox-list">
                                    <br>
                                    <label class="checkbox">
                                        <input type="checkbox" id="categories_checkbox" />
                                        <span></span>
                                        {{ __('Select All Categories') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->


                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Duration of Offer') }}
                            :</label>
                        <!--end::Label-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('From') }} :</label>
                            <!--end::Label-->
                            {!! Form::date('offer_from', null, ['class' => 'form-control', 'min' => now()->toDateString()]) !!}
                        </div>
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('To') }}:</label>
                            <!--end::Label-->
                            {!! Form::date('offer_to', null, ['class' => 'form-control', 'id' => 'offer_to']) !!}
                        </div>
                        <!--begin::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Targeted Memberships') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="targeted_membership[]" id="targeted_membership" data-control="select2" multiple="multiple">
                                @foreach ($plans_data as $plan)
                                <option value="{{ $plan['id'] }}">{{ $plan['plan_name'] }}</option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <div class="checkbox-list">
                                    <br>
                                    <label class="checkbox">
                                        <input type="checkbox" id="targeted_checkbox" />
                                        <span></span>
                                        {{ __('Select All Plans') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Visible Memberships') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="visible_membership[]" id="visible_membership" data-control="select2" multiple="multiple">
                                @foreach ($plans_data as $plan)
                                <option value="{{ $plan['id'] }}">{{ $plan['plan_name'] }}</option>
                                @endforeach
                            </select>
                            <div class="form-group">
                                <div class="checkbox-list">
                                    <br>
                                    <label class="checkbox">
                                        <input type="checkbox" id="visible_checkbox" />
                                        <span></span>
                                        {{ __('Select All Plans') }}
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
                        <div class="col-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Comments') }}</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_comments', null, [
                            'placeholder' => __('Comments'),
                            'class' => 'form-control',
                            'rows' => '3',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Merchant') }}
                                :</label>
                            <!--end::Label-->
                            <select class="form-control" name="merchant_id" id="merchant_id" data-control="select2" onchange="LoadMerchantBranches(this.value)">
                                <option selected disabled>--{{ __('Select an Option') }}--</option>
                                @foreach ($merchants_data as $merchant)
                                <option value="{{ $merchant['id'] }}">{{ $merchant['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <div class="row">
                        <div class="col-12 fv-row">
                            <div class="form-group ">
                                <!--begin::Label-->
                                <label class="col-12 col-form-label fw-bold fs-6">{{ __('Branches') }}</label>
                                <!--end::Label-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="branches_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 50%;">{{ __('Select Branch') }}</th>
                                            <th>{{ __('Promo Count') }}</th>
                                            <th style="width:5%"><button type="button" id="add_row" class="btn btn-dark"><i class="fas fa-plus"></i></button></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1">
                                            <td>
                                                <span class="text-danger" id="text-danger_1"></span>
                                                <select class="form-control offer_branches" data-row-id="offer_branches_1" id="offer_branches_1" name="offer_branches[]" data-control="select2" required>
                                                    <option selected disabled value="0">
                                                        --{{ __('Please Select') }}--</option>
                                                </select>
                                            </td>
                                            <td>
                                                <span class="text-danger" id="text-count-danger"></span>
                                                <input type="number" data-row-id="offer_branches_count_1" id="offer_branches_count_1" name="offer_branches_count[]" class="form-control" autocomplete="off" required value="0" min="0" onchange="countTotalPromo()">
                                            </td>
                                            <td><button type="button" onclick="removeRow('1')" id="remove_row" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button>
                                            </td>
                                        </tr>
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
                    <button type="submit" class="btn btn-primary px-6" id="btm-submit">{{ __('Save Changes') }}</button>
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
    $(document).ready(function() {
        $("#moNav").addClass('show');
        $("#offerNav").addClass('active');
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
                'targeted_membership[]': {
                    required: true,
                },
                'visible_membership[]': {
                    required: true,
                },
                offer_status: {
                    required: true,
                },
                merchant_id: {
                    required: true,
                },
                offer_coupon_type: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_type"][value=1]').is(
                                ":checked");
                        }
                    },
                },
                offer_code_generation: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(
                                ":checked");
                        }
                    },

                },
                coupons_csv: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_code_generation"][value=1]')
                                .is(":checked");
                        }
                    },
                },
                offer_price: {
                    required: {
                        depends: function(element) {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(
                                ":checked");
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

        $('input').attr('autocomplete', 'off');
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
                    '<select class="form-control offer_branches" data-row-id="offer_branches_' +
                    row_id + '" id="offer_branches_' + row_id +
                    '" name="offer_branches[]" data-control="select2">' +
                    '<option selected disabled value="0">--Please Select--</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" data-row-id="offer_branches_count_' + row_id +
                    '" id="offer_branches_count_' + row_id +
                    '" name="offer_branches_count[]" class="form-control" autocomplete="off" value="0" min="0" onchange="countTotalPromo()">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id +
                    '\')" id="remove_row_\'' + row_id +
                    '\'" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#branches_table tbody tr:last").after(html);
                } else {
                    $("#branches_table tbody").html(html);
                }
                var offer_branches = $('#offer_branches_' + row_id).empty();
                offer_branches.append('<option selected disabled value="0">--Please Select--</option>');
                merchantBranches.forEach(element => {
                    offer_branches.append('<option value=' + element.id + '>' + element
                        .branch_name + '</option>');
                });
            } else if (count_table_tbody_tr < 1) {
                $("#text-danger_" + count_table_tbody_tr).text(" ");
                var row_id = count_table_tbody_tr + 1;
                var html = '<tr id="row_' + row_id + '">';
                html +=
                    '<td>' +
                    '<span class="text-danger" id="text-danger_' + row_id + '"></span>' +
                    '<select class="form-control offer_branches" data-row-id="offer_branches_' +
                    row_id + '" id="offer_branches_' + row_id +
                    '" name="offer_branches[]" data-control="select2">' +
                    '<option selected disabled value="0">--Please Select--</option>' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" data-row-id="offer_branches_count_' + row_id +
                    '" id="offer_branches_count_' + row_id +
                    '" name="offer_branches_count[]" class="form-control" autocomplete="off" onchange="countTotalPromo()">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id +
                    '\')" id="remove_row_\'' + row_id +
                    '\'" class="btn btn-outline-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#branches_table tbody tr:last").after(html);
                } else {
                    $("#branches_table tbody").html(html);
                }
                var offer_branches = $('#offer_branches_' + row_id).empty();
                offer_branches.append('<option selected disabled value="0">--Please Select--</option>');
                merchantBranches.forEach(element => {
                    offer_branches.append('<option value=' + element.id + '>' + element
                        .branch_name + '</option>');
                });
            } else if (offer_branches_val == "" || offer_branches_val == null || offer_branches_val ==
                0) {
                $("#text-danger_" + count_table_tbody_tr).text("Please select an option first");
            }
            countTotalPromo();
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

        $("#targeted_checkbox").click(function() {
            if ($("#targeted_checkbox").is(':checked')) {
                $("#targeted_membership > option").prop("selected", "selected");
                $("#targeted_membership").trigger("change");
            } else {
                $("#targeted_membership > option").removeAttr("selected");
                $("#targeted_membership").trigger("change");
            }
        });

        $("#visible_checkbox").click(function() {
            if ($("#visible_checkbox").is(':checked')) {
                $("#visible_membership > option").prop("selected", "selected");
                $("#visible_membership").trigger("change");
            } else {
                $("#visible_membership > option").removeAttr("selected");
                $("#visible_membership").trigger("change");
            }
        });

        $('#merchant_id').change(function() {
            $("branches_table").find("tr:gt(0)").remove();
            var count_table_tbody_tr = $("#branches_table tbody tr").length;
            let offer_branches_val = $("#offer_branches_" + count_table_tbody_tr).val();
            if (offer_branches_val == "" || offer_branches_val == null || offer_branches_val == 0) {
                $(':input[type="submit"]').prop('disabled', true);
            } else {
                $(':input[type="submit"]').prop('disabled', false);
            }
        });

        // $('input[type=date][id=offer_to]').change(function() {
        //     let fromDate = $('#offer_from').val();
        //     let toDate = $('#offer_to').val();
        //     if (Date.parse(fromDate) <= Date.parse(toDate)) {
        //         console.log('d')
        //     }

        //     console.log('dddd')
        // });
    });

    function LoadMerchantBranches(val) {
        $('#selected-merchant').val(val);
        if (val != 0) {
            var offer_branches = $("#offer_branches_1").empty();
            var url = "{{ route('branches.merchant-branches', ':id') }}";
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
                    offer_branches.append('<option selected disabled value="0">--Please Select--</option>');
                    response.forEach(element => {
                        offer_branches.append('<option value=' + element.id + '>' + element
                            .branch_name + '</option>');
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

    function removeRow(tr_id) {
        $("#branches_table tbody tr#row_" + tr_id).remove();
        countTotalPromo();
    }

    function getTime(d) {
        return new Date(d.split("-").reverse().join("-")).getTime()
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