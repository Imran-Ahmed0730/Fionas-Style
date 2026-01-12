@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Color
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
                            <div class="card-title">@isset($item) Edit @else Add @endisset Color Information</div>
                            <a href="{{route('admin.color.index')}}" title="View Colors" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Colors</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="Color _form" action="@isset($item){{route('admin.color.update')}}@else{{route('admin.color.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" value="@isset($item){{$item->name}}@else{{old('name')}}@endisset" placeholder="Enter name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="color_code" class="form-label">Color Code </label>
                                    <input type="color" name="color_code" id="color_code" value="@isset($item){{$item->color_code ?? ''}}@else{{old('color_code')}}@endisset" placeholder="Enter color code" class="form-control">
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
        })
    </script>

@endpush
