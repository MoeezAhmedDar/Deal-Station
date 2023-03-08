@extends('layouts.auth')

@section('content')
<style>
    .bg-body {
        height: 100vh;
    }

    .auth-head-title {
        padding: 20px;
    }
</style>
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10 mx-auto">
                    <div class="text-center auth-head">
                        <div class="auth-head-logo">
                            <!--begin::Logo-->
                            <img alt="Logo" src="{{ asset('admin/assets/media/logos/deal-station-logo-black.png') }}" class="h-50px" />
                            <!--end::Logo-->
                        </div>
                        <div class="m-5 auth-head-title">
                            <!--begin::Title-->
                            <h1 class="text-dark mb-3">{{ __('Deal Station Payment Response') }}</h1>
                            <!--end::Title-->
                        </div>
                    </div>
                    <!--begin::Heading-->
                    <div class="text-center text-dark mb-10">
                        @if(Session::has('success'))
                        <img alt="Logo" src="{{ asset('admin/assets/media/tick.png') }}" class="h-50px" />
                        <div class="text-dark mt-5">
                            <p>{{ Session::get('success') }}</p>
                        </div>
                        @elseif(Session::has('error'))
                        <img alt="Logo" src="{{ asset('admin/assets/media/error.png') }}" class="h-50px" />
                        <div class="text-dark mt-5">
                            <p>{{ Session::get('error') }}</p>
                        </div>
                        @endif
                    </div>
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