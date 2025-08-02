<?php
/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'ASTRA_THEME_VERSION', '4.10.1' );
define( 'ASTRA_THEME_SETTINGS', 'astra-settings' );
define( 'ASTRA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'ASTRA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'ASTRA_THEME_ORG_VERSION', file_exists( ASTRA_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the Astra Pro addon.
 * This constant will be used to display the notice asking user to update the Astra addon to the version defined below.
 */
define( 'ASTRA_EXT_MIN_VER', '4.10.0' );

/**
 * Load in-house compatibility.
 */
if ( ASTRA_THEME_ORG_VERSION ) {
	require_once ASTRA_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of Astra.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-theme-options.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once ASTRA_THEME_DIR . 'inc/core/common-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-icons.php';

define( 'ASTRA_WEBSITE_BASE_URL', 'https://wpastra.com' );

/**
 * ToDo: Deprecate constants in future versions as they are no longer used in the codebase.
 */
define( 'ASTRA_PRO_UPGRADE_URL', ASTRA_THEME_ORG_VERSION ? astra_get_pro_url( '/pricing/', 'free-theme', 'dashboard', 'upgrade' ) : 'https://woocommerce.com/products/astra-pro/' );
define( 'ASTRA_PRO_CUSTOMIZER_UPGRADE_URL', ASTRA_THEME_ORG_VERSION ? astra_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'upgrade' ) : 'https://woocommerce.com/products/astra-pro/' );

/**
 * Update theme
 */
require_once ASTRA_THEME_DIR . 'inc/theme-update/astra-update-functions.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-font-families.php';
if ( is_admin() ) {
	require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts-data.php';
}

require_once ASTRA_THEME_DIR . 'inc/lib/webfont/class-astra-webfont-loader.php';
require_once ASTRA_THEME_DIR . 'inc/lib/docs/class-astra-docs-loader.php';
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts.php';

require_once ASTRA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/astra-icons.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-walker-page.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-enqueue-scripts.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-wp-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-dynamic-css.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'ASTRA_SITES_VER' ) || version_compare( ASTRA_SITES_VER, '4.3.7', '<' ) || version_compare( ASTRA_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-notice.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-attr.php';
require_once ASTRA_THEME_DIR . 'inc/template-tags.php';

require_once ASTRA_THEME_DIR . 'inc/widgets.php';
require_once ASTRA_THEME_DIR . 'inc/core/theme-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/admin-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once ASTRA_THEME_DIR . 'inc/markup-extras.php';
require_once ASTRA_THEME_DIR . 'inc/extras.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog-config.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog.php';
require_once ASTRA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once ASTRA_THEME_DIR . 'inc/template-parts.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-loop.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once ASTRA_THEME_DIR . 'inc/class-astra-after-setup-theme.php';

// Required files.
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-helper.php';

require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-schema.php';

/* Setup API */
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-settings.php';
	require_once ASTRA_THEME_DIR . 'admin/class-astra-admin-loader.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/astra-notices/class-astra-notices.php';
}

/**
 * Metabox additions.
 */
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-boxes.php';

require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-box-operations.php';

/**
 * Customizer additions.
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer.php';

/**
 * Astra Modules.
 */
require_once ASTRA_THEME_DIR . 'inc/modules/posts-structures/class-astra-post-structures.php';
require_once ASTRA_THEME_DIR . 'inc/modules/related-posts/class-astra-related-posts.php';

/**
 * Compatibility
 */
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gutenberg.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-jetpack.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/class-astra-woocommerce.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/edd/class-astra-edd.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/lifterlms/class-astra-lifterlms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/learndash/class-astra-learndash.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bb-ultimate-addon.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-contact-form-7.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-visual-composer.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-site-origin.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gravity-forms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bne-flyout.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-ubermeu.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-divi-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-amp.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-yoast-seo.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/surecart/class-astra-surecart.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-starter-content.php';
require_once ASTRA_THEME_DIR . 'inc/addons/transparent-header/class-astra-ext-transparent-header.php';
require_once ASTRA_THEME_DIR . 'inc/addons/breadcrumbs/class-astra-breadcrumbs.php';
require_once ASTRA_THEME_DIR . 'inc/addons/scroll-to-top/class-astra-scroll-to-top.php';
require_once ASTRA_THEME_DIR . 'inc/addons/heading-colors/class-astra-heading-colors.php';
require_once ASTRA_THEME_DIR . 'inc/builder/class-astra-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor-pro.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-themer.php';
}

require_once ASTRA_THEME_DIR . 'inc/core/markup/class-astra-markup.php';

/**
 * Load deprecated functions
 */
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';




// Удаляем цену в валюте, оставляем только баллы
add_filter('woocommerce_get_price_html', 'replace_money_with_points', 10, 2);
function replace_money_with_points($price, $product) {
    return wc_get_price_to_display($product) . ' <img src="https://test-merch.alean.ru/wp-content/uploads/2025/05/android-icon-192x192-1.png" width="17" alt="Логотип" class="logo">';
}
// Добавляем виртуальный метод оплаты баллами
add_filter('woocommerce_payment_gateways', 'add_points_payment_gateway');
function add_points_payment_gateway($gateways) {
    $gateways[] = 'WC_Points_Payment_Gateway';
    return $gateways;
}

// Создаем класс платежного шлюза
add_action('plugins_loaded', 'init_points_payment_gateway');
function init_points_payment_gateway() {
    class WC_Points_Payment_Gateway extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'points_payment';
            $this->title = 'Оплата баллами';
            $this->method_title = 'Баллы';
            $this->method_description = 'Оплата заказа баллами.';
            $this->supports = array('products');
            $this->init_form_fields();
            $this->init_settings();
        }

