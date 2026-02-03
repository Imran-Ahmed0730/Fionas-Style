@extends('backend.master')

@section('title')
    Payment Methods
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Payment Methods</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{route('admin.dashboard')}}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item"><a href="#">Settings</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">Payment Methods</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4 class="card-title">List</h4>
                            <div class="ms-auto">
                                <a href="{{ route('admin.payment-method.create') }}" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Add New
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Icon</th>
                                            <th>Name</th>
                                            <th>Sandbox</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    @if($item->icon)
                                                        <img src="{{ asset('storage/' . $item->icon) }}" width="40" alt="">
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $item->sandbox ? 'warning' : 'info' }}">
                                                        {{ $item->sandbox ? 'Yes' : 'No' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status" type="checkbox"
                                                            data-id="{{ $item->id }}" {{ $item->status ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.payment-method.edit', $item->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.payment-method.destroy', $item->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger btn-delete">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
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
    <script>
        $(document).on('change', '.toggle-status', function () {
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/payment-method/status/change') }}/" + id,
                type: "GET",
                success: function (res) {
                    if (res.success) {
                        Toast.fire({ icon: 'success', title: res.message });
                    }
                }
            });
        });
    </script>
@endpush