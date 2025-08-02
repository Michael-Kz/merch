<?php
/**
 * Plugin Name: Alean SSO интеграция
 * Description: Интеграция с SSO-авторизацией Alean
 * Version: 1.2
 * Author: Alean
 */

defined('ABSPATH') or die('Direct access forbidden!');

// Конфигурация SSO
define('SSO_CLIENT_ID', 'web_store');
define('SSO_CLIENT_SECRET', 'w7fs707ha6iqmid74wrm6gq7hvj51hqhvwnzbl6bowwukudxn06w53ebi59lkeqitty2wkqm7f0xlgdjcn1aombe1soawlft4wrm6gq7hvj51hqhvwnzbl6b');
define('SSO_AUTHORIZE_URL', 'https://sso-test.alean.ru/auth/connect/authorize');
define('SSO_TOKEN_URL', 'https://sso-test.alean.ru/auth/connect/token');
define('SSO_USERINFO_URL', 'https://sso-test.alean.ru/auth/connect/userinfo');
define('SSO_LOGOUT_URL', 'https://sso-test.alean.ru/auth/connect/endsession');
define('SSO_REDIRECT_URI', home_url('/wp-login.php?action=sso-callback'));
define('SSO_POST_LOGOUT_REDIRECT_URI', home_url('/'));
define('SSO_DEBUG', true);

// Инициализация сессии
add_action('init', function() {
    if (!session_id() && !headers_sent()) {
        session_start();
    }
}, 1);


add_action('init', function() {
    if (isset($_GET['sso_login'])) {
        $code_verifier = bin2hex(random_bytes(32));
        $code_challenge = rtrim(strtr(base64_encode(hash('sha256', $code_verifier, true)), '+/', '-_'), '=');
        $state = bin2hex(random_bytes(16));

        $_SESSION['sso_code_verifier'] = $code_verifier;
        $_SESSION['sso_state'] = $state;

        $params = [
            'client_id' => SSO_CLIENT_ID,
            'response_type' => 'code',
            'scope' => 'openid profile offline_access IdentityServerApi',
            'redirect_uri' => SSO_REDIRECT_URI,
            'state' => $state,
            'code_challenge' => $code_challenge,
            'code_challenge_method' => 'S256',
        ];

        wp_redirect(SSO_AUTHORIZE_URL . '?' . http_build_query($params));
        exit;
    }
});


add_action('init', function() {
    if (isset($_GET['code']) && isset($_GET['state'])) {
        if (empty($_SESSION['sso_state']) || $_GET['state'] !== $_SESSION['sso_state']) {
            wp_die('Ошибка безопасности: неверный state');
        }

        $response = wp_remote_post(SSO_TOKEN_URL, [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(SSO_CLIENT_ID . ':' . SSO_CLIENT_SECRET),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => [
                'grant_type' => 'authorization_code',
                'code' => $_GET['code'],
                'redirect_uri' => SSO_REDIRECT_URI,
                'client_id' => SSO_CLIENT_ID,
                'code_verifier' => $_SESSION['sso_code_verifier'],
            ],
        ]);

        if (is_wp_error($response)) {
            wp_die('Ошибка при запросе токена: ' . $response->get_error_message());
        }

        $tokens = json_decode(wp_remote_retrieve_body($response), true);
        if (isset($tokens['error'])) {
            wp_die('Ошибка SSO: ' . $tokens['error_description']);
        }

        $userinfo = wp_remote_get(SSO_USERINFO_URL, [
            'headers' => ['Authorization' => 'Bearer ' . $tokens['access_token']]
        ]);

        $user_data = json_decode(wp_remote_retrieve_body($userinfo), true);
        $email = $user_data['email'] ?? ($user_data['preferred_username'] . '@alean.ru');

        if (empty($email)) {
            wp_die('SSO не предоставил email пользователя.');
        }

        $user = get_user_by('email', $email);
        if (!$user) {
            $user_id = wp_create_user(
                sanitize_user($email),
                wp_generate_password(),
                $email
            );
            
            if (is_wp_error($user_id)) {
                wp_die('Ошибка создания пользователя: ' . $user_id->get_error_message());
            }
            
            $user = get_user_by('id', $user_id);
        }

        wp_set_auth_cookie($user->ID);
        unset($_SESSION['sso_code_verifier'], $_SESSION['sso_state']);
        
       
        $bonus_response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['email' => $email])
        ]);
        
        if (!is_wp_error($bonus_response)) {
            $bonus_data = json_decode(wp_remote_retrieve_body($bonus_response), true);
            if ($bonus_data['lp-status'] !== 'TRUE') {
                setcookie('bonus_register_url', 
                    'https://bonus.alean.ru/register?email=' . urlencode($email), 
                    0, '/', '', true, true
                );
            }
        }
        
        wp_redirect(home_url());
        exit;
    }
});

// Выход из системы
add_action('wp_logout', function() {
    wp_redirect(SSO_LOGOUT_URL . '?post_logout_redirect_uri=' . urlencode(SSO_POST_LOGOUT_REDIRECT_URI));
    exit;
});


add_shortcode('sso_login', function() {
    return '<a href="' . esc_url(home_url('/?sso_login=1')) . '" class="sso-login-button">Войти через SSO</a>';
});