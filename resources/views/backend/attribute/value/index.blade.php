@extends('backend.master')
@section('title')
    @isset($item) Edit @else Add @endisset Attribute Value
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3"><span class="process-type">Add</span> Attribute Value</h3>
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
                        <a href="#">Attributes</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Value</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#"><span class="process-type">Add</span></a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card"> <!--begin::Header-->
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title"><span class="process-type">Add</span> Attribute Value for {{$item->name}}</div>
                            <a href="{{route('admin.attribute.index')}}" title="View Attributes" class="btn btn-primary ms-auto"><i class="fa fa-list me-2"></i>View Attributes</a>
                        </div> <!--end::Header--> <!--begin::Form-->
                        <form id="attribute_form" action="{{route('admin.attribute.value.store')}}" method="post">
                            @csrf<!--begin::Body-->
                            <input type="hidden" name="attribute_id" value="{{$item->id}}">
                            <input type="hidden" id="attribute_value_id" name="attribute_value_id" value="">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value</label>
                                    <input id="value" type="text" name="value" value="{{old('value')}}" placeholder="Enter value" class="form-control" required>
                                </div>
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end">
                                <button type="reset" id="reset_btn" class="btn btn-secondary me-2">Reset</button>
                                <button type="submit" id="submit_btn" class="btn btn-primary">Submit</button>
                            </div> <!--end::Footer-->
                        </form>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">View Values</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Value</th>
                                    <th ></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($item->attributeValues as $key => $value)
                                    <tr class="align-middle">
                                        <td>{{$key+1}}.</td>
                                        <td class="attribute_value">{{$value->value}}</td>
                                        <td>
                                            <div class="d-flex">
                                                @can('Attribute Value Update')
                                                    <a href="#" data-id="{{$value->id}}" title="Edit" class="edit_attribute_value btn btn-primary me-2"><i class="fa fa-pencil"></i></a>
                                                @endcan
                                                @can('Attribute Value Delete')
                                                    <form action="{{route('admin.attribute.value.delete')}}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{$value->id}}">
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
    <script>
        $(document).ready(function () {
            $('.edit_attribute_value').click(function (event) {
                event.preventDefault();
                let attribute_value = $(this).closest('tr').find('.attribute_value').text();
                $('#value').val(attribute_value);
                $('#attribute_value_id').val($(this).data('id'));
                $('#attribute_form').attr('action', '{{route('admin.attribute.value.update')}}');
                $('#submit_btn').text('Update');
                $('.process-type').text('Edit');
            });

            $('#reset_btn').click(function () {
                $('#attribute_value_id').val('');
                $('#attribute_form').attr('action', '{{route('admin.attribute.value.store')}}');
                $('#submit_btn').text('Submit');
                $('.process-type').text('Add');
            });
        })
    </script>
@endpush
