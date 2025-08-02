/**
 * Admin JavaScript for Astra Child Theme
 * 
 * @package Astra Child
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Admin object
    const AstraChildAdmin = {
        
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
                AstraChildAdmin.onDocumentReady();
            });
        },

        /**
         * Initialize components
         */
        initComponents: function() {
            this.initLoyaltyManagement();
            this.initUserPoints();
            this.initSettings();
            this.initModals();
        },

        /**
         * Document ready handler
         */
        onDocumentReady: function() {
            console.log('Astra Child Admin initialized');
        },

        /**
         * Initialize loyalty management
         */
        initLoyaltyManagement: function() {
            // Add points to user
            $(document).on('click', '.add-points-btn', function(e) {
                e.preventDefault();
                AstraChildAdmin.showAddPointsModal($(this).data('user-id'));
            });

            // Remove points from user
            $(document).on('click', '.remove-points-btn', function(e) {
                e.preventDefault();
                AstraChildAdmin.showRemovePointsModal($(this).data('user-id'));
            });

            // Bulk actions
            $(document).on('click', '.bulk-add-points', function(e) {
                e.preventDefault();
                AstraChildAdmin.showBulkAddPointsModal();
            });

            // Export loyalty data
            $(document).on('click', '.export-loyalty-data', function(e) {
                e.preventDefault();
                AstraChildAdmin.exportLoyaltyData();
            });
        },

        /**
         * Initialize user points
         */
        initUserPoints: function() {
            // Quick edit points
            $(document).on('click', '.quick-edit-points', function(e) {
                e.preventDefault();
                const userId = $(this).data('user-id');
                const currentPoints = $(this).data('points');
                AstraChildAdmin.showQuickEditModal(userId, currentPoints);
            });

            // Save quick edit
            $(document).on('click', '.save-quick-edit', function(e) {
                e.preventDefault();
                AstraChildAdmin.saveQuickEdit();
            });
        },

        /**
         * Initialize settings
         */
        initSettings: function() {
            // Save settings
            $(document).on('click', '.save-loyalty-settings', function(e) {
                e.preventDefault();
                AstraChildAdmin.saveSettings();
            });

            // Test API connection
            $(document).on('click', '.test-api-connection', function(e) {
                e.preventDefault();
                AstraChildAdmin.testApiConnection();
            });

            // Reset settings
            $(document).on('click', '.reset-loyalty-settings', function(e) {
                e.preventDefault();
                if (confirm('Вы уверены, что хотите сбросить все настройки?')) {
                    AstraChildAdmin.resetSettings();
                }
            });
        },

        /**
         * Initialize modals
         */
        initModals: function() {
            // Close modal on background click
            $(document).on('click', '.loyalty-modal', function(e) {
                if (e.target === this) {
                    AstraChildAdmin.closeModal();
                }
            });

            // Close modal on close button
            $(document).on('click', '.loyalty-modal-close', function(e) {
                e.preventDefault();
                AstraChildAdmin.closeModal();
            });

            // Close modal on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $('.loyalty-modal.active').length) {
                    AstraChildAdmin.closeModal();
                }
            });
        },

        /**
         * Show add points modal
         */
        showAddPointsModal: function(userId) {
            const modalHtml = `
                <div class="loyalty-modal active">
                    <div class="loyalty-modal-content">
                        <div class="loyalty-modal-header">
                            <h3>Добавить баллы пользователю</h3>
                            <button class="loyalty-modal-close">&times;</button>
                        </div>
                        <div class="loyalty-modal-body">
                            <form class="add-points-form">
                                <input type="hidden" name="user_id" value="${userId}">
                                <div class="form-row">
                                    <label for="points_amount">Количество баллов:</label>
                                    <input type="number" id="points_amount" name="points_amount" min="1" required>
                                </div>
                                <div class="form-row">
                                    <label for="points_reason">Причина:</label>
                                    <textarea id="points_reason" name="points_reason" rows="3" placeholder="Укажите причину начисления баллов"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="loyalty-modal-footer">
                            <button class="loyalty-button-secondary loyalty-modal-close">Отмена</button>
                            <button class="loyalty-button add-points-submit">Добавить баллы</button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);

            // Handle form submission
            $(document).on('click', '.add-points-submit', function(e) {
                e.preventDefault();
                AstraChildAdmin.addPoints();
            });
        },

        /**
         * Show remove points modal
         */
        showRemovePointsModal: function(userId) {
            const modalHtml = `
                <div class="loyalty-modal active">
                    <div class="loyalty-modal-content">
                        <div class="loyalty-modal-header">
                            <h3>Списать баллы у пользователя</h3>
                            <button class="loyalty-modal-close">&times;</button>
                        </div>
                        <div class="loyalty-modal-body">
                            <form class="remove-points-form">
                                <input type="hidden" name="user_id" value="${userId}">
                                <div class="form-row">
                                    <label for="points_amount">Количество баллов:</label>
                                    <input type="number" id="points_amount" name="points_amount" min="1" required>
                                </div>
                                <div class="form-row">
                                    <label for="points_reason">Причина:</label>
                                    <textarea id="points_reason" name="points_reason" rows="3" placeholder="Укажите причину списания баллов"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="loyalty-modal-footer">
                            <button class="loyalty-button-secondary loyalty-modal-close">Отмена</button>
                            <button class="loyalty-button loyalty-button-danger remove-points-submit">Списать баллы</button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);

            // Handle form submission
            $(document).on('click', '.remove-points-submit', function(e) {
                e.preventDefault();
                AstraChildAdmin.removePoints();
            });
        },

        /**
         * Show bulk add points modal
         */
        showBulkAddPointsModal: function() {
            const selectedUsers = $('input[name="users[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedUsers.length === 0) {
                AstraChildAdmin.showNotice('Выберите пользователей для начисления баллов', 'error');
                return;
            }

            const modalHtml = `
                <div class="loyalty-modal active">
                    <div class="loyalty-modal-content">
                        <div class="loyalty-modal-header">
                            <h3>Массовое начисление баллов</h3>
                            <button class="loyalty-modal-close">&times;</button>
                        </div>
                        <div class="loyalty-modal-body">
                            <p>Выбрано пользователей: <strong>${selectedUsers.length}</strong></p>
                            <form class="bulk-add-points-form">
                                <input type="hidden" name="user_ids" value="${selectedUsers.join(',')}">
                                <div class="form-row">
                                    <label for="points_amount">Количество баллов:</label>
                                    <input type="number" id="points_amount" name="points_amount" min="1" required>
                                </div>
                                <div class="form-row">
                                    <label for="points_reason">Причина:</label>
                                    <textarea id="points_reason" name="points_reason" rows="3" placeholder="Укажите причину начисления баллов"></textarea>
                                </div>
                            </form>
                        </div>
                        <div class="loyalty-modal-footer">
                            <button class="loyalty-button-secondary loyalty-modal-close">Отмена</button>
                            <button class="loyalty-button bulk-add-points-submit">Начислить баллы</button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);

            // Handle form submission
            $(document).on('click', '.bulk-add-points-submit', function(e) {
                e.preventDefault();
                AstraChildAdmin.bulkAddPoints();
            });
        },

        /**
         * Show quick edit modal
         */
        showQuickEditModal: function(userId, currentPoints) {
            const modalHtml = `
                <div class="loyalty-modal active">
                    <div class="loyalty-modal-content">
                        <div class="loyalty-modal-header">
                            <h3>Быстрое редактирование баллов</h3>
                            <button class="loyalty-modal-close">&times;</button>
                        </div>
                        <div class="loyalty-modal-body">
                            <form class="quick-edit-form">
                                <input type="hidden" name="user_id" value="${userId}">
                                <div class="form-row">
                                    <label for="points_amount">Количество баллов:</label>
                                    <input type="number" id="points_amount" name="points_amount" value="${currentPoints}" min="0" required>
                                </div>
                            </form>
                        </div>
                        <div class="loyalty-modal-footer">
                            <button class="loyalty-button-secondary loyalty-modal-close">Отмена</button>
                            <button class="loyalty-button save-quick-edit">Сохранить</button>
                        </div>
                    </div>
                </div>
            `;

            $('body').append(modalHtml);
        },

        /**
         * Add points to user
         */
        addPoints: function() {
            const formData = $('.add-points-form').serialize();
            
            $.post(ajaxurl, {
                action: 'add_user_points',
                nonce: astraChildAdmin.nonce,
                ...this.serializeFormToObject('.add-points-form')
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('Баллы успешно добавлены', 'success');
                    AstraChildAdmin.closeModal();
                    location.reload();
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при добавлении баллов', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Remove points from user
         */
        removePoints: function() {
            $.post(ajaxurl, {
                action: 'remove_user_points',
                nonce: astraChildAdmin.nonce,
                ...this.serializeFormToObject('.remove-points-form')
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('Баллы успешно списаны', 'success');
                    AstraChildAdmin.closeModal();
                    location.reload();
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при списании баллов', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Bulk add points
         */
        bulkAddPoints: function() {
            $.post(ajaxurl, {
                action: 'bulk_add_user_points',
                nonce: astraChildAdmin.nonce,
                ...this.serializeFormToObject('.bulk-add-points-form')
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice(`Баллы успешно добавлены ${response.data.count} пользователям`, 'success');
                    AstraChildAdmin.closeModal();
                    location.reload();
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при добавлении баллов', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Save quick edit
         */
        saveQuickEdit: function() {
            $.post(ajaxurl, {
                action: 'save_user_points',
                nonce: astraChildAdmin.nonce,
                ...this.serializeFormToObject('.quick-edit-form')
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('Баллы успешно обновлены', 'success');
                    AstraChildAdmin.closeModal();
                    location.reload();
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при обновлении баллов', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Save settings
         */
        saveSettings: function() {
            const formData = $('.loyalty-settings-form').serialize();
            
            $.post(ajaxurl, {
                action: 'save_loyalty_settings',
                nonce: astraChildAdmin.nonce,
                form_data: formData
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('Настройки успешно сохранены', 'success');
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при сохранении настроек', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Test API connection
         */
        testApiConnection: function() {
            const $btn = $('.test-api-connection');
            const originalText = $btn.text();
            
            $btn.prop('disabled', true).text('Тестирование...');
            
            $.post(ajaxurl, {
                action: 'test_loyalty_api',
                nonce: astraChildAdmin.nonce
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('API соединение успешно', 'success');
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка API соединения', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при тестировании API', 'error');
            }).always(function() {
                $btn.prop('disabled', false).text(originalText);
            });
        },

        /**
         * Reset settings
         */
        resetSettings: function() {
            $.post(ajaxurl, {
                action: 'reset_loyalty_settings',
                nonce: astraChildAdmin.nonce
            }, function(response) {
                if (response.success) {
                    AstraChildAdmin.showNotice('Настройки сброшены', 'success');
                    location.reload();
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при сбросе настроек', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            });
        },

        /**
         * Export loyalty data
         */
        exportLoyaltyData: function() {
            const $btn = $('.export-loyalty-data');
            const originalText = $btn.text();
            
            $btn.prop('disabled', true).text('Экспорт...');
            
            $.post(ajaxurl, {
                action: 'export_loyalty_data',
                nonce: astraChildAdmin.nonce
            }, function(response) {
                if (response.success) {
                    // Create download link
                    const link = document.createElement('a');
                    link.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(response.data);
                    link.download = 'loyalty_data_' + new Date().toISOString().split('T')[0] + '.csv';
                    link.click();
                    
                    AstraChildAdmin.showNotice('Данные успешно экспортированы', 'success');
                } else {
                    AstraChildAdmin.showNotice(response.data || 'Ошибка при экспорте данных', 'error');
                }
            }).fail(function() {
                AstraChildAdmin.showNotice('Ошибка при выполнении запроса', 'error');
            }).always(function() {
                $btn.prop('disabled', false).text(originalText);
            });
        },

        /**
         * Close modal
         */
        closeModal: function() {
            $('.loyalty-modal').remove();
        },

        /**
         * Show notice
         */
        showNotice: function(message, type = 'info') {
            const noticeHtml = `
                <div class="loyalty-notice ${type}">
                    <p>${message}</p>
                </div>
            `;

            $('.wrap h1').after(noticeHtml);
            
            // Auto-hide after 5 seconds
            setTimeout(function() {
                $('.loyalty-notice').fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },

        /**
         * Serialize form to object
         */
        serializeFormToObject: function(selector) {
            const form = $(selector);
            const data = {};
            
            form.find('input, select, textarea').each(function() {
                const $field = $(this);
                const name = $field.attr('name');
                const value = $field.val();
                
                if (name && value !== undefined) {
                    data[name] = value;
                }
            });
            
            return data;
        }
    };

    // Initialize
    AstraChildAdmin.init();

    // Make available globally
    window.AstraChildAdmin = AstraChildAdmin;

})(jQuery);