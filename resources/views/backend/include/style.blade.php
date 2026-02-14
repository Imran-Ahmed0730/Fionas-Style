<!-- CSS Files -->
<link rel="stylesheet" href="{{asset('backend')}}/assets/css/bootstrap.min.css" />
<link rel="stylesheet" href="{{asset('backend')}}/assets/css/plugins.min.css" />
<link rel="stylesheet" href="{{asset('backend')}}/assets/css/kaiadmin.min.css" />

{{--Laravel Project Style--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
<style>
    .form-check {
        padding: 0 !important
    }

    /* Sidebar Search Styles */
    .sidebar-search-wrapper {
        margin-top: 5px;
    }

    .sidebar-search-inner {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .sidebar-search-inner:hover,
    .sidebar-search-inner:focus-within {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .sidebar-search-inner i {
        color: rgba(255, 255, 255, 0.4);
        font-size: 13px;
        margin-right: 10px;
    }

    .sidebar-search-inner input {
        background: transparent;
        border: none;
        color: #fff;
        font-size: 12px;
        width: 100%;
        outline: none;
        padding: 0;
    }

    .sidebar-search-inner input::placeholder {
        color: rgba(255, 255, 255, 0.35);
    }

    /* Mobile Search Visibility */
    @media (max-width: 991px) {
        .sidebar-search-wrapper {
            display: block !important;
            margin-top: 15px !important;
            padding: 0 15px !important;
            z-index: 1000;
        }

        .sidebar-search-inner {
            background: rgba(255, 255, 255, 0.1) !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
    }
</style>
@stack('css')