<?php
/**
 * Astra Child Theme Functions
 *
 * @package Astra Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Enqueue parent and child theme styles
 */
function astra_child_enqueue_styles() {
	// Родительская тема стили
	wp_enqueue_style( 'astra-parent-style', get_template_directory_uri() . '/style.css' );
	
	// Дочерняя тема стили
	wp_enqueue_style( 'astra-child-style', get_stylesheet_directory_uri() . '/style.css', array( 'astra-parent-style' ), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles' );

/**
 * Подключаем дополнительные файлы
 */
require_once get_stylesheet_directory() . '/inc/class-theme-assets.php';
require_once get_stylesheet_directory() . '/inc/class-cart-template.php';

// =============================
// КОПИРУЕМ ВСЮ ФУНКЦИОНАЛЬНОСТЬ ИЗ РОДИТЕЛЬСКОЙ ТЕМЫ
// =============================

// Удаляем цену в валюте, оставляем только баллы
add_filter('woocommerce_get_price_html', 'replace_money_with_points', 10, 2);
function replace_money_with_points($price, $product) {
    return wc_get_price_to_display($product) . ' <img src="https://test-merch.alean.ru/wp-content/uploads/2025/05/android-icon-192x192-1.png" width="17" alt="Логотип" class="logo">';
}

// Добавляем колонку "Баллы" в админку пользователей
add_filter('manage_users_columns', 'add_user_points_column');
function add_user_points_column($columns) {
    $columns['user_points'] = 'Баллы';
    return $columns;
}

// Заполняем колонку данными
add_action('manage_users_custom_column', 'fill_user_points_column', 10, 3);
function fill_user_points_column($value, $column_name, $user_id) {
    if ($column_name == 'user_points') {
        return get_user_meta($user_id, 'user_points', true) ?: '0';
    }
    return $value;
}

// Заменяем символ валюты на картинку
add_filter('woocommerce_currency_symbol', 'custom_currency_symbol_image', 10, 2);
function custom_currency_symbol_image($currency_symbol, $currency) {
    if ($currency === 'RUB' || $currency === 'USD' || $currency === 'EUR') {
        return '<img src="/wp-content/uploads/2025/05/android-icon-192x192-1.png" alt="Баллы" class="custom-currency-icon" width="20" height="20" />';
    }
    return $currency_symbol;
}

// AJAX обработчики для лояльности
add_action('wp_ajax_get_loyalty_data', 'get_loyalty_data_callback');
add_action('wp_ajax_nopriv_get_loyalty_data', 'auth_required');

function get_loyalty_data_callback() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Требуется авторизация', 401);
    }

    $user = wp_get_current_user();
    $response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode(['email' => $user->user_email])
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message(), 500);
    }

    wp_send_json(json_decode(wp_remote_retrieve_body($response), true));
}

function auth_required() {
    wp_send_json_error('Требуется авторизация', 401);
}

// CORS заголовки
add_filter('rest_pre_serve_request', function($value) {
    header('Access-Control-Allow-Origin: ' . get_site_url());
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, X-WP-Nonce');
    return $value;
});

// Кэш заголовки
add_filter('rest_post_dispatch', function($response) {
    $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    return $response;
});

// Обработчик списания бонусов (тестовый режим)
function handle_bonus_deduction(WP_REST_Request $request) {
    $user = wp_get_current_user();
    $amount = $request->get_param('amount');
    $reason = $request->get_param('reason');
    
    // Тестовый режим - не списываем реально, только логируем
    $log_entry = sprintf(
        "[%s] Тестовое списание: %s, сумма: %d, причина: %s",
        current_time('mysql'),
        $user->user_email,
        $amount,
        $reason
    );
    
    // Логируем в файл (для теста)
    file_put_contents(
        WP_CONTENT_DIR . '/bonus-deductions.log',
        $log_entry . PHP_EOL,
        FILE_APPEND
    );
    
    // Получаем текущие баллы (имитация)
    $current_points = get_user_meta($user->ID, 'alean_bonus_points', true) ?: 100;
    
    // Проверяем достаточно ли баллов
    if ($current_points < $amount) {
        return new WP_Error(
            'insufficient_funds',
            'Недостаточно бонусных баллов',
            ['status' => 400]
        );
    }
    
    // Имитируем списание
    $new_balance = $current_points - $amount;
    update_user_meta($user->ID, 'alean_bonus_points', $new_balance);
    
    return new WP_REST_Response([
        'success' => true,
        'new_balance' => $new_balance,
        'spent' => $amount
    ], 200);
}