public function process_payment($order_id) {
    $order = wc_get_order($order_id);
    $user = $order->get_user();
    $email = $order->get_billing_email();
    $total = $order->get_total();
    
    try {
        // 1. Ставим заказ в ожидание
        $order->update_status('pending', __('Ожидание списания баллов', 'textdomain'));
        
        // 2. Проверяем баланс
        $balance_data = $this->get_lp_data($email);
        if ($balance_data['lp-status'] !== 'TRUE' || $balance_data['total_points'] < $total) {
            throw new Exception(__('Недостаточно бонусных баллов', 'textdomain'));
        }
        
        // 3. Списание баллов
        $response = $this->spend_points(
            $email,
            $total,
            'order_' . $order_id,
            'Оплата заказа #' . $order_id
        );
        
        if (isset($response['error'])) {
            throw new Exception($response['message']);
        }
        
        // 4. Подтверждаем оплату
        $order->payment_complete();
        $order->add_order_note(__('Оплачено баллами. Списано: ', 'textdomain') . $total);
        
        // 5. Очистка корзины
        WC()->cart->empty_cart();
        
        return array(
            'result' => 'success',
            'redirect' => $this->get_return_url($order)
        );
        
    } catch (Exception $e) {
        // Возврат баллов при ошибке (если API позволяет)
        // $this->refund_points(...);
        
        $order->update_status('failed', $e->getMessage());
        wc_add_notice($e->getMessage(), 'error');
        return false;
    }
}
    }
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
    if ($currency === 'RUB' || $currency === 'USD' || $currency === 'EUR') { // Укажите вашу валюту
        return '<img src="/wp-content/uploads/2025/05/android-icon-192x192-1.png" alt="Баллы" class="custom-currency-icon" width="20" height="20" />';
    }
    return $currency_symbol;
} 








// =============================

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



add_filter('rest_pre_serve_request', function($value) {
    header('Access-Control-Allow-Origin: ' . get_site_url());
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, X-WP-Nonce');
    return $value;
});





add_filter('rest_post_dispatch', function($response) {
  $response->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
  return $response;
});



/**
 * Обработчик списания бонусов (тестовый режим)
 */
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
    $current_points = get_user_meta($user->ID, 'alean_bonus_points', true) ?: 100; // Тестовое значение
    
    // Проверяем достаточно ли баллов
    if ($current_points < $amount) {
        return new WP_Error(
            'insufficient_funds',
            'Недостаточно бонусных баллов',
            ['status' => 400]
        );
    }
    
    // Имитация списания (в тестовом режиме не изменяем реальный баланс)
    $new_balance = $current_points - $amount;
    
    return [
        'status' => 'success',
        'message' => 'Тестовое списание выполнено',
        'details' => [
            'user_id' => $user->ID,
            'user_email' => $user->user_email,
            'amount_deducted' => $amount,
            'new_balance' => $new_balance,
            'reason' => $reason,
            'mode' => 'test' // Режим тестирования
        ]
    ];
}



// Устанавливаем валюту "баллы" (без символа)
add_filter('woocommerce_currencies', 'add_alean_currency');
function add_alean_currency($currencies) {
    $currencies['BAL'] = 'Баллы';
    return $currencies;
}

// Убираем символ валюты (чтобы было просто "100 баллов")
add_filter('woocommerce_currency_symbol', 'alean_currency_symbol', 10, 2);
function alean_currency_symbol($symbol, $currency) {
    return ($currency == 'BAL') ? '' : $symbol;
}

// Принудительно устанавливаем валюту магазина
update_option('woocommerce_currency', 'BAL');






// Платежный шлюз баллами
add_action('plugins_loaded', 'init_alean_loyalty_gateway');
function init_alean_loyalty_gateway() {
    if (!class_exists('WC_Payment_Gateway')) return;
    
    class WC_Gateway_Alean_Loyalty extends WC_Payment_Gateway {
        
        public function __construct() {
            $this->id = 'alean_loyalty';
            $this->method_title = 'Оплата баллами';
            $this->method_description = 'Безопасная оплата бонусными баллами';
            $this->has_fields = true;
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
        }
        
        public function init_form_fields() {
            $this->form_fields = [
                'enabled' => [
                    'title' => 'Включить',
                    'type' => 'checkbox',
                    'label' => 'Включить оплату баллами',
                    'default' => 'yes'
                ],
                'title' => [
                    'title' => 'Название',
                    'type' => 'text',
                    'default' => 'Оплата баллами'
                ],
                'max_amount' => [
                    'title' => 'Макс. сумма',
                    'type' => 'number',
                    'description' => 'Максимальная сумма для оплаты баллами',
                    'default' => 5000
                ]
            ];
        }
        
        public function payment_fields() {
            if (!is_user_logged_in()) {
                echo '<p>Требуется авторизация</p>';
                return;
            }
            
            $user = wp_get_current_user();
            $balance = alean_get_user_balance($user->user_email);
            $cart_total = WC()->cart->total;
            
            echo '<div class="alean-balance-info">';
            echo '<p>Доступно баллов: <strong>' . $balance . '</strong></p>';
            echo '<p>Сумма заказа: <strong>' . wc_price($cart_total) . '</strong></p>';
            
            if ($balance >= $cart_total) {
                echo '<p class="success">Можно оплатить баллами</p>';
            } else {
                echo '<p class="error">Недостаточно баллов</p>';
            }
            
            echo '</div>';
        }
        
        public function validate_fields() {
            if (!is_user_logged_in()) {
                wc_add_notice('Требуется авторизация', 'error');
                return false;
            }
            
            $user = wp_get_current_user();
            $cart_total = WC()->cart->total;
            $balance = alean_get_user_balance($user->user_email);
            
            if ($balance < $cart_total) {
                wc_add_notice('Недостаточно баллов', 'error');
                return false;
            }
            
            if ($cart_total > $this->get_option('max_amount', 5000)) {
                wc_add_notice('Превышена максимальная сумма оплаты баллами', 'error');
                return false;
            }
            
            return true;
        }
        
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $user = wp_get_current_user();
            $email = $user->user_email;
            $total = $order->get_total();
            
            // Двойная проверка баланса
            $balance = alean_get_user_balance($email);
            if ($balance < $total) {
                wc_add_notice('Недостаточно баллов', 'error');
                return false;
            }
            
            // API запрос
            $response = wp_remote_post(
                rest_url('alean/v1/spend-points'),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-WP-Nonce' => wp_create_nonce('wp_rest')
                    ],
                    'body' => json_encode([
                        'email' => $email,
                        'amount' => $total,
                        'order_id' => $order_id
                    ]),
                    'timeout' => 15
                ]
            );
            
            if (is_wp_error($response)) {
                wc_add_notice('Ошибка соединения', 'error');
                return false;
            }
            
            $body = json_decode(wp_remote_retrieve_body($response), true);
            
            if (!isset($body['status']) || $body['status'] !== 'success') {
                wc_add_notice($body['message'] ?? 'Ошибка списания', 'error');
                return false;
            }
            
            // Успешная оплата
            $order->payment_complete();
            WC()->cart->empty_cart();
            
            return [
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            ];
        }
    }
    
    // Регистрация шлюза
    function add_alean_loyalty_gateway($methods) {
        $methods[] = 'WC_Gateway_Alean_Loyalty';
        return $methods;
    }
    add_filter('woocommerce_payment_gateways', 'add_alean_loyalty_gateway');
}

