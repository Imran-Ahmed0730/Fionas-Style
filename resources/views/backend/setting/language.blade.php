@extends('backend.master')
@section('title')
    Edit Language Setting
@endsection
@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection {
            height: 39px;
            border: 1px solid #dee2e6 !important;
            border-radius: 6px;
        }
        .badge {
            position: relative; /* To position the 'X' button */
        }
        .remove-lang {
            font-size: 10px;
            position: absolute;
            top: -10px;
            right: -5px;
            cursor: pointer;
            color: red;
        }
        @media screen and (max-width: 667px) {
            .select2{
                max-width: 1025px !important;
            }
        }
    </style>
@endpush
@section('content')
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="mb-0">Language Setting</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item" aria-current="page">Settings</li>
                        <li class="breadcrumb-item active" aria-current="page">Language</li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div>
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid">
            @include('backend.include.setting_menu')
            <form action="{{route('admin.setting.update')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row mt-3 g-4">
                    <div class="col-md-12"> <!--begin::Quick Example-->
                        <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                            <div class="card-header">
                                <div class="card-title d-flex">Physical Store Information</div>
                            </div> <!--end::Header--> <!--begin::Form-->

                            <div class="card-body" id="shop-container">
                                <div class="mb-3">
                                    <label for="site_language" class="form-label">Languages To Be Translated</label>
                                    <select name="site_language[]" class="js-example-basic-multiple form-control" id="" multiple required>
                                        <option value="">Select</option>
                                        @foreach($languages as $language)
                                            @if(getSetting('site_language') != null && !in_array($language->lang_code, json_decode(getSetting('site_language'))))
                                                <option value="{{$language->lang_code}}">{{$language->language}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('site_language')
                                    <div class="invalid-feedback" role="alert">
                                        {{$message}}
                                    </div>
                                    @enderror
                                </div>
                                @if(getSetting('site_language'))
                                    <div class="mb-3">
                                        <label for="language" class="form-label">Selected Languages</label>
                                        @foreach(json_decode(getSetting('site_language')) as $selected_language)
                                            @php $lang_name = \App\Models\Admin\Country::where('lang_code', $selected_language)->first()->language @endphp
                                            <span class="badge text-bg-primary p-2 me-3" id="{{$selected_language}}">
                                                {{$lang_name ?? 'N/A'}}
                                                <span class="remove-lang" data-lang-code="{{$selected_language}}"><i class="fa fa-x text-white bg-danger p-1 rounded-circle"></i></span>
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div> <!--end::Body--> <!--begin::Footer-->
                            <div class="card-footer d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">@isset($item) Update @else Submit @endisset</button>
                            </div> <!--end::Footer-->
                        </div>
                    </div><!--end::Quick Example-->
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert -->
    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();

            // Handle the click event on the remove language button
            $('.remove-lang').on('click', function() {
                const langCode = $(this).data('lang-code');

                // Show confirmation dialog
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // AJAX request to remove language
                        $('#'+langCode).remove();
                        $.ajax({
                            url: "{{ route('admin.setting.language.remove') }}", // Your route to handle removal
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}', // CSRF token
                                lang_code: langCode // Language code to remove
                            },
                            success: function(response) {
                                // Handle success (optional)
                                Swal.fire(
                                    'Deleted!',
                                    'Language has been removed.',
                                    'success'
                                );
                                // Remove the badge from the UI
                                $(this).closest('.badge').remove(); // Adjust context with 'this'
                            },
                            error: function(xhr, status, error) {
                                // Handle error (optional)
                                Swal.fire(
                                    'Error!',
                                    'There was a problem removing the language.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
