@extends('backend.master')
@section('title')
    Edit Contact Information
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Contact Setting</h3>
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
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Contact Information</div>
                        </div>
                        <form action="{{route('admin.setting.update')}}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12"> <!--begin::Quick Example-->
                                                <div class="mb-3"> <label for="phone" class="form-label">Contact number</label>
                                                    <input type="tel" name="phone" placeholder="Enter contact no" class="form-control @error('phone') is-invalid @enderror" value="{{getSetting('phone')}}" id="phone" aria-describedby="phone" required>
                                                    @error('phone')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="whatsapp" class="form-label">Whatsapp</label>
                                                    <input type="tel" name="whatsapp" placeholder="Enter whatsapp contact no" class="form-control @error('whatsapp') is-invalid @enderror" value="{{getSetting('whatsapp')}}" id="whatsapp" aria-describedby="whatsapp">

                                                    @error('whatsapp')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="email" class="form-label">Email</label>
                                                    <input type="email" name="email" placeholder="Enter email address" class="form-control @error('email') is-invalid @enderror" value="{{getSetting('email')}}" id="email" aria-describedby="email" required>

                                                    @error('email')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div><!--end::Quick Example-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
