@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Coupon
@endsection
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
                            <a href="{{route('admin.coupon.index')}}" data-bs-toggle="tooltip" title="View Coupons" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Coupons</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="coupon_form" action="@isset($item){{route('admin.coupon.update')}}@else{{route('admin.coupon.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
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
    <script>
        $(document).ready(function () {
            $('#image').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewImage').attr('src', e.target.result); // Change the src of img tag
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
