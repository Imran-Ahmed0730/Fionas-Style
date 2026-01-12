@extends('backend.master')
@section('title')
    @isset($item)Edit @else Add @endisset User Role
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
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">@isset($item)Edit @else Add @endisset User Role</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item " aria-current="page">
                            User Role
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @isset($item)Edit @else Add @endisset
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div>
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid">
            <div class="row g-4">
                <form action="{{route('admin.staff.role.assign.submit')}}" method="post" enctype="multipart/form-data">
                    @csrf<!--begin::Body-->
                    <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title">@isset($item)Edit @else Add @endisset User Role </div>
                            </div> <!--end::Header--> <!--begin::Form-->
                            <div class="card-body row">
                                <div class="col-md-6 mb-3"> <label for="exampleInputName1" class="form-label">User
                                    </label>
                                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror" id="exampleInputName1" aria-describedby="nameHelp" required>
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
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
                                            <option value="{{$role->name}}" @isset($item){{in_array($role->name, $user_roles) ? 'selected':''}}@endisset>{{Str::title($role->name)}}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end"> <button type="submit" class="btn btn-primary">Update</button> </div> <!--end::Footer-->
                        </div>
                    </div><!--end::Quick Example-->

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
