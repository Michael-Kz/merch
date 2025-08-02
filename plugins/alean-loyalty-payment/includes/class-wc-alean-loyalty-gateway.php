<?php
/**
 * WC Gateway Alean Loyalty
 * 
 * @package Alean Loyalty Payment
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Платежный шлюз для Alean Loyalty
 */
class WC_Gateway_Alean_Loyalty extends WC_Payment_Gateway {
    
    /**
     * Конструктор
     */
    public function __construct() {
        $this->id = 'alean_loyalty';
        $this->icon = '';
        $this->has_fields = false;
        $this->method_title = 'Alean Loyalty';
        $this->method_description = 'Оплата баллами лояльности Alean';
        
        $this->supports = array(
            'products',
            'refunds',
        );
        
        $this->init_form_fields();
        $this->init_settings();
        
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->api_url = $this->get_option('api_url');
        
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }
    
    /**
     * Инициализация полей формы
     */
    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Включить/Выключить',
                'type' => 'checkbox',
                'label' => 'Включить Alean Loyalty',
                'default' => 'yes',
            ),
            'title' => array(
                'title' => 'Заголовок',
                'type' => 'text',
                'description' => 'Заголовок метода оплаты',
                'default' => 'Alean Loyalty',
                'desc_tip' => true,
            ),
            'description' => array(
                'title' => 'Описание',
                'type' => 'textarea',
                'description' => 'Описание метода оплаты',
                'default' => 'Оплатите заказ баллами лояльности Alean',
                'desc_tip' => true,
            ),
            'api_url' => array(
                'title' => 'API URL',
                'type' => 'url',
                'description' => 'URL API для получения данных лояльности',
                'default' => 'https://n8n.alean.ru/webhook/',
                'desc_tip' => true,
            ),
        );
    }
    
    /**
     * Поля формы оплаты
     */
    public function payment_fields() {
        if (!is_user_logged_in()) {
            echo '<p>Для оплаты баллами необходимо войти в систему.</p>';
            return;
        }
        
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $email = $user->user_email;
        
        // Получение баланса баллов
        $api = new Alean_Loyalty_API();
        $balance_data = $api->get_user_balance($email);
        
        if ($balance_data['lp-status'] !== 'TRUE') {
            echo '<p>Бонусная программа недоступна.</p>';
            return;
        }
        
        $total = WC()->cart->get_total('edit');
        $available_points = $balance_data['total_points'];
        
        echo '<div class="alean-loyalty-payment-info">';
        echo '<p>Ваш баланс баллов Alean: <strong>' . esc_html($available_points) . '</strong></p>';
        echo '<p>Стоимость заказа: <strong>' . esc_html($total) . '</strong></p>';
        
        if ($available_points < $total) {
            echo '<p class="error">Недостаточно баллов для оплаты заказа.</p>';
        } else {
            echo '<p class="success">Достаточно баллов для оплаты заказа.</p>';
        }
        echo '</div>';
    }
    
    /**
     * Валидация полей
     */
    public function validate_fields() {
        if (!is_user_logged_in()) {
            wc_add_notice('Для оплаты баллами необходимо войти в систему.', 'error');
            return false;
        }
        
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $email = $user->user_email;
        
        $api = new Alean_Loyalty_API();
        $balance_data = $api->get_user_balance($email);
        $total = WC()->cart->get_total('edit');
        
        if ($balance_data['lp-status'] !== 'TRUE' || $balance_data['total_points'] < $total) {
            wc_add_notice('Недостаточно баллов для оплаты заказа.', 'error');
            return false;
        }
        
        return true;
    }
    
    /**
     * Обработка платежа
     */
    public function process_payment($order_id) {
        $order = wc_get_order($order_id);
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $email = $user->user_email;
        $total = $order->get_total();
        
        try {
            // 1. Ставим заказ в ожидание
            $order->update_status('pending', __('Ожидание списания баллов Alean', 'alean-loyalty-payment'));
            
            // 2. Проверяем баланс
            $api = new Alean_Loyalty_API();
            $balance_data = $api->get_user_balance($email);
            
            if ($balance_data['lp-status'] !== 'TRUE' || $balance_data['total_points'] < $total) {
                throw new Exception(__('Недостаточно бонусных баллов Alean', 'alean-loyalty-payment'));
            }
            
            // 3. Списание баллов
            $response = $api->spend_points(
                $email,
                $total,
                'order_' . $order_id,
                'Оплата заказа #' . $order_id . ' (Alean Loyalty)'
            );
            
            if (!$response) {
                throw new Exception(__('Ошибка при списании баллов Alean', 'alean-loyalty-payment'));
            }
            
            // 4. Подтверждаем оплату
            $order->payment_complete();
            $order->add_order_note(__('Оплачено баллами Alean. Списано: ', 'alean-loyalty-payment') . $total);
            
            // 5. Очистка корзины
            WC()->cart->empty_cart();
            
            // Логируем успешную операцию
            Alean_Loyalty_Payment_Plugin::log("Successful Alean Loyalty payment: Order #{$order_id}, Amount: {$total}, User: {$email}");
            
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order),
            );
            
        } catch (Exception $e) {
            // Логируем ошибку
            Alean_Loyalty_Payment_Plugin::log("Alean Loyalty payment failed: Order #{$order_id}, Error: " . $e->getMessage());
            
            $order->update_status('failed', $e->getMessage());
            wc_add_notice($e->getMessage(), 'error');
            return false;
        }
    }
}