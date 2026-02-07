<!-- Quick Comparison Info (Product Detail Page) -->
<?php
$compareService = app(\App\Services\Frontend\CompareService::class);
$isInComparison = $compareService->isProductInComparison($product->id);
$comparisonCount = $compareService->getComparisonCount();
$canAddMore = $compareService->canAddMore();
?>

<div class="comparison-info" style="
    padding: 15px;
    background: #f8f8f8;
    border-left: 4px solid #e7ab3c;
    border-radius: 3px;
    margin-bottom: 15px;
">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px;">
        <div>
            @if ($isInComparison)
                <p style="margin: 0; color: #27ae60; font-weight: 600;">
                    <i class="fa fa-check"></i> Added to Comparison
                </p>
                <small style="color: #7f8c8d;">
                    {{ $comparisonCount }}/4 products selected
                </small>
            @else
                <p style="margin: 0; color: #333; font-weight: 600;">
                    <i class="fa fa-exchange"></i> Compare this Product
                </p>
                @if (!$canAddMore)
                    <small style="color: #e74c3c;">
                        Maximum 4 products reached. Remove one to add this.
                    </small>
                @else
                    <small style="color: #7f8c8d;">
                        {{ $comparisonCount }}/4 products selected
                    </small>
                @endif
            @endif
        </div>
        <button class="btn-comparison-action"
                id="btn-product-compare"
                onclick="toggleProductComparison({{ $product->id }})"
                style="
                    padding: 10px 20px;
                    background: {{ $isInComparison ? '#27ae60' : '#333' }};
                    color: white;
                    border: none;
                    border-radius: 3px;
                    cursor: pointer;
                    font-weight: 600;
                    transition: all 0.3s;
                    white-space: nowrap;
                ">
            {{ $isInComparison ? 'Remove from Comparison' : 'Add to Comparison' }}
        </button>
    </div>
</div>

@if ($isInComparison)
    <div style="text-align: center; margin-bottom: 15px;">
        <a href="{{ route('compare.index') }}" class="btn-view-comparison" style="
            display: inline-block;
            padding: 10px 20px;
            background: #e7ab3c;
            color: white !important;
            text-decoration: none;
            border-radius: 3px;
            font-weight: 600;
            transition: all 0.3s;
        " onmouseover="this.style.background='#d4952a'" onmouseout="this.style.background='#e7ab3c'">
            <i class="fa fa-eye"></i> View Comparison
        </a>
    </div>
@endif

<script>
    function toggleProductComparison(productId) {
        const button = document.getElementById('btn-product-compare');
        const isAdding = button.textContent.includes('Add');

        const endpoint = isAdding ? '{{ route("compare.add") }}' : '{{ route("compare.remove") }}';

        $.post(endpoint, {
            product_id: productId,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                if (isAdding) {
                    button.style.background = '#27ae60';
                    button.textContent = 'Remove from Comparison';
                } else {
                    button.style.background = '#333';
                    button.textContent = 'Add to Comparison';
                }

                // Show notification
                const message = response.message || (isAdding ? 'Added to comparison' : 'Removed from comparison');
                showComparisonNotification(message, 'success');

                // Update info text
                updateComparisonInfo(response.count, response.max);
            } else {
                showComparisonNotification(response.message, 'error');
            }
        }).fail(function() {
            showComparisonNotification('Error updating comparison', 'error');
        });
    }

    function updateComparisonInfo(count, max) {
        const infoDiv = document.querySelector('.comparison-info');
        if (infoDiv) {
            const smallText = infoDiv.querySelector('small');
            if (smallText) {
                smallText.textContent = count + '/' + max + ' products selected';
            }
        }
    }

    function showComparisonNotification(message, type) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
            color: white;
            border-radius: 3px;
            z-index: 9999;
            animation: slideInUp 0.3s ease-in-out;
            font-weight: 600;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutDown 0.3s ease-in-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Add CSS animations for notifications
    if (!document.getElementById('comparison-animations')) {
        const style = document.createElement('style');
        style.id = 'comparison-animations';
        style.textContent = `
            @keyframes slideInUp {
                from {
                    transform: translateY(100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            @keyframes slideOutDown {
                from {
                    transform: translateY(0);
                    opacity: 1;
                }
                to {
                    transform: translateY(100px);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
</script>
