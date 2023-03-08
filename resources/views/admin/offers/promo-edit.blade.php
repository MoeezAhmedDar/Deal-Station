@extends('admin.layouts.app')
@section('page_title', $page_title)

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
                        {{ __('Edit Offer')}}
                    </div>
                    <!--End::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                        <!--begin::Toolbar-->
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <!--begin::Add Merchant-->
                            <a href="{{ route('offers.index') }}" class="btn btn-primary">{{ __('Back') }}
                            </a>
                            <!--end::Add Merchant-->
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->

                {!! Form::model($offer_data, ['method' => 'POST','route' => 'offers.promo-update', 'class'=>'w-100 position-relative mb-3', 'enctype'=>'multipart/form-data', 'id'=>'offer-form']) !!}
                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-12">
                            <ul class="nav nav-tabs nav-tabs-line">
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="offer-edit" data-toggle="tab" href="{{ route('offers.edit',$offer_data->id)}}">Offer Update</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" id="offer-promo-edit" data-toggle="tab" href="{{ route('offers.promo-edit',$offer_data->id)}}">Coupon Update</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row">
                        <!--begin::Col-->
                        <div class="col-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Offer Type')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_type" value="1" @if($offer_data->offer_type == 1) checked @endif />
                                    <span></span>
                                    {{ __('Digital Offer')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_type" value="2" @if($offer_data->offer_type == 2) checked @endif/>
                                    <span></span>
                                    {{ __('Branch Scannable Offer')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-6 fv-row" id="coupon_type_div" @if($offer_data->offer_type == 2) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Coupon Type')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="1" @if($offer_data->offer_coupon_type == 1) checked @endif/>
                                    <span></span>
                                    {{ __('QR')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_coupon_type" value="2" @if($offer_data->offer_coupon_type == 2) checked @endif />
                                    <span></span>
                                    {{ __('Promo Code')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-6 fv-row" id="code_generation_div" @if($offer_data->offer_coupon_type == 1 || $offer_data->offer_coupon_type == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Code Generation')}}</label>
                            <!--end::Label-->
                            <div class="radio-inline">
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" value="1" @if($offer_data->offer_code_generation == 1) checked @endif />
                                    <span></span>
                                    {{ __('CSV Upload Link')}}
                                </label>
                                <br>
                                <label class="radio">
                                    <input type="radio" name="offer_code_generation" value="2" @if($offer_data->offer_code_generation == 2) checked @endif/>
                                    <span></span>
                                    {{ __('Codes by DS')}}
                                </label>
                            </div>
                        </div>
                        <!--end::Col-->

                        <div class="col-6 fv-row" id="coupons_csv_div" @if($offer_data->offer_code_generation == 2 || $offer_data->offer_code_generation == 0) style="display: none;" @endif>
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Upload CSV File')}} :</label>
                            <!--end::Label-->
                            <br>
                            <input class="col-6 nova-file-selector" type="file" name="coupons_csv" id="coupons_csv" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>

                        <!--begin::Col-->
                        <div class="col-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Total Coupons')}}</label>
                            <!--end::Label-->
                            {!! Form::number('offer_coupons', null, array('class' => 'form-control', 'placeholder'=> __('Total Coupons'))) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label required fw-bold fs-6">{{ __('Per User Per Duration')}}:</label>
                            <!--end::Label-->
                            {!! Form::text('offer_per_user', null, array('placeholder' => __('Per User Per Duration'),'class' => 'form-control')) !!}
                        </div>
                        <!--end::Col-->

                        <!--begin::Col-->
                        <div class="col-lg-6 fv-row">
                            <!--begin::Label-->
                            <label class="col-12 col-form-label fw-bold fs-6">{{ __('Usage Duration')}}:</label>
                            <!--end::Label-->
                            {!! Form::select('offer_usage_duration',['3' => 'Weekly','1' => 'Monthly','2' => 'Yearly'], null, array('class' => 'form-control', 'placeholder'=> __('Select an Option'))) !!}
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
                <!--begin::Card footer-->
                <div class="card-footer d-flex justify-content-start py-6 px-9">
                    <input type="hidden" name="xxyyz" value="{{$offer_data->id}}">
                    <button type="submit" class="btn btn-primary px-6">{{ __('Save Changes') }}</button>
                </div>
                <!--end::Card footer-->
                {!! Form::close() !!}
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
        $("#moNav").addClass('show');
        $("#offerNav").addClass('active');
        $("#offer-promo-edit").addClass('active');
        var createOfferForm = $('#offer-form');
        createOfferForm.validate({
            rules: {
                offer_type: {
                    required: true,
                    number: true,
                },
                offer_coupons: {
                    required: true,
                    number: true,
                },
                offer_coupon_type: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_type"][value=1]').is(":checked");
                        }
                    },
                },
                offer_code_generation: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(":checked");
                        }
                    },

                },
                coupons_csv: {
                    required: {
                        depends: function() {
                            return $('input[type="radio"][name="offer_code_generation"][value=1]').is(":checked");
                        }
                    },
                },
                offer_price: {
                    required: {
                        depends: function(element) {
                            return $('input[type="radio"][name="offer_coupon_type"][value=2]').is(":checked");
                        }
                    },
                },
                offer_usage_duration: {
                    required: {
                        depends: function(element) {
                            if ($('input[type="number"][name="offer_per_user"]').val() != '')
                                return true;
                            else
                                return false;
                        }
                    },
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        $('input').attr('autocomplete', 'off');

        $('input[type=radio][name=offer_type]').change(function() {
            if (this.value == '1') {
                $('#coupon_type_div').show();
            } else if (this.value == '2') {
                $('#coupon_type_div').hide();
            }
            $('#coupons_csv_div').hide();
            $('#code_generation_div').hide();
            $('#offer_price_div').hide();
        });
        $('input[type=radio][name=offer_coupon_type]').change(function() {
            if (this.value == '2') {
                $('#code_generation_div').show();
                $('#offer_price_div').show();
            } else if (this.value == '1') {
                $('#code_generation_div').hide();
                $('#offer_price_div').hide();
            }
            $('#coupons_csv_div').hide();
        });
        $('input[type=radio][name=offer_code_generation]').change(function() {
            if (this.value == '1') {
                $('#coupons_csv_div').show();
            } else if (this.value == '2') {
                $('#coupons_csv_div').hide();
            }
        });
        $('input[type=file][id=offer_image_link]').change(function() {
            var fileUpload = $("input[type=file][id=offer_image_link");
            if (parseInt(fileUpload.get(0).files.length) > 5) {
                fileUpload.val('');
                alert("You can only upload a maximum of 5 files");
            }
        });

        $("#categories_checkbox").click(function() {
            if ($("#categories_checkbox").is(':checked')) {
                $("#offer_categories > option").prop("selected", "selected");
                $("#offer_categories").trigger("change");
            } else {
                $("#offer_categories > option").removeAttr("selected");
                $("#offer_categories").trigger("change");
            }
        });

        $("#branches_checkbox").click(function() {
            if ($("#branches_checkbox").is(':checked')) {
                $("#offer_branches > option").prop("selected", "selected");
                $("#offer_branches").trigger("change");
            } else {
                $("#offer_branches > option").removeAttr("selected");
                $("#offer_branches").trigger("change");
            }
        });

        $("#targeted_checkbox").click(function() {
            if ($("#targeted_checkbox").is(':checked')) {
                $("#targeted_membership > option").prop("selected", "selected");
                $("#targeted_membership").trigger("change");
            } else {
                $("#targeted_membership > option").removeAttr("selected");
                $("#targeted_membership").trigger("change");
            }
        });

        $("#visible_checkbox").click(function() {
            if ($("#visible_checkbox").is(':checked')) {
                $("#visible_membership > option").prop("selected", "selected");
                $("#visible_membership").trigger("change");
            } else {
                $("#visible_membership > option").removeAttr("selected");
                $("#visible_membership").trigger("change");
            }
        });
    });

    function LoadMerchantBranches(val) {
        if (val != 0) {
            var $offer_branches = $('#offer_branches').empty();
            $offer_branches.empty();
            var url = '{{route("branches.merchant-branches", ":id") }}';
            url = url.replace(':id', val);
            $.ajax({
                headers: {
                    "X-CSRF-Token": "{{ csrf_token() }}",
                },
                dataType: "json",
                url: url,
                type: "GET",
                success: function(response) {
                    response.forEach(element => {
                        $offer_branches.append('<option value=' + element.id + '>' + element.branch_name + '</option>');
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log('Error');
                }
            });
        }
    }

    function removeImage(id) {
        if (id) {
            console.log(id);
            Swal.fire({
                text: "Are you sure you want to Delete?",
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
                    var url = '{{ route("offers.destroy-image", ":id") }}';
                    url = url.replace(':id', id);
                    $.ajax({
                        headers: {
                            "X-CSRF-Token": "{{ csrf_token() }}",
                        },
                        url: url,
                        type: "GET",
                        success: function(response) {
                            window.location.reload();
                            Swal.fire("Done!", "Deleted Successfully.", "success");
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            Swal.fire("Error Deleting!", "Please, Try Again", "error");
                        }
                    });
                }
            });
        }
    }

    function updateOrder(id) {
        if (id) {
            Swal.fire({
                text: "Are you sure you want to Update?",
                icon: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                confirmButtonText: "Yes, Update!",
                cancelButtonText: "No, Cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-secondary",
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    var order = $('#order_' + id).val();
                    if (order && order >= 1 && order <= 5) {
                        var url = '{{ route("offers.update-image-order") }}';
                        $.ajax({
                            headers: {
                                "X-CSRF-Token": "{{ csrf_token() }}",
                            },
                            url: url,
                            type: "POST",
                            data: {
                                'id': id,
                                'order': order,
                            },
                            success: function(response) {
                                if (response.data) {
                                    $("#offers_gallery_table").load(window.location + " #offers_gallery_table");
                                    Swal.fire("Done!", "Updated Successfully.", "success");
                                } else {
                                    Swal.fire("Error Updating!", "Parameters Missing", "error");
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                Swal.fire("Error Updating!", "Please, Try Again", "error");
                            }
                        });
                    } else {
                        Swal.fire("Error Updating!", "Please, Enter Order Number between 1 to 5", "error");
                    }
                }
            });
        }
    }
</script>
@endsection