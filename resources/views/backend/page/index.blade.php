@extends('backend.master')
@section('title')
    View Pages
@endsection
@push('css')
    <!-- DataTables CSS and Bootstrap 5 Integration -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">View Pages</h3>
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
                        <a href="#">Pages</a>
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
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">View Pages</h3>
                            @can('Page Create')
                                <a href="{{route('admin.page.create')}}" data-bs-toggle="tooltip" title="Add Page" class="btn btn-primary ms-auto">
                                    <i class="fa fa-plus me-2"></i> Add Page
                                </a>
                            @endcan
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="datatable">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Page Name</th>
                                        <th>Slug</th>
                                        <th>Content</th>
                                        <th style="width: 40px">Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $key => $item)
                                        <tr class="align-middle">
                                            <td>{{$key+1}}.</td>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->slug}}</td>
                                            <td>
                                                {{Str::limit(strip_tags($item->content), 100)}}
                                                <!-- Button trigger modal -->
                                                @if(Str::length(strip_tags($item->content)) > 100)
                                                    <small class="text-decoration-underline text-primary cursor-pointer ms-2 content-btn" data-content="{{$item->content}}"  data-bs-toggle="modal" data-bs-target="#contentModal">
                                                        View All
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                @can('Page Status Change')
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-switch" type="checkbox" role="switch"
                                                               data-module="page" data-id="{{ $item->id }}"
                                                            {{ $item->status == 1 ? 'checked' : '' }}>                                                <label class="form-check-label" for="status"></label>
                                                    </div>
                                                @else
                                                    <span class="p-2 badge text-bg-{{$item->status == 1 ? 'success': 'danger'}}">{{$item->status == 1 ? 'Showed':'Hidden'}}</span>
                                                @endcan 
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('Page Update')
                                                    <a href="{{route('admin.page.edit', $item->id)}}" data-bs-toggle="tooltip" title="Edit" class="btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                    @endcan
                                                    @can('Page Delete')
                                                    <form action="{{route('admin.page.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$item->id}}">
                                                        <button class="btn btn-danger btn-delete" data-bs-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Template Body</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $item->content !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            $(document).on('click', '.content-btn', function () {
                var content = $(this).data('content');
                $('#contentModal .modal-body').html(content);
                $('#contentModal').modal('show');
            });
        });

    </script>
@endpush
