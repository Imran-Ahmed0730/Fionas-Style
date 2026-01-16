@extends('backend.master')
@section('title')
   @isset($item) Edit @else Add @endisset Setting
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Add Setting Key</h3>
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
                        <a href="#">Add Key</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">Key Information</div>
                            <a href="{{route('admin.setting.index')}}" data-bs-toggle="tooltip" title="View Settings" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Settings</a>
                        </div>
                        <form action="@isset($item){{route('admin.setting.update')}}@else{{route('admin.setting.store')}}@endisset" method="post" enctype="multipart/form-data">
                            @csrf<!--begin::Body-->
                            <input type="hidden" name="id" value="@isset($item){{$item->id}}@endisset">
                            <div class="card-body">

                                <div class="mb-3"> <label for="key" class="form-label">Key</label>
                                    <input type="text" name="key" placeholder="Enter key name" class="form-control
                                            @error('key') is-invalid @enderror"
                                           value="@isset($item){{$item->key}}@else{{old('key')}}@endisset"
                                           id="key" aria-describedby="key" required>
                                    @error('key')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                <div class="mb-3"> <label for="value" class="form-label">Value</label>
                                    <textarea rows="3" name="value" placeholder="Enter value"
                                              class="form-control @error('value') is-invalid @enderror"
                                              id="value" required>@isset($item){{$item->value}}@else{{old('value')}}@endisset</textarea>
                                    @error('value')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-action d-flex justify-content-end"> <button type="submit" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button> </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

