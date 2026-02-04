@extends('frontend.master')
@section('title', 'About Us')
@section('meta_title', 'About Us')
@section('meta_description', 'Learn more about ' . getSetting('business_name') . '. Our story, mission and vision.')
@push('css')
    <style>
        .about-section {
            background: #fff;
        }

        .about-text .section-title {
            text-align: left;
            margin-bottom: 30px;
        }

        .about-content {
            font-size: 16px;
            line-height: 1.8;
            color: #636363;
        }
    </style>
@endpush
@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>About Us</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- About Section Begin -->
    <section class="about-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="about-text">
                        <div class="section-title">
                            <h2 class="text-center">{{ $item->title }}</h2>
                        </div>
                        <div class="about-content">
                            {!! $item->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section End -->
@endsection

