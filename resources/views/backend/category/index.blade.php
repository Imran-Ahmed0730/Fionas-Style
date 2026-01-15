@extends('backend.master')
@section('title')
    View Categories
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Categories</h3>
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
                        <a href="#">Categories</a>
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
                            <h3 class="card-title mb-0">View Categories</h3>
                            @can('Category Add')
                                <a href="{{route('admin.category.create')}}" data-bs-toggle="tooltip" title="Add Category" class="btn btn-primary ms-auto">
                                    <i class="fa fa-plus me-2"></i> Add Category
                                </a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th style="width: 50px">Icon</th>
                                    <th>Name</th>
                                    <th>Priority</th>
                                    <th style="width: 150px">Home Include Status</th>
                                    <th style="width: 50px">Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $counter = 0; @endphp
                                @foreach($items as $key => $item)
                                    <tr class="align-middle">
                                        <td>{{$key+1}}.</td>
                                        <td>
                                            @if($item->icon != null)
                                                <img src="{{asset($item->icon)}}" class="rounded-circle" width="50px" height="50px" alt="">

                                            @else
                                                <img src="{{asset('backend')}}/assets/img/default-150x150.png" width="50px" height="50px" class="rounded-circle" alt="">
                                            @endif
                                        </td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->priority}}</td>
                                        <td>
                                            @can('Category Include to Home')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input home-include-toggle-switch" type="checkbox" role="switch"
                                                           data-module="category" data-id="{{ $item->id }}"
                                                        {{ $item->included_to_home == 1 ? 'checked' : '' }}>                                                <label class="form-check-label" for="status"></label>
                                                </div>
                                            @else
                                                <span class="p-2 badge text-bg-{{$item->included_to_home == 1 ? 'success': 'danger'}}">{{$item->included_to_home == 1 ? 'Yes':'No'}}</span>
                                            @endcan
                                        </td>
                                        <td class="">
                                            @can('Category Status Change')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                           data-module="category" data-id="{{ $item->id }}"
                                                        {{ $item->status == 1 ? 'checked' : '' }}>                                                <label class="form-check-label" for="status"></label>
                                                </div>
                                            @else
                                                <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success': 'danger'}}">{{$item->status == 1 ? 'Active':'Inactive'}}</span>
                                            @endcan
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Category Update')
                                                    <a href="{{route('admin.category.edit', $item->id)}}" title="Edit" class="btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('Category Delete')
                                                    <form action="{{route('admin.category.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                        <button class="btn btn-danger btn-delete" title="Delete"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
            $('#datatable').DataTable();
        })
    </script>
    <script>
        $(document).ready(function () {
            // Bind the click event to the toggle switches
            $('.home-include-toggle-switch').on('change', function (e) {
                e.preventDefault();

                let switchElement = $(this); // The clicked switch
                let id = switchElement.data('id'); // Get the item ID from data-id
                let newStatus = switchElement.prop('checked') ? 1 : 0; // Determine new status
                let module = switchElement.data('module'); // Get the module from data-module

                // SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to ${newStatus ? 'include' : 'remove'} this ${module}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, change it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Build the route dynamically with the ID
                        let route = `{{ route('admin.category.include.home', ['id' => ':id']) }}`;
                        route = route.replace(':id', id);

                        // Send AJAX request to update the status
                        $.ajax({
                            url: route, // Use the dynamically constructed route
                            type: 'GET',
                            success: function (response) {
                                if (response.success) {
                                    toastr.success(`The ${module} inclusion to home has been successfully updated.`);
                                } else {
                                    toastr.error(`Failed to update the ${module} status.`);
                                    // Optionally, revert the switch back to the original state
                                    switchElement.prop('checked', !newStatus);
                                }
                            },
                            error: function (error) {
                                toastr.error('An error occurred while updating the status.');
                                switchElement.prop('checked', !newStatus); // Revert the switch
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        switchElement.prop('checked', !newStatus); // Revert the switch on cancel
                    }
                });
            });
        });

    </script>

@endpush