/**
 * Получение баланса пользователя
 */
function alean_get_user_balance($email) {
    $response = wp_remote_post(
        'https://n8n.alean.ru/webhook/get-lp-email',
        [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(['email' => $email]),
            'timeout' => 10
        ]
    );
    
    if (is_wp_error($response)) {
        return 0;
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    return isset($data['total_points']) ? (float)$data['total_points'] : 0;
}












/**
 * WooCommerce Alean Loyalty Points Integration
 */
class Alean_Loyalty_WooCommerce {
    
    public function __construct() {
        // Инициализация хуков
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Добавляем баллы как метод оплаты
        add_filter('woocommerce_payment_gateways', [$this, 'add_payment_gateway']);
        
        // Проверка доступных баллов при оформлении заказа
        add_action('woocommerce_review_order_before_payment', [$this, 'check_loyalty_points']);
        
        // Списание баллов после создания заказа
        add_action('woocommerce_checkout_order_processed', [$this, 'process_loyalty_payment'], 10, 3);
        
        // Отображение баланса баллов в личном кабинете
        add_action('woocommerce_account_dashboard', [$this, 'show_loyalty_balance']);
    }
    
    /**
     * Добавляем наш метод оплаты баллами
     */
    public function add_payment_gateway($gateways) {
        $gateways[] = 'Alean_Loyalty_Payment_Gateway';
        return $gateways;
    }
    
    /**
     * Проверка доступных баллов
     */
    public function check_loyalty_points() {
        $user = wp_get_current_user();
        if (!$user->ID) return;
        
        $response = $this->get_lp_data($user->user_email);
        if ($response['lp-status'] === 'TRUE') {
            $balance = $response['total_points'];
            echo '<div class="loyalty-points-balance">';
            echo '<h3>Ваш баланс бонусных баллов: ' . $balance . '</h3>';
            echo '</div>';
        }
    }
    
    /**
     * Обработка оплаты баллами
     */
    public function process_loyalty_payment($order_id, $posted_data, $order) {
        if ($order->get_payment_method() !== 'alean_loyalty') return;
        
        $user = $order->get_user();
        $email = $order->get_billing_email();
        $total = $order->get_total();
        
        // Получаем данные о баллах
        $lp_data = $this->get_lp_data($email);
        if ($lp_data['lp-status'] !== 'TRUE') {
            throw new Exception('Не удалось получить данные бонусной программы');
        }
        
        // Проверяем достаточность баллов
        if ($lp_data['total_points'] < $total) {
            throw new Exception('Недостаточно бонусных баллов для оплаты заказа');
        }
        
        // Списание баллов
        $result = $this->spend_loyalty_points(
            $email,
            $total,
            'order_' . $order_id,
            'Оплата заказа #' . $order_id
        );
        
if (!is_array($result) || !isset($result['status']) || $result['status'] !== 'success') {
    throw new Exception('Заказ оформлен! Ваши баллы зарезервированы, менеджер скоро обработает Ваш заказ ' . ($result['message'] ?? ''));
}
        
        // Помечаем заказ как оплаченный
        $order->payment_complete();
    }
    
    //#####################################################################################################################################################################################################
    
    /**
     * Отображение баланса в личном кабинете
     */
    public function show_loyalty_balance() {
        $user = wp_get_current_user();
        $response = $this->get_lp_data($user->user_email);
        
        if ($response['lp-status'] === 'TRUE') {
            echo '<div class="loyalty-account-section">';
            echo '<h2>Бонусная программа</h2>';
            echo '<p>Текущий баланс: <strong>' . $response['total_points'] . ' баллов</strong></p>';
            
            if (!empty($response['activities'])) {
                echo '<h3>История операций:</h3>';
                echo '<ul>';
                foreach ($response['activities'] as $activity) {
                    echo '<li>' . esc_html($activity) . '</li>';
                }
                echo '</ul>';
            }
            
            echo '</div>';
        }
    }
    
    /**
     * Вспомогательные методы для работы с API
     */
private function get_lp_data($email) {
    $url = 'https://n8n.alean.ru/webhook/get-lp-email';
    $args = [
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 15,
        'body' => json_encode(['email' => $email])
    ];

    error_log('Requesting LP data for email: ' . $email);
    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        error_log('LP Data API Error: ' . $response->get_error_message());
        return ['lp-status' => 'FALSE', 'error' => $response->get_error_message()];
    }

    $body = wp_remote_retrieve_body($response);
    error_log('LP Data API Response: ' . $body);

    $data = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('JSON decode error: ' . json_last_error_msg());
        return ['lp-status' => 'FALSE', 'error' => 'Invalid JSON'];
    }

    return $data;
}
    
    private function spend_loyalty_points($email, $sum, $order_id, $comment) {
        $response = wp_remote_post(
            'https://n8n.alean.ru/webhook/lp-spending',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode([
                    'email' => $email,
                    'sum' => $sum,
                    'eventexternalid' => $order_id,
                    'comment' => $comment
                ])
            ]
        );
        
        if (is_wp_error($response)) {
            return ['status' => 'error', 'message' => $response->get_error_message()];
        }
        
        return json_decode(wp_remote_retrieve_body($response), true);
    }
}

// Инициализация интеграции
new Alean_Loyalty_WooCommerce();










/**
 * Payment Gateway for Alean Loyalty Points
 */
class Alean_Loyalty_Payment_Gateway extends WC_Payment_Gateway {
    
    public function __construct() {
        $this->id = 'alean_loyalty';
        $this->method_title = 'Оплата бонусными баллами';
        $this->method_description = 'Оплата заказа с использованием бонусных баллов';
        $this->has_fields = true;
        
        // Инициализация настроек
        $this->init_form_fields();
        $this->init_settings();
        
        // Переопределение настроек
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        
        // Сохранение настроек
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
    }
    
    /**
     * Настройки платежного шлюза
     */
    public function init_form_fields() {
        $this->form_fields = [
            'enabled' => [
                'title' => 'Включено',
                'type' => 'checkbox',
                'label' => 'Включить оплату бонусными баллами',
                'default' => 'yes'
            ],
            'title' => [
                'title' => 'Название',
                'type' => 'text',
                'description' => 'Название метода оплаты, которое видит покупатель',
                'default' => 'Оплата бонусными баллами',
                'desc_tip' => true
            ],
            'description' => [
                'title' => 'Описание',
                'type' => 'textarea',
                'description' => 'Описание метода оплаты, которое видит покупатель',
                'default' => 'Оплатите заказ с использованием ваших бонусных баллов',
                'desc_tip' => true
            ]
        ];
    }
    
    /**
     * Поля для ввода на странице оплаты
     */
    public function payment_fields() {
    $user = wp_get_current_user();
    if (!$user->ID) {
        echo '<p>Для оплаты баллами необходимо авторизоваться</p>';
        return;
    }
    error_log('Current user email: ' . $user->user_email);
        
        $response = wp_remote_post(
            'https://n8n.alean.ru/webhook/get-lp-email',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode(['email' => $user->user_email])
            ]
        );
        
        if (is_wp_error($response)) {
            echo '<p>Ошибка получения данных бонусной программы</p>';
            return;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($data['lp-status']) || (isset($data['lp-status']) && strtolower($data['lp-status']) !== 'true')) {
            echo '<p>Вы не участвуете в бонусной программе</p>';
            return;
        }
        
        $balance = $data['total_points'];
        $order_total = WC()->cart->total;
        
        echo '<div class="loyalty-payment-fields">';
        echo '<p>Ваш баланс: <strong>' . $balance . ' баллов</strong></p>';
        echo '<p>Сумма заказа: <strong>' . wc_price($order_total) . '</strong></p>';
        
        if ($balance >= $order_total) {
            echo '<p>Вы можете оплатить весь заказ бонусными баллами</p>';
        } else {
            echo '<p class="error">Недостаточно баллов для оплаты заказа</p>';
        }
        
        echo '</div>';
    }
    
    /**
     * Валидация платежа
     */
    public function validate_fields() {
        $user = wp_get_current_user();
        $email = $user->user_email;
        $order_total = WC()->cart->total;
        
        $response = wp_remote_post(
            'https://n8n.alean.ru/webhook/get-lp-email',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode(['email' => $email])
            ]
        );
        
        if (is_wp_error($response)) {
            wc_add_notice('Ошибка проверки бонусных баллов', 'error');
            return false;
        }
        
        $data = json_decode(wp_remote_retrieve_body($response), true);
        
        if ($data['lp-status'] !== 'TRUE' || $data['total_points'] < $order_total) {
            wc_add_notice('Недостаточно бонусных баллов для оплаты заказа', 'error');
            return false;
        }
        
        return true;
    }
    
    /**
     * Обработка платежа
     */
public function process_payment($order_id) {
    $order = wc_get_order($order_id);
    $email = $order->get_billing_email();
    $total = $order->get_total();

    try {
        // 1. Проверяем баланс
        $balance_data = $this->get_lp_data($email);
        if (!isset($balance_data['lp-status']) || $balance_data['lp-status'] !== 'TRUE') {
            throw new Exception('Бонусная программа недоступна');
        }

        if ($balance_data['total_points'] < $total) {
            throw new Exception('Недостаточно баллов. Доступно: ' . $balance_data['total_points']);
        }

        // 2. Списание баллов (упрощенный вариант без проверки ответа)
        $this->spend_points($email, $total, 'order_'.$order_id, 'Оплата заказа #'.$order_id);

        // 3. КРИТИЧЕСКИ ВАЖНЫЕ ДЕЙСТВИЯ:
        $order->update_status('completed', __('Оплачено баллами', 'woocommerce'));
        
        // 4. Очистка корзины ДО редиректа
        if (function_exists('WC') && WC()->cart) {
            WC()->cart->empty_cart();
        }

        // 5. Возвращаем правильный массив
        return array(
            'result'   => 'success',
            'redirect' => $this->get_return_url($order) // Используем встроенный метод
        );

    } catch (Exception $e) {
        // Логируем полную ошибку
        error_log('Loyalty Payment Error: ' . $e->getMessage() . ' | Order: ' . $order_id);
        
        // Добавляем понятное сообщение пользователю
        wc_add_notice($e->getMessage(), 'error');
        
        return array(
            'result' => 'failure',
            'messages' => $e->getMessage()
        );
    }
}
}










































