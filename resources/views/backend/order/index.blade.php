@extends('backend.master')
@section('title', 'Orders')
@push('css')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- DateRangePicker CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Orders</h3>
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
                        <a href="#">Orders</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Manage</a>
                    </li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h4 class="card-title">Filter Orders</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ request()->url() }}" method="GET" id="filterForm">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Date Range</label>
                                            <input type="text" name="date_range" class="form-control"
                                                value="{{ request('date_range', date('Y-m-01') . ' - ' . date('Y-m-t')) }}"
                                                id="dateRangePicker">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Order Status</label>
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending
                                                </option>
                                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Confirmed
                                                </option>
                                                <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Processing
                                                </option>
                                                <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Shipped
                                                </option>
                                                <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Delivered
                                                </option>
                                                <option value="5" {{ request('status') === '5' ? 'selected' : '' }}>Cancelled
                                                </option>
                                                <option value="6" {{ request('status') === '6' ? 'selected' : '' }}>Hold
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Payment Method</label>
                                            <select name="payment_method_id" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="">All Methods</option>
                                                @foreach($payment_methods as $pm)
                                                    <option value="{{ $pm->id }}" {{ request('payment_method_id') == $pm->id ? 'selected' : '' }}>{{ $pm->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Payment Status</label>
                                            <select name="payment_status" class="form-control"
                                                onchange="this.form.submit()">
                                                <option value="">All Status</option>
                                                <option value="0" {{ request('payment_status') === '0' ? 'selected' : '' }}>
                                                    Unpaid</option>
                                                <option value="1" {{ request('payment_status') === '1' ? 'selected' : '' }}>
                                                    Paid</option>
                                                <option value="2" {{ request('payment_status') === '2' ? 'selected' : '' }}>
                                                    Partial</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Order List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Invoice</th>
                                            <th>Customer</th>
                                            <th>Total</th>
                                            <th>Order Status</th>
                                            <th>Payment Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->created_at->format('d-m-Y') }}</td>
                                                <td>{{ $item->invoice_no }}</td>
                                                <td>
                                                    {{ $item->name }}<br>
                                                    <small>{{ $item->phone }}</small>
                                                </td>
                                                <td>{{ getCurrency()['symbol'] }}{{ number_format($item->grand_total, 2) }}</td>
                                                <td>
                                                    @php
                                                        $statusClasses = [0 => 'warning', 1 => 'info', 2 => 'primary', 3 => 'secondary', 4 => 'success', 5 => 'danger', 6 => 'dark'];
                                                        $statusTitles = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3 => 'Shipped', 4 => 'Delivered', 5 => 'Cancelled', 6 => 'Hold'];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClasses[$item->status] ?? 'light' }}">
                                                        {{ $statusTitles[$item->status] ?? 'Unknown' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $pStatusClasses = [0 => 'danger', 1 => 'success', 2 => 'warning', 3 => 'secondary'];
                                                        $pStatusTitles = [0 => 'Unpaid', 1 => 'Paid', 2 => 'Partial', 3 => 'Refunded'];
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $pStatusClasses[$item->payment_status] ?? 'light' }}">
                                                        {{ $pStatusTitles[$item->payment_status] ?? 'Unknown' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.order.show', $item->id) }}"
                                                        class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- Moment JS and DateRangePicker JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.datatable').DataTable({
                "order": [[0, "desc"]]
            });

            $('#dateRangePicker').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month'),
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            });
        });
    </script>
@endpush