@extends('backend.master')

@section('title')
    @isset($item) Edit @else Add @endisset Blog Category
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">

            {{-- Page Header --}}
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Blog Category</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="icon-home"></i>
                        </a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item">
                        <a href="{{ route('admin.blog.category.index') }}">Blog Categories</a>
                    </li>
                    <li class="separator"><i class="icon-arrow-right"></i></li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>

            {{-- Card --}}
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="card-title">
                        @isset($item) Edit @else Add @endisset Category Information
                    </div>
                    <a href="{{ route('admin.blog.category.index') }}" class="btn btn-primary ms-auto">
                        <i class="fa fa-list me-2"></i>View Categories
                    </a>
                </div>

                {{-- Form --}}
                <form action="@isset($item){{ route('admin.blog.category.update') }}@else{{ route('admin.blog.category.store') }}@endisset"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @isset($item)
                        <input type="hidden" name="id" value="{{ $item->id }}">
                    @endisset

                    <div class="card-body">

                        {{-- Validation Errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Tabs --}}
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" id="pills-tab-without-border" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill" href="#primary-info" role="tab"
                                    aria-controls="primary-info" aria-selected="true">Primary Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-seo-tab-nobd" data-bs-toggle="pill" href="#seo-info" role="tab"
                                    aria-controls="seo-info" aria-selected="false">SEO Information</a>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content">

                            {{-- ================= PRIMARY INFO ================= --}}
                            <div class="tab-pane fade show active" id="primary-info">
                                <div class="row">

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Name</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name', $item->name ?? '') }}"
                                               placeholder="Enter category name"
                                               required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Description <small>[optional]</small></label>
                                        <textarea name="description"
                                                  rows="4"
                                                  class="form-control @error('description') is-invalid @enderror"
                                                  placeholder="Enter description">{{ old('description', $item->description ?? '') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label required">Status</label>
                                        <div class="d-flex gap-4">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="status"
                                                       value="1"
                                                       {{ old('status', $item->status ?? 1) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">Active</label>
                                            </div>

                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="radio"
                                                       name="status"
                                                       value="0"
                                                       {{ old('status', $item->status ?? 1) == 0 ? 'checked' : '' }}>
                                                <label class="form-check-label">Inactive</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- ================= SEO INFO ================= --}}
                            <div class="tab-pane fade" id="seo-info">
                                <div class="row">

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text"
                                               name="meta_title" placeholder="Enter meta title"
                                               class="form-control @error('meta_title') is-invalid @enderror"
                                               value="{{ old('meta_title', $item->meta_title ?? '') }}">
                                        @error('meta_title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Meta Keywords</label>
                                        <input type="text"
                                               name="meta_keywords" placeholder="Enter meta keywords"
                                               class="form-control @error('meta_keywords') is-invalid @enderror"
                                               value="{{ old('meta_keywords', $item->meta_keywords ?? '') }}">
                                        @error('meta_keywords')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Meta Description</label>
                                        <textarea name="meta_description" placeholder="Enter meta description"
                                                  rows="3"
                                                  class="form-control @error('meta_description') is-invalid @enderror">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                                        @error('meta_description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Meta / Cover Image</label>
                                        <input type="file"
                                               name="meta_image"
                                               id="meta_image"
                                               class="form-control @error('meta_image') is-invalid @enderror"
                                               accept="image/jpg, image/jpeg, image/png">
                                        @error('meta_image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror

                                        <div class="mt-2">
                                            <img id="metaImagePreview"
                                                 src="{{ isset($item) && $item->meta_image ? asset($item->meta_image) : asset('backend/assets/img/default-150x150.png') }}"
                                                 class="img-fluid rounded"
                                                 style="max-height: 80px">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            @isset($item) Update @else Submit @endisset
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $('#meta_image').on('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => $('#metaImagePreview').attr('src', e.target.result);
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush
