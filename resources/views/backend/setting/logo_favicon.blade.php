@extends('backend.master')
@section('title')
    Edit Logo & Favicon Setting
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Logo & Favicon Setting</h3>
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
                        <a href="#">Logo & Favicon</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Logos & Favicon</div>
                        </div>
                        <form action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">

                                <div class="mb-3"><label for="site_favicon" class="form-label">Favicon</label>
                                    <div class="row">
                                        <div class="col-md-9 col-9">
                                            <input type="file" name="site_favicon" class="form-control" id="site_favicon" accept="image/*">
                                            @error('site_favicon')
                                            <div class="invalid-feedback" role="alert">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 col-3 text-center">
                                            @if(getSetting('site_favicon') == null)
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewSiteFavicon" width="50px" class="my-2  " alt="">
                                            @else
                                                <img src="{{asset(getSetting('site_favicon'))}}" id="previewSiteFavicon" width="50px" class="my-2  " alt="">

                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3"><label for="site_logo" class="form-label">Logo</label>
                                    <div class="row">
                                        <div class="col-md-9 col-9">
                                            <input type="file" name="site_logo" class="form-control" id="site_logo" accept="image/*">
                                            @error('site_logo')
                                            <div class="invalid-feedback" role="alert">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 col-3 text-center">
                                            @if(getSetting('site_logo') == null)
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewSiteLogo" width="100px" class="my-2  " alt="">
                                            @else
                                                <img src="{{asset(getSetting('site_logo'))}}" id="previewSiteLogo" width="100px" class="my-2  " alt="">

                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3"><label for="site_dark_logo" class="form-label">Dark logo</label>
                                    <div class="row">
                                        <div class="col-md-9 col-9">
                                            <input type="file" name="site_dark_logo" class="form-control" id="site_dark_logo" accept="image/*">
                                            @error('site_dark_logo')
                                            <div class="invalid-feedback" role="alert">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 col-3 text-center">
                                            @if(getSetting('site_dark_logo') == null)
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewSiteLogoDark" width="100px" class="my-2  " alt="">
                                            @else
                                                <img src="{{asset(getSetting('site_dark_logo'))}}" id="previewSiteLogoDark" width="100px" class="my-2  " alt="">

                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3"><label for="site_footer_logo" class="form-label">Footer logo</label>
                                    <div class="row">
                                        <div class="col-md-9 col-9">
                                            <input type="file" name="site_footer_logo" class="form-control" id="site_footer_logo" accept="image/*">
                                            @error('site_footer_logo')
                                            <div class="invalid-feedback" role="alert">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 col-3 text-center">
                                            @if(getSetting('site_footer_logo') == null)
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewSiteFooterLogo" width="100px" class="my-2  " alt="">
                                            @else
                                                <img src="{{asset(getSetting('site_footer_logo'))}}" id="previewSiteFooterLogo" width="100px" class="my-2  " alt="">

                                            @endif
                                        </div>
                                    </div>
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
@push('js')
    <script>
        // jQuery function to preview the image
        $(document).ready(function(){
            $('#site_favicon').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewSiteFavicon').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#site_logo').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewSiteLogo').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#site_dark_logo').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewSiteLogoDark').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#site_footer_logo').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewSiteFooterLogo').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
        });
    </script>
@endpush
