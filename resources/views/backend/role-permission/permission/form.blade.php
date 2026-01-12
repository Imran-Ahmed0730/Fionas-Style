@extends('backend.master')
@section('title')
    @isset($item) Edit @else Add @endisset Permission
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3"> @isset($item) Edit @else Add @endisset Permission</h3>
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
                        <a href="#">Permissions</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#"> @isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">Permission Information</div>
                            <a href="{{route('admin.permission.index')}}" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Permissions</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form action="@isset($item){{route('admin.permission.update')}}@else{{route('admin.permission.store')}}@endisset" method="post" >
                            @csrf<!--begin::Body-->
                            <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                            <div class="card-body">

                                <div class="mb-3"> <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" placeholder="Enter permission name" class="form-control
                                            @error('name') is-invalid @enderror"
                                           value="@isset($item){{$item->name}}@else{{old('name')}}@endisset"
                                           id="name" aria-describedby="name" required>
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <fieldset class="mb-3">
                                    <legend class="col-form-label ">Status</legend>
                                    <div class="">
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status1" value="1" @isset($item){{$item->status == 1 ? 'checked':''}}@else checked @endisset> <label class="form-check-label" for="status1">
                                                Show
                                            </label> </div>
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="status" id="status0" value="0" @isset($item){{$item->status == 0 ? 'checked':''}} @endisset> <label class="form-check-label" for="status0">
                                                Hide
                                            </label> </div>
                                    </div>
                                </fieldset>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

