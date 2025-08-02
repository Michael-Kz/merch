/**
 * Alean Loyalty Payment Admin JavaScript
 * 
 * @package Alean Loyalty Payment
 * @since 1.0.0
 */

(function($) {
    'use strict';

    var AleanLoyaltyAdmin = {
        
        init: function() {
            this.bindEvents();
            this.initTabs();
        },
        
        bindEvents: function() {
            $('#test-api').on('click', this.testApiConnection);
            $('.nav-tab').on('click', this.switchTab);
        },
        
        initTabs: function() {
            // Показываем первую вкладку по умолчанию
            $('.tab-content').hide();
            $('#settings').show();
        },
        
        switchTab: function(e) {
            e.preventDefault();
            
            var target = $(this).attr('href');
            
            // Убираем активный класс со всех вкладок
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Скрываем все контенты
            $('.tab-content').hide();
            $(target).show();
        },
        
        testApiConnection: function() {
            var $button = $(this);
            var $results = $('#test-results');
            
            $button.prop('disabled', true).text('Тестирование...');
            $results.html('<p>Выполняется тестирование API...</p>');
            
            $.ajax({
                url: aleanLoyaltyAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'test_alean_api',
                    nonce: aleanLoyaltyAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $results.html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                    } else {
                        $results.html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    $results.html('<div class="notice notice-error"><p>Ошибка при выполнении запроса</p></div>');
                },
                complete: function() {
                    $button.prop('disabled', false).text('Тестировать API');
                }
            });
        }
    };

    // Инициализация при загрузке документа
    $(document).ready(function() {
        AleanLoyaltyAdmin.init();
    });

})(jQuery);