@extends('backend.master')
@section('title')
    @isset($role) Edit @else Add @endisset Role Permission
@endsection
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Assign Permission</h3>
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
                        <a href="#">Role</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Assign Permission</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <div class="card-title">Role Permission for {{ Str::title($role->name) }}</div>
                            <a href="{{ route('admin.role.index') }}" class="btn btn-primary ms-auto">
                                <i class="fa fa-list me-2"></i>View Roles
                            </a>
                        </div>

                        <form action="{{ route('admin.role.permission.assign.submit') }}" method="post" >
                            @csrf
                            <input type="hidden" name="id" value="{{ $role->id }}">

                            <div class="card-body">
                                @foreach($permissionsGrouped as $model => $permissions)
                                    <fieldset class="mb-3">
                                        <legend class="col-form-label text-capitalize">
                                            <!-- Model Name with Select All Checkbox -->
                                            <div class="form-check">
                                                <label class="form-check-label fw-bold text-decoration-underline" for="select_all_{{ $model }}">
                                                    {{ $model }}
                                                </label>
                                                <input class="form-check-input select-all-model" type="checkbox" id="select_all_{{ $model }}">
                                            </div>
                                        </legend>

                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permission[]" id="{{ $permission->id }}" value="{{ $permission->name }}"
                                                               @isset($items){{ in_array($permission->id, $items) ? 'checked':'' }}@endisset data-model="{{ $model }}">
                                                        <label class="form-check-label" for="{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                    <hr class="mb-3">
                                @endforeach
                            </div>

                            <div class="card-action d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    @isset($role) Update @else Submit @endisset
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Initialize the "Select All" checkbox based on permissions on load
            $('fieldset').each(function () {
                var model = $(this).find('.select-all-model').attr('id').replace('select_all_', '');
                var permissions = $('.permission-checkbox[data-model="' + model + '"]');
                var allChecked = permissions.length === permissions.filter(':checked').length;
                $('#select_all_' + model).prop('checked', allChecked);
            });

            // When "select all" checkbox is checked/unchecked
            $('.select-all-model').change(function () {
                var model = $(this).attr('id').replace('select_all_', ''); // Get the model name from checkbox ID
                $('.permission-checkbox[data-model="' + model + '"]').prop('checked', this.checked);
            });

            // Check or uncheck the "select all" checkbox based on individual permission checkboxes
            $('.permission-checkbox').change(function () {
                var model = $(this).data('model');
                var allChecked = $('.permission-checkbox[data-model="' + model + '"]:checked').length === $('.permission-checkbox[data-model="' + model + '"]').length;
                $('#select_all_' + model).prop('checked', allChecked);
            });
        });
    </script>
@endpush
