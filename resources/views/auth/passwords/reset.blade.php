@extends('layouts.auth')

@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative bg-deal-station">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <!--begin::Content-->
                <div class="d-flex flex-column text-center p-10 pt-lg-20">

                    <!--begin::Title-->
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #474C55;">{{ __('Welcome to Deal Station') }}
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Content-->
                <!--begin::Illustration-->
                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url('/admin/assets/media/illustrations/sketchy-1/13.png')"></div>
                <!--end::Illustration-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Aside-->

        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10 mx-auto">
                    <!--begin::Heading-->
                    <div class="text-center auth-head">
                        <div class="auth-head-logo">
                            <!--begin::Logo-->
                            <a href="{{ route('dashboard') }}">
                                <img alt="Logo" src="{{ asset('admin/assets/media/logos/deal-station-logo-black.png') }}" class="h-50px" />
                            </a>
                            <!--end::Logo-->
                        </div>
                        <div class="m-5 auth-head-title">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">{{ __('Reset Password') }}</h1>
                            <!--end::Title-->
                        </div>
                    </div>
                    <!--begin::Heading-->
                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Label-->
                            <label class="form-label fs-6 fw-bolder text-dark">{{ __('E-Mail Address') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input id="email" type="email" class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required placeholder="{{ __('Enter Your Email') }}" autocomplete="email" autofocus readonly />
                            <!--end::Input-->
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack mb-2">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Password') }}</label>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Input-->
                            <input class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="{{ __('Enter Your Password') }}" autocomplete="off" />
                            <!--end::Input-->
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <!--end::Input group-->

                        <!--begin::Input group-->
                        <div class="fv-row mb-5">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-stack mb-2">
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Confirm Password') }}</label>
                                <!--end::Label-->
                            </div>
                            <!--end::Wrapper-->
                            <!--begin::Input-->
                            <input type="password" class="form-control form-control-lg form-control-solid" id="password_confirmation" name="password_confirmation" required placeholder="{{ __('Enter Confirm Password')}}" autocomplete="new-password" required>
                            <!--end::Input-->
                        </div>
                        <!--end::Input group-->

                        <div class="text-center">
                            <!--begin::Submit button-->
                            <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                {{ __('Reset Password') }}
                            </button>
                            <!--end::Submit button-->
                        </div>
                    </form>
                </div>
                <!--end::Wrapper-->
                <x-admin.admin-footer />
            </div>
            <!--end::Content-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Main-->
@endsection