@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Category
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Activation Setting</h3>
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
                        <a href="#">Settings</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Activation</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">@isset($item) Edit @else Add @endisset Category Information</div>
                            <a href="{{route('admin.category.index')}}" title="View Categories" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Categories</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="brand_form" action="@isset($item){{route('admin.category.update')}}@else{{route('admin.category.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
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
        })
    </script>

@endpush
