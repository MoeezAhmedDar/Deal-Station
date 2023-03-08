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
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3" d="M2 4V16C2 16.6 2.4 17 3 17H13L16.6 20.6C17.1 21.1 18 20.8 18 20V17H21C21.6 17 22 16.6 22 16V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4Z" fill="black"></path>
                        <path d="M18 9H6C5.4 9 5 8.6 5 8C5 7.4 5.4 7 6 7H18C18.6 7 19 7.4 19 8C19 8.6 18.6 9 18 9ZM16 12C16 11.4 15.6 11 15 11H6C5.4 11 5 11.4 5 12C5 12.6 5.4 13 6 13H15C15.6 13 16 12.6 16 12Z" fill="black"></path>
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
                <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                    <span class="svg-icon svg-icon-1 svg-icon-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
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
                        {{-- {{ __('Campaign Management') }} --}}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            @can('admin-campaign-create')
                            <!--begin::Action-->
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_campaign">
                                {{ __('Add Campaign') }}
                            </a>
                            <!--end::Action-->
                            @endcan
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div style="width: 100%; overflow-x:auto;">
                        <!--begin::Table-->
                        <table class="table table-bordered align-middle fs-6 gy-5" id="campaigns_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-dark fw-bolder fs-7 text-uppercase gs-0">
                                    <th>{{ __('Banner') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Duration') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                                <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                        </table>
                        <!--end::Table-->
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->

            @can('admin-campaign-create')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_new_campaign" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Add Campaign') }}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        {!! Form::open([
                        'route' => 'campaigns.store',
                        'method' => 'POST',
                        'class' => 'w-100 position-relative mb-3',
                        'id' => 'add-campaign-form',
                        'enctype' => 'multipart/form-data',
                        ]) !!}
                        <!--begin::Modal body-->
                        <div class="modal-body">
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">Campaign
                                        Title:</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name', null, ['placeholder' => 'Campaign Title', 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">عنوان الفئة</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name_arabic', null, ['placeholder' => 'عنوان الفئة', 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Duration') }}:</label>
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('From') }}:</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_from', null, ['placeholder' => __('From'), 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('To') }}</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_to', null, ['placeholder' => __('To'), 'class' => 'form-control']) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Campaign Banner') }}
                                        ({{ __('Max 2 MB') }}):</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        <input class="col-lg-6 nova-file-selector" type="file" name="campaign_banner" id="campaign_banner" accept="image/*" />
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Modal body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Save Changes') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - New Card-->
            @endcan

            @can('admin-campaign-edit')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_edit_campaign" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Update Campaign ') }}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        {!! Form::model(null, [
                        'class' => 'w-100 position-relative mb-3',
                        'id' => 'edit-campaign-form',
                        'enctype' => 'multipart/form-data',
                        ]) !!}
                        <!--begin::Modal body-->
                        <div class="modal-body">
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">Campaign
                                        Title:</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name', null, [
                                    'placeholder' => 'Campaign Title',
                                    'class' => 'form-control',
                                    'id' => 'edit_campaign_name',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">عنوان الحملة</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name_arabic', null, [
                                    'placeholder' => 'عنوان الحملة',
                                    'class' => 'form-control',
                                    'id' => 'edit_campaign_name_arabic',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Duration') }}:</label>
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('From') }}:</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_from', null, [
                                    'placeholder' => __('From'),
                                    'class' => 'form-control',
                                    'id' => 'edit_campaign_from',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('To') }}</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_to', null, [
                                    'placeholder' => __('To'),
                                    'class' => 'form-control',
                                    'id' => 'edit_campaign_to',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-12 col-form-label">{{ __('Previously Selected') }}:</label>
                                    <!--end::Label-->
                                    <label class="col-12 col-form-label"><img src="" id="campaign_banner_img" alt="{{ __('Campaign Banner') }} ({{ __('Max 2 MB') }})" width="auto" height="60px !important"> </label>
                                </div>
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Campaign Banner') }}
                                        ({{ __('Max 2 MB') }}):</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        <input class="col-lg-6 nova-file-selector" type="file" name="campaign_banner" id="edit_campaign_banner" accept="image/*" />
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Modal body-->
                        <div class="modal-footer">
                            <input type="hidden" name="xxyyzz" id="xxyyzz">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Save Changes') }}</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - New Card-->
            @endcan

            @can('admin-campaign-list')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_show_campaign" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Campaign') }}</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body">
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">Campaign
                                        Title:</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name', null, [
                                    'placeholder' => 'Campaign Title',
                                    'class' => 'form-control',
                                    'id' => 'show_campaign_name',
                                    'readonly' => 'readonly',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">عنوان الفئة</label>
                                    <!--end::Label-->
                                    {!! Form::text('campaign_name_arabic', null, [
                                    'placeholder' => 'عنوان الفئة',
                                    'class' => 'form-control',
                                    'id' => 'show_campaign_name_arabic',
                                    'readonly' => 'readonly',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row">
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Duration') }}:</label>
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('From') }}:</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_from', null, [
                                    'placeholder' => __('From'),
                                    'class' => 'form-control',
                                    'id' => 'show_campaign_from',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('To') }}</label>
                                    <!--end::Label-->
                                    {!! Form::date('campaign_to', null, [
                                    'placeholder' => __('To'),
                                    'class' => 'form-control',
                                    'id' => 'show_campaign_to',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-12 col-form-label">{{ __('Previously Selected') }}:</label>
                                    <!--end::Label-->
                                    <label class="col-12 col-form-label"><img src="" id="show_campaign_banner_img" alt="{{ __('Campaign Banner') }} ({{ __('Max 2 MB') }})" width="auto" height="60px !important"> </label>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Modal body-->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        </div>
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - New Card-->
            @endcan
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->

<script>
    var campaignsTable;
    $(document).ready(function() {
        $("#appNav").addClass('show');
        $("#campaignNav").addClass('active');
        $("#add-campaign-form").submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $(".alert-dismissible").remove();
            var formData = new FormData(this);
            $.ajax({
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === true) {
                        // hide the modal
                        $("#kt_modal_new_campaign").modal('hide');
                        $("#add-campaign-form")[0].reset();
                        campaignsTable.ajax.reload();
                        Swal.fire("Done!", "Successfully Added.", "success");
                    } else if (response.status === false) {
                        campaignsTable.ajax.reload();
                        text = response.message.toString();
                        Swal.fire("Error!", text, "error");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    campaignsTable.ajax.reload();
                    Swal.fire("Error!", "Please, Try Again", "error");
                }
            });
            return false;
        });
        loadTableData();
    });

    function loadTableData() {
        campaignsTable = $('#campaigns_table').DataTable({
            'ajax': {
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                url: "{{ route('fetch-campaigns') }}",
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
                text: "{{ __('Are you sure you want to delete Campaign ?') }}",
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
                    var url = "{{ route('campaigns.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: url,
                        type: "DELETE",
                        success: function(response) {
                            campaignsTable.ajax.reload();
                            if (response.data) {
                                Swal.fire("Done!",
                                    "{{ __('campaign has been deleted sucessfully') }}",
                                    "success");
                            } else {
                                Swal.fire("Error Deleting!", "This campaign cannot be deleted",
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

    function editFunc(id) {
        var url_i = "{{ route('campaigns.fetch-campaign', ':id') }}";
        url_i = url_i.replace(':id', id);
        $.ajax({
            url: url_i,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $("#edit_campaign_name").val(response.campaign_name);
                $("#edit_campaign_name_arabic").val(response.campaign_name_arabic);
                $("#edit_campaign_from").val(response.campaign_from);
                $("#edit_campaign_to").val(response.campaign_to);
                $("#xxyyzz").val(response.id);
                $('#campaign_banner_img').attr('src', response.campaign_banner);
                $("#edit-campaign-form").submit(function(e) {
                    e.preventDefault();
                    let form = $(this);
                    $(".alert-dismissible").remove();
                    let formData = new FormData(this);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: "{{ route('campaigns.update-campaign') }}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status === true) {
                                // hide the modal
                                $("#kt_modal_edit_campaign").modal('hide');
                                $("#edit-campaign-form")[0].reset();
                                campaignsTable.ajax.reload();
                                Swal.fire("Done!", "Successfully Updated.", "success");
                            } else if (response.status === false) {
                                campaignsTable.ajax.reload();
                                text = response.message.toString();
                                Swal.fire("Error!", text, "error");
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            campaignsTable.ajax.reload();
                            Swal.fire("Error!", "Please, Try Again", "error");
                        }
                    });
                    return false;
                });
            }
        });
    }

    function showFunc(id) {
        var url_i = "{{ route('campaigns.fetch-campaign', ':id') }}";
        url_i = url_i.replace(':id', id);
        $.ajax({
            url: url_i,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $("#show_campaign_name").val(response.campaign_name);
                $("#show_campaign_name_arabic").val(response.campaign_name_arabic);
                $('#show_campaign_banner_img').attr('src', response.campaign_banner);
                $("#show_campaign_from").val(response.campaign_from);
                $("#show_campaign_to").val(response.campaign_to);
            }
        });
    }
</script>
@endsection