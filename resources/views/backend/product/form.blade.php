@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Product
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.6.0/bootstrap-tagsinput.min.css" />
    <style>
        .bootstrap-tagsinput .tag {
            background: #505050;
            border-radius: 4px;
            padding: 0 4px;
        }
        .bootstrap-tagsinput{
            width: 100%;
            min-height: 37px;
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
                        <a href="{{route('admin.dashboard')}}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('admin.product.index')}}">Product</a>
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
                            <div class="card-title">@isset($item) Edit @else Add @endisset Product Information</div>
                            <a href="{{route('admin.product.index')}}" data-bs-toggle="tooltip" title="View Products" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Products</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="product_form" action="@isset($item){{route('admin.product.update')}}@else{{route('admin.product.store')}}@endisset" method="post" enctype="multipart/form-data">
                                @csrf
                                @isset($item) <input type="hidden" name="id" value="{{$item->id}}"> @endisset
                                @isset($item)
                        <input type="hidden" name="" id="variantUpdate" value="0">
                    @endisset
                            <div class="card-body">
                                <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill" href="#primary-info" role="tab" aria-controls="primary-info" aria-selected="true" tabindex="-1">Primary Info</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-price-tab-nobd" data-bs-toggle="pill" href="#price-info" role="tab" aria-controls="price-info" aria-selected="false" tabindex="-1">Price & Stock</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-attribute-tab-nobd" data-bs-toggle="pill" href="#attribute-info" role="tab" aria-controls="attribute-info" aria-selected="false" tabindex="-1">Attributes</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-image-tab-nobd" data-bs-toggle="pill" href="#image-info" role="tab" aria-controls="image-info" aria-selected="false" tabindex="-1">Images</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="pills-shipping-tab-nobd" data-bs-toggle="pill" href="#shipping-info" role="tab" aria-controls="shipping-info" aria-selected="false" tabindex="-1">Shipping Information</a>
                                    </li>
                                    <li class="nav-item submenu" role="presentation">
                                        <a class="nav-link" id="pills-profile-tab-nobd" data-bs-toggle="pill" href="#seo-info" role="tab" aria-controls="seo-info" aria-selected="false" tabindex="-1">Seo Information</a>
                                    </li>
                                    <li class="nav-item submenu" role="presentation">
                                        <a class="nav-link" id="pills-additional-tab-nobd" data-bs-toggle="pill" href="#additional-info" role="tab" aria-controls="additional-info" aria-selected="false" tabindex="-1">Additional Information</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                    <div class="tab-pane fade active show" id="primary-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input id="name" type="text" name="name" value="@isset($item){{$item->name}}@else{{old('name')}}@endisset" placeholder="Enter product name" class="form-control @error('name') is-invalid @enderror" required>
                                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="category_id" class="form-label">Category</label>
                                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}" @isset($item) {{$item->category_id == $category->id ? 'selected':''}} @endisset>{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="brand_id" class="form-label">Brand <small>[optional]</small> </label>
                                                <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach($brands as $brand)
                                                        <option value="{{$brand->id}}" @isset($item) {{$item->brand_id == $brand->id ? 'selected':''}} @endisset>{{$brand->name}}</option>
                                                    @endforeach
                                                </select>
                                                @error('brand_id') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="unit_id" class="form-label">Unit</label>
                                                <select name="unit_id" id="unit_id" class="form-control @error('unit_id') is-invalid @enderror" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach($units as $unit)
                                                        <option value="{{$unit->id}}" @isset($item) {{$item->unit_id == $unit->id ? 'selected':''}} @endisset>{{$unit->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="short_description" class="form-label">Short Description</label>
                                                <textarea id="short_description" name="short_description" rows="2" placeholder="Enter short description" class="form-control @error('short_description') is-invalid @enderror" required>@isset($item){{$item->short_description}}@else{{old('short_description')}}@endisset</textarea>
                                                @error('short_description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="detailed_description" class="form-label">Detailed Description</label>
                                                <textarea id="detailed_description" name="detailed_description" rows="5" class="form-control summernote @error('detailed_description') is-invalid @enderror" required>@isset($item){{$item->detailed_description}}@else{{old('detailed_description')}}@endisset</textarea>
                                                @error('detailed_description') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label">Status</label>
                                                <div class="d-flex">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" @isset($item){{$item->status == 1 ? 'checked':''}}@else checked @endisset>
                                                        <label class="form-check-label" for="status1">Active</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" @isset($item){{$item->status == 0 ? 'checked':''}}@endisset>
                                                        <label class="form-check-label" for="status0">Inactive</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="price-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="regular_price" class="form-label">Regular Price</label>
                                                <input id="regular_price" type="number" step="0.01" name="regular_price" placeholder="Enter regular price" value="@isset($item){{$item->regular_price}}@else{{old('regular_price')}}@endisset" class="form-control" required>
                                                @error('regular_price') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="selling_price" class="form-label">Selling Price</label>
                                                <input id="selling_price" type="number" step="0.01" name="selling_price" placeholder="Enter selling price" value="@isset($item){{$item->selling_price}}@else{{old('selling_price')}}@endisset" class="form-control" required>
                                                @error('selling_price') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            @isset($item)
                                            <input type="hidden" name="stock_qty" value="{{$item->stock_qty}}">
                                            @else
                                            <div class="col-md-4 mb-3">
                                                <label for="stock_qty" class="form-label">Stock Quantity</label>
                                                <input id="stock_qty" type="number" name="stock_qty" value="@isset($item){{$item->stock_qty}}@else{{old('stock_qty')}}@endisset" placeholder="Enter stock quantity" class="form-control" required>
                                                @error('stock_qty') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            @endisset
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="attribute-info" role="tabpanel">
                                            <input type="hidden" name="total_variant" id="total_variant">
                                            <div class="mb-3 ">
                                                <div class="mb-3 d-flex">
                                                    <label for="color" class="form-label me-3">Color</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" id="color" type="checkbox" role="switch" @isset($item){{$item->color != null ? 'checked':''}}@endisset>
                                                    </div>
                                                </div>
                                                <div class="mb-3 color-selection @isset($item){{$item->color != null ? '':'d-none'}}@else d-none @endisset">
                                            @php
                                                if(isset($item) && $item->color != null){
                                                    $selected_color = json_decode($item->color);
                                                }
                                                else{
                                                    $selected_color = [];
                                                }
                                            @endphp
                                            <select name="color_id[]" id="color_id" class="form-select js-example-basic-single" multiple="multiple">
                                                @foreach($colors as $color)
                                                    <option value="{{$color->name}}" @isset($item){{in_array($color->name, $selected_color) ? 'selected':''}}@endisset>{{$color->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3 ">
                                        <div class="mb-3 d-flex">
                                            <label for="attribute" class="form-label me-3">Attribute</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" id="attribute" type="checkbox" role="switch" @isset($item){{$item->attribute_values != null ? 'checked':''}}@endisset>
                                            </div>
                                        </div>
                                        <div class="mb-3 attribute-selection @isset($item){{$item->attribute_values != null ? '':'d-none'}} @else d-none @endisset">
                                            @php
                                                if(isset($item)) {
                                                    $attribute_array = json_decode($item->attribute_values, true); // Decode JSON to associative array
                                                }
                                            @endphp

                                                <!-- Attribute selection dropdown -->
                                            <select name="attribute_id[]" id="attribute_id" class="form-select js-example-basic-single" multiple="multiple">
                                                @foreach($attributes as $attribute)
                                                    <option value="{{$attribute->id}}"
                                                    @isset($attribute_array) {{ array_key_exists($attribute->id, $attribute_array) ? 'selected' : '' }} @endisset>
                                                        {{$attribute->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3" id="attributeValueSelectionContainer">
                                            @if(isset($item) && $item->attribute_values != null)
                                                <div class="mb-3 attribute-value-selection">
                                                    <!-- Loop through each attribute in the JSON data -->
                                                    @foreach($attribute_array as $key => $selected_values)
                                                        @php
                                                            $attr = \App\Models\Admin\Attribute::find($key);
                                                        @endphp

                                                            <!-- Attribute label -->
                                                        <label class="form-label me-3">{{$attr->name ?? 'N/A'}}</label>

                                                        <!-- Attribute values selection -->
                                                        <select name="attribute_value_id[{{$key}}][]" class="form-select js-example-basic-single"  data-attr-name="{{$attr->name ?? 'N/A'}}" multiple="multiple" required>
                                                            @foreach($attr->attributeValues as $attr_value)
                                                                <option value="{{$attr_value->value}}"
                                                                    {{ in_array($attr_value->value, $selected_values) ? 'selected' : '' }}
                                                                >{{$attr_value->value}}</option>
                                                            @endforeach
                                                        </select>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <div id="variantsTableContainer" class="mb-3">
                                            @isset($item)
                                                @if($item->is_variant != 0)
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Variant</th>
                                                            {{--                                                        <th>SKU</th>--}}
                                                            <th>Price</th>
                                                            <th>Stock Quantity</th>
                                                            <th>Image [optional]</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($item->variant as $key => $variant)
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="existing_variant[{{$key}}][id]" value="{{$variant->id}}">
                                                                    <input type="hidden" name="existing_variant[{{$key}}][name]" value="{{$variant->name}}">
                                                                    <input type="hidden" name="existing_variant[{{$key}}][attr_name]" value="{{$variant->attr_name}}">
                                                                    <input type="hidden" name="existing_variant[{{$key}}][sku]" class="form-control" placeholder="SKU" value="{{$variant->sku}}" required>

                                                                    {{$variant->name}}
                                                                </td>
                                                                <td><input type="number" name="existing_variant[{{$key}}][price]" class="form-control" placeholder="Price" value="{{$variant->regular_price}}" required></td>
                                                                <td><input type="number" name="existing_variant[{{$key}}][stock_qty]" class="form-control variant_stock_qty" placeholder="Stock Quantity" value="{{$variant->stock_qty}}" required></td>
                                                                
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                @endif
                                            @endisset
                                        </div>
                                    </div>
                                    </div>
                                    <div class="tab-pane fade" id="image-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="thumbnail" class="form-label">Main Thumbnail</label>
                                                <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                                                @isset($item)
                                                    <img src="{{asset($item->thumbnail)}}" id="previewThumbnail" class="my-2" width="100px" alt="">
                                                @else
                                                    <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewThumbnail" width="100px" class="my-2" alt="">
                                                @endisset
                                                @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="gallery" class="form-label">Gallery Images</label>
                                                <input type="file" name="images[]" id="gallery" class="form-control" accept="image/*" multiple>
                                                <div id="previewGallery" class="d-flex flex-wrap gap-2 mt-2">
                                                    @isset($item)
                                                        @foreach($item->gallery as $img)
                                                            <img src="{{asset($img->image)}}" width="60px" class="border" alt="">
                                                        @endforeach
                                                    @endisset
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="shipping-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="shipping_cost" class="form-label">Shipping Cost</label>
                                                <input id="shipping_cost" type="number" step="0.01" name="shipping_cost" placeholder="Enter shipping cost" value="@isset($item){{$item->shipping_cost}}@else{{old('shipping_cost')}}@endisset" class="form-control" required>
                                                @error('shipping_cost') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="shipping_time" class="form-label">Shipping Time</label>
                                                <input id="shipping_time" type="text" name="shipping_time" placeholder="Enter shipping time" value="@isset($item){{$item->shipping_time}}@else{{old('shipping_time')}}@endisset" class="form-control" required>
                                                @error('shipping_time') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="shipping_return_policy" class="form-label">Shipping & Return Policy <small>[optional]</small></label>
                                                <textarea id="shipping_return_policy" name="shipping_return_policy" rows="5" class="form-control summernote" >@isset($item){{$item->shipping_return_policy}}@else{{old('shipping_return_policy')}}@endisset</textarea>
                                                @error('shipping_return_policy') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="seo-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_title" class="form-label">Meta Title</label>
                                                <input type="text" name="meta_title" id="meta_title" class="form-control" value="@isset($item){{$item->meta_title}}@else{{old('meta_title')}}@endisset" placeholder="Enter meta title">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="@isset($item){{$item->meta_keywords}}@else{{old('meta_keywords')}}@endisset" placeholder="Enter meta keywords">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_description" class="form-label">Meta Description</label>
                                                <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="Enter meta description">@isset($item){{$item->meta_description}}@else{{old('meta_description')}}@endisset</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_image" class="form-label">Meta Image</label>
                                                <input type="file" name="meta_image" id="meta_image" class="form-control" accept="image/*">
                                                @isset($item)
                                                    @if($item->meta_image)
                                                        <img src="{{asset($item->meta_image)}}" id="previewMetaImage" class="my-2" width="100px" alt="">
                                                    @else
                                                        <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewMetaImage" width="100px" class="my-2" alt="">
                                                    @endif
                                                @else
                                                    <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewMetaImage" width="100px" class="my-2" alt="">
                                                @endisset
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="additional-info" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="additional_information" class="form-label">Additional Information</label>
                                                <textarea id="additional_information" name="additional_information" rows="5" class="form-control summernote" required>@isset($item){{$item->additional_information}}@else{{old('additional_information')}}@endisset</textarea>
                                                @error('additional_information') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-md-12 mb-3 ">
                                                <label for="tags" class="form-label">Tags <small><span class="text-danger">(Enter after text to create tags)</span> [optional]</small></label>
                                                <div class="u-tagsinput w-100">
                                                    <input type="text" name="tags" id="tags" value="@isset($item){{$item->tags}}@else{{old('tags')}}@endisset" placeholder="Enter tags" class="form-control tags-input">
                                                </div>
                                            </div>
                                            @if(getSetting('product_point') == 1)
                                    <div class="col-md-12 mb-3">
                                        <label for="stock_qty" class="form-label">Point <small class="text-danger">[Points that will be obtained by customers on every purchase]</small></label>
                                        <input type="number" min="0" name="point" id="point" value="@isset($item){{$item->point}}@else{{old('point') ?? 0}}@endisset" placeholder="Enter points on purchasing product" class="form-control @error('point') is-invalid @enderror" required>
                                        @error('stock_qty')
                                        <div class="invalid-feedback" role="alert">{{$message}}</div>
                                        @enderror
                                    </div>
                                @endif
                                
                                <fieldset class="col-md-6 mb-3">
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="checkbox" name="cod_available" id="cod_available" value="1" @isset($item){{$item->cod_available == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="cod_available">
                                                Cash on Delivery Available
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="col-md-6 mb-3">
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="checkbox" name="include_to_todays_deal" id="include_to_todays_deal" value="1" @isset($item){{$item->include_to_todays_deal == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="include_to_todays_deal">
                                                Include to Today's Deal
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="col-md-6 mb-3">
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @isset($item){{$item->is_featured == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="is_featured">
                                                Featured
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="col-md-6 mb-3">
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="checkbox" name="is_replaceable" id="is_replaceable" value="1" @isset($item){{$item->is_replaceable == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="is_replaceable">
                                                Replaceable
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="col-md-6 mb-3">
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="checkbox" name="is_trending" id="is_trending" value="1" @isset($item){{$item->is_trending == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="is_trending">
                                                Trending
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                
                                        </div>
                                    </div>
                                </div>
                                
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2
            $('.js-example-basic-single').select2();

            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']],
                ],
            });

            // Initialize Tagsinput
            $('#tags').tagsinput({
                confirmKeys: [13],
            });
            $('.bootstrap-tagsinput input').keydown(function(event) {
                if (event.which == 13) {
                    $(this).blur();
                    $(this).focus();
                    return false;
                }
            });

            // Image Preview Handlers
            $('#thumbnail').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewThumbnail').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#gallery').change(function() {
                $('#previewGallery').empty();
                if (this.files) {
                    Array.from(this.files).forEach(file => {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#previewGallery').append(`<img src="${e.target.result}" width="60px" class="border" alt="">`);
                        }
                        reader.readAsDataURL(file);
                    });
                }
            });

            $('#meta_image').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewMetaImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            // Price Calculation
            $('#regular_price, #discount, #discount_type').on('input change', function () {
                let regular_price = parseFloat($('#regular_price').val()) || 0;
                let discount = parseFloat($('#discount').val()) || 0;
                let discount_type = $('#discount_type').val();
                let final_price = regular_price;

                if (discount > 0) {
                    if (discount_type == 1) { // Flat
                        final_price = regular_price - discount;
                    } else { // Percent
                        final_price = regular_price - (regular_price * discount / 100);
                    }
                }
                $('#final_price').val(final_price.toFixed(2));
            });

            // Variant Management Logic
            let attributeValueCache = {};

            function getCombinations(attributeValues) {
                const keys = Object.keys(attributeValues);
                const combinations = [];

                function combine(index, current) {
                    if (index === keys.length) {
                        combinations.push(current);
                        return;
                    }
                    const values = attributeValues[keys[index]];
                    for (const value of values) {
                        combine(index + 1, [...current, value]);
                    }
                }
                combine(0, []);
                return combinations;
            }

            function updateVariantsTable() {
                const colors = $('#color_id').val() || [];
                const selectedAttributes = $('#attribute_id').val() || [];
                const attributeValues = {};
                const product_regular_price = $('#regular_price').val() || 0;

                @isset($item)
                $('#variantUpdate').val(1);
                @endisset

                $('#attributeValueSelectionContainer select').each(function () {
                    const nameAttr = $(this).attr('name');
                    if (!nameAttr) return;
                    const matches = nameAttr.match(/\[(\d+)\]/);
                    if (matches) {
                        const attrId = matches[1];
                        const selectedValues = $(this).val() || [];
                        if (selectedValues.length > 0 && selectedAttributes.includes(attrId)) {
                            attributeValues[attrId] = selectedValues;
                        }
                        attributeValueCache[attrId] = selectedValues;
                    }
                });

                let variants = [];
                if (colors.length > 0 || Object.keys(attributeValues).length > 0) {
                    const attributeCombinations = getCombinations(attributeValues);
                    
                    if (attributeCombinations.length === 0 && colors.length > 0) {
                        colors.forEach(color => {
                            variants.push({ name: color, attr_name: 'Color' });
                        });
                    } else {
                        attributeCombinations.forEach(attrCombination => {
                            const attrNames = [];
                            const attrValues = [];
                            attrCombination.forEach(val => {
                                // Find attribute name and value label
                                const $select = $(`select[name^="attribute_value_id"]`).filter(function() {
                                    return $(this).val() && $(this).val().includes(val);
                                }).first();
                                const attrName = $select.data('attr-name') || 'Attr';
                                attrNames.push(attrName);
                                attrValues.push(val);
                            });

                            if (colors.length > 0) {
                                colors.forEach(color => {
                                    variants.push({
                                        name: color + '-' + attrValues.join('-'),
                                        attr_name: 'Color-' + attrNames.join('-'),
                                    });
                                });
                            } else {
                                variants.push({
                                    name: attrValues.join('-'),
                                    attr_name: attrNames.join('-'),
                                });
                            }
                        });
                    }
                }

                $('#total_variant').val(variants.length);

                if (variants.length > 0) {
                    let tableHtml = `<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Variant</th>
                                <th>Price</th>
                                <th>Stock Qty</th>
                                <th>Image</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>`;

                    variants.forEach((variant, i) => {
                        tableHtml += `
                            <tr>
                                <td>
                                    ${variant.name}
                                    <input type="hidden" name="variant[${i}][name]" value="${variant.name}">
                                    <input type="hidden" name="variant[${i}][attr_name]" value="${variant.attr_name}">
                                </td>
                                <td><input type="number" step="0.01" name="variant[${i}][price]" class="form-control" value="${product_regular_price}" required></td>
                                <td><input type="number" name="variant[${i}][stock_qty]" class="form-control" value="0" required></td>
                                <td><input type="file" name="variant[${i}][image]" class="form-control" accept="image/*"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-variant"><i class="fa fa-trash"></i></button></td>
                            </tr>`;
                    });
                    tableHtml += `</tbody></table>`;
                    $('#variantsTableContainer').html(tableHtml);
                } else {
                    // If no variants, but item exists, keep the existing table (handled by PHP @isset)
                    @isset($item)
                        // Do nothing, let PHP render existing variants unless toggled off
                    @else
                        $('#variantsTableContainer').empty();
                    @endisset
                }
            }

            // Bind remove variant
            $(document).on('click', '.remove-variant', function() {
                $(this).closest('tr').remove();
            });

            // Toggle Handlers
            $('#color').change(function() {
                $('.color-selection').toggleClass('d-none', !this.checked);
                if (!this.checked) {
                    $('#color_id').val([]).trigger('change');
                }
                updateVariantsTable();
            });

            $('#attribute').change(function() {
                $('.attribute-selection').toggleClass('d-none', !this.checked);
                if (!this.checked) {
                    $('#attribute_id').val([]).trigger('change');
                    $('#attributeValueSelectionContainer').empty();
                }
                updateVariantsTable();
            });

            // Selection Change Handlers
            $('#color_id').change(updateVariantsTable);

            $('#attribute_id').change(function() {
                const attribute_ids = $(this).val();
                if (attribute_ids && attribute_ids.length > 0) {
                    $.ajax({
                        method: 'GET',
                        url: '{{ route("admin.product.attribute.values.get") }}',
                        data: { attribute_id: attribute_ids },
                        success: function(data) {
                            let html = '';
                            $.each(data, function(index, attribute) {
                                html += `<div class="mb-3">
                                    <label class="form-label">${attribute.name}</label>
                                    <select name="attribute_value_id[${attribute.id}][]" class="form-select js-example-basic-single" data-attr-name="${attribute.name}" multiple="multiple" required>`;
                                $.each(attribute.attribute_values, function(key, value) {
                                    const selected = (attributeValueCache[attribute.id] && attributeValueCache[attribute.id].includes(value.value)) ? 'selected' : '';
                                    html += `<option value="${value.value}" ${selected}>${value.value}</option>`;
                                });
                                html += `</select></div>`;
                            });
                            $('#attributeValueSelectionContainer').html(html);
                            $('.js-example-basic-single').select2();
                        }
                    });
                } else {
                    $('#attributeValueSelectionContainer').empty();
                }
                updateVariantsTable();
            });

            $(document).on('change', '#attributeValueSelectionContainer select', updateVariantsTable);

            // Modal Button Handlers
            $('#editButton').on('click', function () {
                $('#productSettingsForm .modal-body div input').prop('disabled', false).prop('required', true);
                $(this).addClass('d-none');
                $('#saveButton').removeClass('d-none');
                $('#cancelButton').removeClass('d-none');
            });

            $('#cancelButton, .btn-close').on('click', function () {
                $('#productSettingsForm .modal-body div input').prop('disabled', true).prop('required', false);
                $('#editButton').removeClass('d-none');
                $('#saveButton').addClass('d-none');
                $('#cancelButton').addClass('d-none');
            });
        });
    </script>
    <script>
        function deleteImage(id) {
            // Show confirmation alert before deleting
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you really want to delete this image?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX request to delete image if confirmed
                    $.ajax({
                        method: 'GET',
                        url: '{{ route('admin.product.image.delete') }}',
                        data: {
                            id: id,
                        },
                        success: function(data) {
                            // Remove image from DOM
                            $('#multi-img-' + id).remove();

                            // Show success alert
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Image has been deleted successfully.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function(xhr, status, error) {
                            // Show error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while deleting the image. Please try again.',
                            });
                        }
                    });
                }
            });
        }
    </script>
    @isset($item)
        <script>
            $(document).ready(function () {
                $('.submitBtn').click(function (event) {
                    event.preventDefault(); // Prevent default form submission

                    const form = $('#product_form'); // Form to be submitted
                    const variantUpdate = $('#variantUpdate').val(); // Track if variant is updated

                    if (variantUpdate == 1) {
                        Swal.fire({
                            title: "Variants save changed. What do you want to do with the existing stock",
                            html: `
                    <div style="text-align: left;">
                        <div class="swal-option-row">
                            <input type="radio" name="stockOption" id="optionDelete" value="1">
                            <label for="optionDelete">Delete Previous Stock</label>
                        </div>
<!--                        <div class="swal-option-row">-->
<!--                            <input type="radio" name="stockOption" id="optionAssign" value="2">-->
<!--                            <label for="optionAssign">Assign Previous Stock to Other Product</label>-->
<!--                        </div>-->
                        <div class="swal-option-row">
                            <input type="radio" name="stockOption" id="optionTransfer" value="3">
                            <label for="optionTransfer">Transfer Previous Stock to Another Warehouse</label>
                        </div>
                    </div>
                `,
                            showCancelButton: true,
                            confirmButtonText: "Proceed",
                            cancelButtonText: "Cancel",
                            preConfirm: () => {
                                const selectedOption = document.querySelector('input[name="stockOption"]:checked');

                                if (!selectedOption) {
                                    Swal.showValidationMessage("Please select an option!");
                                    return false;
                                }

                                return selectedOption.value; // Return the selected option value
                            }
                        }).then((stockHandlingResult) => {
                            if (stockHandlingResult.isConfirmed) {
                                const selectedOption = stockHandlingResult.value;

                                if (selectedOption === "3") {
                                    // Show warehouse selection modal
                                    Swal.fire({
                                        title: "Select a Warehouse",
                                        input: "select",
                                        inputOptions: getWarehouseOptions(), // Dynamically load warehouses
                                        inputPlaceholder: "Select a warehouse",
                                        showCancelButton: true,
                                        confirmButtonText: "Transfer Stock",
                                        cancelButtonText: "Cancel",
                                    }).then((warehouseResult) => {
                                        if (warehouseResult.isConfirmed) {
                                            const selectedWarehouseId = warehouseResult.value;

                                            // Append warehouse ID to form
                                            $('<input>')
                                                .attr({
                                                    type: 'hidden',
                                                    name: 'transfer_to_warehouse_id',
                                                    value: selectedWarehouseId
                                                })
                                                .appendTo(form);

                                            // Append the stock handling option
                                            $('<input>')
                                                .attr({
                                                    type: 'hidden',
                                                    name: 'stock_handling_option',
                                                    value: "3"
                                                })
                                                .appendTo(form);

                                            form.submit(); // Submit the form
                                        }
                                    });
                                }
                                    // else if (selectedOption === "2") {
                                    //     // Show product selection modal
                                    //     Swal.fire({
                                    //         title: "Select a Product",
                                    //         input: "select",
                                    //         inputOptions: getProductOptions(), // Dynamically load products
                                    //         inputPlaceholder: "Select a product",
                                    //         showCancelButton: true,
                                    //         confirmButtonText: "Assign Stock",
                                    //         cancelButtonText: "Cancel",
                                    //     }).then((productResult) => {
                                    //         if (productResult.isConfirmed) {
                                    //             const selectedProductId = productResult.value;
                                    //
                                    //             // Append product ID to form
                                    //             $('<input>')
                                    //                 .attr({
                                    //                     type: 'hidden',
                                    //                     name: 'assign_to_product_id',
                                    //                     value: selectedProductId
                                    //                 })
                                    //                 .appendTo(form);
                                    //
                                    //             // Append the stock handling option
                                    //             $('<input>')
                                    //                 .attr({
                                    //                     type: 'hidden',
                                    //                     name: 'stock_handling_option',
                                    //                     value: "2"
                                    //                 })
                                    //                 .appendTo(form);
                                    //
                                    //             form.submit(); // Submit the form
                                    //         }
                                    //     });
                                // }
                                else if (selectedOption === "1") {
                                    // Append the stock handling option for deleting stock
                                    $('<input>')
                                        .attr({
                                            type: 'hidden',
                                            name: 'stock_handling_option',
                                            value: "1"
                                        })
                                        .appendTo(form);

                                    form.submit(); // Submit the form
                                }
                            }
                        });
                    } else {
                        form.submit(); // Directly submit the form if no variant updates are tracked
                    }
                });

                // Helper function to dynamically load product options
                function getProductOptions() {
                    let options = {};

                    $.ajax({
                        url: '{{ route("admin.product.list") }}', // Backend route to fetch products
                        method: 'GET',
                        async: false, // Synchronous to wait for options
                        success: function (data) {
                            options = data.products.reduce((opts, product) => {
                                opts[product.id] = product.name;
                                return opts;
                            }, {});
                        },
                        error: function () {
                            Swal.fire("Error", "Failed to load product options.", "error");
                        }
                    });

                    return options;
                }

                // Helper function to dynamically load warehouse options
                function getWarehouseOptions() {
                    let options = {};

                    @foreach ($warehouses as $warehouse)
                        options['{{ $warehouse->id }}'] = '{{ $warehouse->name }}';
                    @endforeach

                        return options;
                }
            });

        </script>
    @endisset
    <script>
        $(document).ready(function() {
            $('.summernote').summernote();
            $('.js-example-basic-single').select2();
            $('.tags-input').tagsinput();
        });
    </script>
@endpush
