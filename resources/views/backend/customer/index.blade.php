@extends('backend.master')
@section('title')
    View Customers
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Customers</h3>
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
                        <a href="#">Customers</a>
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
                            <h3 class="card-title mb-0">View Customers</h3>
                            <div class="ms-auto">
                                @can('Customer Add')
                                    <button data-bs-toggle="modal" data-bs-target="#customerModal" title="Add Customer" class="btn btn-primary" id="addCustomerBtn">
                                        <i class="fa fa-plus me-2"></i> Add Customer
                                    </button>
                                @endcan
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="datatable">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $key => $item)
                                        <tr class="align-middle">
                                            <td>{{$key + 1}}.</td>
                                            <td>
                                                @if($item->image && file_exists($item->image))
                                                    <img src="{{asset($item->image)}}" width="50px" height="50px" class="rounded-circle" alt="">
                                                @else
                                                    <img src="{{asset('backend')}}/assets/img/profile.jpg" id="previewImage" width="50" class="my-2 rounded-circle " alt="">
                                                @endif
                                            </td>
                                            <td>{{Str::title($item->user->name ?? 'N/A')}}</td>
                                            <td>{{$item->user->email ?? 'N/A'}}</td>
                                            <td>{{$item->phone}}</td>
                                            <td>
                                                @can('Customer Status Change')
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                               data-module="customer" data-id="{{ $item->id }}"
                                                            {{ $item->status == 1 ? 'checked' : '' }}>                                                <label class="form-check-label" for="status"></label>
                                                    </div>
                                                @else
                                                    <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success' : 'danger'}}">{{$item->status == 1 ? 'Active' : 'Inactive'}}</span>
                                                @endcan
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('Customer Update')
                                                        <button class="btn btn-primary me-2 edit-customer-btn" data-id="{{$item->id}}" data-bs-toggle="modal" data-bs-target="#customerModal" title="Edit">
                                                            <i class="fa fa-pencil"></i>
                                                        </button>
                                                    @endcan
                                                    @can('Customer Delete')
                                                        <form action="{{route('admin.customer.delete')}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{$item->id}}">
                                                            <button class="btn btn-danger btn-delete" data-bs-toggle="tooltip" title="Remove"><i class="fa fa-trash"></i></button>
                                                        </form>
                                                    @endcan
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

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1" aria-labelledby="customerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="customerForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="customer_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customerModalLabel">Add Customer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter phone" required>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Phone number will be used as password</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control" placeholder="Enter address" rows="2"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" id="city" class="form-control" placeholder="Enter city">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state" id="state" class="form-control" placeholder="Enter state">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveCustomerBtn">Save Customer</button>
                    </div>
                </form>
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
            var table = $('#datatable').DataTable();

            // Add Customer Button
            $('#addCustomerBtn').click(function() {
                $('#customerForm')[0].reset();
                $('#customer_id').val('');
                $('#customerModalLabel').text('Add Customer');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
            });

            // Edit Customer Button
            $('.edit-customer-btn').click(function() {
                var customerId = $(this).data('id');
                $('#customerModalLabel').text('Edit Customer');
                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                let url = '{{ route('admin.customer.edit', ':customerId') }}'.replace(':customerId', customerId);

                // Fetch customer data
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            var customer = response.data;
                            $('#customer_id').val(customer.id);
                            $('#name').val(customer.user.name);
                            $('#email').val(customer.user.email);
                            $('#phone').val(customer.phone);
                            $('#address').val(customer.address);
                            $('#city').val(customer.city);
                            $('#state').val(customer.state);
                            $('#status').val(customer.status);
                        }
                    },
                    error: function(xhr) {
                        alert('Error loading customer data');
                    }
                });
            });

            // Submit Customer Form
            $('#customerForm').submit(function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var customerId = $('#customer_id').val();
                var url = customerId ? '{{ route("admin.customer.update") }}' : '{{ route("admin.customer.store") }}';

                $('.invalid-feedback').text('').hide();
                $('.form-control').removeClass('is-invalid');
                $('#saveCustomerBtn').prop('disabled', true).text('Saving...');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#customerModal').modal('hide');
                            location.reload(); // Reload to show updated data
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key).next('.invalid-feedback').text(value[0]).show();
                            });
                        } else {
                            alert('Error: ' + (xhr.responseJSON?.message || 'Something went wrong'));
                        }
                    },
                    complete: function() {
                        $('#saveCustomerBtn').prop('disabled', false).text('Save Customer');
                    }
                });
            });
        });
    </script>
@endpush
