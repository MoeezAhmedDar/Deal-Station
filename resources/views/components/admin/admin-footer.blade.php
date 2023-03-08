<style>
    @media (min-width: 1024px) {
        .align-center {
            margin-left: 400px;
        }
    }
</style>
<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <!--begin::Container-->
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1" style="align-content: center;">
            <div class="align-center">
                <span class="text-muted fw-bold me-1">{{ now()->year }} ©</span>
                <a href="{{ route('dashboard') }}" class="text-dark text-hover-dark">{{ __('Deal Station') }}</a>
                <span class="text-muted fw-bold me-1">{{ __('Version') }} 1.0</span>
            </div>
        </div>
        <!--end::Copyright-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
