<?php
/**
 * Alean Loyalty API Class
 * 
 * @package Alean Loyalty Payment
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Класс для работы с API системы лояльности
 */
class Alean_Loyalty_API {
    
    /**
     * API URL
     */
    private $api_url;
    
    /**
     * API Key
     */
    private $api_key;
    
    /**
     * Конструктор
     */
    public function __construct() {
        $this->api_url = get_option('alean_loyalty_api_url', 'https://n8n.alean.ru/webhook/');
        $this->api_key = get_option('alean_loyalty_api_key', '');
    }
    
    /**
     * Получить баланс пользователя
     */
    public function get_user_balance($email) {
        $response = wp_remote_post($this->api_url . 'get-lp-email', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->api_key ? 'Bearer ' . $this->api_key : '',
            ],
            'body' => json_encode(['email' => $email]),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            Alean_Loyalty_Payment_Plugin::log("API Error: " . $response->get_error_message());
            return ['lp-status' => 'FALSE', 'total_points' => 0];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data) {
            Alean_Loyalty_Payment_Plugin::log("API Response parsing failed: " . $body);
            return ['lp-status' => 'FALSE', 'total_points' => 0];
        }

        return $data;
    }
    
    /**
     * Списать баллы
     */
    public function spend_points($email, $amount, $order_id, $comment) {
        $response = wp_remote_post($this->api_url . 'spend-points', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->api_key ? 'Bearer ' . $this->api_key : '',
            ],
            'body' => json_encode([
                'email' => $email,
                'amount' => $amount,
                'order_id' => $order_id,
                'comment' => $comment,
            ]),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            Alean_Loyalty_Payment_Plugin::log("Spend points API Error: " . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data) {
            Alean_Loyalty_Payment_Plugin::log("Spend points API Response parsing failed: " . $body);
            return false;
        }

        // Проверяем успешность операции
        if (isset($data['success']) && $data['success']) {
            Alean_Loyalty_Payment_Plugin::log("Points spent successfully: Email: {$email}, Amount: {$amount}, Order: {$order_id}");
            return true;
        } else {
            $error = isset($data['error']) ? $data['error'] : 'Unknown error';
            Alean_Loyalty_Payment_Plugin::log("Points spend failed: Email: {$email}, Amount: {$amount}, Error: {$error}");
            return false;
        }
    }
    
    /**
     * Тестирование API соединения
     */
    public function test_connection() {
        $test_email = 'test@example.com';
        $response = wp_remote_post($this->api_url . 'get-lp-email', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->api_key ? 'Bearer ' . $this->api_key : '',
            ],
            'body' => json_encode(['email' => $test_email]),
            'timeout' => 10,
        ]);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => 'Ошибка соединения: ' . $response->get_error_message(),
            ];
        }

        $status_code = wp_remote_retrieve_response_code($response);
        
        if ($status_code === 200) {
            return [
                'success' => true,
                'message' => 'API соединение успешно',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'API вернул статус: ' . $status_code,
            ];
        }
    }
    
    /**
     * Получить историю операций
     */
    public function get_transaction_history($email, $limit = 10) {
        $response = wp_remote_post($this->api_url . 'get-transactions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->api_key ? 'Bearer ' . $this->api_key : '',
            ],
            'body' => json_encode([
                'email' => $email,
                'limit' => $limit,
            ]),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            Alean_Loyalty_Payment_Plugin::log("Get transactions API Error: " . $response->get_error_message());
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || !isset($data['transactions'])) {
            return [];
        }

        return $data['transactions'];
    }
    
    /**
     * Начислить баллы
     */
    public function add_points($email, $amount, $reason) {
        $response = wp_remote_post($this->api_url . 'add-points', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => $this->api_key ? 'Bearer ' . $this->api_key : '',
            ],
            'body' => json_encode([
                'email' => $email,
                'amount' => $amount,
                'reason' => $reason,
            ]),
            'timeout' => 30,
        ]);

        if (is_wp_error($response)) {
            Alean_Loyalty_Payment_Plugin::log("Add points API Error: " . $response->get_error_message());
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data) {
            Alean_Loyalty_Payment_Plugin::log("Add points API Response parsing failed: " . $body);
            return false;
        }

        if (isset($data['success']) && $data['success']) {
            Alean_Loyalty_Payment_Plugin::log("Points added successfully: Email: {$email}, Amount: {$amount}, Reason: {$reason}");
            return true;
        } else {
            $error = isset($data['error']) ? $data['error'] : 'Unknown error';
            Alean_Loyalty_Payment_Plugin::log("Points add failed: Email: {$email}, Amount: {$amount}, Error: {$error}");
            return false;
        }
    }
}