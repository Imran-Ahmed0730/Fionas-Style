@extends('backend.master')
@section('title')
    @isset($item)Edit @else Add @endisset Product Stock
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Product Stock</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.stock.index') }}">Product Stocks</a>
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
                    <form id="product_stock_form" action="@isset($item){{route('admin.stock.update', $item->id)}}@else{{route('admin.stock.store')}}@endisset" method="post">
                        @csrf
                        @isset($item) @method('PUT') @endisset
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <div class="card-title">Stock Information</div>
                                <div class="ms-auto">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#stockSetting"
                                    title="Stock Settings" class="btn btn-secondary me-2">
                                    <i class="fa fa-cog me-2"></i>Settings
                                    </button>
                                    <a href="{{route('admin.stock.index')}}" title="View Product Stocks" class="btn btn-primary">
                                        <i class="fa fa-list me-2"></i> View Product Stocks
                                    </a>
                                </div>
                            </div>

                            <div class="card-body row">
                                <div class="col-md-6 mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select name="product_id" id="product_id" class="form-select js-example-basic @error('product_id') is-invalid @enderror" required>
                                        <option value="" disabled selected>Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{$product->id}}" @if(request('product') == $product->id) selected @endif>{{$product->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id') <div class="invalid-feedback">{{$message}}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="supplier_id" class="form-label">Supplier <small>[optional]</small></label>
                                    <select name="supplier_id" id="supplier_id" class="form-select js-example-basic @error('supplier_id') is-invalid @enderror">
                                        <option value="" selected disabled>Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}} ({{$supplier->phone}})</option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id') <div class="invalid-feedback">{{$message}}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sku" class="form-label">Stock SKU</label>
                                    <a href="#" id="generateSku" class="btn btn-link p-0 ms-2">Generate SKU</a>
                                    <input type="text" name="sku" id="sku" value="@isset($item){{$item->sku}}@else{{old('sku')}}@endisset" placeholder="Enter SKU or Generate a new" class="form-control @error('sku') is-invalid @enderror" required>
                                    @error('sku') <div class="invalid-feedback">{{$message}}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="buying_price" class="form-label">Buying Price</label>
                                    <input type="number" step="0.01" name="buying_price" id="buying_price" value="@isset($item){{$item->buying_price}}@else{{old('buying_price')}}@endisset" placeholder="Enter buying price" class="form-control @error('buying_price') is-invalid @enderror" required>
                                    @error('buying_price') <div class="invalid-feedback">{{$message}}</div> @enderror
                                </div>

                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered" id="stockTable" style="display: none;">
                                        <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th width="40%">Variant</th>
                                            <th width="20%">Quantity</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
             <!-- Product Settings Modal -->
    <div class="modal fade" id="stockSetting" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="stockSettingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="stockSettingModalLabel">Stock Settings</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.setting.update-fields') }}" method="post" id="stockSettingsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="stock_sku_syntax" class="form-label">Stock SKU Syntax</label>
                            <input type="text" name="stock_sku_syntax" id="stock_sku_syntax"
                                value="{{ getSetting('stock_sku_syntax') }}" placeholder="Enter SKU syntax stocks"
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
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.js-example-basic').select2();
            
            if ($('#product_id').val()) {
                $('#product_id').trigger('change');
            }

            $('#generateSku').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.stock.sku.generate') }}",
                    method: "get",
                    success: function(response) {
                        if (response.sku) {
                            $('#sku').val(response.sku);
                        }
                    }
                });
            });

            $('#product_id').on('change', function () {
                const productId = $(this).val();
                if (!productId) return;

                $.ajax({
                    method: 'GET',
                    url: '{{ route("admin.stock.get-product") }}',
                    data: { productId: productId },
                    success: function (data) {
                        $('#buying_price').val(data.buying_price);
                        const $tableBody = $('#stockTable tbody');
                        $tableBody.empty();

                        if (!data.variants || data.variants.length === 0) {
                            const row = `
                            <tr>
                                <td>${data.name}</td>
                                <td>N/A</td>
                                <td>
                                    <input type="number" name="quantity[default]" class="form-control" min="1" placeholder="Enter Quantity" required>
                                </td>
                            </tr>`;
                            $tableBody.append(row);
                        } else {
                            data.variants.forEach(function (variant) {
                                const row = `
                                <tr>
                                    <td>${data.name}</td>
                                    <td>${variant.name}</td>
                                    <td>
                                        <input type="number" name="quantity[${variant.sku}]" class="form-control" min="1" placeholder="Enter Quantity" required>
                                    </td>
                                </tr>`;
                                $tableBody.append(row);
                            });
                        }
                        $('#stockTable').show();
                    },
                    error: function () {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Error fetching product details.');
                        } else {
                            alert('Error fetching product details.');
                        }
                    }
                });
            });
            
            // Modal handlers
            $('#editButton').on('click', function () {
                $('#stockSettingsForm .modal-body input').prop('disabled', false);
                $(this).addClass('d-none');
                $('#saveButton, #cancelButton').removeClass('d-none');
            });

            $('#cancelButton, .btn-close').on('click', function () {
                $('#stockSettingsForm .modal-body input').prop('disabled', true);
                $('#editButton').removeClass('d-none');
                $('#saveButton, #cancelButton').addClass('d-none');
            });

            $('#stockSettingsForm').on('submit', function (e) {
                const $form = $(this);
                const $saveBtn = $('#saveButton');
                const originalText = $saveBtn.html();
                e.preventDefault();
                 $saveBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    success: function (response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                            $('#stockSetting').modal('hide');
                            // Disable inputs again
                            $('#stockSettingsForm .modal-body input').prop('disabled', true);
                            $('#editButton').removeClass('d-none');
                            $('#saveButton, #cancelButton').addClass('d-none');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Something went wrong.'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to update settings. Please try again.'
                        });
                    },
                    complete: function () {
                        $saveBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
