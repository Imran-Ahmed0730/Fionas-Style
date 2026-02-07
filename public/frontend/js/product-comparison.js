/**
 * Product Comparison Module
 *
 * This module provides utility functions for managing product comparisons
 * in the Fionas-Style application.
 */

const ProductComparison = {
    // Configuration
    config: {
        maxItems: 4,
        addRoute: '/compare/add',
        removeRoute: '/compare/remove',
        clearRoute: '/compare/clear',
        countRoute: '/compare/count',
    },

    /**
     * Get CSRF token from page
     */
    getCsrfToken: function() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               $('meta[name="csrf-token"]').attr('content') || '';
    },

    /**
     * Add product to comparison
     * @param {number} productId - The product ID to add
     * @param {function} callback - Callback function with response
     */
    addProduct: function(productId, callback) {
        this._makeRequest(this.config.addRoute, { product_id: productId }, callback);
    },

    /**
     * Remove product from comparison
     * @param {number} productId - The product ID to remove
     * @param {function} callback - Callback function with response
     */
    removeProduct: function(productId, callback) {
        this._makeRequest(this.config.removeRoute, { product_id: productId }, callback);
    },

    /**
     * Clear all comparisons
     * @param {function} callback - Callback function with response
     */
    clearAll: function(callback) {
        this._makeRequest(this.config.clearRoute, {}, callback);
    },

    /**
     * Get comparison count
     * @param {function} callback - Callback function with response
     */
    getCount: function(callback) {
        $.get(this.config.countRoute, callback);
    },

    /**
     * Toggle product in comparison
     * @param {number} productId - The product ID to toggle
     * @param {function} callback - Callback function with response
     */
    toggleProduct: function(productId, callback) {
        const self = this;
        const csrfToken = this.getCsrfToken();
        
        $.post(this.config.addRoute, {
            product_id: productId,
            _token: csrfToken
        }, function(response) {
            if (!response.success && response.message.includes('already in comparison')) {
                // If already in, remove it
                self._makeRequest(self.config.removeRoute, { product_id: productId }, callback);
            } else if (callback) {
                callback(response);
            }
        }).fail(function(error) {
            if (callback) {
                callback({ success: false, message: 'Request failed' });
            }
        });
    },

    /**
     * Check if product is in comparison
     * @param {number} productId - The product ID to check
     * @param {function} callback - Callback function with response
     */
    isInComparison: function(productId, callback) {
        const csrfToken = this.getCsrfToken();
        $.post('/compare/is-in-comparison', {
            product_id: productId,
            _token: csrfToken
        }, callback).fail(function() {
            callback({ success: false, isInComparison: false });
        });
    },

    /**
     * Get all compared products
     * @param {function} callback - Callback function with array of products
     */
    getComparedProducts: function(callback) {
        $.get('/compare', function(html) {
            // This would need to be implemented based on your API structure
            if (callback) callback(null);
        }).fail(function() {
            if (callback) callback(null);
        });
    },

    /**
     * Show notification to user
     * @param {string} message - The message to display
     * @param {string} type - 'success', 'error', 'info'
     * @param {number} duration - Duration in milliseconds
     */
    notify: function(message, type = 'info', duration = 3000) {
        const notification = document.createElement('div');
        notification.className = `comparison-notification ${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background: ${this._getNotificationColor(type)};
            color: white;
            border-radius: 3px;
            z-index: 9999;
            animation: slideIn 0.3s ease-in-out;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            max-width: 300px;
            word-wrap: break-word;
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-in-out';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    },

    /**
     * Update UI button state
     * @param {string} buttonSelector - CSS selector for the button
     * @param {boolean} isInComparison - Whether product is in comparison
     */
    updateButtonState: function(buttonSelector, isInComparison) {
        const button = document.querySelector(buttonSelector);
        if (button) {
            if (isInComparison) {
                button.classList.add('is-comparing');
                button.textContent = 'Remove from Comparison';
            } else {
                button.classList.remove('is-comparing');
                button.textContent = 'Add to Comparison';
            }
        }
    },

    /**
     * Update comparison count badge
     * @param {number} count - The new count
     * @param {string} badgeSelector - CSS selector for the badge
     */
    updateCountBadge: function(count, badgeSelector = '.comparison-count-badge') {
        const badge = document.querySelector(badgeSelector);
        if (badge) {
            badge.textContent = count;
            if (count === 0) {
                badge.style.display = 'none';
            } else {
                badge.style.display = 'inline-block';
            }
        }
    },

    /**
     * Enable comparison button with event listener
     * @param {string} buttonSelector - CSS selector for the button
     */
    enableComparisonButton: function(buttonSelector = '.btn-compare-product') {
        const buttons = document.querySelectorAll(buttonSelector);
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = button.getAttribute('data-product-id');
                ProductComparison.toggleProduct(productId, (response) => {
                    if (response.success) {
                        ProductComparison.notify(response.message, 'success');
                        if (response.message.includes('added') || response.message.includes('Added')) {
                            button.classList.add('is-comparing');
                        } else {
                            button.classList.remove('is-comparing');
                        }
                    } else {
                        ProductComparison.notify(response.message, 'error');
                    }
                });
            });
        });
    },

    /**
     * Redirect to comparison page
     */
    goToComparison: function() {
        window.location.href = '/compare';
    },

    /**
     * Private method to make AJAX request
     */
    _makeRequest: function(route, data, callback) {
        const csrfToken = this.getCsrfToken();
        data._token = csrfToken;
        $.post(route, data, function(response) {
            if (callback) callback(response);
        }).fail(function(error) {
            if (callback) {
                callback({ success: false, message: 'Request failed' });
            }
        });
    },

    /**
     * Private method to get notification color
     */
    _getNotificationColor: function(type) {
        const colors = {
            'success': '#27ae60',
            'error': '#e74c3c',
            'info': '#3498db',
            'warning': '#f39c12'
        };
        return colors[type] || colors['info'];
    }
};

// Add CSS animations if not already present
if (!document.getElementById('comparison-module-styles')) {
    const style = document.createElement('style');
    style.id = 'comparison-module-styles';
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
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        ProductComparison.enableComparisonButton();
    });
} else {
    ProductComparison.enableComparisonButton();
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ProductComparison;
}
