@extends('backend.master')
@section('title')
    View Products
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Products</h3>
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
                        <a href="#">Products</a>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">View Products</h3>
                            @can('Product Add')
                                <a href="{{route('admin.product.create')}}" data-bs-toggle="tooltip" title="Add Product" class="btn btn-primary ms-auto">
                                    <i class="fa fa-plus me-2"></i> Add Product
                                </a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Thumbnail</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Featured</th>
                                    <th>Today's Deal</th>
                                    <th style="width: 40px">Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $key => $item)
                                    <tr class="align-middle">
                                        <td>{{$key+1}}.</td>
                                        <td>
                                            @if($item->thumbnail)
                                                <img src="{{asset($item->thumbnail)}}" width="50px" alt="">
                                            @else 
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" width="50px" alt="">
                                            @endif
                                        </td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->category->name ?? 'N/A'}}</td>
                                        <td>
                                            <div>Reg: {{$item->regular_price}}</div>
                                            <div class="text-success">Sell: {{$item->selling_price}}</div>
                                        </td>
                                        <td>{{$item->stock_qty}}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                       data-url="{{route('admin.product.featured.change', $item->id)}}"
                                                    {{ $item->is_featured == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                       data-url="{{route('admin.product.todays-deal.change', $item->id)}}"
                                                    {{ $item->include_to_todays_deal == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @can('Product Status Change')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                           data-url="{{route('admin.product.status.change', $item->id)}}"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            @else
                                                <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success': 'danger'}}">{{$item->status == 1 ? 'Active':'Inactive'}}</span>
                                            @endcan 
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Product Update')
                                                <a href="{{route('admin.product.edit', $item->id)}}" data-bs-toggle="tooltip" title="Edit" class="btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('Product Delete')
                                                <form action="{{route('admin.product.delete')}}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                    <button class="btn btn-danger btn-delete" data-bs-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>
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
            
            $(document).on('change', '.toggle-switch', function() {
                var url = $(this).data('url');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if(response.status == 'success'){
                            toastr.success(response.message);
                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong');
                    }
                });
            });
        });

    </script>
@endpush
