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
                        {{ __('Show Membership Plan')}}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('plans.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                {!! Form::model($plan, ['class'=>'w-100 position-relative mb-3', 'enctype'=>'multipart/form-data']) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">Plan Name:</label>
                            <!--end::Label-->
                            {!! Form::text('plan_name', null, array('placeholder' => 'Plan Name','class' => 'form-control', 'disabled')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">?????? ??????????</label>
                            <!--end::Label-->
                            {!! Form::text('plan_name_arabic', null, array('placeholder' => '?????? ??????????','class' => 'form-control', 'disabled')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('City')}}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="plan_city" id="plan_city" data-control="select2" disabled>
                                <option disabled selected>--{{ __('Select an Option')}}--</option>
                                @foreach ($city_data as $city)
                                <option value="{{$city['id']}}" @if($plan->plan_city==$city['id']){{'selected'}}@endif>{{$city['city_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Terms and Conditions')}}</label>
                            <!--end::Label-->
                            {!! Form::url('plan_terms', null, array('placeholder' => __('Terms and Conditions'),'class' => 'form-control','disabled')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6">{{ __('Description')}}</label>
                            <!--end::Label-->
                            {!! Form::textarea('plan_description', null, array('placeholder' => __('Description'), 'rows' => '2', 'class' => 'form-control' , 'disabled')) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Icon')}} ({{ __('Max 2 MB')}}):</label>
                            <!--end::Label-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-12 col-form-label">{{__('Previously Selected')}}:</label>
                                <!--end::Label-->
                                <label class="col-12 col-form-label"><img src="{{asset($plan->plan_icon)}}" alt="{{ __('Icon')}} ({{ __('Max 2 MB')}})" width="auto" height="60px !important"> </label>
                            </div>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Cashier Card-->
                <div class="card">
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            {{ __('Subscription Plan')}}
                        </div>
                        <!--End::Card title-->
                    </div>
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row" id="previous_diagnosis">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <table class="table table-bordered" id="subscription_plan_table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Select Subscription Plan')}}</th>
                                                <th style="width:35%">{{ __('Price')}} (&#65020;)</th>
                                                <th style="width:5%"><button disabled type="button" id="add_row" class="btn btn-dark"><i class="fas fa-plus"></i></button></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $x = 1; ?>
                                            @foreach ($plan_subscriptions as $list_item)
                                            <tr id="row_<?php echo $x ?>">
                                                <td>
                                                    <span class="text-danger" id="text-danger_<?php echo $x ?>"></span>
                                                    <select disabled class="form-control" data-row-id="plan_subscription_<?php echo $x ?>" id="plan_subscription_<?php echo $x ?>" name="plan_subscription[]">
                                                        <option selected disabled value="0">--{{ __('Select an Option')}}--</option>
                                                        @foreach ($subscription_plans as $subscription_plan)
                                                        <option value="{{$subscription_plan['id']}}" @if($list_item->subscription_id==$subscription_plan['id']){{'selected'}}@endif>{{$subscription_plan['subscription_name']}} - ({{$subscription_plan['subscription_duration']}} Months)</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input disabled type="number" step="0.001" min="0" name="plan_subscription_price[]" id="plan_subscription_price_<?php echo $x ?>" value="{{$list_item->subscription_price}}" class="form-control" autocomplete="off" placeholder="{{ __('Price')}}">
                                                </td>
                                                <td><button disabled type="button" onclick="removeRow(<?php echo $x ?>)" id="remove_row" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>
                                            </tr>
                                            <?php $x++; ?>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--End::Cashier Card-->
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
    $(document).ready(function() {
        $("#spNav").addClass('show');
        $("#planNav").addClass('active');
        $('input').attr('autocomplete', 'off');
        $("#add_row").click(function(e) {
            e.preventDefault();
            var count_table_tbody_tr = $("#subscription_plan_table tbody tr").length;
            let plan_subscription_val = $("#plan_subscription_" + count_table_tbody_tr).val();
            if (count_table_tbody_tr >= 1 && plan_subscription_val != 0 && plan_subscription_val != null) {
                var row_id = count_table_tbody_tr + 1;
                $("#text-danger_" + count_table_tbody_tr).text(" ");
                var html = '<tr id="row_' + row_id + '">';
                html +=
                    '<td>' +
                    '<span class="text-danger" id="text-danger_' + row_id + '"></span>' +
                    '<select class="form-control" id="plan_subscription_' + row_id + '" name="plan_subscription[]">' +
                    '<option selected disabled value="0">--{{ __("Select an Option")}}--</option>' +
                    ' @foreach ($subscription_plans as $subscription_plan)' +
                    '<option value="{{$subscription_plan["id"]}}">{{$subscription_plan["subscription_name"]}} - ({{$subscription_plan["subscription_duration"]}} Months)</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    ' <input type="number" step="0.001" min="0" name="plan_subscription_price[]" id="plan_subscription_price_' + row_id + '" class="form-control" placeholder="{{ __("Price")}}" autocomplete="off">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id + '\')" id="remove_row_\'' + row_id + '\'" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#subscription_plan_table tbody tr:last").after(html);
                } else {
                    $("#subscription_plan_table tbody").html(html);
                }
            } else if (count_table_tbody_tr < 1) {
                $("#text-danger_" + count_table_tbody_tr).text(" ");
                var row_id = count_table_tbody_tr + 1;
                var html = '<tr id="row_' + row_id + '">';
                html +=
                    '<td>' +
                    '<span class="text-danger" id="text-danger_' + row_id + '"></span>' +
                    '<select class="form-control" id="plan_subscription_' + row_id + '" name="plan_subscription[]" onchange="getDiagnosisValue(' + row_id + ')">' +
                    '<option selected disabled value="0">--{{ __("Select an Option")}}--</option>' +
                    ' @foreach ($subscription_plans as $subscription_plan)' +
                    '<option value="{{$subscription_plan["id"]}}">{{$subscription_plan["subscription_name"]}} - ({{$subscription_plan["subscription_duration"]}} Months)</option>' +
                    '@endforeach' +
                    '</select>' +
                    '</td>' +
                    '<td>' +
                    ' <input type="number" step="0.001" min="0" name="plan_subscription_price[]" id="plan_subscription_price_' + row_id + '" class="form-control" placeholder="{{ __("Price")}}" autocomplete="off">' +
                    '</td>' +
                    '<td><button type="button" onclick="removeRow(\'' + row_id + '\')" id="remove_row_\'' + row_id + '\'" class="btn btn-dark"><i class="fas fa-minus-circle"></i></button></td>' +
                    '</tr>';
                if (count_table_tbody_tr >= 1) {
                    $("#subscription_plan_table tbody tr:last").after(html);
                } else {
                    $("#subscription_plan_table tbody").html(html);
                }
            } else if (plan_subscription_val == "" || plan_subscription_val == null || plan_subscription_val == 0) {
                $("#text-danger_" + count_table_tbody_tr).text("Please select an option first");
            }
        });
    });

    function removeRow(tr_id) {
        $("#subscription_plan_table tbody tr#row_" + tr_id).remove();
    }
</script>
@endsection