@extends('backend.master')

@section('title')
    @isset($item) Edit @else Add @endisset FAQ
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset FAQ</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item">
                        <a href="{{ route('admin.faq.index') }}">FAQs</a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="card-title">@isset($item) Edit @else Add @endisset FAQ Information</div>
                    <a href="{{ route('admin.faq.index') }}" class="btn btn-primary ms-auto">
                        <i class="fa fa-list me-2"></i>View FAQs
                    </a>
                </div>

                <form action="@isset($item){{ route('admin.faq.update') }}@else{{ route('admin.faq.store') }}@endisset"
                    method="POST">
                    @csrf
                    @isset($item) <input type="hidden" name="id" value="{{ $item->id }}"> @endisset

                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label required">Question</label>
                                    <input type="text" name="question"
                                        class="form-control @error('question') is-invalid @enderror"
                                        value="{{ old('question', $item->question ?? '') }}" required
                                        placeholder="Enter question">
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Answer</label>
                                    <textarea name="answer" class="form-control @error('answer') is-invalid @enderror"
                                        rows="5" required
                                        placeholder="Enter answer">{{ old('answer', $item->answer ?? '') }}</textarea>
                                    @error('answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label required">Category</label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $item->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required">Status</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="status1"
                                                value="1" {{ old('status', $item->status ?? 1) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status1">Active</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="status0"
                                                value="0" {{ old('status', $item->status ?? 1) == 0 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="status0">Inactive</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>@isset($item) Update @else Submit @endisset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection