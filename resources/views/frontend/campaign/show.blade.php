@extends('frontend.master')
@section('title', $campaign->name)
@section('meta_title', $campaign->meta_title ?? $campaign->name)
@section('meta_description', $campaign->meta_description ?? 'Shop products from '.$campaign->name)
@section('meta_keywords', $campaign->meta_keywords . ', campaign, products')

@push('css')
    <style>
        .campaign-hero {
            background: linear-gradient(135deg, #e7ab3c 0%, #f4d48f 100%);
            padding: 60px 0;
        }

        .campaign-banner {
            text-align: center;
            color: white;
        }

        .campaign-banner h1 {
            font-size: 48px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .campaign-banner p {
            font-size: 18px;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .campaign-banner .countdown-timer {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .countdown-timer .cd-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 20px 25px;
            border-radius: 8px;
            text-align: center;
            min-width: 80px;
        }

        .countdown-timer .cd-item span {
            display: block;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 5px;
        }

        .countdown-timer .cd-item p {
            font-size: 12px;
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            font-weight: 600;
        }

        .campaign-expired {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            font-size: 24px;
            font-weight: 700;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .campaign-banner h1 {
                font-size: 32px;
            }

            .campaign-banner p {
                font-size: 14px;
                margin-bottom: 20px;
            }

            .campaign-banner .countdown-timer {
                gap: 10px;
            }

            .countdown-timer .cd-item {
                padding: 15px 18px;
                min-width: 65px;
            }

            .countdown-timer .cd-item span {
                font-size: 24px;
            }

            .countdown-timer .cd-item p {
                font-size: 10px;
            }
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
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <a href="{{ route('campaign.index') }}"><i class="fa fa-tag"></i> Campaigns</a>
                        <span>{{ $campaign->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->

    <!-- Campaign Hero Section -->
    <section class="campaign-hero spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="campaign-banner">
                        <h1>{{ $campaign->name }}</h1>
                        <div class="campaign-description">
                            {!! $campaign->description !!}
                        </div>

                        @if($campaign->getCountdownEndDate())
                            <div class="countdown-timer" id="campaign-countdown"
                                 data-end-date="{{ $campaign->getCountdownEndDate() }} 23:59:59">
                                <div class="cd-item">
                                    <span class="days">00</span>
                                    <p>Days</p>
                                </div>
                                <div class="cd-item">
                                    <span class="hours">00</span>
                                    <p>Hrs</p>
                                </div>
                                <div class="cd-item">
                                    <span class="minutes">00</span>
                                    <p>Mins</p>
                                </div>
                                <div class="cd-item">
                                    <span class="seconds">00</span>
                                    <p>Secs</p>
                                </div>
                            </div>
                        @else
                            <div class="campaign-expired">Campaign Ended</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Campaign Hero Section End -->

    <!-- Product Shop Section Begin -->
    <section class="product-shop spad">
        <div class="container">
            <div class="row">
                @include('frontend.product.partials.sidebar', ['route' => 'campaign.show', 'routeParam' => $campaign->slug])

                <div class="col-lg-9 order-1 order-lg-2">
                    <div class="product-show-option">
                        <div class="row">
                            <div class="col-lg-7 col-md-7">
                                <div class="select-option">
                                    <select class="sorting" onchange="window.location.href = this.value;">
                                        <option
                                            value="{{ route('campaign.show', ['slug' => $campaign->slug, 'sort' => 'latest']) }}"
                                            {{ request('sort') == 'latest' ? 'selected' : '' }}>Default Sorting (Latest)
                                        </option>
                                        <option
                                            value="{{ route('campaign.show', ['slug' => $campaign->slug, 'sort' => 'price_asc']) }}"
                                            {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option
                                            value="{{ route('campaign.show', ['slug' => $campaign->slug, 'sort' => 'price_desc']) }}"
                                            {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5 text-right">
                                <p>Showing {{ $items->firstItem() ?? 0 }} - {{ $items->lastItem() ?? 0 }} Of
                                    {{ $items->total() }} Product
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="product-list">
                        <div class="row">
                            @forelse($items as $product)
                                <div class="col-lg-4 col-sm-6">
                                    @include('frontend.product.partials.product_item', ['product' => $product])
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">No products found in this campaign.</div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="loading-more">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Shop Section End -->
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Simple Countdown Timer Function
            function updateCampaignCountdown() {
                const $countdown = $("#campaign-countdown");

                if (!$countdown.length) return;

                const endDateStr = $countdown.data('end-date');
                if (!endDateStr) return;

                const endDate = new Date(endDateStr);
                const now = new Date();
                const diff = endDate - now;

                if (diff <= 0) {
                    // Campaign expired
                    $countdown.html('<div class="campaign-expired">Campaign Ended</div>');
                    return;
                }

                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                $countdown.find('.days').text(days.toString().padStart(2, '0'));
                $countdown.find('.hours').text(hours.toString().padStart(2, '0'));
                $countdown.find('.minutes').text(minutes.toString().padStart(2, '0'));
                $countdown.find('.seconds').text(seconds.toString().padStart(2, '0'));
            }

            // Initialize countdown on page load
            updateCampaignCountdown();

            // Update countdown every second
            setInterval(updateCampaignCountdown, 1000);

            // Additional initialization after page fully loads
            $(window).on('load', function() {
                setTimeout(updateCampaignCountdown, 500);
            });
        });
    </script>
@endpush
