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
                        {{ __('Show Offer') }}
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
                {!! Form::model($offer_data, ['class' => 'w-100 position-relative mb-3', 'enctype' => 'multipart/form-data']) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in English')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name', null, ['placeholder' => __('Offer Name in English'), 'class' => 'form-control', 'disabled']) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Name in Arabic')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_name_arabic', null, [
                            'placeholder' => __('Offer Name in Arabic'),
                            'class' => 'form-control',
                            'disabled',
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
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Main Offer
                                Description:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description', null, [
                            'placeholder' =>__('Main Offer Description in English'),
                            'class' => 'form-control',
                            'rows' => '2',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_description_arabic', null, [
                            'placeholder' => __('Main Offer Description in Arabic'),

                            'class' => 'form-control',
                            'rows' => '2',
                            'disabled',
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
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Offer Desc Details:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description', null, [
                            'placeholder' => __('Offer Desc Details in English'),
                            'class' => 'form-control',
                            'rows' => '2',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Main Offer Description in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_desc_description_arabic', null, [
                            'placeholder' => __('Offer Desc Details in Arabic'),

                            'class' => 'form-control',
                            'rows' => '2',
                            'disabled',
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
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Discount') }}
                                %:</label>
                            <!--end::Label-->
                            {!! Form::number('offer_discount', null, [
                            'placeholder' => __('Offer Discount'),
                            'class' => 'form-control',
                            'min' => '1',
                            'max' => '100',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Type') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" disabled name="offer_type" value="1" @if ($offer_data->offer_type == 1) checked @endif />
                                    <span></span>
                                    {{ __('Digital Offer') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" disabled name="offer_type" value="2" @if ($offer_data->offer_type == 2) checked @endif />
                                    <span></span>
                                    {{ __('Branch Scannable Offer') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="coupon_type_div" @if ($offer_data->offer_type == 2) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Coupon Type') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" disabled name="offer_coupon_type" value="1" @if ($offer_data->offer_coupon_type == 1) checked @endif />
                                    <span></span>
                                    {{ __('QR') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" disabled name="offer_coupon_type" value="2" @if ($offer_data->offer_coupon_type == 2) checked @endif />
                                    <span></span>
                                    {{ __('Promo Code') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="code_generation_div" @if ($offer_data->offer_coupon_type == 1 || $offer_data->offer_coupon_type == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Code Generation') }}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" disabled name="offer_code_generation" value="1" @if ($offer_data->offer_code_generation == 1) checked @endif />
                                    <span></span>
                                    {{ __('CSV Upload Link') }}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" disabled name="offer_code_generation" value="2" @if ($offer_data->offer_code_generation == 2) checked @endif />
                                    <span></span>
                                    {{ __('Codes by DS') }}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row" id="coupons_csv_div" @if ($offer_data->offer_code_generation == 2 || $offer_data->offer_code_generation == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Upload CSV File') }}
                                :</label>
                            <!--end::Label-->
                            <br>
                            <input class="col-lg-6 nova-file-selector" disabled type="file" name="coupons_csv" id="coupons_csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row" id="offer_price_div" @if ($offer_data->offer_coupon_type == 1) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Original Price') }}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_price', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Original Price'),
                            'step' => '0.001',
                            'min' => '0',
                            'oninput' => '!!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Total Coupons') }}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_coupons', null, [
                            'class' => 'form-control',
                            'placeholder' => __('Total Coupons'),
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Offer Campaign') }} :</label>
                            <!--end::Label-->
                            <select class="form-control" name="offer_campaign" id="offer_campaign" data-control="select2" disabled>
                                <option selected disabled>--{{ __('Select an Option') }}--</option>
                                @foreach ($campaigns_data as $campaign)
                                <option value="{{ $campaign['id'] }}" @if ($campaign['id']==$offer_data['offer_campaign']) selected @endif>
                                    {{ $campaign['campaign_name'] }} -
                                    {{ $campaign['campaign_from'] }}/{{ $campaign['campaign_to'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                            <!--end::Label-->
                            {!! Form::select('offer_status', ['1' => 'Unapproved', '2' => 'Approved'], null, [
                            'class' => 'form-control',
                            'placeholder' => __('Select an Option'),
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Per User Per Duration') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_per_user', null, [
                            'placeholder' => __('Per User Per Duration'),
                            'class' => 'form-control',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Categories') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="offer_categories[]" id="offer_categories" data-control="select2" multiple="multiple" disabled>
                                @foreach ($categories_data as $category)
                                <option value="{{ $category['id'] }}" @foreach ($offer_categories as $categories) @if ($category['id']==$categories['category']) selected @endif @endforeach>{{ $category['category_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->

                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Duration of Offer') }}
                            :</label>
                        <!--end::Label-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('From') }}
                                :</label>
                            <!--end::Label-->
                            {!! Form::date('offer_from', null, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('To') }}:</label>
                            <!--end::Label-->
                            {!! Form::date('offer_to', null, ['class' => 'form-control', 'disabled']) !!}
                        </div>
                        <!--begin::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">

                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Targeted Memberships') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="targeted_membership[]" id="targeted_membership" data-control="select2" multiple="multiple" disabled>
                                @foreach ($plans_data as $plan)
                                <option value="{{ $plan['id'] }}" @foreach ($targeted_plans as $plans) @if ($plan['id']==$plans['plan']) selected @endif @endforeach>{{ $plan['plan_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Visible Memberships') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="visible_membership[]" id="visible_membership" data-control="select2" multiple="multiple" disabled>
                                @foreach ($plans_data as $plan)
                                <option value="{{ $plan['id'] }}" @foreach ($visible_plans as $plans) @if ($plan['id']==$plans['plan']) selected @endif @endforeach>{{ $plan['plan_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Comments') }}</label>
                            <!--end::Label-->
                            {!! Form::textarea('offer_comments', null, [
                            'placeholder' => __('Comments'),
                            'class' => 'form-control',
                            'rows' => '3',
                            'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->


                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Offer Merchant') }}
                                :</label>
                            <!--end::Label-->
                            <select class="form-control" name="merchant_id" id="merchant_id" data-control="select2" onchange="LoadMerchantBranches(this.value)" disabled>
                                <option selected disabled>--{{ __('Select an Option') }}--</option>
                                @foreach ($merchants_data as $merchant)
                                <option value="{{ $merchant['id'] }}" @if ($merchant['id']==$offer_data['merchant_id']) selected @endif>{{ $merchant['name'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

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
                                        <?php $x = 1; ?>
                                        @foreach ($offer_branches as $branches)
                                        <tr id="row_<?php echo $x; ?>">
                                            <td>
                                                <span class="text-danger" id="text-danger_<?php echo $x; ?>"></span>
                                                <select class="form-control offer_branches" data-row-id="offer_branches_<?php echo $x; ?>" id="offer_branches_<?php echo $x; ?>" name="offer_branches[]" data-control="select2" required>
                                                    <option selected disabled value="0">--Please Select--
                                                    </option>
                                                    @foreach ($branches_data as $branch)
                                                    <option value="{{ $branch['id'] }}" @if ($branch['id']==$branches->branch) selected @endif>
                                                        {{ $branch['branch_name'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <span class="text-danger" id="text-count-danger"></span>
                                                <input type="number" data-row-id="offer_branches_count_<?php echo $x; ?>" id="offer_branches_count_<?php echo $x; ?>" name="offer_branches_count[]" class="form-control" autocomplete="off" required value="{{ $branches->coupons }}" min="0" onchange="countTotalPromo()">
                                            </td>
                                            <td><button type="button" onclick="removeRow('1')" id="remove_row" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>
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
                {!! Form::close() !!}

                <!--begin::Cashier Card-->
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            {{ __('Offer Gallery') }}
                        </div>
                        <!--End::Card title-->
                    </div>
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-12 fv-row">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="offers_table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-dark fw-bolder fs-7 text-uppercase gs-0">
                                            <th>{{ __('Image') }}</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <!--begin::Table body-->
                                    <tbody class="text-dark-600 fw-bold">
                                        @foreach ($gallery_data as $image)
                                        <tr>
                                            <td><img src="{{ asset($image['image']) }}" alt="Gallery Image" style="height: 60px;"></td>
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
    $(document).ready(function() {
        $("#moNav").addClass('show');
        $("#offerNav").addClass('active');
        $("#branches_table").find("*").attr("disabled", "disabled");
        $('input').attr('autocomplete', 'off');
    });
</script>
@endsection