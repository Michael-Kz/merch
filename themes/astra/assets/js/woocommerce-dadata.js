/**
 * Official DaData Suggestions Integration
 * @docs https://dadata.ru/api/suggest/address/
 */
jQuery(function($) {
    const $form = $('form.checkout');
    const $searchField = $('#billing_dadata_search');
    const $hiddenData = $('<input>').attr({
        type: 'hidden',
        name: 'billing_dadata_data',
        id: 'billing_dadata_data'
    }).appendTo($form);

    // Инициализация согласно документации
    $searchField.suggestions({
        token: dadataSettings.api_key,
        type: dadataSettings.type,
        count: dadataSettings.count,
        
        /* Параметры геолокации */
        locations: dadataSettings.locations,
        bounds: dadataSettings.bounds,
        
        /* Ограничения из документации */
        constraints: dadataSettings.constraints,
        from_bound: dadataSettings.from_bound,
        to_bound: dadataSettings.to_bound,
        
        /* Форматирование */
        formatSelected: function(suggestion) {
            return suggestion.value;
        },
        
        /* Обработчики */
        onSelect: function(suggestion) {
            // Сохраняем полный ответ DaData
            $hiddenData.val(JSON.stringify(suggestion.data));
            
            // Заполняем поля WooCommerce
            fillWooCommerceFields(suggestion.data);
            
            // Обновляем чекаут
            $form.trigger('update_checkout');
        },
        
        onRequestError: function(error) {
            console.error('DaData API Error:', error);
            $form.addClass('dadata-error');
        }
    });

    function fillWooCommerceFields(data) {
        // Основные поля согласно структуре ответа DaData
        $('#billing_address_1').val([
            data.street_type || '',
            data.street || '',
            data.house_type || '',
            data.house || ''
        ].filter(Boolean).join(' ')).trigger('change');

        $('#billing_city').val(data.city || data.settlement || '').trigger('change');
        $('#billing_postcode').val(data.postal_code || '').trigger('change');
        
        // Для международных адресов
        if (data.country) {
            $('#billing_country').val(data.country_iso_code || data.country).trigger('change');
        }
    }
});