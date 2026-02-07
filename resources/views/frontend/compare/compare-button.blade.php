<!-- Compare Button Component -->
<button class="btn-compare-product"
        data-product-id="{{ $product->id }}"
        title="Add to Comparison"
        onclick="addToComparison(event, {{ $product->id }})">
    <i class="fa fa-exchange"></i>
    <span class="compare-text">Compare</span>
</button>

<style>
    .btn-compare-product {
        background: transparent;
        border: 1px solid #e7ab3c;
        color: #e7ab3c;
        padding: 8px 15px;
        border-radius: 3px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
    }

    .btn-compare-product:hover {
        background: #e7ab3c;
        color: white;
    }

    .btn-compare-product.is-comparing {
        background: #e7ab3c;
        color: white;
        border-color: #e7ab3c;
    }

    .btn-compare-product i {
        font-size: 14px;
    }
</style>

<script>
    function addToComparison(event, productId) {
        event.preventDefault();
        const button = event.target.closest('.btn-compare-product');

        $.post('{{ route("compare.add") }}', {
            product_id: productId,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                button.classList.add('is-comparing');
                // Show notification
                showNotification(response.message, 'success');
                // Update comparison count
                updateComparisonCount();
            } else {
                showNotification(response.message, 'error');
            }
        }).fail(function() {
            showNotification('Error adding product to comparison', 'error');
        });
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${type === 'success' ? '#27ae60' : '#e74c3c'};
            color: white;
            border-radius: 3px;
            z-index: 9999;
            animation: slideIn 0.3s ease-in-out;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    function updateComparisonCount() {
        $.get('{{ route("compare.count") }}', function(response) {
            // Update comparison count badge if exists
            const badge = document.querySelector('.comparison-count-badge');
            if (badge) {
                badge.textContent = response.count;
                badge.style.display = response.count > 0 ? 'inline-block' : 'none';
            }
        });
    }

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>
