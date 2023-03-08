@extends('admin.layouts.app')
@section('page_title', $page_title)
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                <!--begin::Card-->
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
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            {{-- {{ __('Member Management') }} --}}
                        </div>
                        <!--End::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">

                                <!--begin::Add user-->
                                <button class="btn btn-primary" onclick="exportToPDF()"
                                    style="margin-right: 10px;">{{ __('Download Report') }}</button>
                                <!--end::Add user-->

                                @can('admin-member-create')
                                    <!--begin::Add user-->
                                    <a href="{{ route('members.create') }}" class="btn btn-primary">
                                        {{ __('Add Members') }}
                                    </a>
                                    <!--end::Add user-->
                                @endcan
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="members_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-dark fw-bolder fs-7 text-uppercase gs-0">
                                    <th> {{ __('Name') }}</th>
                                    <th> {{ __('Email') }}</th>
                                    <th> {{ __('Phone') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th style="width: 20% !important;"> {{ __('Action') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                        </table>
                        <!--end::Table-->
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
        let membersTable;
        $(document).ready(function() {
            $("#auNav").addClass('show');
            $("#memberNav").addClass('active');
            loadTableData();

            $(document).on('click', '.changeStatus', function(e) {
                e.preventDefault();
                let id = $(this).attr("href");
                let status = $(this).text();
                const currentRow = $(this).closest("tr");

                let url = "{{ route('change-status') }}";
                $.ajax({
                    headers: {
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    },
                    url: url,
                    type: "POST",
                    data: {
                        id: id,
                        status: status,
                    },
                    success: function(response) {
                        currentRow.find(".changeStatus").text(response);
                        Swal.fire("Status Updated!", "Status Updated Successfully", "success");

                    },
                    error: function(xhr, ajaxOptions, thrownError) {}
                });
            });
        });

        function loadTableData() {
            membersTable = $('#members_table').DataTable({
                'ajax': {
                    headers: {
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    },
                    url: "{{ route('fetch-members') }}",
                    type: "POST",
                },
                'columnDefs': [{
                    orderable: false,
                    targets: -1
                }],
                'language': {
                    'infoFiltered': ' - filtered from _MAX_ records',
                    'infoPostFix': '',
                    'processing': true,
                    'serverSide': true,
                    'search': "{{ __('Search') }}",
                    'next': "{{ __('Next') }}",
                    'previous': "{{ __('Previous') }}",
                },
                "aaSorting": []
            });
        }

        function removeFunc(id) {
            if (id) {
                Swal.fire({
                    text: "{{ __('Are you sure you want to delete Member ?') }}",
                    icon: "warning",
                    showCancelButton: !0,
                    buttonsStyling: !1,
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, Cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-secondary",
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url = "{{ route('members.destroy', ':id') }}";
                        url = url.replace(':id', id);
                        $.ajax({
                            headers: {
                                "X-CSRF-Token": "{{ csrf_token() }}",
                            },
                            url: url,
                            type: "DELETE",
                            success: function(response) {
                                membersTable.ajax.reload();
                                if (response.data) {
                                    Swal.fire("Done!",
                                        "{{ __('Member has been deleted successully') }}",
                                        "success");
                                } else {
                                    Swal.fire("Error Deleting!", "This member cannot be deleted",
                                        "error");
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                Swal.fire("Error Deleting!", "Error, Please Try Again", "error");
                            }
                        });
                    }
                });
            }
        }

        function exportToPDF() {
            $.ajax({
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                url: "{{ route('reports.export-pdf-members') }}",
                type: "GET",
                success: function(response) {
                    if (response.status) {
                        window.open(response.link, '_blank');
                    } else {
                        Swal.fire("Error!", "Please Try Agin", "error");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    Swal.fire("Error!", "Please Try Agin", "error");
                }
            });
        }
    </script>
@endsection
