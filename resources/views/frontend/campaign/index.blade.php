@extends('frontend.master')
@section('title', 'Campaigns')
@section('meta_title', 'Active Campaigns')
@section('meta_description', 'Browse our active campaigns and exclusive deals.')
@section('meta_keywords', 'campaigns, deals, exclusive, promotions')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <div class="breacrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-text">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Campaigns</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Campaigns Section Begin -->
    <section class="campaigns-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Active Campaigns</h2>
                        <p>Explore our exclusive campaign collections</p>
                    </div>
                </div>
            </div>

            @if($campaigns->count())
                <div class="row">
                    @foreach($campaigns as $campaign)
                        @php
                            $products = $campaign->campaignProducts->map->product->filter();
                            $firstProduct = $products->first();
                            $bgImage = $firstProduct && $firstProduct->thumbnail ? asset($firstProduct->thumbnail) : asset('frontend/assets/img/time-bg.jpg');
                        @endphp
                        <div class="col-lg-6 col-md-6 mb-4">
                            <div class="campaign-card set-bg" data-setbg="{{ $bgImage }}">
                                <div class="campaign-overlay"></div>
                                <div class="campaign-info">
                                    <h3>{{ $campaign->name }}</h3>
                                    <p>{{ Str::limit($campaign->description ?? 'Explore our exclusive collection', 80) }}</p>
                                    <span class="product-count">{{ $products->count() }} Products</span>
                                    <a href="{{ route('campaign.show', $campaign->slug) }}" class="primary-btn mt-3">View Campaign</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info text-center">No active campaigns at the moment.</div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Campaigns Section End -->

    @push('css')
        <style>
            .campaigns-section {
                background: #f8f9fa;
                padding: 80px 0;
            }

            .campaigns-section .section-title h2 {
                font-size: 36px;
                margin-bottom: 10px;
                text-transform: capitalize;
            }

            .campaigns-section .section-title p {
                color: #666;
                margin-bottom: 40px;
            }

            .campaign-card {
                background-size: cover;
                background-position: center;
                min-height: 300px;
                border-radius: 8px;
                overflow: hidden;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.3s ease;
            }

            .campaign-card:hover {
                transform: translateY(-5px);
            }

            .campaign-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1;
            }

            .campaign-info {
                position: relative;
                z-index: 2;
                text-align: center;
                color: white;
                padding: 30px;
            }

            .campaign-info h3 {
                font-size: 28px;
                margin-bottom: 10px;
                color: #fff;
                font-weight: 700;
            }

            .campaign-info p {
                font-size: 14px;
                margin-bottom: 15px;
                color: rgba(255, 255, 255, 0.9);
            }

            .product-count {
                display: inline-block;
                font-size: 12px;
                background: rgba(255, 255, 255, 0.2);
                padding: 5px 15px;
                border-radius: 20px;
                color: #fff;
                margin-bottom: 15px;
            }

            @media (max-width: 768px) {
                .campaign-card {
                    min-height: 250px;
                }

                .campaign-info h3 {
                    font-size: 20px;
                }

                .campaigns-section .section-title h2 {
                    font-size: 24px;
                }
            }
        </style>
    @endpush
@endsection
