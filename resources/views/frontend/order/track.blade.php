@extends('frontend.master')
@section('title', 'Track Order')
@section('content')

    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text product-more">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Order Tracking</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="spad">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <div class="card p-4">
                        <h3 class="mb-4 text-center">Track Your Order</h3>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('order.track.submit') }}">
                            @csrf
                            <div class="form-group">
                                <label for="invoice">Invoice Number</label>
                                <input type="text" name="invoice" id="invoice" class="form-control" placeholder="Enter your invoice number" required>
                            </div>
                            <div class="text-center mt-4">
                                <button class="site-btn" type="submit">Track Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
