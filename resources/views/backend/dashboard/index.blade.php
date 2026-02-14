@extends('backend.master')
@section('title')
    Dashboard
@endsection
@push('css')
    <link rel="stylesheet" href="{{asset('frontend')}}/assets/css/owl.carousel.min.css">
    <style>
        .hover-shadow-lg:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            transform: translateY(-2px);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .todays-deal-carousel .owl-stage {
            display: flex !important;
        }

        .todays-deal-carousel .owl-item {
            display: flex !important;
            height: auto !important;
        }

        .todays-deal-card {
            width: 100%;
            height: 100%;
            margin-bottom: 5px;
            /* Adjust for shadow on hover */
        }
    </style>
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                <div>
                    <h3 class="fw-bold mb-3">Dashboard</h3>
                </div>

            </div>
            <div class="row mb-5">
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-primary bubble-shadow-small">
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
                    <div class="card card-stats card-round h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-info bubble-shadow-small">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Latest Balance</p>
                                        <h4 class="card-title">৳ {{ Number::abbreviate($currentBalance ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-success bubble-shadow-small">
                                        <i class="fas fa-luggage-cart"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ms-3 ms-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Today Sales</p>
                                        <h4 class="card-title">৳ {{ Number::abbreviate($todaySales ?? 0) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="card card-stats card-round h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="icon-big text-center icon-secondary bubble-shadow-small">
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
                                <div class="card-title">Order Statistics</div>
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
                            </div>
                            <div class="card-category">{{ \Carbon\Carbon::now()->startOfMonth()->format('M d') }} -
                                {{ \Carbon\Carbon::now()->format('M d') }}</div>
                        </div>
                        <div class="card-body pb-0">
                            <div class="mb-4 mt-2">
                                <h1>৳ {{ number_format($monthSales ?? 0, 2) }}</h1>
                            </div>
                            <div class="pull-in">
                                <canvas id="dailySalesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header border-0 pb-0">
                            <div class="card-title">Order Payments</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="min-height: 250px">
                                <canvas id="paymentPieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-header border-0 pb-0">
                            <div class="card-title">Top 5 Selling Products</div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="min-height: 250px">
                                <canvas id="topProductsChart"></canvas>
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
                            </div>
                            <div class="card-list py-4">
                                @forelse($newCustomers ?? collect() as $cust)
                                    <div class="item-list">
                                        <div class="avatar">
                                            @if($cust->image)
                                                <img src="{{ asset($cust->image) }}" alt="{{ $cust->name }}" class="avatar-img rounded-circle" />
                                            @else
                                                <span class="avatar-title rounded-circle border border-white">{{ strtoupper(substr($cust->name, 0, 1)) }}</span>
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
                                @empty
                                    <p class="text-center text-muted">No new customers</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="card-head-row card-tools-still-right">
                                <div class="card-title">Today's Transactions History</div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table align-items-center mb-0">
                                    <thead class="thead-light sticky-top bg-white">
                                        <tr>
                                            <th scope="col">Payment Number</th>
                                            <th scope="col" class="text-end">Time</th>
                                            <th scope="col" class="text-end">Amount</th>
                                            <th scope="col" class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($todayTransactions ?? collect() as $order)
                                            @php
    $amount = $order->orderPayments->sum('amount') ?: $order->grand_total;
                                            @endphp
                                            <tr>
                                                <th scope="row">
                                                    <div class="d-flex align-items-center">
                                                        <span class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                            <i class="fa fa-check"></i>
                                                        </span>
                                                        <span>Payment from #{{ $order->id }}</span>
                                                    </div>
                                                </th>
                                                <td class="text-end">{{ optional($order->created_at)->format('g:ia') }}</td>
                                                <td class="text-end">৳ {{ number_format($amount, 2) }}</td>
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
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4">No transactions today</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-round shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center bg-light">
                            <h4 class="card-title mb-0 fw-bold"><i class="fas fa-fire text-danger me-2"></i>Today's Deals</h4>
                            <span class="badge badge-danger">Limited Time Offers</span>
                        </div>
                        <div class="card-body">
                            <div class="todays-deal-carousel owl-carousel owl-theme">
                                @forelse($todaysDealProducts ?? [] as $product)
                                    <div class="todays-deal-card p-3 border rounded-3 transition-all hover-shadow-lg text-center bg-white d-flex flex-column">
                                        <div class="position-relative mb-3">
                                            <img src="{{ asset($product->thumbnail) }}" class="img-fluid rounded"
                                                style="height: 120px; object-fit: contain; width: 100%;"
                                                alt="{{ $product->name }}">
                                            @if($product->discount > 0)
                                                <div class="position-absolute top-0 start-0 badge badge-success shadow-sm">
                                                    -{{ $product->discount_type == 2 ? $product->discount . '%' : '৳' . number_format($product->discount, 2) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2 text-truncate px-1" title="{{ $product->name }}">{{ $product->name }}</h6>
                                            <div class="text-primary fw-bold mb-1">৳{{ number_format($product->final_price, 2) }}</div>
                                            @if($product->discount > 0)
                                                <div class="text-muted text-decoration-line-through small">৳{{ number_format($product->regular_price, 2) }}</div>
                                            @endif
                                        </div>
                                        <div class="mt-auto pt-2">
                                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 w-100">View</a>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5">
                                        <i class="fas fa-shopping-basket fa-3x text-muted mb-3 opacity-25"></i>
                                        <p class="text-muted">Stay tuned! Exciting deals are coming soon.</p>
                                    </div>
                                @endforelse
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
    <!-- Owl Carousel -->
    <script src="{{asset('backend')}}/assets/js/plugin/owl-carousel/owl.carousel.min.js"></script>
    <script>
        // Hyper-safe Chart.js font configuration
        (function() {
            try {
                if (typeof Chart !== 'undefined' && Chart.defaults) {
                    var d = Chart.defaults;
                    // Chart.js 3+
                    if (d.font && typeof d.font === 'object') {
                        d.font.family = "'Public Sans', sans-serif";
                    } 
                    // Chart.js 2.x
                    else if (d.global) {
                        d.global.defaultFontFamily = "'Public Sans', sans-serif";
                    }
                }
            } catch (err) {
                console.warn("Chart font setting skipped:", err);
            }
        })();

        $(document).ready(function() {
            // Initialize Owl Carousel
            $(".todays-deal-carousel").owlCarousel({
                loop: true,
                margin: 20,
                nav: true,
                dots: false,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                responsive: {
                    0: { items: 2 },
                    600: { items: 3 },
                    1000: { items: 5 },
                    1200: { items: 6 }
                }
            });

            // Data from PHP safely
            const graphData = {
                dates: {!! $orderGraphData['dates'] ?? '[]' !!},
                pos: {!! $orderGraphData['posOrders'] ?? '[]' !!},
                online: {!! $orderGraphData['onlineOrders'] ?? '[]' !!}
            };
            const payData = {
                labels: {!! $paymentChartData['labels'] ?? '[]' !!},
                amounts: {!! $paymentChartData['amounts'] ?? '[]' !!}
            };
            const topData = {
                labels: {!! $topSellingChartData['labels'] ?? '[]' !!},
                counts: {!! $topSellingChartData['counts'] ?? '[]' !!}
            };

            // Order Statistics
            const statsCtx = document.getElementById('statisticsChart');
            if (statsCtx) {
                new Chart(statsCtx, {
                    type: 'line',
                    data: {
                        labels: graphData.dates,
                        datasets: [
                            { label: 'POS Orders', data: graphData.pos, borderColor: '#177dff', backgroundColor: 'rgba(23, 125, 255, 0.1)', fill: true, tension: 0.4 },
                            { label: 'Online Orders', data: graphData.online, borderColor: '#f3545d', backgroundColor: 'rgba(243, 84, 93, 0.1)', fill: true, tension: 0.4 }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } },
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });
            }

            // Daily Sales
            const salesCtx = document.getElementById('dailySalesChart');
            if (salesCtx) {
                new Chart(salesCtx, {
                    type: 'bar',
                    data: { labels: graphData.dates, datasets: [{ label: 'Orders', data: graphData.pos, backgroundColor: 'rgba(255, 255, 255, 0.5)', borderRadius: 4 }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { display: false }, y: { display: false } }
                    }
                });
            }

            // Payment Pie
            const payCtx = document.getElementById('paymentPieChart');
            if (payCtx) {
                new Chart(payCtx, {
                    type: 'pie',
                    data: {
                        labels: payData.labels,
                        datasets: [{
                            data: payData.amounts,
                            backgroundColor: ['#1d7af3', '#f3545d', '#fdaf4b', '#59d05d', '#1572e8', '#af49ff'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15 } } }
                    }
                });
            }

            // Top Products
            const topCtx = document.getElementById('topProductsChart');
            if (topCtx) {
                new Chart(topCtx, {
                    type: 'bar',
                    data: {
                        labels: topData.labels.map(l => l.length > 15 ? l.substring(0, 15) + '...' : l),
                        datasets: [{ label: 'Qty Sold', data: topData.counts, backgroundColor: '#1572e8', borderRadius: 5 }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true } }
                    }
                });
            }
        });
    </script>
@endpush