<?php
/**
 * Plugin Name: Alean Loyalty Payment
 * Plugin URI: https://alean.ru
 * Description: Плагин для оплаты баллами лояльности в WooCommerce
 * Version: 1.0.0
 * Author: Alean Team
 * Text Domain: alean-loyalty-payment
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('ALEAN_LOYALTY_PAYMENT_VERSION', '1.0.0');
define('ALEAN_LOYALTY_PAYMENT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ALEAN_LOYALTY_PAYMENT_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class Alean_Loyalty_Payment_Plugin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        // Check if WooCommerce is active
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }
        
        // Load plugin files
        $this->load_files();
        
        // Initialize hooks
        $this->init_hooks();
        
        // Initialize payment gateways
        $this->init_payment_gateways();
    }
    
    /**
     * Load required files
     */
    private function load_files() {
        require_once ALEAN_LOYALTY_PAYMENT_PLUGIN_DIR . 'includes/class-wc-points-payment-gateway.php';
        require_once ALEAN_LOYALTY_PAYMENT_PLUGIN_DIR . 'includes/class-wc-alean-loyalty-gateway.php';
        require_once ALEAN_LOYALTY_PAYMENT_PLUGIN_DIR . 'includes/class-alean-loyalty-api.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add payment gateways
        add_filter('woocommerce_payment_gateways', array($this, 'add_payment_gateways'));
        
        // Add settings
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add AJAX handlers
        add_action('wp_ajax_get_loyalty_data', array($this, 'get_loyalty_data_callback'));
        add_action('wp_ajax_nopriv_get_loyalty_data', array($this, 'auth_required'));
        
        // Add REST API endpoints
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        
        // Add admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Add frontend scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }
    
    /**
     * Initialize payment gateways
     */
    private function init_payment_gateways() {
        // Payment gateways will be loaded in the filter
    }
    
    /**
     * Add payment gateways to WooCommerce
     */
    public function add_payment_gateways($gateways) {
        $gateways[] = 'WC_Points_Payment_Gateway';
        $gateways[] = 'WC_Gateway_Alean_Loyalty';
        return $gateways;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'woocommerce',
            'Alean Loyalty',
            'Alean Loyalty',
            'manage_woocommerce',
            'alean-loyalty-settings',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('alean_loyalty_settings', 'alean_loyalty_api_url');
        register_setting('alean_loyalty_settings', 'alean_loyalty_api_key');
        register_setting('alean_loyalty_settings', 'alean_loyalty_enabled');
        
        add_settings_section(
            'alean_loyalty_general',
            'Общие настройки',
            array($this, 'settings_section_callback'),
            'alean_loyalty_settings'
        );
        
        add_settings_field(
            'alean_loyalty_enabled',
            'Включить плагин',
            array($this, 'enabled_field_callback'),
            'alean_loyalty_settings',
            'alean_loyalty_general'
        );
        
        add_settings_field(
            'alean_loyalty_api_url',
            'API URL',
            array($this, 'api_url_field_callback'),
            'alean_loyalty_settings',
            'alean_loyalty_general'
        );
        
        add_settings_field(
            'alean_loyalty_api_key',
            'API Ключ',
            array($this, 'api_key_field_callback'),
            'alean_loyalty_settings',
            'alean_loyalty_general'
        );
    }
    
    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>Настройки интеграции с системой лояльности Alean</p>';
    }
    
    /**
     * Enabled field callback
     */
    public function enabled_field_callback() {
        $enabled = get_option('alean_loyalty_enabled', '1');
        ?>
        <input type="checkbox" name="alean_loyalty_enabled" value="1" <?php checked($enabled, '1'); ?> />
        <span class="description">Включить плагин оплаты баллами</span>
        <?php
    }
    
    /**
     * API URL field callback
     */
    public function api_url_field_callback() {
        $api_url = get_option('alean_loyalty_api_url', 'https://n8n.alean.ru/webhook/');
        ?>
        <input type="url" name="alean_loyalty_api_url" value="<?php echo esc_attr($api_url); ?>" class="regular-text" />
        <p class="description">URL API для получения данных лояльности</p>
        <?php
    }
    
    /**
     * API Key field callback
     */
    public function api_key_field_callback() {
        $api_key = get_option('alean_loyalty_api_key', '');
        ?>
        <input type="password" name="alean_loyalty_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text" />
        <p class="description">Ключ API для аутентификации</p>
        <?php
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>Alean Loyalty Payment</h1>
            
            <div class="alean-loyalty-admin-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#settings" class="nav-tab nav-tab-active">Настройки</a>
                    <a href="#logs" class="nav-tab">Логи</a>
                    <a href="#test" class="nav-tab">Тестирование</a>
                </nav>
                
                <div id="settings" class="tab-content">
                    <form method="post" action="options.php">
                        <?php
                        settings_fields('alean_loyalty_settings');
                        do_settings_sections('alean_loyalty_settings');
                        submit_button();
                        ?>
                    </form>
                </div>
                
                <div id="logs" class="tab-content" style="display: none;">
                    <h3>Логи операций</h3>
                    <div class="alean-loyalty-logs">
                        <?php $this->display_logs(); ?>
                    </div>
                </div>
                
                <div id="test" class="tab-content" style="display: none;">
                    <h3>Тестирование API</h3>
                    <button type="button" class="button button-primary" id="test-api">Тестировать API</button>
                    <div id="test-results"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Display logs
     */
    private function display_logs() {
        $log_file = WP_CONTENT_DIR . '/alean-loyalty.log';
        
        if (file_exists($log_file)) {
            $logs = file_get_contents($log_file);
            $logs = array_reverse(explode("\n", $logs));
            $logs = array_slice($logs, 0, 50); // Show last 50 lines
            
            echo '<pre style="background: #f1f1f1; padding: 15px; max-height: 400px; overflow-y: auto;">';
            foreach ($logs as $log) {
                if (!empty(trim($log))) {
                    echo esc_html($log) . "\n";
                }
            }
            echo '</pre>';
        } else {
            echo '<p>Логи не найдены</p>';
        }
    }
    
    /**
     * AJAX callback for getting loyalty data
     */
    public function get_loyalty_data_callback() {
        if (!is_user_logged_in()) {
            wp_send_json_error('Требуется авторизация', 401);
        }

        $user = wp_get_current_user();
        $api = new Alean_Loyalty_API();
        $data = $api->get_user_balance($user->user_email);

        if ($data) {
            wp_send_json_success($data);
        } else {
            wp_send_json_error('Ошибка получения данных', 500);
        }
    }
    
    /**
     * Auth required callback
     */
    public function auth_required() {
        wp_send_json_error('Требуется авторизация', 401);
    }
    
    /**
     * Register REST routes
     */
    public function register_rest_routes() {
        register_rest_route('alean/v1', '/bonus', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_bonus_data'),
            'permission_callback' => array($this, 'check_user_permission'),
        ));
        
        register_rest_route('alean/v1', '/spend-points', array(
            'methods' => 'POST',
            'callback' => array($this, 'spend_points'),
            'permission_callback' => array($this, 'check_user_permission'),
        ));
    }
    
    /**
     * Get bonus data
     */
    public function get_bonus_data($request) {
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $email = $user->user_email;
        
        $api = new Alean_Loyalty_API();
        $data = $api->get_user_balance($email);
        
        return new WP_REST_Response($data, 200);
    }
    
    /**
     * Spend points
     */
    public function spend_points($request) {
        $user_id = get_current_user_id();
        $user = get_userdata($user_id);
        $email = $user->user_email;
        
        $amount = $request->get_param('amount');
        $order_id = $request->get_param('order_id');
        
        if (!$amount || !$order_id) {
            return new WP_Error('missing_params', 'Missing required parameters', array('status' => 400));
        }
        
        $api = new Alean_Loyalty_API();
        $result = $api->spend_points($email, $amount, $order_id, 'Order payment');
        
        if ($result) {
            return new WP_REST_Response(array('success' => true), 200);
        } else {
            return new WP_Error('spend_failed', 'Failed to spend points', array('status' => 500));
        }
    }
    
    /**
     * Check user permission
     */
    public function check_user_permission() {
        return is_user_logged_in();
    }
    
    /**
     * Admin scripts
     */
    public function admin_scripts($hook) {
        if ($hook !== 'woocommerce_page_alean-loyalty-settings') {
            return;
        }
        
        wp_enqueue_script(
            'alean-loyalty-admin',
            ALEAN_LOYALTY_PAYMENT_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            ALEAN_LOYALTY_PAYMENT_VERSION,
            true
        );
        
        wp_enqueue_style(
            'alean-loyalty-admin',
            ALEAN_LOYALTY_PAYMENT_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            ALEAN_LOYALTY_PAYMENT_VERSION
        );
        
        wp_localize_script('alean-loyalty-admin', 'aleanLoyaltyAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alean_loyalty_admin_nonce'),
        ));
    }
    
    /**
     * Frontend scripts
     */
    public function frontend_scripts() {
        if (!is_checkout() && !is_cart()) {
            return;
        }
        
        wp_enqueue_script(
            'alean-loyalty-frontend',
            ALEAN_LOYALTY_PAYMENT_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            ALEAN_LOYALTY_PAYMENT_VERSION,
            true
        );
        
        wp_enqueue_style(
            'alean-loyalty-frontend',
            ALEAN_LOYALTY_PAYMENT_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            ALEAN_LOYALTY_PAYMENT_VERSION
        );
        
        wp_localize_script('alean-loyalty-frontend', 'aleanLoyalty', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alean_loyalty_nonce'),
            'isLoggedIn' => is_user_logged_in(),
        ));
    }
    
    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('Alean Loyalty Payment requires WooCommerce to be installed and active.', 'alean-loyalty-payment'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Log message
     */
    public static function log($message) {
        $log_file = WP_CONTENT_DIR . '/alean-loyalty.log';
        $timestamp = current_time('mysql');
        $log_entry = "[{$timestamp}] {$message}" . PHP_EOL;
        
        file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
    }
}

// Initialize plugin
new Alean_Loyalty_Payment_Plugin();

// Activation hook
register_activation_hook(__FILE__, 'alean_loyalty_payment_activate');
function alean_loyalty_payment_activate() {
    // Create log file
    $log_file = WP_CONTENT_DIR . '/alean-loyalty.log';
    if (!file_exists($log_file)) {
        file_put_contents($log_file, '');
    }
    
    // Set default options
    add_option('alean_loyalty_enabled', '1');
    add_option('alean_loyalty_api_url', 'https://n8n.alean.ru/webhook/');
    add_option('alean_loyalty_api_key', '');
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'alean_loyalty_payment_deactivate');
function alean_loyalty_payment_deactivate() {
    // Clean up if needed
}