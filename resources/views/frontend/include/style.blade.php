<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">

<!-- Css Styles -->
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/font-awesome.min.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/themify-icons.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/elegant-icons.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/owl.carousel.min.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/nice-select.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/jquery-ui.min.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/slicknav.min.css" type="text/css">
<link rel="stylesheet" href="{{asset('frontend')}}/assets/css/style.css" type="text/css">

{{--Laravel Project Style--}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />

<!-- Product Comparison Styles -->
<style>
    /* Comparison Icon in Product Card - Match Wishlist Style */
    .compare-icon {
        position: relative !important;
    }

    .compare-icon a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 20px;
        cursor: pointer;
        color: inherit;
        transition: color 0.3s ease;
        text-decoration: none;
    }

    .compare-icon a:hover {
        color: #e7ab3c;
        text-decoration: none;
    }

    .compare-icon.is-comparing {
        background: rgba(231, 171, 60, 0.1);
    }

    .compare-icon.is-comparing a {
        color: #e7ab3c;
        font-weight: bold;
    }

    /* Comparison Badge in Header */
    .exchange-icon {
        position: relative;
    }

    .exchange-icon a {
        position: relative;
    }

    #comparisonCount {
        animation: pulse 0.5s ease-in-out;
    }

    @keyframes pulse {
        0% {
            transform: scale(0.8);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Comparison Page Buttons */
    .btn-compare-product-item {
        cursor: pointer;
        color: inherit;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        font-size: 20px;
    }

    .btn-compare-product-item:hover {
        color: #e7ab3c;
        text-decoration: none;
    }

    .btn-compare-product-item.is-comparing {
        color: #e7ab3c;
    }
</style>

@stack('css')
