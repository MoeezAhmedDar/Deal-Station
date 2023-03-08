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

                        <!--begin::Description-->

                        <!--end::Description-->
                    </div>
                    <!--end::Content-->
                    <!--begin::Illustration-->
                    <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                        style="background-image: url('/admin/assets/media/illustrations/sketchy-1/13.png')"></div>
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
                        <div style="float: right;">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary w-100 mb-5 dropdown-toggle" type="button"
                                    data-toggle="dropdown">
                                    <img src="{{ asset('admin/assets/media/svg/shapes/language_black.svg') }}"
                                        alt="Lang Switcher" />
                                </button>
                                <ul class="dropdown-menu">
                                    @foreach ($available_locales as $locale_name => $available_locale)
                                        <li>
                                            <a href="{{ route('lang', $available_locale) }}">{{ __($locale_name) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="text-center auth-head">
                            <div class="auth-head-logo">
                                <!--begin::Logo-->
                                <a href="{{ route('dashboard') }}">
                                    <img alt="Logo"
                                        src="{{ asset('admin/assets/media/logos/deal-station-logo-black.png') }}"
                                        class="h-50px" />
                                </a>
                                <!--end::Logo-->
                            </div>
                            <div class="m-5 auth-head-title">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">{{ __('Log in to Deal Station') }}</h1>
                                <!--end::Title-->
                            </div>
                        </div>
                        <!--begin::Heading-->
                        <div class="row">
                            <div class="col-12">
                                @if (count($errors) > 0)
                                    <div class="alert alert-danger">
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="POST"
                            action="{{ route('login') }}">
                            @csrf
                            <!--begin::Input group-->
                            <div class="fv-row mb-5">
                                <!--begin::Label-->
                                <label class="form-label fs-6 fw-bolder text-dark">{{ __('Email Address') }}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input id="email" type="email"
                                    class="form-control form-control-lg form-control-solid @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email"
                                    placeholder="{{ __('Enter Your Email') }}" autofocus />
                                <!--end::Input-->
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
                                <input
                                    class="form-control form-control-lg form-control-solid @error('password') is-invalid @enderror"
                                    type="password" id="password" name="password"
                                    placeholder="{{ __('Enter Your Password') }}" autocomplete="off" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-5">
                                <!--begin::Input-->
                                <!-- <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}> -->
                                <!--end::Input-->
                                <!--begin::Label-->
                                <label class="form-label fw-bolder text-dark fs-6 mb-0" for="remember">
                                    <!-- {{ __('Remember Me') }} -->

                                </label>
                                <!--end::Label-->
                                <!--begin::Link-->
                                @if (Route::has('password.request'))
                                    <a class="link-dark fs-6 fw-bolder" href="{{ route('password.request') }}"
                                        style="float:right">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                                <!--end::Link-->
                            </div>
                            <!--end::Input group-->
                            <div class="text-center">
                                <!--begin::Submit button-->
                                <button type="submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    {{ __('Login') }}
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
