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
                        {{-- {{ __('Advertisement Management') }} --}}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            @can('admin-advertisement-create')
                            <!--begin::Action-->
                            <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_advertisement">
                                {{ __('Add Advertisement') }}
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
                        <table class="table table-bordered align-middle fs-6 gy-5" id="advertisements_table">
                            <!--begin::Table head-->
                            <thead>
                                <!--begin::Table row-->
                                <tr class="text-start text-dark fw-bolder fs-7 text-uppercase gs-0">
                                    <th>{{ __('Icon') }}</th>
                                    <th>{{ __('Name') }}</th>
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

            @can('admin-advertisement-create')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_new_advertisement" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Add Advertisement') }}</h2>
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
                        'route' => 'advertisements.store',
                        'method' => 'POST',
                        'class' => 'w-100 position-relative mb-3',
                        'id' => 'add-advertisement-form',
                        'enctype' => 'multipart/form-data',
                        ]) !!}
                        <!--begin::Modal body-->
                        <div class="modal-body">
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in English') }}</label>
                                    <!--end::Label-->
                                    {!! Form::text('advertisement_name', null, [
                                    'placeholder' => __('Advertisement Title in English'),
                                    'class' => 'form-control',
                                    ]) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in Arabic') }}</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::text('advertisement_name_arabic', null, [
                                        'placeholder' => __('Advertisement Title in Arabic'),
                                        'class' => 'form-control',
                                        ]) !!}
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Item') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_item',
                                    ['Category' => 'Category', 'Campaign' => 'Campaign', 'Offer' => 'Offer', 'External Link' => 'External Link'],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_item',
                                    'id' => 'advertisement_item',
                                    ],
                                    ) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row items_div" style="display: none;">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Select Item') }}</label>
                                    <!--end::Label-->
                                    <select name="advertisement_item_id" id="advertisement_item_id" class="form-control db_item" data-control="select2" style="display: none;">

                                    </select>
                                    <!--end::Col-->
                                    <input type="url" name="advertisement_item_link" id="advertisement_item_link" class="form-control ex_link" style="display: none;">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Image') }}
                                        ({{ __('Max 2 MB') }}):</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        <input class="col-lg-6 nova-file-selector" style="width: 100%;" type="file" name="advertisement_image" id="advertisement_image" accept="image/*" />
                                    </div>
                                </div>
                                <!--end::Col-->
                                {{-- start::col --}}
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Type') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_type',
                                    [
                                    '1' => __('1st ad'),
                                    '2' => __('2nd ad'),
                                    '3' => __('3rd ad'),
                                    '4' => __('4th ad'),
                                    '5' => __('5th ad'),
                                    '6' => __('Fixed ad 1'),
                                    '7' => __('Fixed ad 2'),
                                    ],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_type',
                                    'id' => 'advertisement_type',
                                    ],
                                    ) !!}
                                </div>
                                {{-- end::col --}}

                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row advertisement_text_div" style="display: none;">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Description') }}:</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::textarea('advertisement_text', null, [
                                        'placeholder' => __('Description'),
                                        'class' => 'form-control advertisement_text',
                                        'id' => 'advertisement_text',
                                        'rows' => '2',
                                        ]) !!}
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

            @can('admin-advertisement-edit')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_edit_advertisement" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Update Advertisement') }}</h2>
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
                        'id' => 'edit-advertisement-form',
                        'enctype' => 'multipart/form-data',
                        ]) !!}
                        <!--begin::Modal body-->
                        <div class="modal-body">
                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in English') }}</label>
                                    <!--end::Label-->
                                    {!! Form::text('advertisement_name', null, [
                                    'placeholder' => __('Advertisement Title in English'),
                                    'class' => 'form-control',
                                    'id' => 'edit_advertisement_name',
                                    ]) !!}
                                </div>
                                <!--end::Col-->

                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in Arabic') }}</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::text('advertisement_name_arabic', null, [
                                        'placeholder' => __('Advertisement Title in Arabic'),
                                        'class' => 'form-control',
                                        'id' => 'edit_advertisement_name_arabic',
                                        ]) !!}
                                    </div>
                                </div>
                                <!--end::Col-->


                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Item') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_item',
                                    ['Category' => 'Category', 'Campaign' => 'Campaign', 'Offer' => 'Offer', 'External Link' => 'External Link'],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_item',
                                    'id' => 'edit_advertisement_item',
                                    ],
                                    ) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row items_div" style="display: none;">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Select Item') }}</label>
                                    <!--end::Label-->
                                    <select name="advertisement_item_id" id="edit_advertisement_item_id" class="form-control db_item" data-control="select2" style="display: none;">

                                    </select>
                                    <!--end::Col-->

                                    <input type="url" name="advertisement_item_id" id="edit_advertisement_item_id" class="form-control ex_link" style="display: none;">
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
                                    <label class="col-12 col-form-label"><img src="" id="advertisement_image_img" alt="{{ __('Advertisement Image') }}" width="auto" height="60px !important"> </label>
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Image') }}
                                        ({{ __('Max 2 MB') }}):</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        <input class="col-lg-6 nova-file-selector" type="file" style="width: 100%" name="advertisement_image" id="advertisement_image" accept="image/*" />
                                    </div>
                                </div>
                                <!--end::Col-->

                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Type') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_type',
                                    [
                                    '1' => '1st Ad',
                                    '2' => '2nd Ad',
                                    '3' => '3rd Ad',
                                    '4' => '4th Ad',
                                    '5' => '5th Ad',
                                    '6' => 'Scrolling Ad 1',
                                    '7' => 'Scrolling Ad 2',
                                    ],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_type',
                                    'id' => 'edit_advertisement_type',
                                    ],
                                    ) !!}
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row advertisement_text_div" id="edit_advertisement_text_div">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Description') }}:</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::textarea('advertisement_text', null, [
                                        'placeholder' => __('Description'),
                                        'class' => 'form-control advertisement_text',
                                        'id' => 'edit_advertisement_text',
                                        'rows' => '2',
                                        ]) !!}
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

            @can('admin-advertisement-list')
            <!--begin::Modal - New Card-->
            <div class="modal fade" id="kt_modal_show_advertisement" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2>{{ __('Advertisement') }}</h2>
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
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in English') }}</label>
                                    <!--end::Label-->
                                    {!! Form::text('advertisement_name', null, [
                                    'placeholder' => __('Advertisement Title in English'),
                                    'class' => 'form-control',
                                    'id' => 'show_advertisement_name',
                                    'disabled',
                                    ]) !!}
                                </div>
                                <!--end::Col-->

                                {{-- Start::col --}}
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Title in Arabic') }}</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::text('advertisement_name_arabic', null, [
                                        'placeholder' => __('Advertisement Title in Arabic'),
                                        'class' => 'form-control',
                                        'id' => 'show_advertisement_name_arabic',
                                        'disabled',
                                        ]) !!}
                                    </div>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row">
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Item') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_item',
                                    ['Category' => 'Category', 'Campaign' => 'Campaign', 'Offer' => 'Offer', 'External Link' => 'External Link'],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_item',
                                    'id' => 'show_advertisement_item',
                                    'disabled',
                                    ],
                                    ) !!}
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row items_div" style="display: none;">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Select Item') }}</label>
                                    <!--end::Label-->
                                    <select name="advertisement_item_id" id="edit_advertisement_item_id" class="form-control db_item" data-control="select2" style="display: none;">

                                    </select>
                                    <!--end::Col-->
                                    <input type="url" name="advertisement_item_id" id="show_advertisement_item_id" class="form-control ex_link" style="display: none;">
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row">
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-12 col-form-label">{{ __('Previously Selected') }}:</label>
                                    <!--end::Label-->
                                    <label class="col-12 col-form-label"><img src="" id="advertisement_image_img_show" alt="{{ __('Advertisement Image') }}" width="auto" height="60px !important"> </label>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-6 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Advertisement Type') }}</label>
                                    <!--end::Label-->
                                    {!! Form::select(
                                    'advertisement_type',
                                    [
                                    '1' => '1st Ad',
                                    '2' => '2nd Ad',
                                    '3' => '3rd Ad',
                                    '4' => '4th Ad',
                                    '5' => '5th Ad',
                                    '6' => 'Scrolling Ad 1',
                                    '7' => 'Scrolling Ad 2',
                                    ],
                                    null,
                                    [
                                    'placeholder' => __('Select an Option'),
                                    'class' => 'form-control advertisement_type',
                                    'id' => 'show_advertisement_type',
                                    'disabled',
                                    ],
                                    ) !!}
                                </div>
                                <!--end::Col-->

                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row advertisement_text_div" id="show_advertisement_text_div">
                                <div class="col-lg-12 fv-row">
                                    <!--begin::Label-->
                                    <label class="col-lg-12 col-form-label required fw-bold fs-6">{{ __('Description') }}:</label>
                                    <!--end::Label-->
                                    <div class="col-lg-12 ">
                                        {!! Form::textarea('advertisement_text', null, [
                                        'placeholder' => __('Description'),
                                        'class' => 'form-control advertisement_text',
                                        'id' => 'show_advertisement_text',
                                        'rows' => '2',
                                        'disabled',
                                        ]) !!}
                                    </div>
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
    var advertisementsTable;
    $(document).ready(function() {
        $("#appNav").addClass('show');
        $("#advertisementNav").addClass('active');
        $("#add-advertisement-form").submit(function(e) {
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
                    console.log(response);
                    if (response.status === true) {
                        // hide the modal
                        $("#kt_modal_new_advertisement").modal('hide');
                        $("#add-advertisement-form")[0].reset();
                        advertisementsTable.ajax.reload();
                        Swal.fire("Done!", "Successfully Added.", "success");
                    } else if (response.status === false) {
                        advertisementsTable.ajax.reload();
                        text = response.message.toString();
                        Swal.fire("Error!", text, "error");
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    advertisementsTable.ajax.reload();
                    Swal.fire("Error!", "Please, Try Again", "error");
                }
            });
            return false;
        });
        $(".advertisement_type").change(function() {
            if (this.value == 1) {
                $(".advertisement_text_div").show();
            } else {
                $(".advertisement_text_div").hide();
            }
        });
        $("#advertisement_item").change(function() {
            $(".items_div").show();
            let item = this.value;
            if (item === 'External Link') {
                $(".db_item").hide();
                $(".ex_link").show();
            } else {
                $(".db_item").show();
                $(".ex_link").hide();
                var $advertisement_items = $('#advertisement_item_id').empty();
                $advertisement_items.empty();
                var url = "{{ route('advertisements.fetch-items', ':item') }}";
                url = url.replace(':item', item);
                $.ajax({
                    headers: {
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    },
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (item == 'Offer') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.offer_uniid + '>' + element
                                    .offer_name + '</option>');
                            });
                        } else if (item == 'Campaign') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.campaign_uniid + '>' + element
                                    .campaign_name + '</option>');
                            });
                        } else if (item == 'Category') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.id + '>' + element.category_name +
                                    '</option>');
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log('Error');
                    }
                });
            }
        });
        $("#edit_advertisement_item").change(function() {
            $(".items_div").show();
            let item = this.value;
            if (item === 'External Link') {
                $(".db_item").hide();
                $(".ex_link").show();
            } else {
                $(".db_item").show();
                $(".ex_link").hide();
                var $advertisement_items = $('#edit_advertisement_item_id').empty();
                $advertisement_items.empty();
                var url = "{{ route('advertisements.fetch-items', ':item') }}";
                url = url.replace(':item', item);
                $.ajax({
                    headers: {
                        "X-CSRF-Token": "{{ csrf_token() }}",
                    },
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (item == 'Offer') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.offer_uniid + '>' + element
                                    .offer_name + '</option>');
                            });
                        } else if (item == 'Campaign') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.campaign_uniid + '>' + element
                                    .campaign_name + '</option>');
                            });
                        } else if (item == 'Category') {
                            response.forEach(element => {
                                $advertisement_items.append('<option value=' +
                                    element.id + '>' + element.category_name +
                                    '</option>');
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log('Error');
                    }
                });
            }
        });
        loadTableData();
    });

    function loadTableData() {
        advertisementsTable = $('#advertisements_table').DataTable({
            'ajax': {
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                url: "{{ route('fetch-advertisements') }}",
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
                text: "{{ __('Are you sure you want to delete Advertisment ?') }}",
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
                    var url = "{{ route('advertisements.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: url,
                        type: "DELETE",
                        success: function(response) {
                            advertisementsTable.ajax.reload();
                            if (response.data) {
                                Swal.fire("Done!",
                                    "{{ __('Advertisment has been deleted sucessfully') }}",
                                    "success");
                            } else {
                                Swal.fire("Error Deleting!", "This category cannot be deleted",
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
        var url_i = "{{ route('advertisements.fetch-advertisement', ':id') }}";
        url_i = url_i.replace(':id', id);
        $.ajax({
            url: url_i,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $(".items_div").show();
                $("#edit_advertisement_name").val(response.advertisement_name);
                $("#edit_advertisement_item").val(response.advertisement_item);
                $("#edit_advertisement_name_arabic").val(response.advertisement_name_arabic);
                if (response.advertisement_item == 'External Link') {
                    $(".db_item").hide();
                    $(".ex_link").show();
                } else {
                    $(".db_item").show();
                    $(".ex_link").hide();
                }
                $("#edit_advertisement_type").val(response.advertisement_type);
                $('#advertisement_image_img').attr('src', response.advertisement_image);
                if (response.advertisement_type == 1) {
                    $('#edit_advertisement_text_div').show();
                    $("#edit_advertisement_text").val(response.advertisement_text);
                } else {
                    $('#edit_advertisement_text_div').hide();
                }
                $("#xxyyzz").val(response.id);
                $("#edit-advertisement-form").submit(function(e) {
                    e.preventDefault();
                    let form = $(this);
                    $(".alert-dismissible").remove();
                    let formData = new FormData(this);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: "{{ route('advertisements.update-advertisement') }}",
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status === true) {
                                // hide the modal
                                $("#kt_modal_edit_advertisement").modal('hide');
                                $("#edit-advertisement-form")[0].reset();
                                advertisementsTable.ajax.reload();
                                Swal.fire("Done!", "Successfully Updated.", "success");
                            } else if (response.status === false) {
                                advertisementsTable.ajax.reload();
                                text = response.message.toString();
                                Swal.fire("Error!", text, "error");
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            advertisementsTable.ajax.reload();
                            Swal.fire("Error!", "Please, Try Again", "error");
                        }
                    });
                    return false;
                });
            }
        });
    }

    function showFunc(id) {
        var url_i = "{{ route('advertisements.fetch-advertisement', ':id') }}";
        url_i = url_i.replace(':id', id);
        $.ajax({
            url: url_i,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $("#show_advertisement_name").val(response.advertisement_name);
                $("#show_advertisement_type").val(response.advertisement_type);
                $("#show_advertisement_name_arabic").val(response.advertisement_name_arabic);
                if (response.advertisement_item == 'External Link') {
                    $(".db_item").hide();
                    $(".ex_link").show();
                } else {
                    $(".db_item").show();
                    $(".ex_link").hide();
                }
                $("#show_advertisement_item").val(response.advertisement_item);
                $('#advertisement_image_img_show').attr('src', response.advertisement_image);
                if (response.advertisement_type == 1) {
                    $('#show_advertisement_text_div').show();
                    $("#show_advertisement_text").val(response.advertisement_text);
                } else {
                    $('#show_advertisement_text_div').hide();
                }
            }
        });
    }
</script>
@endsection