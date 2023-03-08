@extends('admin.layouts.app')


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
                            {{ __('Create New User') }}
                        </div>
                        <!--End::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add user-->
                                <a href="{{ route('users.index') }}" class="btn btn-primary">{{ __('Back') }}
                                </a>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    {!! Form::open(['route' => 'users.store', 'method' => 'POST', 'class' => 'w-100 position-relative mb-3']) !!}
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Name') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('name', null, ['placeholder' => __('Name'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Phone') }}:</label>
                                <!--end::Label-->
                                {!! Form::text('phone', null, ['placeholder' => __('Phone'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('DOB') }}:</label>
                                <!--end::Label-->
                                {!! Form::date('dob', null, ['placeholder' => __('DOB'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Gender') }}:</label>
                                <!--end::Label-->
                                {!! Form::select('gender', ['Male' => 'Male', 'Female' => 'Female'], null, [
                                    'placeholder' => __('Select an Option'),
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
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('City') }}:</label>
                                <!--end::Label-->
                                <select class="form-control" name="city" id="city" data-control="select2">
                                    <option disabled selected>--{{ __('Select an Option') }}--</option>
                                    @foreach ($city_data as $city)
                                        <option value="{{ $city['id'] }}">{{ $city['city_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->

                            <!--begin::Col-->
                            <div class="col-lg-6 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Email') }}:</label>
                                <!--end::Label-->
                                {!! Form::email('email', null, ['placeholder' => __('Email'), 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->

                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label
                                        class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Password') }}</label>
                                    <!--end::Label-->
                                    {!! Form::password('password', ['placeholder' => '********', 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->

                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label
                                        class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Confirm Password') }}</label>
                                    <!--end::Label-->
                                    {!! Form::password('confirm-password', ['placeholder' => '********', 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="row">
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Role') }}</label>
                                <!--end::Label-->
                                <select class="form-control" name="roles" id="roles">
                                    <option disabled selected>--{{ __('Select an Option') }}--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->

                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
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
            $("#auNav").addClass('show');
            $("#usersNav").addClass('active');
            $("#addUserNav").addClass('active');
        });
    </script>
@endsection
