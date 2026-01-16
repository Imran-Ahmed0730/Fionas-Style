@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Slider
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Slider</h3>
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
                        <a href="#">Slider</a>
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
                            <div class="card-title">@isset($item) Edit @else Add @endisset Slider Information</div>
                            <a href="{{route('admin.slider.index')}}" data-bs-toggle="tooltip" title="View Sliders" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Sliders</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="slider_form" action="@isset($item){{route('admin.slider.update')}}@else{{route('admin.slider.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <small>[optional]</small></label>
                                    <input id="title" type="text" name="title" value="@isset($item){{$item->title}}@else{{old('title')}}@endisset" placeholder="Enter title" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="subtitle" class="form-label">Subtitle <small>[optional]</small></label>
                                    <input id="subtitle" type="text" name="subtitle" value="@isset($item){{$item->subtitle}}@else{{old('subtitle')}}@endisset" placeholder="Enter subtitle" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description <small>[optional]</small></label>
                                    <textarea id="description" name="description" rows="3" placeholder="Enter description" class="form-control note-icon-summernote" required>@isset($item){{$item->description}}@else{{old('description')}}@endisset</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="link" class="form-label">Link<small>[optional]</small></label>
                                    <input id="link" type="url" name="link" value="@isset($item){{$item->link}}@else{{old('link')}}@endisset" placeholder="Enter Slider link" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="btn_text" class="form-label">Button Text<small>[optional]</small></label>
                                    <input id="btn_text" type="text" name="btn_text" value="@isset($item){{$item->btn_text}}@else{{old('btn_text')}}@endisset" placeholder="Enter button text" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select name="priority" class="form-select" id="priority">
                                        @for($i=1; $i<=10; $i++)
                                            <option value="{{$i}}" @isset($item){{$item->priority == $i ? 'selected':''}}@endisset>{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <select name="position" class="form-select" id="position">
                                        <option value="1" @isset($item){{$item->position == '1' ? 'selected':''}}@endisset>Header</option>
                                        <option value="2" @isset($item){{$item->position == '2' ? 'selected':''}}@endisset>Main</option>
                                        <option value="3" @isset($item){{$item->position == '3' ? 'selected':''}}@endisset>Footer</option>
                                    </select>
                                </div>
                                <div class="mb-3"><label for="image" class="form-label">Image</label><input type="file" name="image" class="form-control" id="image" accept="image/*" @isset($item) @else required @endisset>
                                    @error('image')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->image == null)
                                            <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewImage" width="100px" class="my-2  " alt="">
                                        @else
                                            <img src="{{asset($item->image)}}" id="previewImage" width="100px" class="my-2  " alt="">
                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewImage" width="100px" class="my-2  " alt="">

                                    @endisset
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
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
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
            $('#image').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewImage').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
        })
    </script>

@endpush
