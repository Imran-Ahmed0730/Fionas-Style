@extends('backend.master')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div
                class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4"
            >
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                    <h6 class="op-7 mb-2">Free Bootstrap 5 Admin Dashboard</h6>
                </div>
                <div class="ms-md-auto py-2 py-md-0">
                    <a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
                    <a href="#" class="btn btn-primary btn-round">Add Customer</a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div
                                        class="icon-big text-center icon-primary bubble-shadow-small"
                                    >
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Customers</p>
                                        <h4 class="card-title">{{ number_format($totalCustomers ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div
                                        class="icon-big text-center icon-info bubble-shadow-small"
                                    >
                                        <i class="fas fa-user-check"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">New (7d)</p>
                                        <h4 class="card-title">{{ ($newCustomers ?? collect())->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div
                                        class="icon-big text-center icon-success bubble-shadow-small"
                                    >
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Sales</p>
                                        <h4 class="card-title">$ {{ number_format($todaySales ?? 0, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div
                                        class="icon-big text-center icon-secondary bubble-shadow-small"
                                    >
                                        <i class="far fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Orders</p>
                                        <h4 class="card-title">{{ number_format($totalOrders ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">User Statistics</div>
                                <div class="card-tools">
                                    <a
                                        href="#"
                                        class="btn btn-label-success btn-round btn-sm me-2"
                                    >
                          <span class="btn-label">
                            <i class="fa fa-pencil"></i>
                          </span>
                                        Export
                                    </a>
                                    <a href="#" class="btn btn-label-info btn-round btn-sm">
                          <span class="btn-label">
                            <i class="fa fa-print"></i>
                          </span>
                                        Print
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="min-height: 375px">
                                <canvas id="statisticsChart"></canvas>
                            </div>
                            <div id="myChartLegend"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-primary card-round">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Daily Sales</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-sm btn-label-light dropdown-toggle"
                                            type="button"
                                            id="dropdownMenuButton"
                                            data-bs-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                        >
                                            Export
                                        </button>
                                        <div
                                            class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton"
                                        >
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#"
                                            >Something else here</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-category">{{ \Carbon\Carbon::now()->startOfMonth()->format('M d') }} - {{ \Carbon\Carbon::now()->format('M d') }}</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1>$ {{ number_format($monthSales ?? 0, 2) }}</h1>
                            </div>
                            <div class="pull-in">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card card-round">
                        <div class="card-body pb-0">
                            <div class="h1 fw-bold float-end text-primary">+5%</div>
                            <h2 class="mb-2">17</h2>
                            <p class="text-muted">Users online</p>
                            <div class="pull-in sparkline-fix">
                                <div id="lineChart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">New Customers</div>
                                {{-- <div class="card-tools">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-icon btn-clean me-0"
                                            type="button"
                                            id="dropdownMenuButton"
                                            data-bs-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                        >
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div
                                            class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton"
                                        >
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#"
                                            >Something else here</a
                                            >
                                        </div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="card-list py-4">
                                <div class="item-list">
                                    @foreach($newCustomers ?? collect() as $cust)
                                        <div class="item-list">
                                            <div class="avatar">
                                                @if($cust->image)
                                                    <img src="{{ asset($cust->image) }}" alt="{{ $cust->name }}" class="avatar-img rounded-circle" />
                                                @else
                                                    <span class="avatar-title rounded-circle border border-white">{{ strtoupper(substr($cust->name,0,1)) }}</span>
                                                @endif
                                            </div>
                                            <div class="info-user ms-3">
                                                <div class="username">{{ $cust->name }}</div>
                                                <div class="status">{{ $cust->email }}</div>
                                            </div>
                                            <a href="mailto:{{ $cust->email }}" class="btn btn-icon btn-link op-8 me-1">
                                                <i class="far fa-envelope"></i>
                                            </a>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Transaction History</div>
                                <div class="card-tools">
                                    <div class="dropdown">
                                        <button
                                            class="btn btn-icon btn-clean me-0"
                                            type="button"
                                            id="dropdownMenuButton"
                                            data-bs-toggle="dropdown"
                                            aria-haspopup="true"
                                            aria-expanded="false"
                                        >
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
                                        <div
                                            class="dropdown-menu"
                                            aria-labelledby="dropdownMenuButton"
                                        >
                                            <a class="dropdown-item" href="#">Action</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#"
                                            >Something else here</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Payment Number</th>
                                        <th scope="col" class="text-end">Date & Time</th>
                                        <th scope="col" class="text-end">Amount</th>
                                        <th scope="col" class="text-end">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recentTransactions ?? collect() as $order)
                                        @php
                                            $payment = $order->orderPayments->first();
                                            $amount = $order->orderPayments->sum('amount') ?: $order->grand_total;
                                        @endphp
                                        <tr>
                                            <th scope="row">
                                                <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                Payment from #{{ $order->id }}
                                            </th>
                                            <td class="text-end">{{ optional($order->created_at)->format('M d, Y, g:ia') }}</td>
                                            <td class="text-end">$ {{ number_format($amount, 2) }}</td>
                                            <td class="text-end">
                                                @if($order->payment_status == 1)
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($order->payment_status == 2)
                                                    <span class="badge badge-warning">Partial</span>
                                                @else
                                                    <span class="badge badge-secondary">Pending</span>
                                                @endif
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
    <!-- Chart JS -->
    <script src="{{asset('backend')}}/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="{{asset('backend')}}/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="{{asset('backend')}}/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{asset('backend')}}/assets/js/setting-demo.js"></script>
    <script src="{{asset('backend')}}/assets/js/demo.js"></script>
    <script>
        $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#177dff",
            fillColor: "rgba(23, 125, 255, 0.14)",
        });

        $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#f3545d",
            fillColor: "rgba(243, 84, 93, .14)",
        });

        $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: "#ffa534",
            fillColor: "rgba(255, 165, 52, .14)",
        });
    </script>
    <script>
        // Chart.js statistics chart (orders POS vs Online)
        (function(){
            const ctx = document.getElementById('statisticsChart');
            if(!ctx) return;
            const labels = {!! $orderGraphData['dates'] ?? '[]' !!};
            const pos = {!! $orderGraphData['posOrders'] ?? '[]' !!};
            const online = {!! $orderGraphData['onlineOrders'] ?? '[]' !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'POS Orders', data: pos, borderColor: '#36A2EB', backgroundColor: 'rgba(54,162,235,0.1)', fill: true },
                        { label: 'Online Orders', data: online, borderColor: '#FF6384', backgroundColor: 'rgba(255,99,132,0.1)', fill: true }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        })();

        // Daily sales mini chart (use orderGraph dates as x)
        (function(){
            const ctx2 = document.getElementById('dailySalesChart');
            if(!ctx2) return;
            const labels = {!! $orderGraphData['dates'] ?? '[]' !!};
            const sales = {!! $orderGraphData['posOrders'] ?? '[]' !!};
            new Chart(ctx2, {
                type: 'bar',
                data: { labels: labels, datasets: [{ label: 'Orders', data: sales, backgroundColor: '#4BC0C0' }] },
                options: { responsive: true, maintainAspectRatio: false }
            });
        })();
    </script>
@endpush