// Добавляем валюту Alean
add_filter('woocommerce_currencies', 'add_alean_currency');
function add_alean_currency($currencies) {
    $currencies['ALEAN'] = __('Alean Points', 'woocommerce');
    return $currencies;
}

// Символ валюты Alean
add_filter('woocommerce_currency_symbol', 'alean_currency_symbol', 10, 2);
function alean_currency_symbol($symbol, $currency) {
    if ($currency === 'ALEAN') {
        return '<img src="/wp-content/uploads/2025/05/android-icon-192x192-1.png" alt="Alean Points" width="20" height="20" />';
    }
    return $symbol;
}

// Получение баланса пользователя
function alean_get_user_balance($email) {
    $response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode(['email' => $email])
    ]);

    if (is_wp_error($response)) {
        return ['lp-status' => 'FALSE', 'total_points' => 0];
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    return isset($data['total_points']) ? (float)$data['total_points'] : 0;
}

// Класс для интеграции с WooCommerce
class Alean_Loyalty_WooCommerce {
    public function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        add_action('woocommerce_review_order_before_payment', [$this, 'check_loyalty_points']);
        add_action('woocommerce_checkout_order_processed', [$this, 'process_loyalty_payment'], 10, 3);
        add_action('woocommerce_account_dashboard', [$this, 'show_loyalty_balance']);
    }

    public function check_loyalty_points() {
        if (!is_user_logged_in()) return;

        $user = wp_get_current_user();
        $response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['email' => $user->user_email])
        ]);

        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            $balance = $response['total_points'];
            echo '<div class="loyalty-points-balance">';
            echo '<h3>Ваши баллы лояльности</h3>';
            echo '<p>Доступно баллов: <strong>' . esc_html($balance) . '</strong></p>';
            echo '</div>';
        }
    }

    public function process_loyalty_payment($order_id, $posted_data, $order) {
        if ($order->get_payment_method() !== 'alean_loyalty') return;

        $user = $order->get_user();
        $email = $user->user_email;
        $total = $order->get_total();

        $lp_data = $this->get_lp_data($email);

        if ($lp_data['lp-status'] !== 'TRUE' || $lp_data['total_points'] < $total) {
            $order->update_status('failed', 'Недостаточно баллов лояльности');
            return;
        }

        $result = $this->spend_loyalty_points($email, $total, $order_id, 'Order payment');
        if ($result) {
            $order->payment_complete();
            $order->add_order_note('Оплачено баллами лояльности: ' . $total);
        }
    }

    public function show_loyalty_balance() {
        if (!is_user_logged_in()) return;

        $user = wp_get_current_user();
        $balance = alean_get_user_balance($user->user_email);

        echo '<div class="loyalty-dashboard-widget">';
        echo '<h3>Баллы лояльности</h3>';
        echo '<p>Ваш баланс: <strong>' . esc_html($balance) . '</strong> баллов</p>';
        echo '</div>';
    }

    private function get_lp_data($email) {
        $response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['email' => $email])
        ]);

        if (is_wp_error($response)) {
            return ['lp-status' => 'FALSE', 'total_points' => 0];
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    private function spend_loyalty_points($email, $sum, $order_id, $comment) {
        $response = wp_remote_post('https://n8n.alean.ru/webhook/spend-points', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode([
                'email' => $email,
                'amount' => $sum,
                'order_id' => $order_id,
                'comment' => $comment
            ])
        ]);

        return !is_wp_error($response);
    }
}

// Инициализация класса лояльности
new Alean_Loyalty_WooCommerce();

// AJAX обработчики
add_action('wp_ajax_alean_get_bonus_data', 'alean_get_bonus_data_ajax');
add_action('wp_ajax_nopriv_alean_get_bonus_data', 'alean_auth_required');

function alean_get_bonus_data_ajax() {
    if (!is_user_logged_in()) {
        wp_send_json_error('Требуется авторизация');
        return;
    }

    $user = wp_get_current_user();
    $response = wp_remote_post('https://n8n.alean.ru/webhook/get-lp-email', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode(['email' => $user->user_email])
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error('Ошибка получения данных');
        return;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    wp_send_json_success($data);
}

function alean_auth_required() {
    wp_send_json_error('Требуется авторизация');
}

// Настройки темы
add_action('admin_menu', 'custom_theme_settings_page');
function custom_theme_settings_page() {
    add_menu_page(
        'Настройки темы',
        'Настройки темы',
        'manage_options',
        'theme-settings',
        'render_theme_settings_page',
        'dashicons-admin-generic',
        60
    );
}

add_action('admin_init', 'register_theme_settings');
function register_theme_settings() {
    register_setting('theme_settings', 'theme_logo');
    register_setting('theme_settings', 'theme_contacts');
    register_setting('theme_settings', 'theme_social');
    
    add_settings_section(
        'theme_general_section',
        'Общие настройки',
        null,
        'theme-settings'
    );
    
    add_settings_field(
        'theme_logo',
        'Логотип',
        'render_logo_meta_box',
        'theme-settings',
        'theme_general_section'
    );
    
    add_settings_field(
        'theme_contacts',
        'Контакты',
        'render_contacts_meta_box',
        'theme-settings',
        'theme_general_section'
    );
    
    add_settings_field(
        'theme_social',
        'Социальные сети',
        'render_social_meta_box',
        'theme-settings',
        'theme_general_section'
    );
}

function render_theme_settings_page() {
    ?>
    <div class="wrap">
        <h1>Настройки темы</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_settings');
            do_settings_sections('theme-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function render_logo_meta_box() {
    $logo = get_option('theme_logo');
    ?>
    <input type="text" name="theme_logo" value="<?php echo esc_attr($logo); ?>" class="regular-text" />
    <p class="description">URL логотипа сайта</p>
    <?php
}

function render_contacts_meta_box() {
    $contacts = get_option('theme_contacts');
    ?>
    <textarea name="theme_contacts" rows="5" cols="50"><?php echo esc_textarea($contacts); ?></textarea>
    <p class="description">Контактная информация</p>
    <?php
}

function render_social_meta_box() {
    $social = get_option('theme_social');
    ?>
    <textarea name="theme_social" rows="5" cols="50"><?php echo esc_textarea($social); ?></textarea>
    <p class="description">Ссылки на социальные сети</p>
    <?php
}

// Добавление favicon
add_action('wp_head', 'add_favicon_to_head');
function add_favicon_to_head() {
    $favicon = get_option('theme_logo');
    if ($favicon) {
        echo '<link rel="icon" type="image/x-icon" href="' . esc_url($favicon) . '">';
    }
}

// Подключение медиа загрузчика
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');
function enqueue_media_uploader() {
    wp_enqueue_media();
}

// Шорткод авторизации
add_shortcode('alean_auth', 'alean_auth_shortcode');
function alean_auth_shortcode($atts) {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        $balance = alean_get_user_balance($user->user_email);
        
        ob_start();
        ?>
        <div class="alean-auth-widget">
            <div class="user-info">
                <div class="user-avatar">
                    <?php
                    function get_initials($full_name) {
                        $words = explode(' ', $full_name);
                        $initials = '';
                        foreach ($words as $word) {
                            $initials .= mb_substr($word, 0, 1, 'UTF-8');
                        }
                        return mb_strtoupper($initials, 'UTF-8');
                    }
                    
                    $initials = get_initials($user->display_name);
                    ?>
                    <div class="avatar-circle">
                        <span class="initials"><?php echo esc_html($initials); ?></span>
                    </div>
                </div>
                <div class="user-details">
                    <h3><?php echo esc_html($user->display_name); ?></h3>
                    <p class="user-email"><?php echo esc_html($user->user_email); ?></p>
                    <p class="user-balance">Баланс: <strong><?php echo esc_html($balance); ?></strong> баллов</p>
                </div>
            </div>
            <div class="auth-actions">
                <a href="<?php echo esc_url(wp_logout_url()); ?>" class="logout-btn">Выйти</a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    } else {
        ob_start();
        ?>
        <div class="alean-auth-widget">
            <p>Для просмотра баллов необходимо войти в систему.</p>
            <a href="<?php echo esc_url(wp_login_url()); ?>" class="login-btn">Войти</a>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Регистрация меню пользователя
add_action('init', 'register_user_menu');
function register_user_menu() {
    register_nav_menu('user-menu', 'Меню пользователя');
}

// Кастомные стили
add_action('wp_head', 'my_custom_styles');
function my_custom_styles() {
    ?>
    <style>
        .alean-auth-widget {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .avatar-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #007cba;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        
        .user-details h3 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .user-email {
            color: #666;
            margin: 0 0 5px 0;
        }
        
        .user-balance {
            color: #007cba;
            font-weight: bold;
            margin: 0;
        }
        
        .auth-actions {
            margin-top: 15px;
        }
        
        .login-btn, .logout-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007cba;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .login-btn:hover, .logout-btn:hover {
            background: #005a87;
            color: white;
        }
        
        .custom-currency-icon {
            vertical-align: middle;
            margin-right: 5px;
        }
        
        .loyalty-points-balance {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #007cba;
        }
        
        .loyalty-points-balance h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .loyalty-dashboard-widget {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .loyalty-dashboard-widget h3 {
            margin: 0 0 15px 0;
            color: #333;
            border-bottom: 2px solid #007cba;
            padding-bottom: 10px;
        }
    </style>
    <?php
}

// Кастомизация блоков
add_filter('render_block', 'add_custom_class_to_specific_heading', 10, 2);
function add_custom_class_to_specific_heading($block_content, $block) {
    if ($block['blockName'] === 'core/heading') {
        $block_content = str_replace(
            '<h1',
            '<h1 class="custom-heading"',
            $block_content
        );
        $block_content = str_replace(
            '<h2',
            '<h2 class="custom-heading"',
            $block_content
        );
        $block_content = str_replace(
            '<h3',
            '<h3 class="custom-heading"',
            $block_content
        );
    }
    return $block_content;
}

// Добавление поля телефона в корзину
add_filter('woocommerce_checkout_fields', 'add_phone_field_to_checkout');
function add_phone_field_to_checkout($fields) {
    $fields['billing']['cart_phone'] = array(
        'label' => 'Телефон',
        'required' => true,
        'class' => array('form-row-wide'),
        'clear' => true,
        'priority' => 25
    );
    return $fields;
}

// Сохранение телефона из корзины
add_action('woocommerce_checkout_update_order_meta', 'save_cart_phone');
function save_cart_phone($order_id) {
    if (!empty($_POST['cart_phone'])) {
        update_post_meta($order_id, '_cart_phone', sanitize_text_field($_POST['cart_phone']));
    }
}

// Перенос телефона в корзину
add_filter('woocommerce_checkout_get_value', 'transfer_phone_to_checkout', 10, 2);
function transfer_phone_to_checkout($value, $input) {
    if ($input === 'cart_phone' && !empty($_COOKIE['cart_phone'])) {
        return sanitize_text_field($_COOKIE['cart_phone']);
    }
    return $value;
}

// Шорткод партнерских преимуществ
add_shortcode('partners_benefits', 'partners_benefits_shortcode');
function partners_benefits_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Партнерские преимущества',
        'benefits' => 'Быстрая доставка, Гарантия качества, Поддержка 24/7'
    ), $atts);
    
    $benefits_array = explode(',', $atts['benefits']);
    
    ob_start();
    ?>
    <div class="partners-benefits">
        <h2><?php echo esc_html($atts['title']); ?></h2>
        <div class="benefits-grid">
            <?php foreach ($benefits_array as $benefit): ?>
                <div class="benefit-item">
                    <div class="benefit-icon">✓</div>
                    <div class="benefit-text"><?php echo esc_html(trim($benefit)); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <style>
        .partners-benefits {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 8px;
            margin: 30px 0;
        }
        
        .partners-benefits h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        
        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .benefit-icon {
            width: 30px;
            height: 30px;
            background: #007cba;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .benefit-text {
            font-weight: 500;
            color: #333;
        }
    </style>
    <?php
    return ob_get_clean();
}

// Подключение Swiper
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');
function enqueue_swiper_assets() {
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), null, true);
}

// Инициализация Swiper слайдера
add_action('wp_footer', 'init_swiper_slider');
function init_swiper_slider() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiperContainers = document.querySelectorAll('.swiper-container');
        swiperContainers.forEach(function(container) {
            new Swiper(container, {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                pagination: {
                    el: container.querySelector('.swiper-pagination'),
                    clickable: true,
                },
                navigation: {
                    nextEl: container.querySelector('.swiper-button-next'),
                    prevEl: container.querySelector('.swiper-button-prev'),
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    }
                }
            });
        });
    });
    </script>
    <?php
}

// Функции безопасности
function secure_output($data, $type = 'html') {
    switch ($type) {
        case 'html':
            return wp_kses_post($data);
        case 'url':
            return esc_url($data);
        case 'attr':
            return esc_attr($data);
        case 'js':
            return esc_js($data);
        default:
            return sanitize_text_field($data);
    }
}

function safe_echo($value, $type = 'html') {
    echo secure_output($value, $type);
}

// Улучшение безопасности паролей
add_action('wp_login', 'enhance_password_security');
function enhance_password_security($user_login) {
    $user = get_user_by('login', $user_login);
    if ($user && !wp_check_password($user_login, $user->user_pass)) {
        wp_clear_auth_cookie();
        wp_die('Неверный пароль');
    }
}

