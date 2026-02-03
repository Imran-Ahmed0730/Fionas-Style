@extends('backend.master')

@section('title')
    {{ isset($item) ? 'Edit' : 'Add' }} Payment Method
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Payment Method</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{route('admin.dashboard')}}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item"><a href="{{ route('admin.payment-method.index') }}">Payment Methods</a></li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item"><a href="#">{{ isset($item) ? 'Edit' : 'Add' }}</a></li>
                </ul>
            </div>

            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">{{ isset($item) ? 'Edit' : 'Add' }} Payment Method</h4>
                        </div>
                        <div class="card-body">
                            <form
                                action="{{ isset($item) ? route('admin.payment-method.update', $item->id) : route('admin.payment-method.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @if(isset($item)) @method('PUT') @endif

                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $item->name ?? '') }}" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Icon</label>
                                    <input type="file" name="icon" class="form-control">
                                    @if(isset($item) && $item->icon)
                                        <img src="{{ asset('storage/' . $item->icon) }}" width="50" class="mt-2" alt="">
                                    @endif
                                </div>

                                <div class="mb-3 d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="sandbox" value="1"
                                            id="sandbox" {{ old('sandbox', $item->sandbox ?? 0) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sandbox">Sandbox Mode</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status" value="1" id="status"
                                            {{ old('status', $item->status ?? 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('admin.payment-method.index') }}" class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection