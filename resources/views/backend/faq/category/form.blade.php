@extends('backend.master')

@section('title')
    @isset($item) Edit @else Add @endisset FAQ Category
@endsection

@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">@isset($item) Edit @else Add @endisset FAQ Category</h3>
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
                        <a href="{{ route('admin.faq.index') }}">FAQ</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Category</a>
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
                    <div class="card-title">@isset($item) Edit @else Add @endisset Category Information</div>
                    <a href="{{ route('admin.faq.category.index') }}" class="btn btn-primary ms-auto">
                        <i class="fa fa-list me-2"></i>View Categories
                    </a>
                </div>

                <form
                    action="@isset($item){{ route('admin.faq.category.update') }}@else{{ route('admin.faq.category.store') }}@endisset"
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

                        <div class="mb-3">
                            <label class="form-label required">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $item->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>
                        <h4 class="mb-3">FAQs</h4>

                        @php
                            // Prepare data for the loop: old input -> existing data -> empty default
                            $questions = old('question', isset($item) && $item->faqs->count() > 0 ? $item->faqs->pluck('question')->toArray() : ['']);
                            $answers = old('answer', isset($item) && $item->faqs->count() > 0 ? $item->faqs->pluck('answer')->toArray() : ['']);
                        @endphp

                        <div id="faqContainer">
                            @foreach($questions as $key => $q)
                                <div class="faq-item card p-3 mb-3 border bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0">FAQ #{{ $loop->iteration }}</h5>
                                        @if(!$loop->first)
                                            <button type="button" class="btn btn-danger btn-sm remove-faq">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label required">Question</label>
                                        <input type="text" name="question[]" class="form-control"
                                            value="{{ $q }}" required placeholder="Enter question">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label required">Answer</label>
                                        <textarea name="answer[]" rows="3" class="form-control"
                                            required placeholder="Enter answer">{{ $answers[$key] ?? '' }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-3 text-end">
                            <button type="button" class="btn btn-success" id="addFaqBtn">
                                <i class="fa fa-plus me-1"></i> Add Another FAQ
                            </button>
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

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>@isset($item) Update @else Save @endisset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('#addFaqBtn').click(function () {
                let count = $('.faq-item').length + 1;
                let html = `
                    <div class="faq-item card p-3 mb-3 border bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">FAQ #${count}</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-faq">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label required">Question</label>
                            <input type="text" name="question[]" class="form-control" required placeholder="Enter question">
                        </div>
                        <div class="mb-3">
                            <label class="form-label required">Answer</label>
                            <textarea name="answer[]" rows="3" class="form-control" required placeholder="Enter answer"></textarea>
                        </div>
                    </div>
                `;
                $('#faqContainer').append(html);
            });

            $(document).on('click', '.remove-faq', function () {
                $(this).closest('.faq-item').remove();
                // Renumbering (Optional visual polish)
                $('.faq-item').each(function(index) {
                    $(this).find('h5').text('FAQ #' + (index + 1));
                });
            });
        });
    </script>
@endpush