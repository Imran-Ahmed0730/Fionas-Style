@extends('backend.master')
@section('title')
    @isset($item)Edit @else Add @endisset Vendor
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Vendor</h3>
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
                        <a href="#">Vendor</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">@isset($item)Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>
            <div class="row g-4">
                <form action="@isset($item){{route('admin.vendor.update')}}@else{{route('admin.vendor.store')}}@endisset" method="post" enctype="multipart/form-data">
                    @csrf<!--begin::Body-->
                    <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Vendor Information</div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div> <!--end::Header--> <!--begin::Form-->
                            <div class="card-body row">
                                <div class="col-md-6 mb-3"> <label for="exampleInputName1" class="form-label">Name</label> <input type="text" name="name" placeholder="Enter name" class="form-control @error('name') is-invalid @enderror" value="@isset($item){{$item->name}}@else{{old('name')}}@endisset" id="exampleInputName1" aria-describedby="nameHelp" required>
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputEmail1" class="form-label">Email address</label> <input type="email" name="email" placeholder="Enter email" class="form-control @error('email') is-invalid @enderror" value="@isset($item){{$item->email}}@else{{old('email')}}@endisset" id="exampleInputEmail1" aria-describedby="emailHelp" required>
                                    @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputPhone1" class="form-label">Phone</label> <input type="tel" placeholder="Enter contact no" name="phone" value="@isset($item){{$item->phone}}@else{{old('phone')}}@endisset" class="form-control @error('phone') is-invalid @enderror" id="exampleInputPhone1" required>
                                    @error('phone')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputUsername1" class="form-label">Username </label> <input type="text" placeholder="Enter username" name="username" value="@isset($item){{$item->username}}@else{{old('username')}}@endisset" class="form-control @error('username') is-invalid @enderror" id="exampleInputUsername1" required>
                                    @error('username')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3"> <label for="exampleInputAddress1" class="form-label">Address <small>[optional]</small></label> <textarea rows="3" name="address" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" id="exampleInputAddress1">@isset($item){{$item->address}}@else{{old('address')}}@endisset</textarea>
                                    @error('address')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"><label for="inputGroupFile02" class="form-label">Profile Image</label><input type="file" name="image" class="form-control" id="inputGroupFile02" @isset($item) @else required @endisset>
                                    @error('image')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->image == null)
                                            <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewImage" width="128px" height="128px" class="my-2 rounded-circle " alt="">
                                        @else
                                            <img src="{{asset($item->image)}}" id="previewImage" width="128px" height="128px" class="my-2 rounded-circle " alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewImage" width="128px" height="128px" class="my-2 rounded-circle " alt="">
                                    @endisset
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputCommission1" class="form-label">Commission Rate (%) </label> <input type="number" step="0.01" placeholder="Enter commission rate" name="commission_rate" value="@isset($item){{$item->commission_rate}}@else{{old('commission_rate') ?? getSetting('vendor_commission_rate')}}@endisset" class="form-control @error('username') is-invalid @enderror" id="exampleInputCommission1" required>
                                    @error('commission_rate')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>

                                <fieldset class="col-md-6 mb-3">
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
                                <fieldset class="col-md-6 mb-3">
                                    <legend class="col-form-label ">Approval Status</legend>
                                    <div class="">


                                        <div class="form-check"> <input class="form-check-input" type="radio" name="approval_status" id="approval_status0" value="0" @isset($item){{$item->approval_status == 0 ? 'checked':''}}@else checked @endisset> <label class="form-check-label" for="approval_status0">
                                                Pending
                                            </label>
                                        </div>
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="approval_status" id="approval_status1" value="1" @isset($item){{$item->approval_status == 1 ? 'checked':''}}@endisset> <label class="form-check-label" for="approval_status1">
                                                Approved
                                            </label> </div>
                                        <div class="form-check"> <input class="form-check-input" type="radio" name="approval_status" id="approval_status2" value="2" @isset($item){{$item->approval_status == 2 ? 'checked':''}} @endisset> <label class="form-check-label" for="approval_status2">
                                                Rejected
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div> <!--end::Body--> <!--begin::Footer-->
                        </div>
                    </div><!--end::Quick Example-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Shop Information</div>
                            </div> <!--end::Header--> <!--begin::Form-->
                            <div class="card-body row">
                                <div class="col-md-12 mb-3"> <label for="exampleInputShopName" class="form-label">Shop Name</label> <input type="text" name="shop_name" placeholder="Enter shop name" class="form-control @error('name') is-invalid @enderror" value="@isset($item){{$item->shop_name}}@else{{old('shop_name')}}@endisset" id="exampleInputShopName" aria-describedby="shop_nameHelp" required>
                                    @error('shop_name')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"> <label for="exampleInputShopAddress1" class="form-label">Address</label> <textarea rows="3" name="shop_address" placeholder="Enter shop_address" class="form-control @error('shop_address') is-invalid @enderror" id="exampleInputShopAddress1" required>@isset($item){{$item->shop_address}}@else{{old('shop_address')}}@endisset</textarea>
                                    @error('shop_address')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"> <label for="exampleInputShopLocation" class="form-label">Shop Map Location <small class="text-danger">(Embedded Location)</small></label> <textarea rows="3" name="shop_map_location" placeholder="Enter shop map location" class="form-control @error('shop_map_location') is-invalid @enderror" id="exampleInputShopLocation" aria-describedby="emailHelp" required>@isset($item){{$item->shop_map_location}}@else{{old('shop_map_location')}}@endisset</textarea>
                                    @error('shop_map_location')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"> <label for="shopDescription" class="form-label">About Shop <small>[optional]</small></label> <textarea rows="3" name="shop_description" placeholder="Enter shop description" class="form-control @error('shop_description') is-invalid @enderror" id="shopDescription">@isset($item){{$item->shop_description}}@else{{old('shop_description')}}@endisset</textarea>
                                    @error('shop_description')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"><label for="shop_banner" class="form-label">Banner </label><input type="file" name="shop_banner" accept="image/jpg, image/jpeg, image/png" class="form-control" id="shop_banner" @isset($item) @else required @endisset>
                                    @error('shop_banner')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->shop_banner == null)
                                            <img src="{{asset('backend')}}/assets/img/default-banner.jpg" id="previewShopBanner" class="my-2 w-100" height="200px" alt="">
                                        @else
                                            <img src="{{asset($item->shop_banner)}}" id="previewShopBanner" class="img-fluid " alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/default-banner.jpg" id="previewShopBanner" class="my-2 w-100" height="200px" alt="">
                                    @endisset
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                        </div>
                    </div><!--end::Quick Example-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Authentication Documents</div>
                            </div> <!--end::Header--> <!--begin::Form-->
                            <div class="card-body row">
                                <div class="col-md-12 mb-3"><label for="nid_front" class="form-label">NID Card (Front) </label><input type="file" name="nid_front" accept="image/jpg, image/jpeg, image/png" class="form-control" id="nid_front" @isset($item) @else required @endisset>
                                    @error('nid_front')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->nid_front == null)
                                            <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewNidFront" width="200px" class="my-2 " alt="">
                                        @else
                                            <img src="{{asset($item->nid_front)}}" id="previewNidFront" width="200px" class="my-2 " alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewNidFront" width="200px" class="my-2" alt="">
                                    @endisset
                                </div>
                                <div class="col-md-12 mb-3"><label for="nid_back" class="form-label">NID Card (Back) </label><input type="file" name="nid_back" accept="image/jpg, image/jpeg, image/png" class="form-control" id="nid_back" @isset($item) @else required @endisset>
                                    @error('nid_back')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->nid_back == null)
                                            <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewNidBack" width="200px" class="my-2 " alt="">
                                        @else
                                            <img src="{{asset($item->nid_back)}}" id="previewNidBack" width="200px" class="my-2 " alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewNidBack" width="200px" class="my-2" alt="">
                                    @endisset
                                </div>
                                <div class="col-md-12 mb-3"><label for="trade_licence" class="form-label">Trade Licence </label><input type="file" name="trade_licence" accept="image/jpg, image/jpeg, image/png" class="form-control" id="trade_licence" @isset($item) @else required @endisset>
                                    @error('trade_licence')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->trade_licence == null)
                                            <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewTradeLicence" class="my-2 w-50" alt="">
                                        @else
                                            <img src="{{asset($item->trade_licence)}}" id="previewTradeLicence" class="my-2 w-50" alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/no-image.jpg" id="previewTradeLicence" class="my-2 w-50" alt="">
                                    @endisset
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                        </div>
                    </div><!--end::Quick Example-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Login Credentials</div>
                            </div> <!--end::Header--> <!--begin::Form-->

                            <div class="card-body row">

                                <div class="col-md-6 mb-3"> <label for="exampleInputPassword1" class="form-label">Password <small class="text-danger">[Min Length 8]</small></label> <input type="password" minlength="8" name="password" placeholder="Enter password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" @isset($item) @else required @endisset>
                                    @error('password')
                                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputPassword2" class="form-label">Confirm Password <small class="text-danger">[Min Length 8]</small></label> <input type="password" minlength="8" name="password_confirmation" placeholder="Re-enter password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword2" @isset($item) @else required @endisset>
                                    @error('password')
                                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                                    @enderror
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">@isset($item)Update @else Submit @endisset</button> </div> <!--end::Footer-->

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // jQuery function to preview the image
        $(document).ready(function(){
            $('#inputGroupFile02').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewImage').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#shop_banner').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewShopBanner').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#nid_front').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewNidFront').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#nid_back').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewNidBack').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
            $('#trade_licence').change(function(e){
                let reader = new FileReader();
                reader.onload = function(e){
                    $('#previewTradeLicence').attr('src', e.target.result); // Change the src of img tag
                }
                reader.readAsDataURL(this.files[0]); // Read the file as a data URL
            });
        });
    </script>
@endpush