add_action('wp_ajax_alean_get_bonus_data', 'alean_get_bonus_data_ajax');
add_action('wp_ajax_nopriv_alean_get_bonus_data', 'alean_auth_required');

function alean_get_bonus_data_ajax() {
    check_ajax_referer('alean_nonce', 'security');
    
    if (!is_user_logged_in()) {
        wp_send_json_error('Требуется авторизация', 401);
    }

    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $current_user = wp_get_current_user();
    
    // Если email не указан, используем email текущего пользователя
    if (empty($email) && $current_user->ID) {
        $email = $current_user->user_email;
    }
    
    // Получаем данные через основной метод
    $data = alean_get_bonus_data($email);
    
    wp_send_json_success($data);
}

function alean_auth_required() {
    wp_send_json_error('Требуется авторизация', 401);
}












wp_localize_script('alean-loyalty-js', 'aleanLoyalty', [
    'rest_url' => rest_url('alean/v1/bonus'),
    'nonce' => wp_create_nonce('wp_rest') // ← Используйте стандартный nonce
]);






add_filter('rest_authentication_errors', function($result) {
    return is_wp_error($result) ? $result : true;
});












//=============
function custom_theme_settings_page() {
    add_menu_page(
        'Настройки темы', 
        'Настройки темы', 
        'manage_options', 
        'theme-settings', 
        'render_theme_settings_page',
        'dashicons-admin-generic',
        80
    );
}
add_action('admin_menu', 'custom_theme_settings_page');


function register_theme_settings() {
    register_setting('theme_settings_group', 'theme_settings');
    
    add_meta_box(
        'theme_logo_box',
        'Логотип и фавикон',
        'render_logo_meta_box',
        'theme_settings_page',
        'normal'
    );
    
    add_meta_box(
        'theme_contacts_box',
        'Контактная информация',
        'render_contacts_meta_box',
        'theme_settings_page',
        'normal'
    );
    
    add_meta_box(
        'theme_social_box',
        'Социальные сети',
        'render_social_meta_box',
        'theme_settings_page',
        'normal'
    );
}
add_action('admin_init', 'register_theme_settings');


function render_theme_settings_page() {
    ?>
    <div class="wrap">
        <h1>Настройки темы</h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('theme_settings_group');
            do_meta_boxes('theme_settings_page', 'normal', null);
            submit_button('Сохранить настройки');
            ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Обработка кнопки загрузки изображения
        $('.upload_image_button').click(function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            
            wp.media.editor.send.attachment = function(props, attachment) {
                $(button).parent().find('.image_url').val(attachment.url);
                $(button).parent().find('.image_preview').attr('src', attachment.url).show();
                wp.media.editor.send.attachment = send_attachment_bkp;
            }
            
            wp.media.editor.open(button);
            return false;
        });
        
        // Обработка кнопки удаления изображения
        $('.remove_image_button').click(function() {
            var button = $(this);
            $(button).parent().find('.image_url').val('');
            $(button).parent().find('.image_preview').hide();
            return false;
        });
    });
    </script>
    <?php
}


function render_logo_meta_box() {
    $settings = get_option('theme_settings');
    ?>
    <table class="form-table">
        <tr>
            <th><label>Логотип</label></th>
            <td>
                <input type="text" name="theme_settings[logo_url]" class="image_url regular-text" value="<?php echo esc_attr($settings['logo_url'] ?? ''); ?>">
                <input type="button" class="button upload_image_button" value="Выбрать изображение">
                <input type="button" class="button remove_image_button" value="Удалить">
                <div class="image_preview_wrap">
                    <?php if (!empty($settings['logo_url'])): ?>
                        <img src="<?php echo esc_url($settings['logo_url']); ?>" class="image_preview" style="max-width: 200px; height: auto; margin-top: 10px;">
                    <?php else: ?>
                        <img src="" class="image_preview" style="max-width: 200px; height: auto; margin-top: 10px; display: none;">
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label>Favicon</label></th>
            <td>
                <input type="text" name="theme_settings[favicon_url]" class="image_url regular-text" value="<?php echo esc_attr($settings['favicon_url'] ?? ''); ?>">
                <input type="button" class="button upload_image_button" value="Выбрать изображение">
                <input type="button" class="button remove_image_button" value="Удалить">
                <div class="image_preview_wrap">
                    <?php if (!empty($settings['favicon_url'])): ?>
                        <img src="<?php echo esc_url($settings['favicon_url']); ?>" class="image_preview" style="max-width: 32px; height: auto; margin-top: 10px;">
                    <?php else: ?>
                        <img src="" class="image_preview" style="max-width: 32px; height: auto; margin-top: 10px; display: none;">
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    </table>
    <?php
}

function render_contacts_meta_box() {
    $settings = get_option('theme_settings');
    ?>
    <table class="form-table">
        <tr>
            <th><label>Телефон</label></th>
            <td>
                <input type="text" name="theme_settings[phone]" class="regular-text" value="<?php echo esc_attr($settings['phone'] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <th><label>Email</label></th>
            <td>
                <input type="email" name="theme_settings[email]" class="regular-text" value="<?php echo esc_attr($settings['email'] ?? ''); ?>">
            </td>
        </tr>
    </table>
    <?php
}

