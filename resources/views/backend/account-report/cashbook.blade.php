@extends('backend.master')
@section('title', 'Cashbook')
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Daily Cashbook</h3>
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
                        <a href="#">Cashbook</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h3 class="card-title mb-0">Cashbook for {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</h3>
                            </div>
                            <form action="{{ route('admin.account-report.cashbook') }}" method="GET" class="row g-3 mt-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Select Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $date ?? date('Y-m-d') }}" onchange="this.form.submit()">
                                </div>
                            </form>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Account Head</th>
                                    <th>Particular</th>
                                    <th>Income</th>
                                    <th>Expense</th>
                                    <th>Time</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalIncome = 0; $totalExpense = 0; @endphp
                                @foreach($ledgers as $key => $ledger)
                                    <tr class="align-middle">
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $ledger->accountHead->title }}</td>
                                        <td>{{ $ledger->particular }}</td>
                                        <td class="text-success">{{ number_format($ledger->credit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($ledger->debit, 2) }}</td>
                                        <td>{{ $ledger->created_at->format('h:i A') }}</td>
                                    </tr>
                                    @php 
                                        $totalIncome += $ledger->credit;
                                        $totalExpense += $ledger->debit;
                                    @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">Today's Total:</td>
                                        <td class="text-success">{{ number_format($totalIncome, 2) }}</td>
                                        <td class="text-danger">{{ number_format($totalExpense, 2) }}</td>
                                        <td>Diff: {{ number_format($totalIncome - $totalExpense, 2) }}</td>
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
