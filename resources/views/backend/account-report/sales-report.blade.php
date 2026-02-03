@extends('backend.master')
@section('title', 'Sales Report')
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Sales Report</h3>
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
                        <a href="#">Accounts</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Sales Report</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h3 class="card-title mb-0">Product Sales Report</h3>
                            </div>
                            <form action="{{ route('admin.account-report.sales-report') }}" method="GET"
                                class="row g-3 mt-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ $startDate ?? '' }}" onchange="this.form.submit()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ $endDate ?? '' }}" onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Particular</th>
                                        <th>Order ID</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalSales = 0; @endphp
                                    @foreach($ledgers as $ledger)
                                        <tr class="align-middle">
                                            <td>{{ $ledger->created_at->format('d-m-Y') }}</td>
                                            <td>{{ $ledger->particular }}</td>
                                            <td>#{{ $ledger->order_id }}</td>
                                            <td class="text-success fw-bold">{{ number_format($ledger->credit, 2) }}</td>
                                        </tr>
                                        @php $totalSales += $ledger->credit; @endphp
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">Total Sales:</td>
                                        <td class="text-success">{{ number_format($totalSales, 2) }}</td>
                                    </tr>
                                </tfoot>
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
            $('#datatable').DataTable({
                "pageLength": 25
            });
        });
    </script>
@endpush