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
                        <div class="card-body ">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
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
                                        <td>{{$key + 1}}.</td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->thumbnail && file_exists(public_path($item->thumbnail)))
                                                    <img src="{{asset($item->thumbnail)}}" width="35px" height="35px" class="rounded-circle me-2" alt="">
                                                @else
                                                    <img src="{{asset('backend/assets/img/default-150x150.png')}}" class="rounded-circle me-2" width="35px" height="35px" alt="">
                                                @endif
                                                <span>{{$item->name}}</span>
                                            </div>
                                        </td>
                                        <td>{{$item->category->name ?? 'N/A'}}</td>
                                        <td>
                                            <div>Reg: {{$item->regular_price}}</div>
                                            <div class="text-success">Sell: {{$item->selling_price}}</div>
                                        </td>
                                        <td>{{$item->stock_qty}}</td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input product-toggle-switch" type="checkbox" role="switch"
                                                       data-url="{{route('admin.product.featured.change', $item->id)}}"
                                                       data-type="featured"
                                                    {{ $item->is_featured == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input product-toggle-switch" type="checkbox" role="switch"
                                                       data-url="{{route('admin.product.todays-deal.change', $item->id)}}"
                                                       data-type="today's deal"
                                                    {{ $item->include_to_todays_deal == 1 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            @can('Product Status Change')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                           data-url="{{route('admin.product.status.change', $item->id)}}"
                                                           data-type="status"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            @else
                                                <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success' : 'danger'}}">{{$item->status == 1 ? 'Active' : 'Inactive'}}</span>
                                            @endcan
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Product Details')
                                                    <a href="{{route('admin.product.show', $item->id)}}" data-bs-toggle="tooltip" title="Details" class="btn btn-info me-2"><i class="fa fa-eye"></i></a>
                                                @endcan
                                                @can('Product Update')
                                                    <a href="{{route('admin.product.edit', $item->id)}}" data-bs-toggle="tooltip" title="Edit" class="btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('Product Delete')
                                                    <button type="button" class="btn btn-danger btn-delete" data-bs-toggle="tooltip" title="Delete" data-id="{{$item->id}}" data-name="{{$item->name}}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                </table>
                            </div>
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
            // Initialize DataTable
            $('#datatable').DataTable({
                "order": [[0, "asc"]]
            });

            // Handle toggle switches
            $(document).on('change', '.toggle-switch, .product-toggle-switch', function() {
                var url = $(this).data('url');
                var isChecked = $(this).is(':checked');
                var type = $(this).data('type') || 'setting';
                var switchElement = $(this);

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to ${isChecked ? 'activate' : 'deactivate'} this product's ${type}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'GET',
                            success: function (response) {
                                if (response.status == 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: response.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    switchElement.prop('checked', !isChecked); // Revert if error
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: response.message || 'Something went wrong',
                                    });
                                }
                            },
                            error: function (err) {
                                switchElement.prop('checked', !isChecked); // Revert on error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Something went wrong. Please try again.',
                                });
                            }
                        });
                    } else {
                        // Revert the switch if user cancels
                        switchElement.prop('checked', !isChecked);
                    }
                });
            });

            // Handle delete button
        });
    </script>
@endpush
