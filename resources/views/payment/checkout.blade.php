@extends('layouts.auth')

@section('content')
    <style>
        .bg-body {
            height: 100vh;
        }

        .auth-head-title {
            padding: 20px;
        }

        .wpwl-control {
            height: 40px !important;
        }

        body {
            font-family: Metropolis-Regular !important;
        }
    </style>
    <script>
        var wpwlOptions = {
            locale: "en",
            style: "card"
        }
    </script>
    <script async src="https://eu-test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $checkout_data->id }}"></script>
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
                                <img alt="Logo" src="{{ asset('admin/assets/media/logos/deal-station-logo-black.png') }}"
                                    class="h-50px" />
                                <!--end::Logo-->
                            </div>
                            <div class="m-5 auth-head-title">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">{{ __('Deal Station Checkout') }}</h1>
                                <!--end::Title-->
                            </div>
                        </div>
                        <!--begin::Heading-->
                        <form action="{{ route('payment.proceed-payment', $trans_id) }}" class="paymentWidgets"
                            data-brands="VISA MASTER MADA">
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
