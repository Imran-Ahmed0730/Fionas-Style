@extends('backend.master')
@section('title')
    @isset($item) Edit @else Add @endisset Product
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.6.0/bootstrap-tagsinput.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .bootstrap-tagsinput .tag {
            background: #505050;
            border-radius: 4px;
            padding: 2px 6px;
            margin-right: 2px;
        }

        .bootstrap-tagsinput {
            width: 100%;
            min-height: 37px;
        }

        .tab-content {
            padding: 20px 0;
        }

        .nav-pills .nav-link.active {
            background-color: #4361ee;
            color: white;
        }

        .image-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .image-preview {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .variant-image-preview {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 3px;
            margin-right: 5px;
        }

        .attribute-values-section {
            border-left: 3px solid #4361ee;
            padding-left: 15px;
            margin-left: 10px;
        }

        .form-label.required:after {
            content: " *";
            color: red;
        }

        .invalid-feedback {
            display: block;
        }

        .btn-group .btn {
            padding: 0.375rem 0.75rem;
        }

        .form-check.form-switch {
            padding-left: 3.5rem;
        }

        .form-check-input:checked {
            background-color: #4361ee;
            border-color: #4361ee;
        }

        .select2-container--default .select2-selection--multiple,
        .select2-selection--single {
            min-height: 40px;
        }

        .table-variants th {
            background-color: #f8f9fa;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Product</h3>
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
                        <a href="{{ route('admin.product.index') }}">Product</a>
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
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">@isset($item) Edit @else Add @endisset Product Information</div>
                            <div class="card-tools ms-auto">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#productSetting"
                                    title="Product Settings" class="btn btn-secondary me-2">
                                    <i class="fa fa-cog me-2"></i>Settings
                                </button>
                                <a href="{{ route('admin.product.index') }}" data-bs-toggle="tooltip" title="View Products"
                                    class="btn btn-primary">
                                    <i class="fa fa-list me-2"></i>View Products
                                </a>
                            </div>
                        </div>
                        <form id="product_form"
                            action="@isset($item){{ route('admin.product.update') }}@else{{ route('admin.product.store') }}@endisset"
                            method="post" enctype="multipart/form-data">
                            @csrf
                            @isset($item)
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <input type="hidden" name="is_variant" id="is_variant" value="{{ $item->is_variant }}">
                            @else
                                <input type="hidden" name="is_variant" id="is_variant" value="0">
                            @endisset
                            <input type="hidden" name="sku" id="sku"
                                value="@isset($item){{$item->sku}}@else{{old('sku')}}@endisset"
                                class="form-control @error('sku') is-invalid @enderror" required>

                            <input type="hidden" name="variantUpdate" id="variantUpdate" value="0">

                            <div class="card-body">
                                <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" id="pills-tab-without-border"
                                    role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill"
                                            href="#primary-info" role="tab" aria-controls="primary-info"
                                            aria-selected="true">
                                            Primary Info
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-price-tab-nobd" data-bs-toggle="pill"
                                            href="#price-info" role="tab" aria-controls="price-info" aria-selected="false">
                                            Price Info
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-attribute-tab-nobd" data-bs-toggle="pill"
                                            href="#attribute-info" role="tab" aria-controls="attribute-info"
                                            aria-selected="false">
                                            Attributes
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-image-tab-nobd" data-bs-toggle="pill"
                                            href="#image-info" role="tab" aria-controls="image-info" aria-selected="false">
                                            Images
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-shipping-tab-nobd" data-bs-toggle="pill"
                                            href="#shipping-info" role="tab" aria-controls="shipping-info"
                                            aria-selected="false">
                                            Shipping
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-seo-tab-nobd" data-bs-toggle="pill" href="#seo-info"
                                            role="tab" aria-controls="seo-info" aria-selected="false">
                                            SEO
                                        </a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-additional-tab-nobd" data-bs-toggle="pill"
                                            href="#additional-info" role="tab" aria-controls="additional-info"
                                            aria-selected="false">
                                            Additional
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-without-border-tabContent">
                                    <!-- Primary Info Tab -->
                                    <div class="tab-pane fade show active" id="primary-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input id="name" type="text" name="name"
                                                    value="@isset($item){{ $item->name }}@else{{ old('name') }}@endisset"
                                                    placeholder="Enter product name"
                                                    class="form-control @error('name') is-invalid @enderror" required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="category_id" class="form-label">Category</label>
                                                <select name="category_id" id="category_id"
                                                    class="form-control js-example-basic-single @error('category_id') is-invalid @enderror"
                                                    required>
                                                    <option value="" disabled selected>Select Category</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" @isset($item){{ $item->category_id == $category->id ? 'selected' : '' }} @else{{ old('category_id') == $category->id ? 'selected' : '' }}@endisset>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="brand_id" class="form-label">Brand <small
                                                        class="text-muted">[optional]</small></label>
                                                <select name="brand_id" id="brand_id"
                                                    class="form-control js-example-basic-single @error('brand_id') is-invalid @enderror">
                                                    <option value="">Select Brand</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" @isset($item){{ $item->brand_id == $brand->id ? 'selected' : '' }} @else{{ old('brand_id') == $brand->id ? 'selected' : '' }}@endisset>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="unit_id" class="form-label">Unit</label>
                                                <select name="unit_id" id="unit_id"
                                                    class="form-control @error('unit_id') is-invalid @enderror" required>
                                                    <option value="" disabled selected>Select Unit</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{ $unit->id }}" @isset($item){{ $item->unit_id == $unit->id ? 'selected' : '' }} @else{{ old('unit_id') == $unit->id ? 'selected' : '' }}@endisset>
                                                            {{ $unit->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('unit_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="short_description" class="form-label">Short Description</label>
                                                <textarea id="short_description" name="short_description" rows="2"
                                                    placeholder="Enter short description"
                                                    class="form-control @error('short_description') is-invalid @enderror"
                                                    required>@isset($item){{ $item->short_description }}@else{{ old('short_description') }}@endisset</textarea>
                                                @error('short_description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="detailed_description" class="form-label">Detailed
                                                    Description</label>
                                                <textarea id="detailed_description" name="detailed_description" rows="5"
                                                    class="form-control summernote @error('detailed_description') is-invalid @enderror"
                                                    required>@isset($item){{ $item->detailed_description }}@else{{ old('detailed_description') }}@endisset</textarea>
                                                @error('detailed_description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Status</label>
                                                <div class="d-flex gap-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            id="status1" value="1" @isset($item){{ $item->status == 1 ? 'checked' : '' }}@else checked @endisset>
                                                        <label class="form-check-label" for="status1">Active</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            id="status0" value="0" @isset($item){{ $item->status == 0 ? 'checked' : '' }}@endisset>
                                                        <label class="form-check-label" for="status0">Inactive</label>
                                                    </div>
                                                </div>
                                                @error('status')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price Info Tab -->
                                    <div class="tab-pane fade" id="price-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="regular_price" class="form-label">Regular Price</label>
                                                <input id="regular_price" type="number" step="0.01" name="regular_price"
                                                    placeholder="Enter regular price"
                                                    value="@isset($item){{ $item->regular_price }}@else{{ old('regular_price') }}@endisset"
                                                    class="form-control @error('regular_price') is-invalid @enderror"
                                                    required>
                                                @error('regular_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="selling_price" class="form-label">Selling Price</label>
                                                <input id="selling_price" type="number" step="0.01" name="selling_price"
                                                    placeholder="Enter selling price"
                                                    value="@isset($item){{ $item->selling_price }}@else{{ old('selling_price') }}@endisset"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    required>
                                                @error('selling_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="discount" class="form-label">Discount</label>
                                                <input id="discount" type="number" step="0.01" name="discount"
                                                    placeholder="Enter discount"
                                                    value="@isset($item){{ $item->discount }}@else{{ old('discount') ?? 0 }}@endisset"
                                                    class="form-control @error('discount') is-invalid @enderror">
                                                @error('discount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="discount_type" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount_type"
                                                    class="form-control @error('discount_type') is-invalid @enderror">
                                                    <option value="1" @isset($item){{ $item->discount_type == 1 ? 'selected' : '' }}@else selected @endisset>Flat</option>
                                                    <option value="2" @isset($item){{ $item->discount_type == 2 ? 'selected' : '' }}@endisset>Percent</option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="tax" class="form-label">Tax</label>
                                                <input id="tax" type="number" step="0.01" name="tax" placeholder="Enter tax"
                                                    value="@isset($item){{ $item->tax }}@else{{ old('tax') ?? 0 }}@endisset"
                                                    class="form-control @error('tax') is-invalid @enderror" required>
                                                @error('tax')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="tax_inclusion" class="form-label">Tax Inclusion</label>
                                                <select name="tax_inclusion" id="tax_inclusion"
                                                    class="form-control @error('tax_inclusion') is-invalid @enderror">
                                                    <option value="1" @isset($item){{ $item->tax_inclusion == 1 ? 'selected' : '' }}@else selected @endisset>Included</option>
                                                    <option value="2" @isset($item){{ $item->tax_inclusion == 2 ? 'selected' : '' }}@endisset>Excluded</option>
                                                </select>
                                                @error('tax_inclusion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Attributes Tab -->
                                    <div class="tab-pane fade" id="attribute-info" role="tabpanel">
                                        <input type="hidden" name="total_variant" id="total_variant" value="0">
                                        <input type="hidden" name="has_variants" id="has_variants" value="0">
                                        <input type="hidden" name="emptyVariantTable" id="emptyVariantTable" value="1">

                                        <div class="mb-4">
                                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                                <label class="form-label me-3 mb-0">Color Variations</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" id="color_switch" type="checkbox"
                                                        role="switch" @if(isset($item) && $item->color != null) checked
                                                        @endif>
                                                    <label class="form-check-label" for="color_switch"></label>
                                                </div>
                                            </div>
                                            <div
                                                class="color-selection @if(!isset($item) || $item->color == null) d-none @endif">
                                                @php
                                                    $selected_color = [];
                                                    if (isset($item) && $item->color != null) {
                                                        $selected_color = json_decode($item->color);
                                                    }
                                                @endphp
                                                <label class="form-label mb-2">Select Colors</label>
                                                <select name="color_id[]" id="color_id"
                                                    class="form-select js-example-basic-single" multiple="multiple">
                                                    @foreach($colors as $color)
                                                        <option value="{{ $color->name }}" @if(in_array($color->name, $selected_color)) selected @endif>
                                                            {{ $color->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Select multiple colors if your product has color
                                                    variations</small>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                                <label class="form-label me-3 mb-0">Product Attributes</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" id="attribute_switch" type="checkbox"
                                                        role="switch" @if(isset($item) && $item->attribute_values != null)
                                                        checked @endif>
                                                    <label class="form-check-label" for="attribute_switch"></label>
                                                </div>
                                            </div>

                                            <div
                                                class="attribute-selection @if(!isset($item) || $item->attribute_values == null) d-none @endif">
                                                @php
                                                    $attribute_array = [];
                                                    if (isset($item) && $item->attribute_values != null) {
                                                        $attribute_array = json_decode($item->attribute_values, true);
                                                    }
                                                @endphp
                                                <label class="form-label mb-2">Select Attributes</label>
                                                <select name="attribute_id[]" id="attribute_id"
                                                    class="form-select js-example-basic-single" multiple="multiple">
                                                    @foreach($attributes as $attribute)
                                                        <option value="{{ $attribute->id }}" @if(isset($attribute_array) && array_key_exists($attribute->id, $attribute_array)) selected @endif>
                                                            {{ $attribute->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Select attributes like Size, Material,
                                                    etc.</small>
                                            </div>

                                            <div id="attributeValueSelectionContainer"
                                                class="attribute-values-section mt-3">
                                                @if(isset($item) && $item->attribute_values != null)
                                                    @foreach($attribute_array as $key => $selected_values)
                                                        @php
                                                            $attr = \App\Models\Admin\Attribute::find($key);
                                                        @endphp
                                                        @if($attr)
                                                            <div class="mb-3 attribute-value-selection" data-attribute-id="{{ $key }}">
                                                                <label class="form-label">{{ $attr->name }}</label>
                                                                <select name="attribute_value_id[{{ $key }}][]"
                                                                    class="form-select js-example-basic-single"
                                                                    data-attr-name="{{ $attr->name }}" multiple="multiple">
                                                                    @foreach($attr->attributeValues as $attr_value)
                                                                        <option value="{{ $attr_value->value }}" {{ in_array($attr_value->value, $selected_values) ? 'selected' : '' }}>
                                                                            {{ $attr_value->value }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Generate Variants Button -->
                                        <div class="mb-4">
                                            <button type="button" id="generateVariantsBtn" class="btn btn-primary"
                                                @if(!isset($item) || $item->is_variant == 0) disabled @endif>
                                                <i class="fa fa-cogs me-2"></i>Generate Variants
                                            </button>
                                            <button type="button" id="clearVariantsBtn" class="btn btn-danger ms-2">
                                                <i class="fa fa-trash me-2"></i>Clear Variants
                                            </button>
                                        </div>

                                        <!-- Variants Table Container -->
                                        <div id="variantsTableContainer">
                                            @isset($item)
                                                @if($item->is_variant == 1 && $item->variants->count() > 0)
                                                    <div class="alert alert-info">
                                                        <i class="fa fa-info-circle me-2"></i>
                                                        <strong>Note:</strong> Existing variants found. You can edit them below.
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-variants">
                                                            <thead>
                                                                <tr>
                                                                    <th>Variant</th>
                                                                    <th>Price</th>
                                                                    <th>Image [optional]</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($item->variants as $key => $variant)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="existing_variant[{{ $key }}][id]"
                                                                                value="{{ $variant->id }}">
                                                                            <input type="hidden"
                                                                                name="existing_variant[{{ $key }}][name]"
                                                                                value="{{ $variant->name }}">
                                                                            <input type="hidden"
                                                                                name="existing_variant[{{ $key }}][attr_name]"
                                                                                value="{{ $variant->attr_name }}">
                                                                            <input type="hidden"
                                                                                name="existing_variant[{{ $key }}][sku]"
                                                                                value="{{ $variant->sku }}">
                                                                            {{ $variant->name }}
                                                                        </td>
                                                                        <td>
                                                                            <input type="number" step="0.01"
                                                                                name="existing_variant[{{ $key }}][price]"
                                                                                class="form-control" placeholder="Price"
                                                                                value="{{ $variant->regular_price }}" required>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex align-items-center">
                                                                                <img id="variantPreview{{ $key }}"
                                                                                    src="{{ asset($variant->image ?? 'backend/assets/img/default-150x150.png') }}"
                                                                                    class="img-thumbnail variant-image-preview me-2 mt-2 variant-preview"
                                                                                    style="width:50px;height:50px;cursor:pointer;"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#imagePreviewModal"
                                                                                    alt="{{$variant->image ?? ''}}">

                                                                                <input type="file"
                                                                                    name="existing_variant[{{ $key }}][image]"
                                                                                    class="form-control variant-image-input"
                                                                                    accept="image/jpeg, image/png, image/jpg">
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            @endisset
                                        </div>
                                    </div>

                                    <!-- Images Tab -->
                                    <div class="tab-pane fade" id="image-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="thumbnail" class="form-label">Thumbnail</label>
                                                <input type="file" name="thumbnail" id="thumbnail"
                                                    class="form-control @error('thumbnail') is-invalid @enderror"
                                                    accept="image/jpeg, image/png, image/jpg, image/webp" @isset($item) @else required @endisset>
                                                <small class="text-muted">Recommended: 500x500px, Max: 2MB</small>
                                                <div class="mt-2">
                                                    @isset($item)
                                                        <img src="{{ asset($item->thumbnail) }}" id="previewThumbnail"
                                                            class="image-preview" alt="Thumbnail Preview">
                                                    @else
                                                        <img src="{{ asset('backend/assets/img/default-150x150.png') }}"
                                                            id="previewThumbnail" class="image-preview" alt="Thumbnail Preview">
                                                    @endisset
                                                </div>
                                                @error('thumbnail')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="gallery" class="form-label">Gallery Images
                                                    <small>[optional]</small></label>
                                                <input type="file" name="images[]" id="gallery"
                                                    class="form-control @error('images') is-invalid @enderror"
                                                    accept="image/jpeg, image/png, image/jpg, image/webp" multiple>
                                                <small class="text-muted">Select multiple images (Max 10 images, 2MB
                                                    each)</small>
                                                <div id="previewGallery" class="image-preview-container mt-2">
                                                    @isset($item)
                                                        @foreach($item->gallery as $img)
                                                            <div class="position-relative" id="gallery-img-{{ $img->id }}">
                                                                <img src="{{ asset($img->image) }}" width="60px" height="60px"
                                                                    class="image-preview border" alt="Gallery Image">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                                    onclick="deleteImage({{ $img->id }})"
                                                                    style="transform: translate(50%, -50%);">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    @endisset
                                                </div>
                                                @error('images')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Shipping Info Tab -->
                                    <div class="tab-pane fade" id="shipping-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="shipping_cost" class="form-label">Shipping Cost
                                                    <small>[optional]</small></label>
                                                <input id="shipping_cost" type="number" step="0.01" name="shipping_cost"
                                                    placeholder="Enter shipping cost"
                                                    value="@isset($item){{ $item->shipping_cost }}@else{{ old('shipping_cost') }}@endisset"
                                                    class="form-control @error('shipping_cost') is-invalid @enderror">
                                                @error('shipping_cost')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="shipping_time" class="form-label">Shipping Time
                                                    <small>[optional]</small></label>
                                                <input id="shipping_time" type="text" name="shipping_time"
                                                    placeholder="e.g., 3-5 business days"
                                                    value="@isset($item){{ $item->shipping_time }}@else{{ old('shipping_time') }}@endisset"
                                                    class="form-control @error('shipping_time') is-invalid @enderror">
                                                @error('shipping_time')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="shipping_return_policy" class="form-label">Shipping & Return
                                                    Policy <small>[optional]</small></label>
                                                <textarea id="shipping_return_policy" name="shipping_return_policy" rows="5"
                                                    class="form-control summernote @error('shipping_return_policy') is-invalid @enderror">@isset($item){{ $item->shipping_return_policy }}@else{{ old('shipping_return_policy') }}@endisset</textarea>
                                                @error('shipping_return_policy')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SEO Info Tab -->
                                    <div class="tab-pane fade" id="seo-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_title" class="form-label">Meta Title
                                                    <small>[optional]</small></label>
                                                <input type="text" name="meta_title" id="meta_title"
                                                    class="form-control @error('meta_title') is-invalid @enderror"
                                                    value="@isset($item){{ $item->meta_title }}@else{{ old('meta_title') }}@endisset"
                                                    placeholder="Enter meta title" maxlength="60">
                                                <small class="text-muted">Recommended: 50-60 characters</small>
                                                @error('meta_title')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_keywords" class="form-label">Meta Keywords
                                                    <small>[optional]</small></label>
                                                <input type="text" name="meta_keywords" id="meta_keywords"
                                                    class="form-control @error('meta_keywords') is-invalid @enderror"
                                                    value="@isset($item){{ $item->meta_keywords }}@else{{ old('meta_keywords') }}@endisset"
                                                    placeholder="Enter meta keywords (comma separated)">
                                                @error('meta_keywords')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_description" class="form-label">Meta Description
                                                    <small>[optional]</small></label>
                                                <textarea name="meta_description" id="meta_description" rows="3"
                                                    class="form-control @error('meta_description') is-invalid @enderror"
                                                    placeholder="Enter meta description"
                                                    maxlength="160">@isset($item){{ $item->meta_description }}@else{{ old('meta_description') }}@endisset</textarea>
                                                <small class="text-muted">Recommended: 150-160 characters</small>
                                                @error('meta_description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_image" class="form-label">Meta Image
                                                    <small>[optional]</small></label>
                                                <input type="file" name="meta_image" id="meta_image"
                                                    class="form-control @error('meta_image') is-invalid @enderror"
                                                    accept="image/jpg, image/jpeg, image/png">
                                                <div class="mt-2">
                                                    @isset($item)
                                                        @if($item->meta_image)
                                                            <img src="{{ asset($item->meta_image) }}" id="previewMetaImage"
                                                                class="image-preview" alt="Meta Image Preview">
                                                        @else
                                                            <img src="{{ asset('backend/assets/img/default-150x150.png') }}"
                                                                id="previewMetaImage" class="image-preview"
                                                                alt="Meta Image Preview">
                                                        @endif
                                                    @else
                                                        <img src="{{ asset('backend/assets/img/default-150x150.png') }}"
                                                            id="previewMetaImage" class="image-preview"
                                                            alt="Meta Image Preview">
                                                    @endisset
                                                </div>
                                                @error('meta_image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Additional Info Tab -->
                                    <div class="tab-pane fade" id="additional-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="additional_information" class="form-label">Additional
                                                    Information <small>[optional]</small></label>
                                                <textarea id="additional_information" name="additional_information" rows="5"
                                                    class="form-control summernote @error('additional_information') is-invalid @enderror">@isset($item){{ $item->additional_information }}@else{{ old('additional_information') }}@endisset</textarea>
                                                @error('additional_information')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="tags" class="form-label">Tags <small>[optional]</small></label>
                                                <input type="text" name="tags" id="tags"
                                                    value="@isset($item){{ $item->tags }}@else{{ old('tags') }}@endisset"
                                                    placeholder="Enter tags and press Enter"
                                                    class="form-control tags-input @error('tags') is-invalid @enderror">
                                                <small class="text-muted">Press Enter after each tag. Maximum 10 tags
                                                    allowed.</small>
                                                @error('tags')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label mb-2">Product Features</label>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="cod_available"
                                                        id="cod_available" value="1" @isset($item){{ $item->cod_available == 1 ? 'checked' : '' }}@endisset>
                                                    <label class="form-check-label" for="cod_available">
                                                        Cash on Delivery Available
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="include_to_todays_deal" id="include_to_todays_deal" value="1"
                                                        @isset($item){{ $item->include_to_todays_deal == 1 ? 'checked' : '' }}@endisset>
                                                    <label class="form-check-label" for="include_to_todays_deal">
                                                        Include to Today's Deal
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_featured"
                                                        id="is_featured" value="1" @isset($item){{ $item->is_featured == 1 ? 'checked' : '' }}@endisset>
                                                    <label class="form-check-label" for="is_featured">
                                                        Featured
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_replaceable"
                                                        id="is_replaceable" value="1" @isset($item){{ $item->is_replaceable == 1 ? 'checked' : '' }}@endisset>
                                                    <label class="form-check-label" for="is_replaceable">
                                                        Replaceable
                                                    </label>
                                                </div>
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" name="is_trending"
                                                        id="is_trending" value="1" @isset($item){{ $item->is_trending == 1 ? 'checked' : '' }}@endisset>
                                                    <label class="form-check-label" for="is_trending">
                                                        Trending
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                                    <i class="fa fa-arrow-left me-2"></i>Back
                                </button>
                                <button type="submit" id="submit_btn" class="btn btn-primary submitBtn">
                                    @isset($item)
                                        <i class="fa fa-save me-2"></i>Update Product
                                    @else
                                        <i class="fa fa-plus me-2"></i>Add Product
                                    @endisset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Settings Modal -->
    <div class="modal fade" id="productSetting" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="productSettingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="productSettingModalLabel">Product Settings</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.setting.update-fields') }}" method="post" id="productSettingsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="product_sku_syntax" class="form-label">Main Product SKU Syntax</label>
                            <input type="text" name="product_sku_syntax" id="product_sku_syntax"
                                value="{{ getSetting('product_sku_syntax') }}" placeholder="Enter SKU syntax products"
                                class="form-control" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="variant_product_sku_syntax" class="form-label">Variant SKU Syntax</label>
                            <input type="text" name="variant_product_sku_syntax" id="variant_product_sku_syntax"
                                value="{{ getSetting('variant_product_sku_syntax') }}"
                                placeholder="Enter SKU syntax for product's variants" class="form-control" disabled>
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

    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="modalPreviewImage" class="img-fluid rounded" alt="Variant Preview">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.6.0/bootstrap-tagsinput.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            initializeComponents();
            setupEventHandlers();
            @isset($item)
            @else
                generateSKU();
            @endisset
            initializeVariantManagement();
        });

        function generateSKU() {
            $.ajax({
                url: "{{ route('admin.product.sku.generate') }}",
                method: "get",
                success: function (response) {
                    if (response.sku) {
                        $('#sku').val(response.sku);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed to Generate SKU',
                            text: 'Please try again.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed to Generate SKU',
                        text: 'Please try again.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }

        function initializeComponents() {
            // Initialize Select2
            $('.js-example-basic-single').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });

            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize Tagsinput
            if ($.fn.tagsinput) {
                $('#tags').tagsinput({
                    confirmKeys: [13, 44],
                    maxTags: 10,
                    trimValue: true,
                    tagClass: 'badge bg-secondary'
                });

                // Prevent form submission on Enter in tags input
                $('.bootstrap-tagsinput input').on('keydown', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        $(this).blur().focus();
                        return false;
                    }
                });
            }
        }

        function setupEventHandlers() {
            // Image previews
            $('#thumbnail').change(function () { previewImage(this, '#previewThumbnail'); });
            $('#meta_image').change(function () { previewImage(this, '#previewMetaImage'); });

            $('#gallery').change(function () {
                $('#previewGallery').empty();
                if (this.files && this.files.length > 0) {
                    Array.from(this.files).forEach(file => previewImageFile(file, '#previewGallery'));
                }
            });

            // Price calculation
            $('#regular_price, #discount, #discount_type').on('input change', calculateSellingPrice);
            calculateSellingPrice();

            // Modal handlers
            $('#editButton').on('click', function () {
                $('#productSettingsForm .modal-body input').prop('disabled', false);
                $(this).addClass('d-none');
                $('#saveButton, #cancelButton').removeClass('d-none');
            });

            $('#cancelButton, .btn-close').on('click', function () {
                $('#productSettingsForm .modal-body input').prop('disabled', true);
                $('#editButton').removeClass('d-none');
                $('#saveButton, #cancelButton').addClass('d-none');
            });

            // Form submission
            $('#product_form').on('submit', validateForm);

            $('#productSettingsForm').on('submit', function (e) {
                e.preventDefault();
                const $form = $(this);
                const $saveBtn = $('#saveButton');
                const originalText = $saveBtn.html();

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
                            $('#productSetting').modal('hide');
                            // Disable inputs again
                            $('#productSettingsForm .modal-body input').prop('disabled', true);
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
        }

        function previewImage(input, selector) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => $(selector).attr('src', e.target.result);
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewImageFile(file, selector) {
            const reader = new FileReader();
            reader.onload = e => {
                $(selector).append(`
                        <div class="position-relative">
                            <img src="${e.target.result}" width="60px" height="60px" class="image-preview border" alt="">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-preview"
                                    style="transform: translate(50%, -50%);">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    `);
            };
            reader.readAsDataURL(file);
        }

        $(document).on('click', '.remove-preview', function () {
            $(this).closest('.position-relative').remove();
        });

        function calculateSellingPrice() {
            const regularPrice = parseFloat($('#regular_price').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const discountType = $('#discount_type').val();
            let sellingPrice = regularPrice;

            if (discount > 0) {
                sellingPrice = discountType == '1'
                    ? regularPrice - discount
                    : regularPrice - (regularPrice * discount / 100);
                sellingPrice = Math.max(0, sellingPrice);
            }

            $('#selling_price').val(sellingPrice.toFixed(2));
        }

        // Delete image via AJAX
        window.deleteImage = function (id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This image will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'DELETE',
                        url: '{{ route("admin.product.image.delete") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#gallery-img-' + id).remove();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Image has been deleted.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: response.message || 'Failed to delete image.',
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the image.',
                            });
                        }
                    });
                }
            });
        }

        function initializeVariantManagement() {
            // Color switch
            $('#color_switch').change(function () {
                $('.color-selection').toggleClass('d-none', !this.checked);
                if (!this.checked) $('#color_id').val(null).trigger('change');
                updateGenerateButton();
            });

            // Attribute switch
            $('#attribute_switch').change(function () {
                $('.attribute-selection').toggleClass('d-none', !this.checked);
                if (!this.checked) {
                    $('#attribute_id').val(null).trigger('change');
                    $('#attributeValueSelectionContainer').empty();
                }
                updateGenerateButton();
            });

            // Attribute selection
            $('#attribute_id').change(function () {
                const attributeIds = $(this).val();
                $('#attributeValueSelectionContainer').empty();

                if (attributeIds && attributeIds.length > 0) {
                    Swal.fire({ title: 'Loading...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                    $.ajax({
                        method: 'GET',
                        url: '{{ route("admin.product.attribute.values.get") }}',
                        data: { attribute_id: attributeIds },
                        success: function (data) {
                            Swal.close();
                            if (data && data.length > 0) {
                                const html = data.map(attribute => {
                                    if (!attribute) return '';
                                    return `
                                            <div class="mb-3 attribute-value-selection" data-attribute-id="${attribute.id}">
                                                <label class="form-label">${attribute.name || 'Unknown Attribute'}</label>
                                                <select name="attribute_value_id[${attribute.id}][]"
                                                        class="form-select js-example-basic-single attribute-value-select"
                                                        data-attr-name="${attribute.name || 'Attribute'}" multiple="multiple">
                                                    ${(attribute.attribute_values || []).map(v => `<option value="${v.value}">${v.value}</option>`).join('')}
                                                </select>
                                            </div>
                                        `;
                                }).join('');

                                $('#attributeValueSelectionContainer').html(html);
                                $('.js-example-basic-single').select2();
                            }
                            updateGenerateButton();
                        },
                        error: () => {
                            Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to load attribute values.' });
                        }
                    });
                } else {
                    updateGenerateButton();
                }
            });

            // Buttons
            $('#generateVariantsBtn').click(generateVariants);
            $('#clearVariantsBtn').click(function () {
                Swal.fire({
                    title: 'Clear All Variants?',
                    text: "This will remove all generated variants!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, clear all!'
                }).then(result => {
                    if (result.isConfirmed) {
                        $('#variantsTableContainer').empty();
                        $('#total_variant, #has_variants').val(0);
                        $('#variantUpdate, #emptyVariantTable').val(1);
                        $('#generateVariantsBtn').prop('disabled', true);
                        $('#is_variant').val(0);
                        Swal.fire({ icon: 'success', title: 'Cleared!', text: 'Variants cleared.', timer: 1500, showConfirmButton: false });
                    }
                });
            });

            function updateGenerateButton() {
                const hasColors = $('#color_switch').is(':checked') && $('#color_id').val()?.length > 0;
                const hasAttributes = $('#attribute_switch').is(':checked') && $('#attribute_id').val()?.length > 0;
                const hasAttributeValues = $('#attributeValueSelectionContainer .attribute-value-select').length > 0;
                $('#generateVariantsBtn').prop('disabled', !(hasColors || (hasAttributes && hasAttributeValues)));
            }

            function generateVariants() {
                const colors = $('#color_id').val() || [];
                const selectedAttributes = $('#attribute_id').val() || [];
                const attributeValues = {};

                // Collect attribute values
                $('#attributeValueSelectionContainer select').each(function () {
                    const matches = $(this).attr('name')?.match(/\[(\d+)\]/);
                    if (matches) {
                        const attrId = matches[1];
                        const selectedValues = $(this).val() || [];
                        if (selectedValues.length > 0 && selectedAttributes.includes(attrId)) {
                            attributeValues[attrId] = selectedValues;
                        }
                    }
                });

                if (colors.length === 0 && Object.keys(attributeValues).length === 0) {
                    Swal.fire({ icon: 'error', title: 'No Variations', text: 'Select colors or attribute values.' });
                    return;
                }

                const variants = generateVariantCombinations(colors, attributeValues);

                if (variants.length === 0) {
                    Swal.fire({ icon: 'error', title: 'No Variants', text: 'Check your selections.' });
                    return;
                }

                $('#total_variant').val(variants.length);
                $('#has_variants').val(1);
                $('#is_variant').val(1);
                $('#variantUpdate').val(1);
                $('#emptyVariantTable').val(0);

                buildVariantsTable(variants);
                Swal.fire({ icon: 'success', title: 'Generated!', text: `${variants.length} variants created.`, timer: 2000, showConfirmButton: false });
            }

            function generateVariantCombinations(colors, attributeValues) {
                const variants = [];
                const attributeKeys = Object.keys(attributeValues);

                // Get all combinations of attribute values
                function getCombinations(values) {
                    const keys = Object.keys(values);
                    const combinations = [];

                    function combine(index, current) {
                        if (index === keys.length) {
                            if (current.length > 0) {
                                combinations.push(current);
                            }
                            return;
                        }
                        const key = keys[index];
                        const keyValues = values[key];
                        for (const value of keyValues) {
                            combine(index + 1, [...current, { key, value }]);
                        }
                    }
                    combine(0, []);
                    return combinations;
                }

                const attributeCombinations = getCombinations(attributeValues);

                // Generate variants based on selections
                if (colors.length > 0 && attributeCombinations.length === 0) {
                    // Only colors
                    colors.forEach(color => {
                        variants.push({
                            name: color,
                            attr_name: 'Color',
                            display_name: color
                        });
                    });
                } else if (colors.length === 0 && attributeCombinations.length > 0) {
                    // Only attributes
                    attributeCombinations.forEach(combo => {
                        const attrNames = [];
                        const attrValues = [];

                        combo.forEach(item => {
                            const $select = $(`select[name*="[${item.key}]"]`);
                            const attrName = $select.data('attr-name') || 'Attribute';
                            attrNames.push(attrName);
                            attrValues.push(item.value);
                        });

                        variants.push({
                            name: attrValues.join('-'),
                            attr_name: attrNames.join('-'),
                            display_name: attrValues.join(' - ')
                        });
                    });
                } else {
                    // Both colors and attributes
                    colors.forEach(color => {
                        if (attributeCombinations.length === 0) {
                            variants.push({
                                name: color,
                                attr_name: 'Color',
                                display_name: color
                            });
                        } else {
                            attributeCombinations.forEach(combo => {
                                const attrNames = ['Color'];
                                const attrValues = [color];

                                combo.forEach(item => {
                                    const $select = $(`select[name*="[${item.key}]"]`);
                                    const attrName = $select.data('attr-name') || 'Attribute';
                                    attrNames.push(attrName);
                                    attrValues.push(item.value);
                                });

                                variants.push({
                                    name: attrValues.join('-'),
                                    attr_name: attrNames.join('-'),
                                    display_name: attrValues.join(' - ')
                                });
                            });
                        }
                    });
                }

                return variants;
            }

            function buildVariantsTable(variants) {
                const basePrice = parseFloat($('#regular_price').val()) || 0;

                const rows = variants.map((variant, index) => {
                    const sku = generateVariantSKU(variant.name);
                    return `
                            <tr>
                                <td>
                                    ${variant.display_name}
                                    <input type="hidden" name="variant[${index}][name]" value="${variant.name}">
                                    <input type="hidden" name="variant[${index}][attr_name]" value="${variant.attr_name}">
                                    <input type="hidden" name="variant[${index}][sku]" value="${sku}">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="variant[${index}][price]"
                                           class="form-control" value="${basePrice}" required>
                                </td>
                                <td>
                                    <input type="file" name="variant[${index}][image]"
                                           class="form-control variant-image-input" accept="image/jpeg, image/png, image/jpg">
                                    <img id="variantPreview${index}"
                                         src="{{ asset('backend/assets/img/default-150x150.png') }}"
                                         class="img-thumbnail mt-2 variant-preview"
                                         style="width:50px;height:50px;cursor:pointer;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#imagePreviewModal">
                                </td>
                            </tr>`;
                }).join('');

                $('#variantsTableContainer').html(`
                        <div class="table-responsive">
                            <table class="table table-bordered table-variants">
                                <thead>
                                    <tr>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>Image</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `);

                // Initialize variant image preview for newly generated variants
                initVariantImagePreviews();
            }

            function generateVariantSKU(variantName) {
                const productId = {{ $item->id ?? '0' }};
                let prefix = '{{ getSetting("variant_product_sku_syntax") ?? "VAR" }}';
                const random = Math.floor(1000 + Math.random() * 9000);
                const variantCode = variantName.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase();

                // Replace [[random_number]] placeholder with actual random number
                prefix = prefix.replace('[[random_number]]', random);

                return `${prefix}-${productId}-${variantCode}-${random}`;
            }

            // Initialize variant image previews for both existing and new variant inputs
            function initVariantImagePreviews() {
                // Handle existing variant image inputs (from edit mode)
                $('input.variant-image-input[name^="existing_variant"]').each(function () {
                    const index = $(this).attr('name').match(/\[(\d+)\]/)[1];
                    const previewImg = $(`#variantPreview${index}`);

                    $(this).off('change').on('change', function () {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                previewImg.attr('src', e.target.result);
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                });

                // Handle newly generated variant image inputs
                $('input.variant-image-input[name^="variant["]').each(function () {
                    const index = $(this).attr('name').match(/\[(\d+)\]/)[1];
                    const previewImg = $(`#variantPreview${index}`);

                    $(this).off('change').on('change', function () {
                        if (this.files && this.files[0]) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                previewImg.attr('src', e.target.result);
                            };
                            reader.readAsDataURL(this.files[0]);
                        }
                    });
                });
            }

            // Initialize variant image previews on page load
            initVariantImagePreviews();

            $(document).on('change', '#color_id, .attribute-value-select', () => setTimeout(updateGenerateButton, 100));

            // Handle click on variant preview images to show in modal
            $(document).on('click', '.variant-preview', function () {
                $('#modalPreviewImage').attr('src', $(this).attr('src'));
            });

        }

        function validateForm(e) {
            const name = $('#name').val().trim();
            const categoryId = $('#category_id').val();
            const regularPrice = parseFloat($('#regular_price').val()) || 0;
            const sellingPrice = parseFloat($('#selling_price').val()) || 0;
            const hasVariants = $('#has_variants').val() == '1';

            let errorMessage = '';

            if (!name) {
                $('#name').addClass('is-invalid');
                errorMessage = 'Product name is required.';
            } else if (!categoryId) {
                $('#category_id').addClass('is-invalid');
                errorMessage = 'Category is required.';
            } else if (regularPrice <= 0) {
                $('#regular_price').addClass('is-invalid');
                errorMessage = 'Regular price must be greater than 0.';
            } else if (sellingPrice < 0) {
                $('#selling_price').addClass('is-invalid');
                errorMessage = 'Selling price cannot be negative.';
            } else if (hasVariants && $('#variantsTableContainer tbody tr').length === 0) {
                errorMessage = 'Please generate variants or clear the variant selection.';
            }

            if (errorMessage) {
                e.preventDefault();
                Swal.fire({ icon: 'error', title: 'Validation Error', text: errorMessage });
                return false;
            }

            $('#submit_btn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
            return true;
        }
    </script>
@endpush