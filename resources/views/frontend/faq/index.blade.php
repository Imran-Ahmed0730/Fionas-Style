@extends('frontend.master')
@section('title', 'FAQ')
@section('meta_title', 'Frequently Asked Questions')
@section('meta_description', 'Find answers to common questions about ' . getSetting('business_name') . '.')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="ti-home"></i> Home</a>
                        <span>FAQs</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Begin -->

    <!-- Faq Section Begin -->
    <div class="faq-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="faq-accordin">
                        <div class="accordion" id="accordionExample">
                            @foreach($items as $category)
                                <div class="faq-category-title mb-4">
                                    <h3>{{ $category->name }}</h3>
                                </div>
                                @foreach($category->faqs as $faq)
                                    <div class="card">
                                        <div class="card-heading {{ $loop->parent->first && $loop->first ? 'active' : '' }}">
                                            <a class="{{ $loop->parent->first && $loop->first ? 'active' : '' }}"
                                                data-toggle="collapse" data-target="#collapse{{ $faq->id }}">
                                                {{ $faq->question }}
                                            </a>
                                        </div>
                                        <div id="collapse{{ $faq->id }}"
                                            class="collapse {{ $loop->parent->first && $loop->first ? 'show' : '' }}"
                                            data-parent="#accordionExample">
                                            <div class="card-body">
                                                <p>{!! $faq->answer !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Faq Section End -->
@endsection

@push('css')
    <style>
        .faq-category-title h3 {
            color: #252525;
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .faq-category-title h3::after {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background: #e7ab3c;
            content: "";
        }

        .faq-accordin .card {
            border: none;
            margin-bottom: 15px;
        }

        .faq-accordin .card-heading a {
            font-size: 18px;
            color: #252525;
            font-weight: 700;
            display: block;
            padding: 15px 20px;
            background: #f5f5f5;
            position: relative;
            transition: all 0.3s;
            text-decoration: none;
        }

        .faq-accordin .card-heading a.active {
            color: #e7ab3c;
        }

        .faq-accordin .card-heading a:after {
            content: "\e64b";
            font-family: 'themify';
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 14px;
            color: #252525;
        }

        .faq-accordin .card-heading a.active:after {
            content: "\e648";
            color: #e7ab3c;
        }

        .faq-accordin .card-body {
            padding: 20px;
            border: 1px solid #ebebeb;
            border-top: none;
        }

        .faq-accordin .card-body p {
            color: #636363;
            line-height: 26px;
            margin-bottom: 0;
        }
    </style>
@endpush