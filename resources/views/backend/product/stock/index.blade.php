@extends('backend.master')
@section('title')
    View Product Stocks
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Product Stocks</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Product Stocks</a>
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
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h3 class="card-title mb-0">View Product Stocks</h3>
                                @can('Product Stock Add')
                                    <a href="{{route('admin.stock.create')}}" data-bs-toggle="tooltip" title="Add Product Stock"
                                        class="btn btn-primary ms-auto">
                                        <i class="fa fa-plus me-2"></i> Add Product Stock
                                    </a>
                                @endcan
                            </div>
                            <div class="mt-3">
                                <form action="" class="row" method="get" id="filterForm">
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label for="product_id" class="form-label col-md-3">Product</label>
                                            <div class="col-md-9">
                                                <select name="product_id" id="product_id"
                                                    class="form-select js-example-basic">
                                                    <option value="">All</option>
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}"
                                                            @isset($_GET['product_id']){{$_GET['product_id'] == $product->id ? 'selected' : ''}}@endisset>{{$product->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row align-items-center">
                                            <label for="stock_sku" class="form-label col-md-3">Stock SKU</label>
                                            <div class="col-md-9">
                                                <select name="stock_sku" id="stock_sku"
                                                    class="form-select js-example-basic">
                                                    <option value="">All</option>
                                                    @foreach($stock_skus as $stock_sku)
                                                        <option value="{{$stock_sku->sku}}"
                                                            @isset($_GET['stock_sku']){{$_GET['stock_sku'] == $stock_sku->sku ? 'selected' : ''}}@endisset>{{$stock_sku->sku}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Stock SKU</th>
                                            <th>Added By</th>
                                            <th>Added On</th>
                                            <th>Product Name</th>
                                            <th>Variant</th>
                                            <th>Supplier</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $counter = 1; @endphp
                                        @foreach($items as $sku => $skuItems)
                                            @foreach($skuItems as $key => $item)
                                                <tr class="align-middle">
                                                    @if($loop->first)
                                                        <td rowspan="{{ count($skuItems) }}">{{$counter++}}.</td>
                                                        <td rowspan="{{ count($skuItems) }}">{{$sku ?? 'N/A'}}</td>
                                                        <td rowspan="{{ count($skuItems) }}">{{$item->addedBy->name ?? 'N/A'}}</td>
                                                        <td rowspan="{{ count($skuItems) }}">{{ $item->added_on }}</td>
                                                    @endif
                                                    <td>{{$item->product_name ?? 'N/A'}}</td>
                                                    <td>{{$item->variant_name ?? 'N/A'}}</td>
                                                    <td>
                                                        @if($item->supplier_id)
                                                            {{$item->supplier->name ?? 'N/A'}} ({{$item->supplier->phone ?? ''}})
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="qty">{{$item->qty ?? 'N/A'}}</td>
                                                    <td>
                                                        <div class="d-flex">
                                                            @can('Product Stock Delete')
                                                                <form action="{{route('admin.stock.delete')}}" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{$item->id}}">
                                                                    <button class="btn btn-danger btn-delete" data-bs-toggle="tooltip"
                                                                        title="Delete"><i class="fa fa-trash"></i></button>
                                                                </form>
                                                            @endcan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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
            $('#datatable').DataTable();

            // Submit filter form on dropdown change
            $('#product_id, #stock_sku').change(function () {
                $('#filterForm').submit();
            });
        });
    </script>
@endpush