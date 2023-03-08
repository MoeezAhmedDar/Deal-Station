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
                        Show User Details
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

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <!--begin::User Info-->
                    <div class="d-flex flex-center flex-column py-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="{{asset('admin/assets/media/avatars/user.png')}}" alt="image" />
                        </div>
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <span class="fs-3 text-gray-800 fw-bolder mb-3"> {{ $user->name }}</span>
                        <!--end::Name-->
                        <!--begin::Position-->
                        <div class="mb-9">
                            @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                            <!--begin::Badge-->
                            <div class="badge badge-lg badge-light-primary d-inline">{{ $v }}</div>
                            <!--begin::Badge-->
                            @endforeach
                            @endif
                        </div>
                        <!--end::Position-->
                        <!--begin::Info-->
                        <!--begin::Info heading-->
                        <div class="fw-bolder mb-3">{{ $user->email }}</div>
                        <!--end::Info heading-->
                        <div class="d-flex flex-wrap flex-center">

                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::User Info-->
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
        $("#auNav").addClass('show');
        $("#usersNav").addClass('active');
        $("#showUserNav").addClass('active');
    });
</script>
@endsection