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
                @if ($message = Session::get('success'))
                    <div class="alert alert-dismissible bg-light-success d-flex flex-column flex-sm-row w-100 p-5 mb-10">
                        <!--begin::Icon-->
                        <!--begin::Svg Icon | path: icons/duotune/art/art005.svg-->
                        <span class="svg-icon svg-icon-2hx svg-icon-success me-4 mb-5 mb-sm-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path opacity="0.3"
                                    d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z"
                                    fill="black"></path>
                                <path
                                    d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z"
                                    fill="black"></path>
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                        <!--end::Icon-->
                        <!--begin::Content-->
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h4 class="fw-bold">Success</h4>
                            <span>{{ $message }}</span>
                        </div>
                        <!--end::Content-->
                        <!--begin::Close-->
                        <button type="button"
                            class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                            data-bs-dismiss="alert">
                            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                            <span class="svg-icon svg-icon-1 svg-icon-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2"
                                        rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="black"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </button>
                        <!--end::Close-->
                    </div>
                @endif
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            {{-- {{ __('Settings') }} --}}
                        </div>
                        <!--End::Card title-->
                    </div>
                    <!--end::Card header-->
                    {!! Form::model($settings_data, [
                        'method' => 'PATCH',
                        'route' => ['settings.update', $settings_data->id],
                        'class' => 'w-100 position-relative mb-3',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">App Name:</label>
                                <!--end::Label-->
                                {!! Form::text('app_name', null, ['placeholder' => 'App Name', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">اسم التطبيق</label>
                                <!--end::Label-->
                                {!! Form::text('app_name_arabic', null, ['placeholder' => 'اسم التطبيق', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Email') }}</label>
                                <!--end::Label-->
                                {!! Form::email('app_email', null, ['placeholder' => 'demo@deal-station.com', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Phone Number') }}</label>
                                <!--end::Label-->
                                {!! Form::text('app_phone', null, ['placeholder' => __('Phone Number'), 'class' => 'form-control']) !!}
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
                                {!! Form::text('app_building_address', null, ['placeholder' => 'Building No. 2', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                {!! Form::text('app_str_address', null, ['placeholder' => 'St # 4', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>

                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label fw-bold fs-6"></label>
                                <!--end::Label-->
                                {!! Form::text('app_com_address', null, ['placeholder' => 'Al Jomaih Bldg', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Facebook') }} :</label>
                                <!--end::Label-->
                                {!! Form::url('app_facebook', null, ['placeholder' => 'facebook.com', 'class' => 'form-control']) !!}
                            </div>
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Instagram') }}:</label>
                                <!--end::Label-->
                                {!! Form::url('app_insta', null, ['placeholder' => 'instagram.com', 'class' => 'form-control']) !!}
                            </div>
                            <!--begin::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Twitter') }} :</label>
                                <!--end::Label-->
                                {!! Form::url('app_twitter', null, ['placeholder' => 'twitter.com', 'class' => 'form-control']) !!}
                            </div>
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Pinterest') }}:</label>
                                <!--end::Label-->
                                {!! Form::url('app_pinterest', null, ['placeholder' => 'pinterest.com', 'class' => 'form-control']) !!}
                            </div>
                            <!--begin::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('About') }}</label>
                                <!--end::Label-->
                                {!! Form::textarea('app_about', null, ['placeholder' => 'About', 'class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">حول:</label>
                                <!--end::Label-->
                                {!! Form::textarea('app_about_arabic', null, ['placeholder' => 'حول', 'class' => 'form-control', 'rows' => 2]) !!}
                            </div>
                            <!--begin::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Privacy Policy') }}:</label>
                                <!--end::Label-->
                                {!! Form::textarea('app_privacy', null, [
                                    'placeholder' => 'Privacy Policy',
                                    'class' => 'form-control',
                                    'rows' => 2,
                                ]) !!}
                            </div>
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">سياسة خاصة:</label>
                                <!--end::Label-->
                                {!! Form::textarea('app_privacy_arabic', null, [
                                    'placeholder' => 'سياسة خاصة',
                                    'class' => 'form-control',
                                    'rows' => 2,
                                ]) !!}
                            </div>
                            <!--begin::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Default Trial Days') }}
                                    :</label>
                                <!--end::Label-->
                                {!! Form::number('default_trial_days', null, [
                                    'placeholder' => __('Default Trial Days'),
                                    'class' => 'form-control',
                                    'min' => 1,
                                    'max' => 365,
                                ]) !!}
                            </div>
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-6 fv-row">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-12 col-form-label">{{ __('Previously Selected') }}:</label>
                                    <!--end::Label-->
                                    <label class="col-12 col-form-label"><img
                                            src="{{ asset($settings_data->app_logo_ltr) }}" alt="Store Image"
                                            width="auto" height="60px !important"> </label>
                                </div>
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Logo LTR') }}
                                    ({{ __('Max 2 MB') }}):</label>
                                <!--end::Label-->
                                <div class="col-lg-12 ">
                                    <input class="col-lg-6 nova-file-selector" type="file" name="app_logo_ltr"
                                        id="app_logo_ltr" accept="image/*" />
                                </div>
                            </div>
                            <!--end::Col-->

                            <div class="col-lg-6 fv-row">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-12 col-form-label">{{ __('Previously Selected') }}:</label>
                                    <!--end::Label-->
                                    <label class="col-12 col-form-label"><img
                                            src="{{ asset($settings_data->app_logo_rtl) }}" alt="Store Image"
                                            width="auto" height="60px !important"> </label>
                                </div>
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Logo RTL') }}
                                    ({{ __('Max 2 MB') }}):</label>
                                <!--end::Label-->
                                <div class="col-lg-12 ">
                                    <input class="col-lg-6 nova-file-selector" type="file" name="app_logo_rtl"
                                        id="app_logo_rtl" accept="image/*" />
                                </div>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
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
            $("#settingNav").addClass('active');
            $('input').attr('autocomplete', 'off');
        });
    </script>
@endsection
