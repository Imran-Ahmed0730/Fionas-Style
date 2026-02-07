<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('meta_title', getSetting('business_name')) | {{ getSetting('site_name') }}</title>
    <meta name="description" content="@yield('meta_description', getSetting('meta_description'))">
    <meta name="keywords" content="@yield('meta_keywords', getSetting('meta_keywords'))">

    <!-- Open Graph Tags -->
    <meta property="og:title"
        content="@yield('meta_title', getSetting('business_name')) | {{ getSetting('site_name') }}">
    <meta property="og:description" content="@yield('meta_description', getSetting('meta_description'))">
    <meta property="og:image" content="@yield('meta_image', asset(getSetting('site_logo')))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

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
    <!-- Back to Top Button -->
    <a href="#" id="back-to-top" class="back-to-top-btn" title="Back to Top">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', getSetting('whatsapp')) }}" class="whatsapp-btn"
        target="_blank" title="Contact us on WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</body>

</html>