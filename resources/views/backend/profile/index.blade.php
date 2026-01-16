@extends('backend.master')
@section('title')
   Profile
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Manage Profile</h3>
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
                        <a href="#">Profile</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">Profile Information</div>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="brand_form" action="{{route('admin.profile.update')}}" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input id="name" type="text" name="name" value="{{Auth::user()->name}}" placeholder="Enter name" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input id="email" type="email" name="email" value="{{Auth::user()->email}}" placeholder="Enter email" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                @if(Auth::user()->role == 4)
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input id="phone" type="tel" name="phone" value="{{Auth::user()->phone}}" pattern="^01[3-9]\d{8}$" placeholder="Enter phone" class="form-control @error('phone') is-invalid @enderror" required>
                                    @error('phone')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea id="address" name="address" rows="3" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" required>{{Auth::user()->address}}</textarea>
                                    @error('address')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"><label for="image" class="form-label">Image</label><input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="image" accept="image/jpg, image/jpeg, image/png" @isset($item) @else required @endisset>
                                    @error('image')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->image == null)
                                            <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewImage" width="100px" class="my-2  rounded-circle object-fit-cover" alt="">
                                        @else
                                            <img src="{{asset($item->image)}}" id="previewImage" width="100px" class="my-2  rounded-circle object-fit-cover" alt="">
                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/default-150x150.png" id="previewImage" width="100px" class="my-2  rounded-circle object-fit-cover" alt="">

                                    @endisset
                                </div>
                                @endif
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">Change Password</div>
                        </div>
                        <div class="card-body">
                            <form id="password_form" action="{{route('admin.password.update')}}" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <input id="current_password" type="password" name="current_password" placeholder="Enter current password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                        <span class="input-group-text toggle-password" data-target="#current_password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                    </div>
                                    
                                    @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group">
                                        <input id="password" type="password" name="password" placeholder="Enter new password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                                        <span class="input-group-text toggle-password" data-target="#password" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                    </div>
                                    @error('password', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm new password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" required>
                                        <span class="input-group-text toggle-password" data-target="#password_confirmation" style="cursor: pointer;"><i class="fa fa-eye"></i></span>
                                    </div>
                                    @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="card-footer d-flex justify-content-end"> <button type="submit" id="submit_btn" class="btn btn-primary">Update Password</button> </div>
                            </form>
                        </div>
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
            
            // Toggle Password Visibility
            $('.toggle-password').click(function() {
                let target = $($(this).data('target'));
                let icon = $(this).find('i');
                if (target.attr('type') === 'password') {
                    target.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    target.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        })
    </script>

@endpush
