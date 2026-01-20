@extends('backend.master')
@section('title')
    Product Details
@endsection
@push('css')
    <style>
        .nav-tabs.nav-line .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #575962;
            font-weight: 600;
            padding: 10px 20px;
        }

        .nav-tabs.nav-line .nav-link.active {
            border-bottom-color: #4361ee;
            color: #4361ee;
        }

        .description-content {
            line-height: 1.6;
            color: #4a4a4a;
        }

        .description-content ul,
        .description-content ol {
            padding-left: 20px;
        }

        .object-fit-cover {
            object-fit: cover;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Product Details</h3>
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
                        <a href="{{route('admin.product.index')}}">Products</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Details</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="card-title">{{$item->name}} Details</h4>
                            <div class="ms-auto">
                                <a href="{{route('admin.product.edit', $item->id)}}" class="btn btn-primary "><i
                                        class="fa fa-pencil me-2"></i>Edit</a>
                                <a href="{{route('admin.stock.create', ['product' => $item->id])}}"
                                    class="btn btn-success ms-2"><i class="fa fa-plus me-2"></i>Add Stock</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs nav-line nav-color-secondary mb-4" id="productTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="info-tab" data-bs-toggle="tab" href="#info" role="tab"
                                        aria-controls="info" aria-selected="true">General Info</a>
                                </li>
                                @if($item->is_variant)
                                    <li class="nav-item">
                                        <a class="nav-link" id="variants-tab" data-bs-toggle="tab" href="#variants" role="tab"
                                            aria-controls="variants" aria-selected="false">Variants</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" id="stock-tab" data-bs-toggle="tab" href="#stock" role="tab"
                                        aria-controls="stock" aria-selected="false">Stock History</a>
                                </li>
                            </ul>

                            <div class="tab-content mt-2 mb-3" id="productTabsContent">
                                <!-- General Info Tab -->
                                <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="mb-3">
                                                <img src="{{asset($item->thumbnail)}}"
                                                    class="img-fluid border rounded shadow-sm" style="max-height: 300px;"
                                                    alt="{{$item->name}}">
                                            </div>
                                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                                @foreach($item->gallery as $img)
                                                    <a href="{{asset($img->image)}}" target="_blank">
                                                        <img src="{{asset($img->image)}}" width="60px" height="60px"
                                                            class="border rounded object-fit-cover shadow-sm" alt="Gallery">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="mb-1 fw-bold">{{$item->name}}</h3>
                                                    <span
                                                        class="badge text-bg-{{$item->status == 1 ? 'success' : 'danger'}}">{{$item->status == 1 ? 'Active' : 'Inactive'}}</span>
                                                </div>
                                                <div class="text-end">
                                                    <p class="mb-0 text-muted">SKU</p>
                                                    <h4 class="fw-bold">{{$item->sku ?? 'N/A'}}</h4>
                                                </div>
                                            </div>
                                            <p class="mt-3 text-muted lead">{{$item->short_description}}</p>
                                            <hr>
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Category</p>
                                                    <h5 class="fw-bold">{{$item->category->name ?? 'N/A'}}</h5>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Brand</p>
                                                    <h5 class="fw-bold">{{$item->brand->name ?? 'N/A'}}</h5>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Selling Price</p>
                                                    <h4 class="fw-bold text-success">{{$item->selling_price}}</h4>
                                                    @if($item->regular_price > $item->selling_price)
                                                        <small
                                                            class="text-muted text-decoration-line-through">{{$item->regular_price}}</small>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Total Stock</p>
                                                    <div class="d-flex align-items-center">
                                                        <h4 class="fw-bold mb-0 me-2">{{$item->stock_qty}}</h4>
                                                        @if($item->stock_qty <= 5)
                                                            <span class="badge bg-danger">Low Stock</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Total Views</p>
                                                    <div class="d-flex align-items-center text-info">
                                                        <i class="fa fa-eye me-2"></i>
                                                        <h4 class="fw-bold mb-0">{{$item->view_count}}</h4>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <p class="text-muted mb-1">Total Sold</p>
                                                    <div class="d-flex align-items-center text-primary">
                                                        <i class="fa fa-shopping-cart me-2"></i>
                                                        <h4 class="fw-bold mb-0">{{$item->sell_count}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="mt-4">
                                                <h5 class="fw-bold mb-3">Detailed Description</h5>
                                                <div class="description-content">
                                                    {!! $item->detailed_description !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Variants Tab -->
                                @if($item->is_variant)
                                    <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Variant Name</th>
                                                        <th>SKU</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($item->variants as $variant)
                                                        <tr>
                                                            <td>
                                                                <img src="{{asset($variant->image ?? $item->thumbnail)}}" width="40"
                                                                    height="40" class="rounded border shadow-sm" alt="">
                                                            </td>
                                                            <td class="fw-bold">{{$variant->name}}</td>
                                                            <td><code>{{$variant->sku}}</code></td>
                                                            <td>{{$variant->regular_price}}</td>
                                                            <td>
                                                                <span
                                                                    class="badge {{$variant->stock_qty <= 5 ? 'bg-danger' : 'bg-secondary'}}">
                                                                    {{$variant->stock_qty}}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- Stock History Tab -->
                                <div class="tab-pane fade" id="stock" role="tabpanel" aria-labelledby="stock-tab">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Stock SKU</th>
                                                    <th>Variant</th>
                                                    <th>Supplier</th>
                                                    <th>Qty</th>
                                                    <th>Buying Price</th>
                                                    <th>Added By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($item->stocks->sortByDesc('added_on') as $stock)
                                                    <tr>
                                                        <td>{{ $stock->added_on }}</td>
                                                        <td><code>{{ $stock->sku }}</code></td>
                                                        <td>{{ $stock->variant_name ?? 'N/A' }}</td>
                                                        <td>{{ $stock->supplier->name ?? 'N/A' }}</td>
                                                        <td><span class="badge bg-info">+{{ $stock->qty }}</span></td>
                                                        <td>{{ $stock->buying_price }}</td>
                                                        <td>{{ $stock->addedBy->name ?? 'System' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-4">No stock history found.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection