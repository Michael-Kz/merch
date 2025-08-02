<?php
function limit_login_attempts() {
    $max_attempts = 3; // Максимальное количество попыток
    $lockout_time = 60 * 10; // Время блокировки в секундах (10 минут)
    
    if (isset($_POST['log']) && isset($_POST['pwd'])) {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $attempts = get_transient('login_attempts_' . $ip_address);

        if ($attempts >= $max_attempts) {
            $remaining = get_transient('lockout_time_' . $ip_address);
            if ($remaining) {
                wp_die('Превышено количество попыток входа. Пожалуйста, попробуйте позже.');
            } else {
                set_transient('lockout_time_' . $ip_address, true, $lockout_time);
            }
        } else {
            // Если попытка не удалась
            if (!is_wp_error(wp_signon())) {
                delete_transient('login_attempts_' . $ip_address);
            } else {
                set_transient('login_attempts_' . $ip_address, $attempts + 1, $lockout_time);
            }
        }
    }
}
add_action('wp_login_failed', 'limit_login_attempts');