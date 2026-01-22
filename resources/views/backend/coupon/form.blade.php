@extends('backend.master')
@section('title')
    @isset($item) Edit @else Add @endisset Coupon
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Coupon</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{route('admin.dashboard')}}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Coupon</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">@isset($item) Edit @else Add @endisset Coupon Information</div>
                            @if($errors->any())
                                <ul>
                                    @foreach($errors as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <div class="ms-auto">
                                <!-- Button trigger modal -->
                                @can('Coupon Setting Update')
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#CouponSetting">
                                        <i class="fa fa-cog me-2"></i>View Coupon Setting
                                    </button>
                                @endcan
                                <a href="{{route('admin.coupon.index')}}" data-bs-toggle="tooltip" title="View Coupons" class="btn btn-primary"><i class="fa fa-list me-2"></i>View Coupons</a>
                            </div>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="coupon_form" action="@isset($item){{route('admin.coupon.update')}}@else{{route('admin.coupon.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <div class="row">
                                    <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input id="title" type="text" name="title" value="@isset($item){{$item->title}}@else{{old('title')}}@endisset"
                                               placeholder="Enter title"
                                               class="form-control @error('title') is-invalid @enderror" required>
                                        @error('title')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description <small>[optional]</small></label>
                                        <textarea id="description" name="description" rows="3"
                                                  placeholder="Enter description"
                                                  class="form-control summernote @error('description') is-invalid @enderror">@isset($item){{$item->description}}@else{{old('description')}}@endisset</textarea>
                                        @error('description')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="code" class="form-label">Code <a href="#" id="generateCode"
                                                                                     class="text-primary text-underlined">Generate Code</a></label>
                                        <input id="code" type="text" name="code" value="@isset($item){{$item->code}}@else{{old('code')}}@endisset"
                                               placeholder="Enter coupon code" class="form-control @error('code') is-invalid @enderror" required>
                                        @error('code')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Valid From</label>
                                        <input id="start_date" type="datetime-local" name="start_date"
                                               value="@isset($item){{\Carbon\Carbon::parse($item->start_date)->format('Y-m-d H:i')}}@else{{old('start_date')}}@endisset"
                                               class="form-control @error('start_date') is-invalid @enderror" required>
                                        @error('start_date')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">Valid Till</label>
                                        <input id="end_date" type="datetime-local" name="end_date"
                                               value="@isset($item){{\Carbon\Carbon::parse($item->end_date)->format('Y-m-d H:i')}}@else{{old('end_date')}}@endisset"
                                               class="form-control @error('end_date') is-invalid @enderror" required>
                                        @error('end_date')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="min_purchase_price" class="form-label">Minimum Purchasing Price <small>[optional]</small></label>
                                        <input id="min_purchase_price" type="number" step="0.01" name="min_purchase_price"
                                               value="@isset($item){{$item->min_purchase_price}}@else{{old('min_purchase_price') ?? 0}}@endisset"
                                               placeholder="Enter minimum purchasing price"
                                               class="form-control @error('min_purchase_price') is-invalid @enderror" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="discount" class="form-label">Discount</label>
                                        <div class="input-group">
                                            <input id="discount" type="number" step="0.01" name="discount"
                                                   value="@isset($item){{$item->discount}}@else{{old('discount') ?? 0}}@endisset" placeholder="Enter discount"
                                                   class="form-control form-control-solid @error('discount') is-invalid @enderror" required>
                                            <select name="discount_type" id="discount_type" class="form-select">
                                                <option value="1" @isset($item){{$item->discount_type == '1' ? 'selected' : ''}}@endisset>Flat</option>
                                                <option value="2" @isset($item){{$item->discount_type == '2' ? 'selected' : ''}}@endisset>Percent</option>
                                            </select>
                                        </div>
                                        @error('discount')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="applicable_for" class="form-label">Applicable For</label>
                                        <select name="applicable_for" id="applicable_for"
                                                class="form-select js-example-basic-single @error('applicable_for') is-invalid @enderror" required>
                                            <option value="0" selected>All</option>
                                            @foreach($customers as $customer)
                                                <option value="{{$customer->id}}" @isset($item){{$item->applicable_for == $customer->id ? 'selected' : ''}}@endisset>{{$customer->first_name}} {{$customer->last_name}} ({{$customer->phone}})</option>
                                            @endforeach
                                        </select>
                                        @error('applicable_for')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="use_limit_per_user" class="form-label">Usage Limit <span class="text-danger">(Per Person)</span></label>
                                        <input id="use_limit_per_user" type="number" name="use_limit_per_user"
                                               value="@isset($item){{$item->use_limit_per_user}}@else{{old('use_limit_per_user')}}@endisset"
                                               placeholder="Enter use limit for each user" class="form-control @error('use_limit_per_user') is-invalid @enderror"
                                               required>
                                        @error('use_limit_per_user')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="total_use_limit" class="form-label">Total Use Limit</label>
                                        <input id="total_use_limit" type="number" name="total_use_limit"
                                               value="@isset($item){{$item->total_use_limit}}@else{{old('total_use_limit')}}@endisset"
                                               placeholder="Enter total use limit" class="form-control @error('total_use_limit') is-invalid @enderror"
                                               required>
                                        @error('total_use_limit')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        @php
                                            if (isset($item) && $item->applicable_products != 0 && $item->applicable_products != null) {
                                                $coupon_products = json_decode($item->applicable_products);
                                            } else {
                                                $coupon_products = [];
                                            }
                                        @endphp
                                        <label for="applicable_products" class="form-label">Products <small>[optional]</small></label>
                                        <select name="applicable_products[]" id="applicable_products" class="form-select js-example-basic-multiple" multiple>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" @if(isset($coupon_products) && in_array($product->id, $coupon_products)) selected
                                                    @endif>
                                                    {{ Str::limit($product->name, 50) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('applicable_products')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <fieldset class="col-md-6 mb-3">
                                        <legend class="col-form-label ">Status</legend>
                                        <div class="">
                                            <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status1" value="1"
                                                @isset($item){{$item->status == 1 ? 'checked' : ''}}@endisset> <label class="form-check-label"
                                                                                                                      for="status1">
                                                    Active
                                                </label> </div>
                                            <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status0" value="0"
                                                                            @isset($item){{$item->status == 0 ? 'checked' : ''}}@else checked @endisset> <label class="form-check-label"
                                                                                                                                                                for="status0">
                                                    Inactive
                                                </label> </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="CouponSetting" data-bs-backdrop="static" tabindex="-1"
                     aria-labelledby="CouponSettingModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="productSettingModalLabel">Coupon Settings</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{route('admin.setting.update')}}" method="post" id="productSettingsForm">
                                @csrf
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="coupon_code_syntax" class="form-label">Coupon Code Syntax</label>
                                        <input type="text" name="coupon_code_syntax" id="coupon_code_syntax"
                                               value="{{getSetting('coupon_code_syntax')}}" placeholder="Enter SKU syntax products"
                                               class="form-control" disabled>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary change-setting" id="editButton">Edit Settings</button>
                                    <button type="button" class="btn btn-warning d-none" id="cancelButton">Cancel</button>
                                    <button type="submit" class="btn btn-primary d-none" id="saveButton">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();
            $('.js-example-basic-multiple').select2({
                placeholder: 'Select applicable products',
                width: '100%',
                allowClear: true
            });

            $('#editButton').on('click', function () {
                $('#productSettingsForm .modal-body div input').prop('disabled', false).prop('required', true);
                $(this).addClass('d-none'); // Hide the Edit button
                $('#saveButton').removeClass('d-none'); // Show the Save button
                $('#cancelButton').removeClass('d-none'); // Show the cancel button
            });

            $('#cancelButton, .btn-close').on('click', function () {
                $('#productSettingsForm .modal-body div input').prop('disabled', true).prop('required', false);
                $('#editButton').removeClass('d-none'); // Hide the Edit button
                $('#saveButton').addClass('d-none'); // Show the Save button
                $('#cancelButton').addClass('d-none'); // Show the Save button
            });
            $('#generateCode').on('click', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.coupon.code.generate') }}", // AJAX route for generating code
                    method: "get",
                    success: function (response) {
                        console.log(response);
                        if (response.code) {
                            $('#code').val(response.code); // Set the unique code in the input field
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Failed to Generate Code',
                                text: 'Please try again.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Generate Code',
                            text: 'Please try again.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
            const today = new Date().toISOString().split('T')[0];

            // Set the 'min' attribute of the input to disable past dates
            $('#start_date, #end_date').attr('min', today);
        })
    </script>

@endpush
