@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Category
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Category</h3>
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
                        <a href="#">Category</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset </a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">@isset($item) Edit @else Add @endisset Category Information</div>
                            <a href="{{route('admin.category.index')}}" data-bs-toggle="tooltip" title="View Categories" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Categories</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="brand_form" action="@isset($item){{route('admin.category.update')}}@else{{route('admin.category.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill" href="#primary-info" role="tab" aria-controls="primary-info" aria-selected="true" tabindex="-1">Primary Information</a>
                                    </li>
                                    <li class="nav-item submenu" role="presentation">
                                        <a class="nav-link" id="pills-profile-tab-nobd" data-bs-toggle="pill" href="#seo-info" role="tab" aria-controls="seo-info" aria-selected="false" tabindex="-1">Seo Information</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                    <div class="tab-pane fade active show" id="primary-info" role="tabpanel" aria-labelledby="primary-info">
                                        <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" value="@isset($item){{$item->name}}@else{{old('name')}}@endisset" placeholder="Enter name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea id="description" name="description" rows="3" placeholder="Enter description" class="form-control" required>@isset($item){{$item->description}}@else{{old('description')}}@endisset</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select id="parent_id" name="parent_id" class="form-control" required>
                                        <option value="0" selected>Main Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" @isset($item){{$item->parent_id == $category->id ? 'selected':''}}@endisset>{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3"><label for="icon" class="form-label">Icon</label><input type="file" name="icon" class="form-control" id="icon" accept="image/*" @isset($item) @else required @endisset>
                                    @error('icon')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->icon == null)
                                            <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewIcon" width="100px" height="100px" class="my-2 rounded-circle " alt="">
                                        @else
                                            <img src="{{asset($item->icon)}}" id="previewIcon" width="100px" height="100px" class="my-2 rounded-circle " alt="">
                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewIcon" width="100px" height="100px" class="my-2 rounded-circle " alt="">

                                    @endisset
                                </div>
                                <div class="mb-3"><label for="cover_photo" class="form-label">Cover Photo</label><input type="file" name="cover_photo" class="form-control" id="cover_photo" accept="image/*" @isset($item) @else required @endisset>
                                    @error('cover_photo')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->cover_photo == null)
                                            <img src="{{asset('backend')}}/assets/img/default-banner.jpg" id="previewCoverPhoto" class="my-2 w-100" height="200px" alt="">
                                        @else
                                            <img src="{{asset($item->cover_photo)}}" id="previewCoverPhoto" class="my-2  w-100" height="300px" alt="">
                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/default-banner.jpg" id="previewCoverPhoto" class="my-2  w-100" height="300px" alt="">

                                    @endisset
                                </div>
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <small>[optional]</small></label>
                                    <select id="priority" name="priority" class="form-control">
                                        @for($i=1; $i<11; $i++)
                                            <option value="{{$i}}" @isset($item){{$item->priority == $i ? 'selected':''}}@endisset>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <fieldset class="mb-3">
                                    <legend class="col-form-label ">Status</legend>
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status1" value="1" @isset($item){{$item->status == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="status1">
                                                Active
                                            </label> </div>
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status0" value="0" @isset($item){{$item->status == 0 ? 'checked':''}}@else checked @endisset> <label class="form-check-label" for="status0">
                                                Inactive
                                            </label> </div>
                                    </div>
                                </fieldset>
                                    </div>
                                    <div class="tab-pane fade" id="seo-info" role="tabpanel" aria-labelledby="seo-info">
                                        <div class="col-md-12 mb-3">
                                                <label for="meta_title" class="form-label">Meta Title <small>[optional]</small></label>
                                                <input type="text" name="meta_title" id="meta_title" class="form-control" value="@isset($item){{$item->meta_title}}@else{{old('meta_title')}}@endisset" placeholder="Enter meta title">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_keywords" class="form-label">Meta Keywords <small>[optional]</small></label>
                                                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" value="@isset($item){{$item->meta_keywords}}@else{{old('meta_keywords')}}@endisset" placeholder="Enter meta keywords">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_description" class="form-label">Meta Description <small>[optional]</smal></label>
                                                <textarea name="meta_description" id="meta_description" rows="3" class="form-control" placeholder="Enter meta description">@isset($item){{$item->meta_description}}@else{{old('meta_description')}}@endisset</textarea>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="meta_image" class="form-label">Meta Image <small>[optional]</small></label>
                                                <input type="file" name="meta_image" id="meta_image" class="form-control" accept="image/jpg, image/jpeg, image/png">
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

                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $('#icon').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewIcon').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });

            $('#cover_photo').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewCoverPhoto').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });

            $('#meta_image').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewMetaImage').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
        })
    </script>

@endpush