// Безопасность административной области
add_action('admin_init', 'secure_admin_area');
function secure_admin_area() {
    if (is_admin() && !current_user_can('manage_options')) {
        wp_die('Доступ запрещен');
    }
}

// Добавление nonce к публичным формам
add_action('wp_footer', 'add_nonce_to_public_forms');
function add_nonce_to_public_forms() {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            if (!form.querySelector('input[name="_wpnonce"]')) {
                const nonceField = document.createElement('input');
                nonceField.type = 'hidden';
                nonceField.name = '_wpnonce';
                nonceField.value = '<?php echo wp_create_nonce('public_form'); ?>';
                form.appendChild(nonceField);
            }
        });
    });
    </script>
    <?php
}

// Безопасные заголовки
add_action('send_headers', 'add_security_headers');
function add_security_headers() {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Скрытие путей WordPress
add_action('init', 'hide_wp_paths');
function hide_wp_paths() {
    if (isset($_GET['author'])) {
        wp_redirect(home_url());
        exit;
    }
}

// Отключение RSS лент
add_action('init', 'wpcourses_disable_feed');
function wpcourses_disable_feed() {
    wp_die('RSS ленты отключены');
}

// Удаление отступа админ-бара
add_action('wp_head', 'remove_admin_bar_margin');
function remove_admin_bar_margin() {
    if (is_admin_bar_showing()) {
        echo '<style>body { margin-top: 0 !important; }</style>';
    }
}

// Отключение эмодзи в TinyMCE
add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');
function disable_wp_emojis_in_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    }
    return array();
}

// Удаление поля URL из комментариев
add_filter('comment_form_default_fields', 'unset_url_field');
function unset_url_field($fields) {
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    return $fields;
}

// Удаление версий скриптов
add_filter('script_loader_src', 'remove_script_version', 15, 1);
function remove_script_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}

// Отключение неиспользуемых REST API эндпоинтов
add_filter('rest_endpoints', 'disable_unused_rest_endpoints');
function disable_unused_rest_endpoints($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    return $endpoints;
}

// Предотвращение листинга директорий
add_action('init', 'prevent_directory_listing');
function prevent_directory_listing() {
    if (is_dir($_SERVER['REQUEST_URI'])) {
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
}

// Удаление виджетов дашборда
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
function remove_dashboard_widgets() {
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
}

// Проверка enum
add_filter('wp_redirect', 'shapeSpace_check_enum', 10, 2);
function shapeSpace_check_enum($redirect, $request) {
    if (preg_match('/\?author=([0-9]*)/i', $request)) {
        wp_die('Enumeration attack detected');
    }
    return $redirect;
}

// Ограничение heartbeat
add_filter('heartbeat_settings', 'limit_heartbeat');
function limit_heartbeat($settings) {
    $settings['interval'] = 60;
    return $settings;
}

// Удаление ненужных заголовков
add_action('send_headers', 'remove_unnecessary_headers');
function remove_unnecessary_headers() {
    header_remove('X-Pingback');
    header_remove('X-Powered-By');
}

// Очистка head
add_action('wp_head', 'clean_head', 1);
function clean_head() {
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}