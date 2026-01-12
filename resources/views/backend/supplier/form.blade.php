@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Supplier
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Supplier</h3>
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
                        <a href="#">Suppliers</a>
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
                <div class="col-md-12"> <!--begin::Quick Example-->
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">@isset($item) Edit @else Add @endisset Supplier</div>
                            <a href="{{route('admin.supplier.index')}}" title="View Suppliers" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Suppliers</a>

                        </div> <!--end::Header--> <!--begin::Form-->
                        <form action="@isset($item){{route('admin.supplier.update')}}@else{{route('admin.supplier.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3"> <label for="exampleInputName1" class="form-label">Name</label> <input type="text" name="name" placeholder="Enter name" class="form-control @error('name') is-invalid @enderror" value="@isset($item){{$item->name}}@else{{old('name')}}@endisset" id="exampleInputName1" aria-describedby="nameHelp" required>
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="exampleInputEmail1" class="form-label">Email Address <small>[optional]</small></label> <input type="email" name="email" placeholder="Enter email" class="form-control @error('email') is-invalid @enderror" value="@isset($item){{$item->email}}@else{{old('email')}}@endisset" id="exampleInputEmail1" aria-describedby="emailHelp">
                                    @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="exampleInputPhone1" class="form-label">Phone</label> <input type="tel" placeholder="Enter contact no" name="phone" value="@isset($item){{$item->phone}}@else{{old('phone')}}@endisset" class="form-control @error('phone') is-invalid @enderror" id="exampleInputPhone1" required>
                                    @error('phone')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="exampleInputAddress1" class="form-label">Address</label> <textarea rows="3" name="address" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" id="exampleInputAddress1" required>@isset($item){{$item->address}}@else{{old('address')}}@endisset</textarea>
                                    @error('address')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
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
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" class="btn btn-primary">Update</button> </div> <!--end::Footer-->
                        </form> <!--end::Form-->
                    </div>
                </div><!--end::Quick Example-->
            </div>
        </div>
    </div>
@endsection

