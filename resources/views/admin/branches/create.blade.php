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
                        {{ __('Create New Branch') }}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('branches.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                {!! Form::open([
                'route' => 'branches.store',
                'method' => 'POST',
                'class' => 'w-100 position-relative mb-3',
                'id' => 'branch-form',
                'enctype' => 'multipart/form-data',
                ]) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    @can('admin-branch-list')
                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="merchant_id" id="merchant_id" data-control="select2">
                                <option disabled selected>--{{ __('Select an Option') }}--</option>
                                @foreach ($merchants_data as $merchant)
                                <option value="{{ $merchant['id'] }}">{{ $merchant['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Branch Status') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="branch_status" id="branch_status">
                                <option disabled selected>--{{ __('Select an Option') }}--</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endcan

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Branch Name in English') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('branch_name', null, ['placeholder' => __('Branch Name in English'), 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Branch Name in Arabic') }}</label>
                            <!--end::Label-->
                            {!! Form::text('branch_name_arabic', null, ['placeholder' => __('Branch Name in Arabic'), 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Address') }}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            {!! Form::text('branch_building_address', null, ['placeholder' => 'Building No. 2', 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            {!! Form::text('branch_str_address', null, ['placeholder' => 'St # 4', 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label fw-bold fs-6"></label>
                            <!--end::Label-->
                            {!! Form::text('branch_com_address', null, ['placeholder' => 'Al Jomaih Bldg', 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Latitude') }}
                                :</label>
                            <!--end::Label-->
                            {!! Form::text('branch_latitude', null, ['placeholder' => __('Latitude'), 'class' => 'form-control']) !!}
                        </div>
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Longitude') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('branch_longitude', null, ['placeholder' => __('Longitude'), 'class' => 'form-control']) !!}
                        </div>
                        <!--begin::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('City') }}:</label>
                            <!--end::Label-->
                            <select class="form-control" name="branch_city" id="branch_city" data-control="select2">
                                <option disabled selected>--{{ __('Select an Option') }}--</option>
                                @foreach ($city_data as $city)
                                <option value="{{ $city['id'] }}">{{ $city['city_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!--end::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Phone Number') }}</label>
                            <!--end::Label-->
                            {!! Form::text('branch_phone', null, ['placeholder' => __('Phone Number'), 'class' => 'form-control']) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Storefront Image') }}
                                ({{ __('Max 2 MB') }}):</label>
                            <!--end::Label-->
                            <div class="col-lg-12 ">
                                <input class="col-lg-6 nova-file-selector" type="file" name="branch_image" id="branch_image" accept="image/*" />
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
                            {{ __('Cashier Credentials') }}
                        </div>
                        <!--End::Card title-->
                    </div>
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Email') }}:</label>
                                <!--end::Label-->
                                {!! Form::email('email', null, ['placeholder' => __('Email'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Password') }}:</label>
                                <!--end::Label-->
                                {!! Form::password('password', ['placeholder' => __('Password'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Confirm Password') }}:</label>
                                <!--end::Label-->
                                {!! Form::password('confirm-password', ['placeholder' => __('Confirm Password'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                </div>
                <!--End::Cashier Card-->
                <!--begin::Card footer-->
                <div class="card-footer d-flex justify-content-start py-6 px-9">
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
    $(document).ready(function() {
        $("#moNav").addClass('show');
        $("#branchNav").addClass('active');
        $('input').attr('autocomplete', 'off');

        var createBranchForm = $("#branch-form");

        createBranchForm.validate({
            rules: {
                branch_name: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                branch_status: {
                    required: true,
                },
                branch_name_arabic: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                branch_longitude: {
                    required: true,
                    maxlength: 255
                },
                branch_latitude: {
                    required: true,
                    maxlength: 255
                },
                branch_phone: {
                    required: true,
                    minlength: 8,
                    maxlength: 15,
                },
                branch_city: {
                    required: true,
                },
                merchant_id: {
                    required: true,
                },
                branch_building_address: {
                    required: true,
                    maxlength: 255
                },
                branch_str_address: {
                    required: true,
                    maxlength: 255
                },
                branch_com_address: {
                    required: true,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                    maxlength: 20
                },
                'confirm-password': {
                    required: true,
                    minlength: 8,
                    maxlength: 20
                },
                branch_image: {
                    required: true,
                    extension: "jpeg,jpg,png,tif,lzw",

                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection