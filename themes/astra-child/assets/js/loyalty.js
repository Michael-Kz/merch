/**
 * Loyalty System JavaScript
 * 
 * @package Astra Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Loyalty object
    const AleanLoyalty = {
        
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initLoyaltyFeatures();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).ready(function() {
                AleanLoyalty.onDocumentReady();
            });

            // Loyalty points usage
            $(document).on('click', '#use-loyalty-points', function(e) {
                e.preventDefault();
                AleanLoyalty.useLoyaltyPoints();
            });

            // Check loyalty balance
            $(document).on('click', '.check-loyalty-balance', function(e) {
                e.preventDefault();
                AleanLoyalty.checkBalance();
            });
        },

        /**
         * Document ready handler
         */
        onDocumentReady: function() {
            console.log('Alean Loyalty initialized');
            
            // Auto-check balance on page load
            if (this.shouldCheckBalance()) {
                this.checkBalance();
            }
        },

        /**
         * Initialize loyalty features
         */
        initLoyaltyFeatures: function() {
            this.initLoyaltyDisplay();
            this.initPaymentGateway();
        },

        /**
         * Initialize loyalty display
         */
        initLoyaltyDisplay: function() {
            // Add loyalty info to header if not exists
            if (!$('.loyalty-header-info').length) {
                this.addLoyaltyHeader();
            }

            // Update loyalty display on cart/checkout pages
            if (this.isCartOrCheckoutPage()) {
                this.updateLoyaltyDisplay();
            }
        },

        /**
         * Add loyalty header
         */
        addLoyaltyHeader: function() {
            const $header = $('.site-header, .header');
            if ($header.length) {
                const loyaltyHtml = `
                    <div class="loyalty-header-info">
                        <div class="loyalty-balance-display">
                            <span class="loyalty-icon">🎁</span>
                            <span class="loyalty-points">Загрузка...</span>
                        </div>
                    </div>
                `;
                $header.append(loyaltyHtml);
            }
        },

        /**
         * Update loyalty display
         */
        updateLoyaltyDisplay: function() {
            this.checkBalance(function(data) {
                if (data.success && data.data) {
                    AleanLoyalty.updateLoyaltyUI(data.data);
                }
            });
        },

        /**
         * Check loyalty balance
         */
        checkBalance: function(callback) {
            if (!aleanLoyalty || !aleanLoyalty.nonce) {
                console.error('Loyalty nonce not available');
                return;
            }

            $.ajax({
                url: aleanLoyalty.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_loyalty_data',
                    nonce: aleanLoyalty.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AleanLoyalty.updateLoyaltyUI(response.data);
                        if (callback) callback(response);
                    } else {
                        console.error('Failed to get loyalty data:', response.data);
                        if (callback) callback(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    if (callback) callback({ success: false, data: error });
                }
            });
        },

        /**
         * Update loyalty UI
         */
        updateLoyaltyUI: function(data) {
            // Update header display
            $('.loyalty-points').text(data.total_points + ' баллов');

            // Update cart sidebar
            $('.balance-amount').text(data.total_points);

            // Update payment gateway fields
            $('.alean-loyalty-payment-info .loyalty-balance').text(data.total_points);

            // Show/hide loyalty features based on status
            if (data['lp-status'] === 'TRUE') {
                $('.loyalty-features').show();
                $('.loyalty-unavailable').hide();
            } else {
                $('.loyalty-features').hide();
                $('.loyalty-unavailable').show();
            }
        },

        /**
         * Use loyalty points
         */
        useLoyaltyPoints: function() {
            const $btn = $('#use-loyalty-points');
            const originalText = $btn.text();
            
            $btn.prop('disabled', true).text('Обработка...');

            // Get cart total
            const cartTotal = this.getCartTotal();
            const loyaltyBalance = parseInt($('.balance-amount').text()) || 0;

            if (loyaltyBalance < cartTotal) {
                AstraChild.showNotification('Недостаточно баллов для оплаты заказа', 'error');
                $btn.prop('disabled', false).text(originalText);
                return;
            }

            // Show confirmation dialog
            if (confirm(`Использовать ${cartTotal} баллов для оплаты заказа?`)) {
                this.processLoyaltyPayment(cartTotal, function(success) {
                    if (success) {
                        AstraChild.showNotification('Баллы успешно применены к заказу', 'success');
                        // Redirect to checkout or update page
                        if (window.location.href.includes('/cart/')) {
                            window.location.href = aleanLoyalty.checkoutUrl || '/checkout/';
                        }
                    } else {
                        AstraChild.showNotification('Ошибка при применении баллов', 'error');
                    }
                    $btn.prop('disabled', false).text(originalText);
                });
            } else {
                $btn.prop('disabled', false).text(originalText);
            }
        },

        /**
         * Process loyalty payment
         */
        processLoyaltyPayment: function(amount, callback) {
            $.ajax({
                url: aleanLoyalty.apiUrl + 'spend-points',
                type: 'POST',
                headers: {
                    'X-WP-Nonce': aleanLoyalty.nonce,
                    'Content-Type': 'application/json'
                },
                data: JSON.stringify({
                    amount: amount,
                    order_id: this.getOrderId()
                }),
                success: function(response) {
                    if (response.success) {
                        callback(true);
                    } else {
                        callback(false);
                    }
                },
                error: function() {
                    callback(false);
                }
            });
        },

        /**
         * Initialize payment gateway
         */
        initPaymentGateway: function() {
            // Add loyalty payment option to checkout
            if (this.isCheckoutPage()) {
                this.addLoyaltyPaymentOption();
            }
        },

        /**
         * Add loyalty payment option
         */
        addLoyaltyPaymentOption: function() {
            const $paymentMethods = $('.woocommerce-checkout-payment-method');
            
            if ($paymentMethods.length && !$('.loyalty-payment-option').length) {
                const loyaltyOption = `
                    <div class="loyalty-payment-option">
                        <label>
                            <input type="radio" name="payment_method" value="alean_loyalty" id="alean_loyalty">
                            <span class="payment-method-title">Оплата баллами лояльности</span>
                        </label>
                        <div class="payment-method-description">
                            Используйте ваши баллы для оплаты заказа
                        </div>
                    </div>
                `;
                $paymentMethods.prepend(loyaltyOption);
            }
        },

        /**
         * Get cart total
         */
        getCartTotal: function() {
            const $total = $('.cart-subtotal .amount, .order-total .amount');
            if ($total.length) {
                const totalText = $total.text();
                return parseInt(totalText.replace(/[^\d]/g, '')) || 0;
            }
            return 0;
        },

        /**
         * Get order ID
         */
        getOrderId: function() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('order_id') || 'cart';
        },

        /**
         * Check if should check balance
         */
        shouldCheckBalance: function() {
            return this.isCartOrCheckoutPage() || this.isLoggedIn();
        },

        /**
         * Check if user is logged in
         */
        isLoggedIn: function() {
            return aleanLoyalty && aleanLoyalty.isLoggedIn;
        },

        /**
         * Check if current page is cart or checkout
         */
        isCartOrCheckoutPage: function() {
            const currentUrl = window.location.href;
            return currentUrl.includes('/cart/') || currentUrl.includes('/checkout/');
        },

        /**
         * Check if current page is checkout
         */
        isCheckoutPage: function() {
            return window.location.href.includes('/checkout/');
        },

        /**
         * Format points
         */
        formatPoints: function(points) {
            return new Intl.NumberFormat('ru-RU').format(points);
        },

        /**
         * Show loyalty modal
         */
        showLoyaltyModal: function() {
            const modalHtml = `
                <div class="loyalty-modal">
                    <div class="loyalty-modal-content">
                        <div class="loyalty-modal-header">
                            <h3>Бонусная программа</h3>
                            <button class="loyalty-modal-close">&times;</button>
                        </div>
                        <div class="loyalty-modal-body">
                            <div class="loyalty-info">
                                <p>Ваш текущий баланс: <strong class="loyalty-balance-display">Загрузка...</strong></p>
                                <p>Вы можете использовать баллы для оплаты заказов</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);
            
            // Load balance
            this.checkBalance(function(data) {
                if (data.success && data.data) {
                    $('.loyalty-balance-display').text(data.data.total_points + ' баллов');
                }
            });

            // Close modal
            $('.loyalty-modal-close, .loyalty-modal').on('click', function(e) {
                if (e.target === this) {
                    $('.loyalty-modal').remove();
                }
            });
        }
    };

    // Initialize
    AleanLoyalty.init();

    // Make available globally
    window.AleanLoyalty = AleanLoyalty;

})(jQuery);