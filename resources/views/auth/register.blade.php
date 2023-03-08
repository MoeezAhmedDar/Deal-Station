@extends('layouts.auth')

@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-Up -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #F2C98A">
            <!--begin::Wrapper-->
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <!--begin::Content-->
                <div class="d-flex flex-column text-center p-10 pt-lg-20">
                    <!--begin::Logo-->
                    <a href="{{route('dashboard')}}" class="py-9 mb-5">
                        <img alt="Logo" src="{{asset('admin/assets/media/logos/deal-station-logo-black.png')}}" class="h-60px" />
                    </a>
                    <!--end::Logo-->
                    <!--begin::Title-->
                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #986923;">Welcome to Metronic</h1>
                    <!--end::Title-->
                    <!--begin::Description-->
                    <!-- <p class="fw-bold fs-2" style="color: #986923;">Discover Amazing Metronic
                        <br />with great build tools
                    </p> -->
                    <!--end::Description-->
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
                    <div class="text-center mb-10">
                        <!--begin::Title-->
                        <h1 class="text-dark mb-3">{{ __('Register') }}</h1>
                        <!--end::Title-->
                        @if (Route::has('login'))
                        <!--begin::Link-->
                        <div class="text-gray-400 fw-bold fs-4">Already have account?
                            <a href="{{ route('login') }}" class="link-primary fw-bolder">Login</a>
                        </div>
                        <!--end::Link-->
                        @endif
                    </div>
                    <!--begin::Heading-->

                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Content-->
            <!--begin::Footer-->
            <div class="d-flex flex-center flex-wrap fs-6 p-5 pb-0">
                <x-admin.admin-footer />
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-Up-->
</div>
<!--end::Main-->
@endsection