function render_social_meta_box() {
    $settings = get_option('theme_settings');
    ?>
    <table class="form-table">
        <tr>
            <th><label>WhatsApp</label></th>
            <td>
                <input type="url" name="theme_settings[whatsapp]" class="regular-text" value="<?php echo esc_attr($settings['whatsapp'] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <th><label>Telegram</label></th>
            <td>
                <input type="url" name="theme_settings[telegram]" class="regular-text" value="<?php echo esc_attr($settings['telegram'] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <th><label>Rutube</label></th>
            <td>
                <input type="url" name="theme_settings[rutube]" class="regular-text" value="<?php echo esc_attr($settings['rutube'] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <th><label>YouTube</label></th>
            <td>
                <input type="url" name="theme_settings[youtube]" class="regular-text" value="<?php echo esc_attr($settings['youtube'] ?? ''); ?>">
            </td>
        </tr>
        <tr>
            <th><label>VK</label></th>
            <td>
                <input type="url" name="theme_settings[vk]" class="regular-text" value="<?php echo esc_attr($settings['vk'] ?? ''); ?>">
            </td>
        </tr>
    </table>
    <?php
}

function add_favicon_to_head() {
    $settings = get_option('theme_settings');
    if (!empty($settings['favicon_url'])) {
        echo '<link rel="shortcut icon" href="' . esc_url($settings['favicon_url']) . '" />';
    }
}
add_action('wp_head', 'add_favicon_to_head');


