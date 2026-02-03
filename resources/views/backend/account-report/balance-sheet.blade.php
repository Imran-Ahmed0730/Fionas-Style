@extends('backend.master')
@section('title', 'Balance Sheet')
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Balance Sheet</h3>
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
                        <a href="#">Balance Sheet</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h3 class="card-title mb-0">Balance Sheet Report</h3>
                            </div>
                            <form action="{{ route('admin.account-report.balance-sheet') }}" method="GET" class="row g-3 mt-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ $startDate ?? '' }}" onchange="this.form.submit()">
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
                                    <th>Account Head</th>
                                    <th>Particular</th>
                                    <th>Income (Credit)</th>
                                    <th>Expense (Debit)</th>
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalIncome = 0; $totalExpense = 0; @endphp
                                @foreach($ledgers as $ledger)
                                    <tr class="align-middle">
                                        <td>{{ $ledger->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $ledger->accountHead->title }}</td>
                                        <td>{{ $ledger->particular }}</td>
                                        <td class="text-success">{{ number_format($ledger->credit, 2) }}</td>
                                        <td class="text-danger">{{ number_format($ledger->debit, 2) }}</td>
                                        <td class="fw-bold">{{ number_format($ledger->balance, 2) }}</td>
                                    </tr>
                                    @php 
                                        $totalIncome += $ledger->credit;
                                        $totalExpense += $ledger->debit;
                                    @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold bg-light">
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td class="text-success">{{ number_format($totalIncome, 2) }}</td>
                                        <td class="text-danger">{{ number_format($totalExpense, 2) }}</td>
                                        <td>Net: {{ number_format($totalIncome - $totalExpense, 2) }}</td>
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
                "ordering": false, // Disable auto-sorting for ledger
                "pageLength": 25
            });
        });
    </script>
@endpush
