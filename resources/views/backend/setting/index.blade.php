@extends('backend.master')
@section('title')
    Edit Site Setting
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Site Setting</h3>
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
                        <a>Settings</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Site</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <form action="{{route('admin.setting.update')}}" method="post">
                    @csrf
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Site Information</div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12"> <!--begin::Quick Example-->
                                                <div class="mb-3"> <label for="site_name" class="form-label">Site name</label> <input type="text" name="site_name" placeholder="Enter site name" class="form-control @error('site_name') is-invalid @enderror" value="{{getSetting('site_name')}}" id="site_name" aria-describedby="site_name" required>
                                                    @error('site_name')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="business_name" class="form-label">Business name</label> <input type="text" name="business_name" placeholder="Enter business name" class="form-control @error('business_name') is-invalid @enderror" value="{{getSetting('business_name')}}" id="business_name" aria-describedby="business_name" required>
                                                    @error('business_name')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="short_bio" class="form-label">Short bio</label> <textarea rows="3" name="short_bio" placeholder="Enter short bio" class="form-control @error('short_bio') is-invalid @enderror" id="short_bio" required>{{getSetting('short_bio')}}</textarea>
                                                    @error('short_bio')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="site_url" class="form-label">Site url</label> <input type="url" name="site_url" placeholder="Enter site_url" class="form-control @error('site_url') is-invalid @enderror" value="{{getSetting('site_url')}}" id="site_url" aria-describedby="site_url">
                                                    @error('site_url')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="meta_description" class="form-label">Meta description</label> <textarea rows="3" name="meta_description" placeholder="Enter meta description" class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" required>{{getSetting('meta_description')}}</textarea>
                                                    @error('meta_description')
                                                    <div class="invalid-feedback" role="alert">
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3"> <label for="meta_keywords" class="form-label">Meta Keywords</label> <input type="text" name="meta_keywords" placeholder="Enter meta keywords" class="form-control @error('meta_keywords') is-invalid @enderror" value="{{getSetting('meta_keywords')}}" id="meta_keywords" aria-describedby="meta_keywords">
                                                    @error('meta_keywords')
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
                            @if(Auth::user()->hasRole('Admin'))
                                <div class="card-action d-flex justify-content-end">
                                    <button class="btn btn-primary">Update</button>

                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end">
                        <button class="btn btn-primary ">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

