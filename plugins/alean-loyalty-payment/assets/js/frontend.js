/**
 * Alean Loyalty Payment Frontend JavaScript
 * 
 * @package Alean Loyalty Payment
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var AleanLoyalty = {
        
        init: function() {
            this.bindEvents();
            this.initLoyaltyDisplay();
        },
        
        bindEvents: function() {
            $(document).on('change', 'input[name="payment_method"]', this.onPaymentMethodChange);
            $(document).on('click', '.loyalty-balance-check', this.checkLoyaltyBalance);
        },
        
        initLoyaltyDisplay: function() {
            if (aleanLoyalty.isLoggedIn) {
                this.updateLoyaltyDisplay();
            }
        },
        
        onPaymentMethodChange: function() {
            var selectedMethod = $('input[name="payment_method"]:checked').val();
            
            if (selectedMethod === 'points_payment' || selectedMethod === 'alean_loyalty') {
                AleanLoyalty.updateLoyaltyDisplay();
            }
        },
        
        checkLoyaltyBalance: function(e) {
            e.preventDefault();
            
            if (!aleanLoyalty.isLoggedIn) {
                alert('Для проверки баланса необходимо войти в систему');
                return;
            }
            
            var $button = $(this);
            var originalText = $button.text();
            
            $button.prop('disabled', true).text('Проверка...');
            
            $.ajax({
                url: aleanLoyalty.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'get_loyalty_data',
                    nonce: aleanLoyalty.nonce
                },
                success: function(response) {
                    if (response.success) {
                        AleanLoyalty.showLoyaltyBalance(response.data);
                    } else {
                        alert('Ошибка получения данных: ' + response.data);
                    }
                },
                error: function() {
                    alert('Ошибка при выполнении запроса');
                },
                complete: function() {
                    $button.prop('disabled', false).text(originalText);
                }
            });
        },
        
        updateLoyaltyDisplay: function() {
            if (!aleanLoyalty.isLoggedIn) {
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
                        AleanLoyalty.showLoyaltyBalance(response.data);
                    }
                }
            });
        },
        
        showLoyaltyBalance: function(data) {
            var balance = data.total_points || 0;
            var status = data['lp-status'] || 'FALSE';
            
            var $loyaltyInfo = $('.loyalty-payment-info, .alean-loyalty-payment-info');
            
            if ($loyaltyInfo.length === 0) {
                // Создаем блок информации если его нет
                var infoHtml = '<div class="loyalty-payment-info">' +
                    '<h4>Информация о баллах</h4>' +
                    '<p>Статус программы: <strong>' + (status === 'TRUE' ? 'Активна' : 'Неактивна') + '</strong></p>' +
                    '<p>Ваш баланс: <strong>' + balance + '</strong> баллов</p>' +
                    '</div>';
                
                $('.woocommerce-checkout-payment').before(infoHtml);
            } else {
                // Обновляем существующий блок
                $loyaltyInfo.find('p:last').html('Ваш баланс: <strong>' + balance + '</strong> баллов');
            }
            
            // Проверяем достаточно ли баллов для оплаты
            var cartTotal = parseFloat($('.order-total .amount').text().replace(/[^\d.,]/g, '').replace(',', '.'));
            
            if (balance >= cartTotal && status === 'TRUE') {
                $loyaltyInfo.addClass('sufficient-balance').removeClass('insufficient-balance');
            } else {
                $loyaltyInfo.addClass('insufficient-balance').removeClass('sufficient-balance');
            }
        },
        
        formatPoints: function(points) {
            return points.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
    };

    // Инициализация при загрузке документа
    $(document).ready(function() {
        AleanLoyalty.init();
    });

    // Обновление при изменении корзины
    $(document.body).on('updated_checkout', function() {
        AleanLoyalty.initLoyaltyDisplay();
    });

})(jQuery);