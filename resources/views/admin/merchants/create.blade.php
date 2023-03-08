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
                                <li>{{ __($error) }}</li>
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
                            {{ __('Create New Merchant') }}
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
                    {!! Form::open([
                        'route' => 'merchants.store',
                        'method' => 'POST',
                        'class' => 'w-100 position-relative mb-3',
                        'enctype' => 'multipart/form-data',
                        'id' => 'merchantBranchForm',
                    ]) !!}
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Brand Name in English') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_brand', null, [
                                    'placeholder' => __('Brand Name in English'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Brand Name in Arabic') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_brand_arabic', null, [
                                    'placeholder' => __('Brand Name in Arabic'),
                                    'class' => 'form-control',
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
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant Legal Name') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('name', null, ['placeholder' => __('Merchant Legal Name'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Gov Reg. ID') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_gov_id', null, ['placeholder' => __('Gov Reg. ID'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant Website') }}:</label>
                                <!--end::Label-->
                                {!! Form::Url('merchant_website', null, [
                                    'placeholder' => __('Merchant Website'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label  fw-bold fs-6">{{ __('IBAN') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_iban', null, ['placeholder' => __('IBAN'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Business Owner in English') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('business_owner', null, [
                                    'placeholder' => __('Business Owner in English'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Business Owner in Arabic') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('arabic_business_owner', null, [
                                    'placeholder' => __('Business Owner in Arabic'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->
                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Phone Number') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_number', null, ['placeholder' => __('Phone Number'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Contact Person Name in English') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_contact_person', null, [
                                    'placeholder' => __('Contact Person Name in English'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Contact Person Name in Arabic') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('arabic_contact_person_name', null, [
                                    'placeholder' => __('Contact Person Name in Arabic'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-4 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Contact Person Number') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_contact_number', null, [
                                    'placeholder' => __('Phone Number'),
                                    'class' => 'form-control',
                                ]) !!}
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
                                {!! Form::text('merchant_building_address', null, [
                                    'placeholder' => __('Building No. 2'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                {!! Form::text('merchant_str_address', null, ['placeholder' => __('St # 4'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label fw-bold fs-6"></label>
                                <!--end::Label-->
                                {!! Form::text('merchant_com_address', null, ['placeholder' => __('Al Jomaih Bldg'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Commercial Activity') }}:</label>
                                <!--end::Label-->
                                {!! Form::textarea('merchant_commercial_activity', null, [
                                    'placeholder' => __('Commercial Activity'),
                                    'class' => 'form-control',
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
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant Tax Number') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('merchant_tax_number', null, [
                                    'placeholder' => __('Merchant Tax Number'),
                                    'class' => 'form-control',
                                ]) !!}
                            </div>
                            <!--begin::Col-->

                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant Email') }}:</label>
                                <!--end::Label-->
                                {!! Form::email('email', null, ['placeholder' => __('Merchant Email'), 'class' => 'form-control']) !!}
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
                                {!! Form::password('password', ['placeholder' => '********', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Confirm Password') }}:</label>
                                <!--end::Label-->
                                {!! Form::password('confirm-password', ['placeholder' => '********', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Brand Logo') }}
                                    ({{ __('Max 2 MB') }}):</label>
                                <!--end::Label-->
                                <input class="col-lg-6 nova-file-selector" type="file" name="merchant_logo"
                                    id="merchant_logo" accept="image/*" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Merchant Reg. Gov') }}
                                    ({{ __('Max 2 MB') }}):
                                    (<small>{{ __('Copy of Merchant gov reg. letter. File Must be PDF or Word.') }}</small>)</label>
                                <!--end::Label-->
                                <input class="col-lg-6 nova-file-selector" type="file" name="merchant_gov_letter"
                                    id="merchant_gov_letter"
                                    accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Tax Registration Letter') }}
                                    ({{ __('Max 2 MB') }}):</label>
                                <!--end::Label-->
                                <input class="col-lg-6 nova-file-selector" type="file" name="merchant_tax_letter"
                                    id="merchant_tax_letter"
                                    accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                            </div>
                            <!--end::Col-->
                        </div>
                    </div>
                    <!--end::Card body-->

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
            $("#merchantNav").addClass('active');
            $('input').attr('autocomplete', 'off');

            var merchantBranchForm = $('#merchantBranchForm');
            merchantBranchForm.validate({
                rules: {
                    merchant_brand: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_brand_arabic: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_gov_id: {
                        required: true,
                        number: true,
                    },
                    merchant_website: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_iban: {
                        required: true,
                    },
                    merchant_number: {
                        required: true,
                    },
                    business_owner: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_contact_person: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_contact_number: {
                        required: true,
                        minlength: 8,
                        maxlength: 15
                    },
                    merchant_building_address: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_str_address: {
                        required: true,
                    },
                    merchant_tax_number: {
                        required: true,
                        number: true,
                    },
                    merchant_com_address: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    merchant_commercial_activity: {
                        required: true,
                        minlength: 3,
                    },
                    email: {
                        required: true,
                        email: true
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
                    merchant_logo: {
                        required: true,
                        extension: "jpg|jpeg|png",
                    },
                    merchant_gov_letter: {
                        required: true,
                        extension: "pdf|doc",
                    },
                    merchant_tax_letter: {
                        required: true,
                        extension: "pdf|doc",
                    },
                    arabic_business_owner: {
                        required: true,
                    },
                    arabic_contact_person_name: {
                        required: true,
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });

            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param)
            }, 'File size must be less than {0}');
        });
    </script>
@endsection
