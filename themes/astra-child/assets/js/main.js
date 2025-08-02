/**
 * Main JavaScript for Astra Child Theme
 * 
 * @package Astra Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Main object
    const AstraChild = {
        
        /**
         * Initialize
         */
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).ready(function() {
                AstraChild.onDocumentReady();
            });

            $(window).on('load', function() {
                AstraChild.onWindowLoad();
            });

            $(window).on('resize', function() {
                AstraChild.onWindowResize();
            });
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            this.initQuantityInputs();
            this.initLazyLoading();
            this.initSmoothScroll();
            this.initMobileMenu();
        },

        /**
         * Document ready handler
         */
        onDocumentReady: function() {
            console.log('Astra Child Theme initialized');
        },

        /**
         * Window load handler
         */
        onWindowLoad: function() {
            // Hide loading spinner if exists
            $('.loading-spinner').fadeOut();
        },

        /**
         * Window resize handler
         */
        onWindowResize: function() {
            // Handle responsive behavior
            this.handleResponsive();
        },

        /**
         * Initialize quantity inputs
         */
        initQuantityInputs: function() {
            $(document).on('click', '.quantity-btn', function(e) {
                e.preventDefault();
                
                const $btn = $(this);
                const action = $btn.data('action');
                const $input = $btn.siblings('.quantity-input');
                const currentValue = parseInt($input.val()) || 0;
                const min = parseInt($input.attr('min')) || 1;
                const max = parseInt($input.attr('max')) || 999;
                
                let newValue = currentValue;
                
                if (action === 'plus') {
                    newValue = Math.min(currentValue + 1, max);
                } else if (action === 'minus') {
                    newValue = Math.max(currentValue - 1, min);
                }
                
                if (newValue !== currentValue) {
                    $input.val(newValue).trigger('change');
                    
                    // Update cart if on cart page
                    if (typeof wc_add_to_cart_params !== 'undefined') {
                        AstraChild.updateCartItem($input);
                    }
                }
            });

            // Handle direct input changes
            $(document).on('change', '.quantity-input', function() {
                if (typeof wc_add_to_cart_params !== 'undefined') {
                    AstraChild.updateCartItem($(this));
                }
            });
        },

        /**
         * Update cart item
         */
        updateCartItem: function($input) {
            const $form = $input.closest('form');
            const $submitBtn = $form.find('button[name="update_cart"]');
            
            if ($submitBtn.length) {
                $submitBtn.prop('disabled', false);
                $submitBtn.addClass('updating');
                
                // Auto-submit after delay
                clearTimeout(this.updateTimeout);
                this.updateTimeout = setTimeout(function() {
                    $form.submit();
                }, 1000);
            }
        },

        /**
         * Initialize lazy loading
         */
        initLazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            const src = img.dataset.src;
                            
                            if (src) {
                                img.src = src;
                                img.classList.remove('lazy');
                                imageObserver.unobserve(img);
                            }
                        }
                    });
                });

                document.querySelectorAll('img[data-src]').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        },

        /**
         * Initialize smooth scroll
         */
        initSmoothScroll: function() {
            $('a[href^="#"]').on('click', function(e) {
                const href = $(this).attr('href');
                
                if (href !== '#') {
                    e.preventDefault();
                    
                    const $target = $(href);
                    if ($target.length) {
                        $('html, body').animate({
                            scrollTop: $target.offset().top - 100
                        }, 600);
                    }
                }
            });
        },

        /**
         * Initialize mobile menu
         */
        initMobileMenu: function() {
            $('.mobile-menu-toggle').on('click', function(e) {
                e.preventDefault();
                
                const $menu = $('.mobile-menu');
                const $body = $('body');
                
                $menu.toggleClass('active');
                $body.toggleClass('menu-open');
                
                // Prevent body scroll when menu is open
                if ($menu.hasClass('active')) {
                    $body.css('overflow', 'hidden');
                } else {
                    $body.css('overflow', '');
                }
            });

            // Close menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.mobile-menu, .mobile-menu-toggle').length) {
                    $('.mobile-menu').removeClass('active');
                    $('body').removeClass('menu-open').css('overflow', '');
                }
            });
        },

        /**
         * Handle responsive behavior
         */
        handleResponsive: function() {
            const width = $(window).width();
            
            // Handle mobile menu
            if (width > 768) {
                $('.mobile-menu').removeClass('active');
                $('body').removeClass('menu-open').css('overflow', '');
            }
        },

        /**
         * Show notification
         */
        showNotification: function(message, type = 'info') {
            const $notification = $(`
                <div class="notification notification-${type}">
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close">&times;</button>
                    </div>
                </div>
            `);

            $('body').append($notification);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            }, 5000);

            // Manual close
            $notification.find('.notification-close').on('click', function() {
                $notification.fadeOut(() => {
                    $notification.remove();
                });
            });
        },

        /**
         * AJAX request helper
         */
        ajax: function(action, data, callback) {
            const requestData = {
                action: action,
                nonce: astraChild.nonce,
                ...data
            };

            $.post(astraChild.ajaxUrl, requestData, function(response) {
                if (callback) {
                    callback(response);
                }
            }).fail(function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                AstraChild.showNotification('Произошла ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Format currency
         */
        formatCurrency: function(amount, currency = '₽') {
            return new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'RUB',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(amount);
        },

        /**
         * Debounce function
         */
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function() {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },

        /**
         * Throttle function
         */
        throttle: function(func, limit) {
            let inThrottle;
            return function() {
                const args = arguments;
                const context = this;
                if (!inThrottle) {
                    func.apply(context, args);
                    inThrottle = true;
                    setTimeout(() => inThrottle = false, limit);
                }
            };
        }
    };

    // Initialize
    AstraChild.init();

    // Make available globally
    window.AstraChild = AstraChild;

})(jQuery);