@extends('backend.master')

@section('title')
    @isset($item) Edit @else Add @endisset Blog
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" />
    <style>
        .object-fit-cover {
            object-fit: cover;
        }

        .nav-pills .nav-link.active {
            background-color: #4361ee;
        }

        .tab-content {
            padding-top: 20px;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset Blog</h3>
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
                        <a href="{{ route('admin.blog.index') }}">Blogs</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">@isset($item) Edit @else Add @endisset</a>
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="card-title">@isset($item) Edit @else Add @endisset Blog Information</div>
                    <a href="{{ route('admin.blog.index') }}" class="btn btn-primary ms-auto">
                        <i class="fa fa-list me-2"></i>List
                    </a>
                </div>

                <form action="@isset($item){{ route('admin.blog.update') }}@else{{ route('admin.blog.store') }}@endisset"
                    method="POST" enctype="multipart/form-data">
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

                        {{-- Tabs --}}
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd mb-3" id="pills-tab-without-border"
                            role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab-nobd" data-bs-toggle="pill"
                                    href="#primary-info" role="tab" aria-controls="primary-info"
                                    aria-selected="true">Primary Information</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-seo-tab-nobd" data-bs-toggle="pill" href="#seo-info"
                                    role="tab" aria-controls="seo-info" aria-selected="false">SEO Information</a>
                            </li>
                        </ul>

                        <div class="tab-content mt-3" id="pills-without-border-tabContent">

                            {{-- PRIMARY INFO --}}
                            <div class="tab-pane fade show active" id="primary-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label required">Title</label>
                                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                                value="{{ old('title', $item->title ?? '') }}" required
                                                placeholder="Enter blog title">
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label required">Description</label>
                                            <textarea name="description"
                                                class="form-control summernote @error('description') is-invalid @enderror">{{ old('description', $item->description ?? '') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label required">Category</label>
                                            <select name="category_id" class="form-select js-select @error('category_id') is-invalid @enderror" required>
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
                                            <label class="form-label required">Thumbnail</label>
                                            <input type="file" name="thumbnail" id="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror"
                                                accept="image/jpeg, image/png, image/jpg" @empty($item) required @endempty>
                                            <div class="mt-2">
                                                <img id="thumbnailPreview"
                                                    src="{{ isset($item) && $item->thumbnail ? asset($item->thumbnail) : asset('backend/assets/img/default-banner.jpg') }}"
                                                    class="img-fluid rounded object-fit-cover"
                                                    style="max-height: 150px; width: 100%">
                                            </div>
                                            @error('thumbnail')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Cover Photo <small>[optional]</small></label>
                                            <input type="file" name="cover_photo" id="cover_photo" class="form-control @error('cover_photo') is-invalid @enderror"
                                                accept="image/jpeg, image/png, image/jpg">
                                            <div class="mt-2">
                                                <img id="coverPreview"
                                                    src="{{ isset($item) && $item->cover_photo ? asset($item->cover_photo) : asset('backend/assets/img/default-banner.jpg') }}"
                                                    class="img-fluid rounded object-fit-cover"
                                                    style="max-height: 150px; width: 100%">
                                            </div>
                                            @error('cover_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label required">Status</label>
                                            <div class="d-flex gap-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="status1"
                                                        value="1" {{ old('status', $item->status ?? 1) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status1">Active</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="status" id="status0"
                                                        value="0" {{ old('status', $item->status ?? 1) == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="status0">Inactive</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEO INFO --}}
                            <div class="tab-pane fade" id="seo-info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Meta Title <small>[optional]</small></label>
                                            <input type="text" name="meta_title" class="form-control @error('meta_title') is-invalid @enderror"
                                                value="{{ old('meta_title', $item->meta_title ?? '') }}"
                                                placeholder="Enter meta title">
                                            @error('meta_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Meta Keywords <small>[optional]</small></label>
                                            <input type="text" name="meta_keywords" class="form-control @error('meta_keywords') is-invalid @enderror"
                                                value="{{ old('meta_keywords', $item->meta_keywords ?? '') }}"
                                                placeholder="Enter meta keywords">
                                            @error('meta_keywords')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Meta Description <small>[optional]</small></label>
                                            <textarea name="meta_description" class="form-control @error('meta_description') is-invalid @enderror" rows="3"
                                                placeholder="Enter meta description">{{ old('meta_description', $item->meta_description ?? '') }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Meta Image <small>[optional]</small></label>
                                            <input type="file" name="meta_image" id="meta_image" class="form-control @error('meta_image') is-invalid @enderror"
                                                accept="image/jpeg, image/png, image/jpg">
                                            <div class="mt-2">
                                                <img id="metaImagePreview"
                                                    src="{{ isset($item) && $item->meta_image ? asset($item->meta_image) : asset('backend/assets/img/default-banner.jpg') }}"
                                                    class="img-fluid rounded object-fit-cover"
                                                    style="max-height: 150px; width: 100%">
                                            </div>
                                            @error('meta_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
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

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            $('.js-select').select2({
                placeholder: "Select Category",
                width: '100%'
            });

            // Image Previews
            function readURL(input, previewId) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(previewId).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#thumbnail").change(function () {
                readURL(this, '#thumbnailPreview');
            });
            $("#cover_photo").change(function () {
                readURL(this, '#coverPreview');
            });
            $("#meta_image").change(function () {
                readURL(this, '#metaImagePreview');
            });
        });
    </script>
@endpush