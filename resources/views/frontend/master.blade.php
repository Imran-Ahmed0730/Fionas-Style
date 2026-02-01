<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="{{ getSetting('meta_description') }}">
    {{--
    <meta name="keywords" content="Fashi, unica, creative, html">--}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{getSetting('business_name')}} | @yield('title')</title>
    <link rel="icon" href="{{ asset(getSetting('site_favicon')) }}" type="image/x-icon" />
    @include('frontend.include.style')
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('frontend.include.header')

    @yield('content')

    @include('frontend.include.footer')

    @include('frontend.include.script')

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="position: absolute; right: 15px; top: 10px; z-index: 999;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div id="quick-view-content">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>