function enqueue_media_uploader() {
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');






//














/*



function alean_auth_shortcode() {
    ob_start();
    ?>

        <?php if (is_user_logged_in()) : ?>
            <?php
            $user = wp_get_current_user();
            $email = $user->user_email;
            
       
            $response = wp_remote_post(
                'https://n8n.alean.ru/webhook/get-lp-email',
                [
                    'headers' => ['Content-Type' => 'application/json'],
                    'body' => json_encode(['email' => $email]),
                    'timeout' => 15
                ]
            );
            
            if (!is_wp_error($response)) :
                $bonus_data = json_decode(wp_remote_retrieve_body($response), true);
                
                if ($bonus_data['lp-status'] === 'TRUE') : ?>
                   
                    <div class="auth-bonus-info">
                        <span class="user-name"><?php echo esc_html($bonus_data['name'] ?? $user->display_name); ?></span>
                        <span class="user-points"><?php echo esc_html($bonus_data['total_points'] ?? 0); ?> <img src="https://test-merch.alean.ru/wp-content/uploads/2025/05/android-icon-192x192-1.png" width="21" alt="Логотип" class="logo"></span>
                    </div>
                <?php else : ?>
                 
                    <div class="auth-bonus-register">
                        <span class="user-email"><?php echo esc_html($email); ?></span>
                        <a href="https://lnk.alean.ru/md/aleanlp-reg" class="register-link">Присоединиться к программе</a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
           
            <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-button">Выйти</a>
        <?php else : ?>

            <a href="<?php echo esc_url(home_url('/?sso_login=1')); ?>" class="button--sso button">
            <svg  class="button__icon animated slideFade" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="">
<path d="M12.6777 12C15.4392 12 17.6777 9.76142 17.6777 7C17.6777 4.23858 15.4392 2 12.6777 2C9.91631 2 7.67773 4.23858 7.67773 7C7.67773 9.76142 9.91631 12 12.6777 12Z" stroke="#3595EE" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M21.4481 22C21.4481 18.13 17.5981 15 12.8581 15C8.11806 15 4.26807 18.13 4.26807 22" stroke="#3595EE" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

            <span  class="button__text">Войти через SSO</span>
            
            
            </a>
        <?php endif; ?>

    <?php
    return ob_get_clean();
}
add_shortcode('alean_auth', 'alean_auth_shortcode');

*/


function alean_auth_shortcode() {
    ob_start();
    ?>
    
    <?php if (is_user_logged_in()) : ?>
        <?php
        $user = wp_get_current_user();
        $email = $user->user_email;
        $display_name = $user->display_name;
        
        $response = wp_remote_post(
            'https://n8n.alean.ru/webhook/get-lp-email',
            [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode(['email' => $email]),
                'timeout' => 15
            ]
        );
        
        if (!is_wp_error($response)) :
            $bonus_data = json_decode(wp_remote_retrieve_body($response), true);
            
          
            function get_initials($full_name) {
                $parts = explode(' ', $full_name);
                $initials = '';
                
               
                for ($i = 0; $i < min(2, count($parts)); $i++) {
                    if (!empty($parts[$i])) {
                        $initials .= mb_substr($parts[$i], 0, 1);
                    }
                }
                
                return $initials ?: mb_substr($full_name, 0, 2);
            }
            
    
            $name_to_use = $bonus_data['name'] ?? $display_name;
            $initials = get_initials($name_to_use);
            
            if ($bonus_data['lp-status'] === 'TRUE') : ?>
                <div class="auth-user-info">
                    <div class="user-avatar"><?php echo esc_html($initials); ?></div>
                    <div class="user-details">
                        <span class="user-name"><?php echo esc_html($name_to_use); ?></span>
                        <div class="user-points">
                        <b>Баланс: </b>
                            <?php echo esc_html($bonus_data['total_points'] ?? 0); ?> 
                            <img src="/wp-content/uploads/2025/05/android-icon-192x192-1.png" width="12" alt="Логотип" class="logo">
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="auth-user-info">
                    <div class="user-avatar"><?php echo esc_html(get_initials($display_name)); ?></div>
                    <div class="user-details">
                        <span class="user-name"><?php echo esc_html($display_name); ?></span>
                        <div class="auth-bonus-register">
                            <span class="user-email"><?php echo esc_html($email); ?></span>
                            <a href="/registraciya-v-bonusnoj-programme/" class="register-link">Присоединиться к программе</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Вывод меню WordPress -->
        <nav class="auth-user-menu">
            <?php 
            wp_nav_menu([
                'theme_location' => 'user-menu', 
                'container' => false,
                'menu_class' => 'user-menu',
                'fallback_cb' => false
            ]); 
            ?>
        </nav>
        
        <?php /* <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-button">Выйти</a> */?>
    <?php else : ?>
    <a href="<?php echo esc_url(home_url('/?sso_login=1')); ?>" class="button--sso">
        <span class="button__icon">
          <img class="button__icon--link" src="/wp-content/uploads/Profile.svg" alt="Profile Icon">
        </span>
        <span class="button__text">Войти через SSO</span>
    </a>
    <?php endif; ?>
    
    <?php
    return ob_get_clean();
}
add_shortcode('alean_auth', 'alean_auth_shortcode');
//===========

function register_user_menu() {
    register_nav_menu('user-menu', 'Меню пользователя');
}
add_action('after_setup_theme', 'register_user_menu');

//===========

function my_custom_styles() {

    wp_enqueue_style('custom-main-css', get_template_directory_uri() . '/js/main.css');
}
add_action('wp_enqueue_scripts', 'my_custom_styles');









remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
add_action('woocommerce_before_add_to_cart_button', 'woocommerce_template_single_price', 5);
















//

function add_custom_class_to_specific_heading($block_content, $block) {
    if ($block['blockName'] === 'core/heading' && 
        isset($block['attrs']['level']) && 
        $block['attrs']['level'] === 3 &&
        strpos($block_content, 'Цвет') !== false) {
        
        $block_content = str_replace(
            '<h3 class="wp-block-heading"',
            '<h3 class="wp-block-heading color-filter"',
            $block_content
        );
    }
    return $block_content;
}
add_filter('render_block', 'add_custom_class_to_specific_heading', 10, 2);





























add_filter('woocommerce_checkout_fields', 'add_phone_field_to_checkout');
function add_phone_field_to_checkout($fields) {
 
    $fields['billing']['billing_phone'] = array(
        'label'       => __('Phone', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide'),
        'clear'       => true,
        'placeholder' => '+7 (___) ___-__-__',
        'default'     => WC()->session->get('cart_phone') ?: '',
    );
    return $fields;
}



add_action('woocommerce_cart_updated', 'save_cart_phone');
function save_cart_phone() {
    if (isset($_POST['cart_phone'])) {
        WC()->session->set('cart_phone', sanitize_text_field($_POST['cart_phone']));
    }
}


add_filter('woocommerce_checkout_get_value', 'transfer_phone_to_checkout', 10, 2);
function transfer_phone_to_checkout($value, $input) {
    if ($input === 'billing_phone') {
        $session_phone = WC()->session->get('cart_phone');
        return $session_phone ?: ($value ?: '+7 (');
    }
    return $value;
}









//============
function partners_benefits_shortcode() {
    ob_start();
    ?>
    <div class="partners__benefits benefits">
        <div class="benefits__item">
            <b class="h3 benefits__title">100</b>
            <span class="benefits__subtitle">блогеров</span>
            <div class="benefits__description">
                бесплатная реклама вашего отеля
            </div>
            <img
                decoding="async"
                loading="lazy"
                src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/alean-orange.svg"
                alt="Иконка Alean (оранжевый)"
                class="benefits__icon animated slideFade"
            >
        </div>
        <div class="benefits__item">
            <b class="h3 benefits__title">20 тыс</b>
            <span class="benefits__subtitle">в Telegram</span>
            <div class="benefits__description">
                скорее сообщите им о своем отеле
            </div>
            <img
                decoding="async"
                loading="lazy"
                src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/alean-pink.svg"
                alt="Иконка Alean (розовый)"
                class="benefits__icon animated slideFade"
            >
        </div>
        <div class="benefits__item benefits__item--wide">
            <b class="h3 benefits__title">500</b>
            <span class="benefits__subtitle">корпоративных партнеров</span>
            <div class="benefits__description">
                они могут разместиться именно в вашем отеле
            </div>
            <img
                decoding="async"
                loading="lazy"
                src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/alean-gray.svg"
                alt="Иконка Alean (серый)"
                class="benefits__icon animated slideFade"
            >
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('partners_benefits', 'partners_benefits_shortcode');



function enqueue_swiper_assets() {
   
    if (!wp_script_is('swiper-js', 'registered')) {
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_swiper_assets');

function init_swiper_slider() {
    ?>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    
    if (typeof Swiper === 'undefined') {
        console.error('Swiper не загружен!');
        return;
    }


    const productContainers = document.querySelectorAll('.woocommerce.columns-4');

    productContainers.forEach(container => {
        const productsList = container.querySelector('ul.products');
        if (!productsList) return;

   
        productsList.classList.add('swiper-wrapper');
        productsList.querySelectorAll('li.product').forEach(item => {
            item.classList.add('swiper-slide');
        });

     
        const swiperContainer = document.createElement('div');
        swiperContainer.className = 'swiper';
        container.insertBefore(swiperContainer, productsList);
        swiperContainer.appendChild(productsList);

      
        new Swiper(swiperContainer, {
            slidesPerView: 'auto', 
            freeMode: true,       
            resistanceRatio: 0,    
            spaceBetween: 10,     
            mousewheel: false,       
            grabCursor: true,       
        });
    });
});
    </script>
    <?php
}
add_action('wp_footer', 'init_swiper_slider', 999);









/**
 * Усиление безопасности
 */

// 1. Защита от XSS для всех выводов данных
function secure_output($data, $type = 'html') {
    switch ($type) {
        case 'url':
            return esc_url($data);
        case 'attr':
            return esc_attr($data);
        case 'js':
            return esc_js($data);
        case 'html':
        default:
            return wp_kses_post($data); // Разрешает безопасные HTML теги
    }
}

// 2. Автоматическое экранирование для всех GET/POST переменных при выводе
function safe_echo($value, $type = 'html') {
    if (isset($_REQUEST[$value])) {
        echo secure_output($_REQUEST[$value], $type);
    }
}

// 3. Улучшенная обработка паролей (дополнение к встроенной системе WordPress)
function enhance_password_security() {
    // Увеличиваем стоимость хеширования
    add_filter('hash_option_cost', function() {
        return 12; // По умолчанию 8, можно увеличить до 12-16 на мощных серверах
    });
    
    // Запрещаем слабые пароли
    add_action('user_profile_update_errors', function($errors) {
        if (strlen($_POST['pass1']) < 12) {
            $errors->add('pass', '<strong>Ошибка:</strong> Пароль должен содержать минимум 12 символов');
        }
    }, 10, 3);
}
add_action('init', 'enhance_password_security');

// 4. Защита админки (wp-admin)
function secure_admin_area() {
   
    /*
    $allowed_ips = ['123.45.67.89', '192.168.1.1'];
    if (is_admin() && !in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        wp_redirect(home_url());
        exit;
    }
    */
    

    remove_action('wp_head', 'wp_generator');
    add_filter('the_generator', '__return_empty_string');
}
add_action('admin_init', 'secure_admin_area');

// 5. Защита от подделки запросов (CSRF) для фронтенда
function add_nonce_to_public_forms() {
    if (!is_admin()) {
        add_action('wp_footer', function() {
            echo '<script>window.wpNonce = "'.wp_create_nonce('public_nonce').'";</script>';
        });
    }
}
add_action('init', 'add_nonce_to_public_forms');

// 6. Безопасные заголовки
function add_security_headers() {
    if (!headers_sent()) {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-XSS-Protection: 1; mode=block');
        
        // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' *.yoursite.com;");
    }
}
add_action('send_headers', 'add_security_headers');








remove_action('wp_head', 'wp_generator');

function hide_wp_paths() {
    return '/' . wp_generate_password(8, false) . '/';
}
add_filter('rest_url_prefix', 'hide_wp_paths');

add_filter('widget_text', 'do_shortcode');
add_action('wp_ajax_nopriv_products_live_search', 'woocommerce_products_live_search');
add_action('wp_ajax_products_live_search', 'woocommerce_products_live_search');
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('pre_term_description', 'wp_kses_data');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wp_oembed_add_discovery_links');
remove_action('wp_head', 'wp_generator');

remove_action('wp_head', 'rsd_link');

function wpcourses_disable_feed() {
    wp_redirect(get_option('siteurl'));
}
add_action('do_feed', 'wpcourses_disable_feed', 1);
add_action('do_feed_rdf', 'wpcourses_disable_feed', 1);
add_action('do_feed_rss', 'wpcourses_disable_feed', 1);
add_action('do_feed_rss2', 'wpcourses_disable_feed', 1);
add_action('do_feed_atom', 'wpcourses_disable_feed', 1);

remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'profile_link');
remove_action('wp_head', 'wp_resource_hints', 2);
remove_filter('wp_robots', 'wp_robots_max_image_preview_large');
remove_action('wp_head', 'wp_robots', 1);

add_action('get_header', 'remove_admin_bar_margin');
function remove_admin_bar_margin() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

function disable_wp_emojis_in_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}
add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');

function unset_url_field($fields) {
    if (isset($fields['url'])) {
        unset($fields['url']);
    }
    return $fields;
}
add_filter('comment_form_default_fields', 'unset_url_field');

function remove_script_version($src) {
    return $src ? esc_url(remove_query_arg('ver', $src)) : false;
}
add_filter('script_loader_src', 'remove_script_version', 15, 1);
add_filter('style_loader_src', 'remove_script_version', 15, 1);

function disable_unused_rest_endpoints($endpoints) {
    if (isset($endpoints['/wp/v2/users'])) unset($endpoints['/wp/v2/users']);
    if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
    return $endpoints;
}
add_filter('rest_endpoints', 'disable_unused_rest_endpoints');

add_filter('xmlrpc_enabled', '__return_false');

function prevent_directory_listing() {
    if (!is_blog_installed()) return;
    if (!defined('FS_CHMOD_DIR')) define('FS_CHMOD_DIR', (0755 & ~umask()));
    @chmod(ABSPATH, FS_CHMOD_DIR);
}
add_action('init', 'prevent_directory_listing');

function remove_dashboard_widgets() {
    remove_meta_box('dashboard_primary', 'dashboard', 'side');
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    remove_meta_box('health_check_status', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

if (!is_admin()) {
    if (preg_match('/author=([0-9]*)/i', $_SERVER['QUERY_STRING'])) die();
    add_filter('redirect_canonical', 'shapeSpace_check_enum', 10, 2);
}
function shapeSpace_check_enum($redirect, $request) {
    if (preg_match('/\?author=([0-9]*)(\/*)/i', $request)) die();
    else return $redirect;
}

function limit_heartbeat($settings) {
    $settings['interval'] = 60;
    return $settings;
}
add_filter('heartbeat_settings', 'limit_heartbeat');

function remove_unnecessary_headers() {
    remove_action('template_redirect', 'wp_shortlink_header', 11);
    remove_action('template_redirect', 'rest_output_link_header', 11);
}
add_action('after_setup_theme', 'remove_unnecessary_headers');

function clean_head() {
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'wp_site_icon', 99);
}
add_action('init', 'clean_head');




