<?php
/**
 * Plugin Name: Alean Loyalty API Integration
 * Description: Интеграция с бонусной системой Alean
 * Version: 1.0
 * Author: Alean
 */

defined('ABSPATH') or die('Direct access forbidden!');

// Основные API endpoints
add_action('rest_api_init', function() {
    // Получение данных о баллах
    register_rest_route('alean/v1', '/bonus', [
        'methods' => 'GET',
        'callback' => 'alean_get_bonus_data',
        'permission_callback' => 'is_user_logged_in',
        'args' => [
            'email' => [
                'required' => false,
                'validate_callback' => 'is_email'
            ]
        ]
    ]);

    // Списание баллов
    register_rest_route('alean/v1', '/spend-points', [
        'methods' => 'POST',
        'callback' => 'alean_spend_points',
        'permission_callback' => 'alean_verify_request',
        'args' => [
            'email' => [
                'required' => true,
                'validate_callback' => 'is_email'
            ],
            'amount' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_numeric($param) && $param > 0;
                }
            ],
            'order_id' => [
                'required' => true,
                'validate_callback' => 'is_numeric'
            ]
        ]
    ]);
});

/**
 * Проверка запроса на списание
 */
function alean_verify_request(WP_REST_Request $request) {

    if (!is_user_logged_in()) {
        return false;
    }


    $nonce = $request->get_header('X-WP-Nonce');
    if (!wp_verify_nonce($nonce, 'wp_rest')) {
        return false;
    }


    $order = wc_get_order($request->get_param('order_id'));
    if (!$order || $order->get_customer_id() != get_current_user_id()) {
        return false;
    }

    return true;
}

/**
 * Получение данных о баллах
 */
function alean_get_bonus_data(WP_REST_Request $request) {
    $email = $request->get_param('email');
    $user = wp_get_current_user();
    
    if (empty($email)) {
        $email = $user->user_email;
    }


    alean_log_access($user->ID, 'balance_check');

    $response = wp_remote_post(
        'https://n8n.alean.ru/webhook/get-lp-email',
        [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['email' => $email]),
            'timeout' => 15
        ]
    );

    if (is_wp_error($response)) {
        return new WP_REST_Response([
            'lp-status' => 'FALSE',
            'message' => 'API connection error'
        ], 500);
    }

    return new WP_REST_Response(json_decode(wp_remote_retrieve_body($response), true), 200);
}

/**
 * Списание баллов
 */
function alean_spend_points(WP_REST_Request $request) {
    $params = $request->get_params();
    $user = wp_get_current_user();

    // Дополнительная проверка баланса
    $balance = alean_get_user_balance($user->user_email);
    if ($balance < $params['amount']) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Недостаточно баллов'
        ], 400);
    }

    $response = wp_remote_post(
        'https://n8n.alean.ru/webhook/lp-spending',
        [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'email' => $params['email'],
                'sum' => $params['amount'],
                'eventexternalid' => 'order_'.$params['order_id'],
                'comment' => 'Оплата заказа #'.$params['order_id']
            ]),
            'timeout' => 15
        ]
    );

    if (is_wp_error($response)) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'API connection error'
        ], 500);
    }


    alean_log_access($user->ID, 'points_spent', [
        'amount' => $params['amount'],
        'order_id' => $params['order_id']
    ]);

    return new WP_REST_Response(json_decode(wp_remote_retrieve_body($response), true), 200);
}


function alean_log_access($user_id, $action, $meta = []) {
    $log = [
        'time' => current_time('mysql'),
        'user_id' => $user_id,
        'action' => $action,
        'ip' => $_SERVER['REMOTE_ADDR']
    ];

    if (!empty($meta)) {
        $log['meta'] = $meta;
    }

    file_put_contents(
        WP_CONTENT_DIR . '/alean-access.log',
        json_encode($log) . PHP_EOL,
        FILE_APPEND
    );
}


add_filter('rest_pre_serve_request', function($value) {
    header('Access-Control-Allow-Origin: ' . get_site_url());
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type, X-WP-Nonce');
    return $value;
});