( function( $ ) {
    'use strict';
    
    $( document ).ready( function() {
        // ������������� ��� �������� � ����� ���������� �������
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
                    
                    // ����������� �� ������
                    constraints: dadata_params.country_restriction ? {
                        locations: { country: dadata_params.country_restriction }
                    } : null,
                    
                    // ��������� �����������
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
        
        // ���������� ����� WooCommerce
        function fillAddressFields(data) {
            // �������� ����
            $('#billing_address_1').val(data.street_with_type || '').trigger('change');
            $('#billing_address_2').val(data.house || '').trigger('change');
            $('#billing_city').val(data.city || data.settlement_with_type || '').trigger('change');
            $('#billing_state').val(data.region_with_type || '').trigger('change');
            $('#billing_postcode').val(data.postal_code || '').trigger('change');
            
            // ������ (���� ����������)
            if (data.country) {
                $('#billing_country').val('RU').trigger('change');
            }
            
            // ��� ��������� ����� (���� �����)
            $(document.body).trigger('dadata_address_selected', [data]);
        }
        
        // ������������� ��� ��������
        initDadata();
        
        // ��������� ������������� ����� ���������� �������
        $(document.body).on('updated_checkout', function() {
            initDadata();
        });
    });
} )( jQuery );