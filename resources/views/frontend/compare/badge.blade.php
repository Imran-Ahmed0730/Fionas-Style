<!-- Comparison Count Badge -->
<?php
$compareService = app(\App\Services\Frontend\CompareService::class);
$comparisonCount = $compareService->getComparisonCount();
$maxCompare = 4;
?>

<a href="{{ route('compare.index') }}" class="product-social" title="Product Comparison">
    <i class="fa fa-exchange"></i>
    @if ($comparisonCount > 0)
        <span class="comparison-count-badge" style="
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e7ab3c;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        ">{{ $comparisonCount }}</span>
    @endif
</a>

<style>
    .comparison-count-badge {
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
</style>
