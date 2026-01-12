@extends('backend.master')
@section('title')
    Edit Activation Setting
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
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Activation Information</div>
                        </div>
                        <form action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3"> <label for="free_delivery_above" class="form-label">Free delivery above</label>
                                    <input type="number" name="free_delivery_above" placeholder="Enter free delivery amount minimum limit" class="form-control @error('free_delivery_above') is-invalid @enderror" value="{{getSetting('free_delivery_above')}}" min="0" id="free_delivery_above" aria-describedby="free_delivery_above">
                                    @error('free_delivery_above')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="user_verification" class="form-label">User verification</label>
                                    <select name="user_verification" class="form-control @error('user_verification') is-invalid @enderror"
                                            id="user_verification" aria-describedby="user_verification" required>
                                        <option value="0" {{getSetting('user_verification' == '0' ? 'selected' : '')}}>Off</option>
                                        <option value="1" {{getSetting('user_verification' == '1' ? 'selected' : '')}}>Email verification</option>
                                        <option value="2" {{getSetting('user_verification' == '2' ? 'selected' : '')}}>Phone verification</option>
                                    </select>
                                    @error('user_verification')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="user_verification" class="form-label">Guest account</label>
                                    <select name="guest_account" class="form-control @error('guest_account') is-invalid @enderror"
                                            id="guest_account" aria-describedby="guest_account" required>
                                        <option value="0" selected>Off</option>
                                        <option value="1" {{getSetting('guest_account' == '1' ? 'selected' : '')}}>On</option>
                                    </select>
                                    @error('user_verification')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">Update</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
