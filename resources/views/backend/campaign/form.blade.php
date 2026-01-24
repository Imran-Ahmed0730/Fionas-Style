@extends('backend.master')

@section('title')
    @isset($item) Edit @else Add @endisset Campaign
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css" />
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .nav-pills .nav-link.active {
            background-color: #4361ee;
        }

        .tab-content {
            padding-top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Campaign</h3>
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
                        <a href="{{ route('admin.campaign.index') }}">Campaign</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="card-title">@isset($item) Edit @else Add @endisset Campaign Information</div>
                    <a href="{{ route('admin.campaign.index') }}" class="btn btn-primary ms-auto">
                        <i class="fa fa-list me-2"></i>View Campaigns
                    </a>
                </div>

                <form
                    action="@isset($item){{ route('admin.campaign.update') }}@else{{ route('admin.campaign.store') }}@endisset"
                    method="POST" enctype="multipart/form-data" id="campaignForm">
                    @csrf
                    {{-- @method('PUT') --}}

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Tabs --}}
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" id="pills-tab-without-border"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill"
                                    href="#primary-info" role="tab" aria-controls="primary-info"
                                    aria-selected="true">Primary Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-product-tab-nobd" data-bs-toggle="pill" href="#product-info"
                                    role="tab" aria-controls="product-info" aria-selected="false">Product Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-seo-tab-nobd" data-bs-toggle="pill" href="#seo-info"
                                    role="tab" aria-controls="seo-info" aria-selected="false">SEO Information</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="pills-without-border-tabContent">

                            {{-- PRIMARY INFO --}}
                            <div class="tab-pane fade show active" id="primary-info" role="tabpanel">
                                @isset($item)
                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                @endisset

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Title</label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter title" required
                                            value="{{ old('title', $item->title ?? '') }}">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Subtitle</label>
                                        <input type="text" name="subtitle"
                                            class="form-control @error('subtitle') is-invalid @enderror"
                                            placeholder="Enter subtitle"
                                            value="{{ old('subtitle', $item->subtitle ?? '') }}">
                                        @error('subtitle')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Description</label>
                                    <textarea name="description" id="description"
                                        class="summernote @error('description') is-invalid @enderror"
                                        placeholder="Enter description">{{ old('description', $item->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Duration</label>
                                        <input type="text" id="duration" name="duration"
                                            class="form-control @error('duration') is-invalid @enderror"
                                            placeholder="Select duration" required
                                            value="{{ old('duration', $item->duration ?? '') }}">
                                        @error('duration')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label required">Category</label>
                                        <select name="category_id" id="category_id"
                                            class="form-select js-select @error('category_id') is-invalid @enderror"
                                            required>
                                            <option value="">Select Category</option>
                                            <option value="0" {{ old('category_id', $item->category_id ?? '') == 0 ? 'selected' : '' }}>All Categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label @empty($item) required @endempty">Thumbnail</label>
                                    <input type="file" name="thumbnail" id="thumbnail"
                                        class="form-control @error('thumbnail') is-invalid @enderror"
                                        accept="image/jpg, image/jpeg, image/png, image/webp" @empty($item) required
                                        @endempty>
                                    <div class="mt-2">
                                        <img id="previewImage"
                                            src="{{ isset($item) && $item->thumbnail ? asset($item->thumbnail) : asset('backend/assets/img/default-banner.jpg') }}"
                                            class="img-fluid object-fit-cover" style="max-height: 300px; width: 100%;">
                                    </div>
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status1"
                                                value="1" {{ old('status', $item->status ?? 1) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status1">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="status0"
                                                value="0" {{ old('status', $item->status ?? 1) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status0">Inactive</label>
                                        </div>
                                    </div>
                                    @error('status')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- PRODUCT INFO --}}
                            <div class="tab-pane fade" id="product-info" role="tabpanel">
                                <div class="mb-3">
                                    <label class="form-label">Products</label>
                                    <select id="product_id" name="product_id[]" class="form-select js-select-multiple"
                                        multiple="multiple">
                                        <option value="">Select Products</option>
                                        @foreach($products as $product)
                                            @php
                                                $isSelected = false;
                                                if (isset($campaign_products)) {
                                                    $isSelected = in_array($product->id, $campaign_products);
                                                } else {
                                                    $isSelected = in_array($product->id, old('product_id', []));
                                                }
                                            @endphp
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-buying-price="{{ $product->buying_price ?? 0 }}"
                                                data-regular-price="{{ $product->regular_price }}"
                                                data-image="{{ $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/default-150x150.png') }}"
                                                data-category-id="{{ $product->category_id }}"
                                                        {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $product->name }} - ${{ $product->regular_price }}
                                                    </option>
                                        @endforeach
                                        </select>
                                        <small class="text-muted">Select products to include in this campaign</small>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="productTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th width="80">Image</th>
                                                    <th>Name</th>
                                                    <th width="100">Buying</th>
                                                    <th width="100">Regular</th>
                                                    <th width="150">Discount</th>
                                                    <th width="120">Final Price</th>
                                                    <th width="80">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($item) && $item->campaignProducts->count() > 0)
                                                    @foreach($item->campaignProducts as $index => $campaignProduct)
                                                        @php
                                                            $product = $campaignProduct->product;
                                                        @endphp
                                                        <tr data-product-id="{{ $product->id }}">
                                                            <td>{{ $index + 1 }}</td>
                                                            <td>
                                                                <img src="{{ $product->thumbnail ? asset($product->thumbnail) : asset('backend/assets/img/default-150x150.png') }}" 
                                                                     width="50" height="50" class="object-fit-cover rounded">
                                                            </td>
                                                            <td>{{ $product->name }}</td>
                                                            <td>${{ $product->buying_price ?? 0 }}</td>
                                                            <td>$<span class="regular-price">{{ $product->regular_price }}</span></td>
                                                            <td>
                                                                <input type="number" name="discount[{{ $product->id }}]" 
                                                                       class="discount form-control form-control-sm" 
                                                                       value="{{ $campaignProduct->discount ?? 0 }}" min="0" step="0.01" placeholder="0">
                                                                <select name="discount_type[{{ $product->id }}]" class="discount_type form-select form-select-sm mt-1">
                                                                    <option value="1" {{ ($campaignProduct->discount_type ?? 1) == 1 ? 'selected' : '' }}>Flat</option>
                                                                    <option value="2" {{ ($campaignProduct->discount_type ?? 1) == 2 ? 'selected' : '' }}>Percent</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                $<span class="final-price-text">
                                                                    {{ ($campaignProduct->final_price > 0 ? $campaignProduct->final_price : $product->regular_price) }}
                                                                </span>
                                                                <input type="hidden" name="final_price[{{ $product->id }}]" 
                                                                       class="final-price-input" 
                                                                       value="{{ ($campaignProduct->final_price > 0 ? $campaignProduct->final_price : $product->regular_price) }}">
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-sm remove-product">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- SEO --}}
                                <div class="tab-pane fade" id="seo-info" role="tabpanel">
                                    <div class="mb-3">
                                        <label class="form-label">Meta Title <small class="text-muted">[optional]</small></label>
                                        <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                                               value="{{ old('meta_title', $item->meta_title ?? '') }}"
                                               placeholder="Enter meta title" maxlength="60">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                        @error('meta_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Keywords <small class="text-muted">[optional]</small></label>
                                        <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror"
                                               value="{{ old('meta_keywords', $item->meta_keywords ?? '') }}"
                                               placeholder="Enter meta keywords (comma separated)">
                                        @error('meta_keywords')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Description <small class="text-muted">[optional]</small></label>
                                        <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror"
                                                  rows="3" placeholder="Enter meta description" maxlength="160">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                                        <small class="text-muted">Recommended: 150-160 characters</small>
                                        @error('meta_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Meta Image <small class="text-muted">[optional]</small></label>
                                        <input type="file" name="meta_image" id="meta_image" class="form-control @error('meta_image') is-invalid @enderror"
                                               accept="image/jpg, image/jpeg, image/png, image/webp">
                                        <div class="mt-2">
                                            <img id="metaImagePreview" 
                                                 src="{{ isset($item) && $item->meta_image ? asset($item->meta_image) : asset('backend/assets/img/default-150x150.png') }}" 
                                                 width="150" height="150" class="object-fit-cover rounded">
                                        </div>
                                        @error('meta_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                <i class="fa fa-arrow-left me-2"></i>Back
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save me-2"></i>@isset($item) Update @else Submit @endisset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function () {
        // Initialize Summernote
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Initialize Select2
        $('.js-select').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });

        // Initialize Select2 for multiple selection
        $('.js-select-multiple').select2({
            placeholder: "Select products",
            allowClear: true,
            width: '100%'
        });

        // Initialize Date Range Picker
        // Initialize Date Range Picker
        var start = moment().startOf('day');
        var end = moment().endOf('day').add(7, 'days');

        var durationVal = $('#duration').val();
        if (durationVal) {
            var parts = durationVal.split(' to ');
            if (parts.length === 2) {
                start = moment(parts[0], 'YYYY-MM-DD HH:mm:ss');
                end = moment(parts[1], 'YYYY-MM-DD HH:mm:ss');
            }
        }

        $('#duration').daterangepicker({
            timePicker: true,
            timePicker24Hour: true,
            timePickerSeconds: true,
            locale: {
                format: 'YYYY-MM-DD HH:mm:ss',
                separator: ' to ',
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                weekLabel: 'W',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            },
            startDate: start,
            endDate: end
        });

        // Thumbnail preview
        $('#thumbnail').on('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Meta image preview
        $('#meta_image').on('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#metaImagePreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Store all products for client-side filtering
        var allProducts = [];

        // Initialize allProducts array from the select options
        $('#product_id option').each(function() {
            if ($(this).val()) {
                allProducts.push({
                    value: $(this).val(),
                    name: $(this).data('name'),
                    buying_price: $(this).data('buying-price'),
                    regular_price: $(this).data('regular-price'),
                    image: $(this).data('image'),
                    category_id: $(this).data('category-id')
                });
            }
        });

        // Category change event - Filter products client-side
        $('#category_id').on('change', function () {
            var category_id = $(this).val();

            // Clear existing rows in product table
            $('#productTable tbody').empty();
            $('#productTable').hide();

            // Store current selections
            var currentSelections = $('#product_id').val();

            // Clear select2 and reset
            $('#product_id').val(null).trigger('change');
            $('#product_id').empty();

            // Filter products based on category
            var filteredProducts = [];
            if (category_id == '0') {
                // Show all products
                filteredProducts = allProducts;
            } else if (category_id) {
                // Filter by category
                filteredProducts = allProducts.filter(function(product) {
                    return product.category_id == category_id;
                });
            } else {
                // Show all if no category selected
                filteredProducts = allProducts;
            }

            // Build options HTML
            var html = '<option value="">Select Products</option>';
            if (filteredProducts.length > 0) {
                $.each(filteredProducts, function(key, product) {
                    var isSelected = currentSelections && currentSelections.includes(product.value.toString());
                    html += `<option value="${product.value}" 
                              data-name="${product.name}" 
                              data-buying-price="${product.buying_price}" 
                              data-regular-price="${product.regular_price}" 
                              data-image="${product.image}"
                              data-category-id="${product.category_id}"
                              ${isSelected ? 'selected' : ''}>
                              ${product.name} - $${product.regular_price}
                             </option>`;
                });
            } else {
                html += '<option value="" disabled>No products found in this category</option>';
            }

            $('#product_id').html(html);

            // Reinitialize select2
            $('.js-select-multiple').select2({
                placeholder: "Select products",
                allowClear: true,
                width: '100%'
            });

            // Trigger change to rebuild the table
            $('#product_id').trigger('change');
        });

        // Product selection handling
        $('#product_id').on('change', function() {
            const selectedIds = $(this).val() || [];
            const tableBody = $('#productTable tbody');

            // Clear existing rows
            tableBody.find('tr').each(function() {
                const productId = $(this).data('product-id');
                if (!selectedIds.includes(productId.toString())) {
                    $(this).remove();
                }
            });

            // Add new products
            $.each(selectedIds, function(index, productId) {
                if (!tableBody.find(`tr[data-product-id="${productId}"]`).length) {
                    const option = $('#product_id option[value="' + productId + '"]');
                    addProductToTable(option, index + 1);
                }
            });

            // Update row numbers
            updateRowNumbers();

            // Show/hide table
            if (tableBody.find('tr').length > 0) {
                $('#productTable').show();
            } else {
                $('#productTable').hide();
            }
        });

        function addProductToTable(option, rowNumber) {
            const productId = option.val();
            const productName = option.data('name') || 'Unknown Product';
            const buyingPrice = parseFloat(option.data('buying-price')) || 0;
            const regularPrice = parseFloat(option.data('regular-price')) || 0;
            const image = option.data('image') || '{{ asset("backend/assets/img/default-150x150.png") }}';

            const row = `
                <tr data-product-id="${productId}">
                    <td>${rowNumber}</td>
                    <td>
                        <img src="${image}" width="50" height="50" class="object-fit-cover rounded">
                    </td>
                    <td>${productName}</td>
                    <td>$${buyingPrice.toFixed(2)}</td>
                    <td>$<span class="regular-price">${regularPrice.toFixed(2)}</span></td>
                    <td>
                        <input type="number" name="discount[${productId}]" 
                               class="discount form-control form-control-sm" 
                               value="0" min="0" step="0.01" placeholder="0">
                        <select name="discount_type[${productId}]" class="discount_type form-select form-select-sm mt-1">
                            <option value="1" selected>Flat</option>
                            <option value="2">Percent</option>
                        </select>
                    </td>
                    <td>
                        $<span class="final-price-text">${regularPrice.toFixed(2)}</span>
                        <input type="hidden" name="final_price[${productId}]" 
                               class="final-price-input" 
                               value="${regularPrice.toFixed(2)}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm remove-product">
                            <i class="fa fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;

            $('#productTable tbody').append(row);
            $('#productTable').show();
        }

        function updateRowNumbers() {
            $('#productTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Remove product from table
        $(document).on('click', '.remove-product', function() {
            const row = $(this).closest('tr');
            const productId = row.data('product-id');

            // Remove from select2
            $('#product_id option[value="' + productId + '"]').prop('selected', false);
            $('#product_id').trigger('change.select2');

            // Remove row
            row.remove();

            // Update row numbers
            updateRowNumbers();

            // Hide table if empty
            if ($('#productTable tbody tr').length === 0) {
                $('#productTable').hide();
            }
        });

        // Calculate final price when discount changes
        $(document).on('input change', '.discount, .discount_type', function() {
            const row = $(this).closest('tr');
            const regularPrice = parseFloat(row.find('.regular-price').text()) || 0;
            const discount = parseFloat(row.find('.discount').val()) || 0;
            const discountType = row.find('.discount_type').val();

            let finalPrice;
            if (discountType == '1') { // Flat
                finalPrice = regularPrice - discount;
            } else { // Percent
                finalPrice = regularPrice - (regularPrice * discount / 100);
            }

            finalPrice = Math.max(finalPrice, 0).toFixed(2);

            row.find('.final-price-text').text(finalPrice);
            row.find('.final-price-input').val(finalPrice);
        });

        // Form validation
        $('#campaignForm').on('submit', function(e) {
            const title = $('input[name="title"]').val().trim();
            const duration = $('input[name="duration"]').val().trim();
            const categoryId = $('select[name="category_id"]').val();

            if (!title) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Title is required.',
                });
                $('input[name="title"]').focus();
                return false;
            }

            if (!duration) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Duration is required.',
                });
                $('input[name="duration"]').focus();
                return false;
            }

            if (!categoryId && categoryId !== '0') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select a category or choose "All Categories".',
                });
                $('select[name="category_id"]').focus();
                return false;
            }

            @empty($item)
                if (!$('#thumbnail')[0].files[0]) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Thumbnail image is required.',
                    });
                    return false;
                }
            @endempty

            // Show loading
            $(this).find('button[type="submit"]').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm"></span> Processing...');

            return true;
        });

        // Initialize product table on page load
        if ($('#productTable tbody tr').length > 0) {
            $('#productTable').show();
        } else {
            $('#productTable').hide();
        }
    });
    </script>
@endpush