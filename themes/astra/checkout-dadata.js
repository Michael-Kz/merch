( function( $ ) {
    'use strict';
    
    $( document ).ready( function() {
        // Инициализация при загрузке и после обновления чекаута
        function initDadata() {
            var $addressField = $('#billing_dadata_address');
            
            if (!$addressField.length || !dadata_params.api_key) return;
            
            try {
                $addressField.suggestions({
                    token: dadata_params.api_key,
                    type: 'ADDRESS',
                    count: 5,
                    language: dadata_params.language,
                    bounds: 'city-settlement',
                    restrictValue: true,
                    
                    // Ограничение по стране
                    constraints: dadata_params.country_restriction ? {
                        locations: { country: dadata_params.country_restriction }
                    } : null,
                    
                    // Параметры отображения
                    formatSelected: function(suggestion) {
                        return suggestion.value;
                    },
                    
                    onSelect: function(suggestion) {
                        fillAddressFields(suggestion.data);
                    },
                    
                    onSelectNothing: function() {
                        console.warn(dadata_params.i18n.no_suggestions);
                    }
                });
            } catch (e) {
                console.error('DaData Error:', e);
            }
        }
        
        // Заполнение полей WooCommerce
        function fillAddressFields(data) {
            // Основные поля
            $('#billing_address_1').val(data.street_with_type || '').trigger('change');
            $('#billing_address_2').val(data.house || '').trigger('change');
            $('#billing_city').val(data.city || data.settlement_with_type || '').trigger('change');
            $('#billing_state').val(data.region_with_type || '').trigger('change');
            $('#billing_postcode').val(data.postal_code || '').trigger('change');
            
            // Страна (если определена)
            if (data.country) {
                $('#billing_country').val('RU').trigger('change');
            }
            
            // Для кастомных полей (если нужно)
            $(document.body).trigger('dadata_address_selected', [data]);
        }
        
        // Инициализация при загрузке
        initDadata();
        
        // Повторная инициализация после обновления чекаута
        $(document.body).on('updated_checkout', function() {
            initDadata();
        });
    });
} )( jQuery );