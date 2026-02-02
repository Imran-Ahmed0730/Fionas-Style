@extends('backend.master')
@section('title', 'Edit Order settings')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Order Setting</h3>
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
                        <a href="#">Order</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Shipping & Order Configuration</div>
                        </div>
                        <form action="{{route('admin.setting.update')}}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_method" class="form-label">Shipping Calculation
                                                Method</label>
                                            <select name="shipping_method" id="shipping_method" class="form-control">
                                                <option value="location_wise" {{ getSetting('shipping_method') == 'location_wise' ? 'selected' : '' }}>
                                                    Location Wise (State based)</option>
                                                <option value="product_wise" {{ getSetting('shipping_method') == 'product_wise' ? 'selected' : '' }}>
                                                    Product Wise (Sum of product shipping costs)</option>
                                                <option value="flat_rate" {{ getSetting('shipping_method') == 'flat_rate' ? 'selected' : '' }}>Flat Rate</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="free_delivery_threshold" class="form-label">Free Delivery Threshold
                                                (0 to disable)</label>
                                            <input type="number" name="free_delivery_threshold" id="free_delivery_threshold"
                                                class="form-control" value="{{ getSetting('free_delivery_threshold', 0) }}">
                                            <small class="text-muted">If subtotal exceeds this amount, shipping becomes
                                                free.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="shipping_cost_inside_dhaka" class="form-label">Shipping Cost (Inside
                                                Dhaka)</label>
                                            <input type="number" name="shipping_cost_inside_dhaka"
                                                id="shipping_cost_inside_dhaka" class="form-control"
                                                value="{{ getSetting('shipping_cost_inside_dhaka', 60) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="shipping_cost_outside_dhaka" class="form-label">Shipping Cost
                                                (Outside Dhaka)</label>
                                            <input type="number" name="shipping_cost_outside_dhaka"
                                                id="shipping_cost_outside_dhaka" class="form-control"
                                                value="{{ getSetting('shipping_cost_outside_dhaka', 120) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="flat_rate_shipping_cost" class="form-label">Flat Rate Shipping
                                                Cost</label>
                                            <input type="number" name="flat_rate_shipping_cost" id="flat_rate_shipping_cost"
                                                class="form-control"
                                                value="{{ getSetting('flat_rate_shipping_cost', 100) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tax_percentage" class="form-label">Tax Percentage (%)</label>
                                            <input type="number" step="0.01" name="tax_percentage" id="tax_percentage"
                                                class="form-control" value="{{ getSetting('tax_percentage', 0) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Update Settings</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection