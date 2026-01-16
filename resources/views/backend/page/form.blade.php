@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Page
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Page</h3>
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
                        <a href="#">Page</a>
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
                            <div class="card-title">@isset($item) Edit @else Add @endisset Page Information</div>
                            <a href="{{route('admin.page.index')}}" data-bs-toggle="tooltip" title="View Pages" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Pages</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="page_form" action="@isset($item){{route('admin.page.update')}}@else{{route('admin.page.store')}}@endisset" method="post" enctype="multipart/form-data">
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
                                            <label for="title" class="form-label">Title</label>
                                            <input id="title" type="text" name="title" value="@isset($item){{$item->title}}@else{{old('title')}}@endisset" placeholder="Enter title" class="form-control" required>
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description <small>[optional]</small></label>
                                            <textarea id="description" name="content" rows="3" placeholder="Enter description" class="form-control note-icon-summernote" required>@isset($item){{$item->content}}@else{{old('content')}}@endisset</textarea>
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
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{asset('backend')}}/assets/js/plugin/summernote/summernote-lite.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#description').summernote({
                height: 200,
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
