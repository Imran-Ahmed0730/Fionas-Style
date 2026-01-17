@extends('backend.master')
@section('title')
    Product Details
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Product Details: {{$item->name}}</h3>
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
                            <h4 class="card-title">Product Information</h4>
                            <a href="{{route('admin.product.edit', $item->id)}}" class="btn btn-primary ms-auto"><i class="fa fa-pencil me-2"></i>Edit Product</a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="{{asset($item->thumbnail)}}" class="img-fluid border rounded" alt="{{$item->name}}">
                                    <div class="mt-3 d-flex flex-wrap justify-content-center gap-2">
                                        @foreach($item->gallery as $img)
                                            <img src="{{asset($img->image)}}" width="80px" class="border rounded" alt="">
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h3>{{$item->name}}</h3>
                                    <p class="text-muted">{{$item->short_description}}</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <strong>Category:</strong> {{$item->category->name ?? 'N/A'}}
                                        </div>
                                        <div class="col-6 mb-3">
                                            <strong>Brand:</strong> {{$item->brand->name ?? 'N/A'}}
                                        </div>
                                        <div class="col-6 mb-3">
                                            <strong>Regular Price:</strong> {{$item->regular_price}}
                                        </div>
                                        <div class="col-6 mb-3">
                                            <strong>Selling Price:</strong> <span class="text-success h5">{{$item->selling_price}}</span>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <strong>Stock:</strong> {{$item->stock_qty}}
                                        </div>
                                        <div class="col-6 mb-3">
                                            <strong>Status:</strong> {{$item->status == 1 ? 'Active':'Inactive'}}
                                        </div>
                                    </div>
                                    <hr>
                                    <h5>Detailed Description</h5>
                                    <div>{!! $item->detailed_description !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
