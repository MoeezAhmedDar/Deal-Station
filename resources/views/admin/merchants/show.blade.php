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
                        {{ __('Show Merchant') }}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('merchants.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label">{{ __('Brand Logo') }} ({{ __('Max 2 MB') }}):</label>
                            <!--end::Label-->
                            <label class="col-12 col-form-label"><img src="{{ asset($merchant_details->merchant_logo) }}" alt="Brand Logo" width="auto" height="60px !important"> </label>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Brand Name in English')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_brand', $merchant_details->merchant_brand, [
                            'placeholder' => __('Brand Name in English'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Brand Name in Arabic')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_brand_arabic', $merchant_details->merchant_brand_arabic, [
                            'placeholder' => __('Brand Name in Arabic'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
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
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Merchant Legal Name') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('name', $merchant->name, [
                            'placeholder' => __('Brand Name in English'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Gov Reg. ID') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_gov_id', $merchant_details->merchant_gov_id, [
                            'placeholder' => __('Gov Reg. ID'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
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
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Merchant Website') }}:</label>
                            <!--end::Label-->
                            {!! Form::Url('merchant_website', $merchant_details->merchant_website, [
                            'placeholder' => __('Merchant Website'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('IBAN') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_iban', $merchant_details->merchant_iban, [
                            'placeholder' => __('IBAN'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Business Owner in English') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('business_owner', $merchant_details->business_owner, [
                            'placeholder' => __('Business Owner in English'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Business Owner in Arabic') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('arabic_business_owner', $merchant_details->arabic_business_owner, [
                            'placeholder' => __('Business Owner in Arabic'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Phone Number') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_number', $merchant_details->merchant_number, [
                            'placeholder' => __('Phone Number'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Contact Person Name in English') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_contact_person', $merchant_details->merchant_contact_person, [
                            'placeholder' => __('Contact Person Name in English'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('الشخص الذي يمكن ال') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('arabic_contact_person_name', $merchant_details->arabic_contact_person_name, [
                            'placeholder' => 'الشخص الذي يمكن ال',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-4 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Contact Person Number') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_contact_number', $merchant_details->merchant_contact_number, [
                            'placeholder' => __('Phone Number'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Label-->
                        <label class="col-12 col-form-label fw-bold fs-6">{{ __('Address') }}:</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            {!! Form::text('merchant_building_address', $merchant_details->merchant_building_address, [
                            'placeholder' => 'Building No. 2',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            {!! Form::text('merchant_str_address', $merchant_details->merchant_str_address, [
                            'placeholder' => 'St # 4',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6"></label>
                            <!--end::Label-->
                            {!! Form::text('merchant_com_address', $merchant_details->merchant_com_address, [
                            'placeholder' => 'Al Jomaih Bldg',
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Commercial Activity') }}:</label>
                            <!--end::Label-->
                            {!! Form::textarea('merchant_commercial_activity', $merchant_details->merchant_commercial_activity, [
                            'placeholder' => __('Commercial Activity'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            'rows' => 2,
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Merchant Tax Number') }}:</label>
                            <!--end::Label-->
                            {!! Form::text('merchant_tax_number', $merchant_details->merchant_tax_number, [
                            'placeholder' => __('Merchant Tax Number'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--begin::Col-->

                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Merchant Email') }}:</label>
                            <!--end::Label-->
                            {!! Form::email('email', $merchant->email, [
                            'placeholder' => __('Merchant Email'),
                            'class' => 'form-control',
                            'readonly' => 'readonly',
                            ]) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Status') }}</label>
                            <!--end::Label-->
                            {!! Form::select('merchant_status', ['1' => 'Active', '2' => 'Inactive'], $merchant_details->merchant_status, [
                            'class' => 'form-control',
                            'placeholder' => __('Select an Option'),
                            'disabled' => 'disabled',
                            ]) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label">{{ __('Merchant Reg. Gov') }}
                                ({{ __('Max 2 MB') }}):</label>
                            <!--end::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6"><a target="_blank" class="btn btn-info px-4" href="{{ url($merchant_details->merchant_gov_letter) }}">{{ __('View File') }}</a></label>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->

                    <!--begin::Input group-->
                    <div class="row">
                        <div class="col-lg-12 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label">{{ __('Tax Registration Letter') }}
                                ({{ __('Max 2 MB') }}):</label>
                            <!--end::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6"><a target="_blank" class="btn btn-info px-4" href="{{ url($merchant_details->merchant_tax_letter) }}">{{ __('View File') }}</a></label>
                        </div>
                        <!--end::Col-->
                    </div>
                </div>
                <!--end::Card body-->
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
        $("#merchantNav").addClass('active');
        $('input').attr('autocomplete', 'off');
    });
</script>
@endsection