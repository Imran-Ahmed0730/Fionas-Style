@extends('backend.master')
@section('title', 'Account Heads')
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Account Heads</h3>
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
                        <a href="#">Account Heads</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">Manage Account Heads</h3>
                            @can('Account Head Add')
                                <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="fa fa-plus me-2"></i> Add New
                                </button>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="datatable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th style="width: 50px">Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($heads as $key => $head)
                                            <tr class="align-middle">
                                                <td>{{ $key + 1 }}.</td>
                                                <td>{{ $head->title }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $head->type == 1 ? 'success' : 'danger' }}">
                                                        {{ $head->type == 1 ? 'Income' : 'Expense' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($head->editable == 1)
                                                        @can('Account Head Status Change')
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input toggle-switch" type="checkbox"
                                                                    role="switch" data-module="account-head" data-id="{{ $head->id }}"
                                                                    {{ $head->status == 1 ? 'checked' : '' }}>
                                                            </div>
                                                        @else
                                                            <span class="badge badge-{{ $head->status == 1 ? 'success' : 'warning' }}">
                                                                {{ $head->status == 1 ? 'Active' : 'Inactive' }}
                                                            </span>
                                                        @endcan
                                                    @else
                                                        <span class="badge badge-{{ $head->status == 1 ? 'success' : 'warning' }}">
                                                            {{ $head->status == 1 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @if($head->editable == 1)
                                                            @can('Account Head Update')
                                                                <button class="btn btn-primary me-2 edit-btn"
                                                                    data-id="{{ $head->id }}" data-title="{{ $head->title }}"
                                                                    data-type="{{ $head->type }}" data-bs-toggle="modal"
                                                                    data-bs-target="#editModal" title="Edit">
                                                                    <i class="fa fa-pencil"></i>
                                                                </button>
                                                            @endcan

                                                            @can('Account Head Delete')
                                                                <form action="{{ route('admin.account-head.delete') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $head->id }}">
                                                                    <button type="submit" class="btn btn-danger btn-delete"
                                                                        title="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            @endcan
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->
                </div> <!-- /.col -->
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.account-head.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Add Account Head</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="1">Income</option>
                                <option value="2">Expense</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.account-head.update') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Account Head</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" id="edit_type" class="form-select" required>
                                <option value="1">Income</option>
                                <option value="2">Expense</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#datatable').DataTable();

            $('.edit-btn').on('click', function () {
                let id = $(this).data('id');
                let title = $(this).data('title');
                let type = $(this).data('type');

                $('#edit_id').val(id);
                $('#edit_title').val(title);
                $('#edit_type').val(type);
            });
        });
    </script>
@endpush