@extends('backend.master')
@section('title', 'User Messages')

@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .select-all-container { margin-bottom: 15px; }
        .bulk-delete-btn { display: none; }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">User Messages</h3>
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
                        <a href="#">Messages</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">View</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">User Messages</h3>
                            @can('Message Delete')
                                <button type="button" class="btn btn-danger ms-auto bulk-delete-btn" id="bulkDelete">
                                    <i class="fas fa-trash me-2"></i> Bulk Delete
                                </button>
                            @endcan
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        @can('Message Delete')
                                            <th style="width: 40px">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                            </th>
                                        @endcan
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Subject</th>
                                        <th>Message</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $key => $item)
                                        <tr class="align-middle" id="row_{{ $item->id }}">
                                            @can('Message Delete')
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="form-check-input message-checkbox">
                                                </td>
                                            @endcan
                                            <td>{{ $key + 1 }}.</td>
                                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <strong>Email:</strong> {{ $item->email }}<br>
                                                <strong>Phone:</strong> {{ $item->phone }}
                                            </td>
                                            <td>{{ $item->subject }}</td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#msgModal{{ $item->id }}">
                                                    View Message
                                                </button>

                                                <!-- Message Modal -->
                                                <div class="modal fade" id="msgModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Message from {{ $item->name }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Subject:</strong> {{ $item->subject }}</p>
                                                                <hr>
                                                                <p>{{ $item->message }}</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @can('Message Delete')
                                                <form action="{{ route('admin.user-message.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
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
@endsection

@push('js')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            const table = $('#datatable').DataTable({
                "order": [[ @can('Message Delete') 2 @else 1 @endcan, "desc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [0, @can('Message Delete') 7 @else 6 @endcan] }
                ]
            });

            // Select All functionality
            $('#selectAll').on('click', function() {
                $('.message-checkbox').prop('checked', this.checked);
                toggleBulkDeleteBtn();
            });

            $('.message-checkbox').on('change', function() {
                if ($('.message-checkbox:checked').length === $('.message-checkbox').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
                toggleBulkDeleteBtn();
            });

            function toggleBulkDeleteBtn() {
                if ($('.message-checkbox:checked').length > 0) {
                    $('.bulk-delete-btn').fadeIn();
                } else {
                    $('.bulk-delete-btn').fadeOut();
                }
            }

            // Bulk Delete AJAX
            $('#bulkDelete').on('click', function() {
                const ids = [];
                $('.message-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if (ids.length > 0) {
                    if (confirm('Are you sure you want to delete ' + ids.length + ' selected messages?')) {
                        $.ajax({
                            url: "{{ route('admin.user-message.bulk-delete') }}",
                            type: 'DELETE',
                            data: {
                                ids: ids,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert(response.message);
                                }
                            },
                            error: function() {
                                alert('Something went wrong. Please try again.');
                            }
                        });
                    }
                }
            });
        })
    </script>
@endpush