@extends('backend.master')
@section('title')
    View Vendors
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Vendors</h3>
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
                        <a href="#">Vendors</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">View</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">View Vendors</h3>
                            @can('Vendor Add')
                                <a href="{{route('admin.vendor.create')}}" data-bs-toggle="tooltip" title="Add Vendor" class="btn btn-primary ms-auto">
                                    <i class="fa fa-plus me-2"></i> Add Vendor
                                </a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Shop Name</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $key => $item)
                                    <tr class="align-middle">
                                        <td>{{$key+1}}.</td>
                                        <td>
                                            @if($item->image)
                                                <img src="{{asset($item->image)}}" width="50px" height="50px" class="rounded-circle" alt="">
                                            @else
                                                <img src="{{asset('backend')}}/assets/img/user1-128x128.jpg" id="previewImage" width="50" class="my-2 rounded-circle " alt="">
                                            @endif
                                        </td>
                                        <td>{{Str::title($item->name)}}</td>
                                        <td><a href="mailto:{{$item->email}}">{{$item->email}}</a></td>
                                        <td><a href="tel:{{$item->phone}}">{{$item->phone}}</a></td>
                                        <td>{{$item->shop_name}}</td>
                                        <td>
                                            @can('Vendor Status Change')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                           data-module="vendor" data-id="{{ $item->id }}"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status"></label>
                                                </div>
                                            @else
                                                <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success': 'danger'}}">{{$item->status == 1 ? 'Active':'Inactive'}}</span>
                                            @endcan
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Vendor Update')
                                                    <a href="{{route('admin.vendor.edit', $item->id)}}" title="Edit" class="btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('Vendor Delete')
                                                    <form action="{{route('admin.vendor.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                        <button class="btn btn-danger btn-delete" title="Remove"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div>
        </div>
    </div>
@endsection
@push('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();
        })
    </script>
@endpush
