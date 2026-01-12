@extends('backend.master')
@section('title')
    Edit Social Media Setting
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Social Media Setting</h3>
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
                        <a href="#">Social Media</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Social Media Information</div>
                        </div>
                        <form action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3"> <label for="facebook_url" class="form-label">Facebook</label> <input type="url" name="facebook_url" placeholder="Enter facebook url" class="form-control @error('facebook_url') is-invalid @enderror" value="{{getSetting('facebook_url')}}" id="facebook_url" aria-describedby="facebook_url" >
                                    @error('facebook_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="instagram_url" class="form-label">Instagram</label> <input type="url" placeholder="Enter instagram url" name="instagram_url" value="{{getSetting('instagram_url')}}" class="form-control @error('instagram_url') is-invalid @enderror" id="instagram_url" >
                                    @error('instagram_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="x_url" class="form-label">Twitter</label> <input type="url" placeholder="Enter twitter url" name="x_url" value="{{getSetting('x_url')}}" class="form-control @error('x_url') is-invalid @enderror" id="x_url" >
                                    @error('x_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="youtube_url" class="form-label">Youtube</label> <input type="url" placeholder="Enter youtube url" name="youtube_url" value="{{getSetting('youtube_url')}}" class="form-control @error('youtube_url') is-invalid @enderror" id="youtube_url" >
                                    @error('youtube_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="pinterest_url" class="form-label">Pinterest</label> <input type="url" placeholder="Enter pinterest url" name="pinterest_url" value="{{getSetting('pinterest_url')}}" class="form-control @error('pinterest_url') is-invalid @enderror" id="pinterest_url" >
                                    @error('pinterest_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="linkedin_url" class="form-label">LinkedIn</label> <input type="url" placeholder="Enter linkedin url" name="linkedin_url" value="{{getSetting('linkedin_url')}}" class="form-control @error('linkedin_url') is-invalid @enderror" id="linkedin_url">
                                    @error('linkedin_url')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">Update</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
