@extends('backend.master')
@section('title')
    @isset($item)Edit @else Add @endisset Staff
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection{
            height: 39px;
            border: 1px solid #dee2e6 !important;
            border-radius: 6px;
        }
    </style>
@endpush
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
                <form action="@isset($item){{route('admin.staff.update')}}@else{{route('admin.staff.store')}}@endisset" method="post" enctype="multipart/form-data">
                    @csrf<!--begin::Body-->
                    <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header d-flex align-items-center">
                                <div class="card-title">@isset($item)Edit @else Add @endisset Staff</div>
                                <a href="{{route('admin.staff.index')}}" data-bs-toggle="tooltip" title="View Staff" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Staff</a>
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
                                <div class="col-md-6 mb-3"> <label for="exampleInputPhone1" class="form-label">Phone <small>[optional]</small></label> <input type="tel" placeholder="Enter contact no" name="phone" value="@isset($item){{$item->phone}}@else{{old('phone')}}@endisset" class="form-control @error('phone') is-invalid @enderror" id="exampleInputPhone1" required>
                                    @error('phone')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputRole1" class="form-label">Role <small>[multiple available]</small></label>
                                    @isset($item)
                                        @php $user_roles = $item->user->roles->pluck('name')->toArray(); @endphp
                                    @endisset
                                    <select name="role[]" id="exampleInputRole1" class="js-example-basic-multiple form-control " multiple>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{$role->name}}" @isset($item){{in_array($role->name, $user_roles) ? 'selected':''}}@endisset>{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"> <label for="exampleInputAddress1" class="form-label">Address <small>[optional]</small></label> <textarea rows="3" name="address" placeholder="Enter address" class="form-control @error('address') is-invalid @enderror" id="exampleInputAddress1" required>@isset($item){{$item->address}}@else{{old('address')}}@endisset</textarea>
                                    @error('address')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3"><label for="inputGroupFile02" class="form-label">Profile Image <small>[optional]</small></label><input type="file" name="image" class="form-control" id="inputGroupFile02">
                                    @error('image')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                    @isset($item)
                                        @if($item->image == null)
                                            <img src="{{asset('backend')}}/assets/img/user1-128x128.jpg" id="previewImage" width="128px" class="my-2 rounded-circle " alt="">
                                        @else
                                            <img src="{{asset($item->image)}}" id="previewImage" width="128px" class="my-2 rounded-circle " alt="">

                                        @endif
                                    @else
                                        <img src="{{asset('backend')}}/assets/img/user1-128x128.jpg" id="previewImage" width="128px" class="my-2 rounded-circle " alt="">
                                    @endisset
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputSalary1" class="form-label">Salary <small>[optional]</small></label> <input min="0" type="number" name="salary" placeholder="Enter salary" value="@isset($item){{$item->salary}}@else{{old('salary')}}@endisset" class="form-control @error('salary') is-invalid @enderror" id="exampleInputSalary1" required>
                                    @error('salary')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputAddress1" class="form-label">Joining Date <small>[optional]</small></label> <input type="date" name="join_date" value="@isset($item){{$item->join_date}}@else{{date('Y-m-d')}}@endisset" class="form-control @error('join_date') is-invalid @enderror" id="exampleInputAddress1" required>
                                    @error('join_date')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputNid1" class="form-label">NID no <small>[optional]</small></label> <input type="text" placeholder="Enter NID no" name="nid_no" value="@isset($item){{$item->nid_no}}@else{{old('nid_no')}}@endisset" class="form-control @error('nid_no') is-invalid @enderror" id="exampleInputNid1" required>
                                    @error('nid_no')
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
                            </div> <!--end::Body--> <!--begin::Footer-->
                        </div>
                    </div><!--end::Quick Example-->
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">Login Credentials</div>
                            </div> <!--end::Header--> <!--begin::Form-->

                            <div class="card-body row">

                                <div class="col-md-6 mb-3"> <label for="exampleInputPassword1" class="form-label">Password</label> <input type="password" minlength="8" name="password" placeholder="Enter password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword1" @isset($item) @else required @endisset>
                                    @error('password')
                                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3"> <label for="exampleInputPassword2" class="form-label">Confirm Password</label> <input type="password" minlength="8" name="password_confirmation" placeholder="Re-enter password" class="form-control @error('password') is-invalid @enderror" id="exampleInputPassword2" @isset($item) @else required @endisset>
                                    @error('password')
                                    <div class="invalid-feedback" role="alert">{{$message}}</div>
                                    @enderror
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->

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
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
