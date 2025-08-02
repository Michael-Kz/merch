<?php
/**
 * Alean Loyalty Plugin Class
 * 
 * @package Astra Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Основной класс для работы с бонусной программой
 */
class Alean_Loyalty_Plugin {
	
	/**
	 * Конструктор класса
	 */
	public function __construct() {
		$this->init_hooks();
	}
	
	/**
	 * Инициализация хуков
	 */
	private function init_hooks() {
		// WooCommerce интеграция
		add_filter( 'woocommerce_get_price_html', array( $this, 'replace_money_with_points' ), 10, 2 );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_points_payment_gateway' ) );
		add_action( 'plugins_loaded', array( $this, 'init_points_payment_gateway' ) );
		
		// Административная панель
		add_filter( 'manage_users_columns', array( $this, 'add_user_points_column' ) );
		add_action( 'manage_users_custom_column', array( $this, 'fill_user_points_column' ), 10, 3 );
		
		// AJAX обработчики
		add_action( 'wp_ajax_get_loyalty_data', array( $this, 'get_loyalty_data_callback' ) );
		add_action( 'wp_ajax_nopriv_get_loyalty_data', array( $this, 'auth_required' ) );
		
		// REST API
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
		
		// Инициализация платежного шлюза
		add_action( 'plugins_loaded', array( $this, 'init_alean_loyalty_gateway' ) );
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_alean_loyalty_gateway' ) );
	}
	
	/**
	 * Замена цены на баллы
	 */
	public function replace_money_with_points( $price, $product ) {
		// Логика замены цены на баллы
		return $price;
	}
	
	/**
	 * Добавление платежного шлюза баллов
	 */
	public function add_points_payment_gateway( $gateways ) {
		$gateways[] = 'WC_Points_Payment_Gateway';
		return $gateways;
	}
	
	/**
	 * Инициализация платежного шлюза баллов
	 */
	public function init_points_payment_gateway() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}
		
		require_once get_stylesheet_directory() . '/inc/gateways/class-wc-points-payment-gateway.php';
	}
	
	/**
	 * Добавление колонки баллов пользователя
	 */
	public function add_user_points_column( $columns ) {
		$columns['user_points'] = 'Баллы';
		return $columns;
	}
	
	/**
	 * Заполнение колонки баллов пользователя
	 */
	public function fill_user_points_column( $value, $column_name, $user_id ) {
		if ( $column_name == 'user_points' ) {
			return get_user_meta( $user_id, 'user_points', true ) ?: '0';
		}
		return $value;
	}
	
	/**
	 * AJAX обработчик получения данных лояльности
	 */
	public function get_loyalty_data_callback() {
		// Проверка nonce
		if ( ! wp_verify_nonce( $_POST['nonce'], 'alean_loyalty_nonce' ) ) {
			wp_die( 'Security check failed' );
		}
		
		$user_id = get_current_user_id();
		if ( ! $user_id ) {
			wp_send_json_error( 'User not logged in' );
		}
		
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		// Получение данных лояльности
		$loyalty_data = $this->get_loyalty_data( $email );
		
		wp_send_json_success( $loyalty_data );
	}
	
	/**
	 * Обработчик для неавторизованных пользователей
	 */
	public function auth_required() {
		wp_send_json_error( 'Authentication required' );
	}
	
	/**
	 * Регистрация REST API маршрутов
	 */
	public function register_rest_routes() {
		register_rest_route( 'alean/v1', '/bonus', array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_bonus_data' ),
			'permission_callback' => array( $this, 'check_user_permission' ),
		) );
		
		register_rest_route( 'alean/v1', '/spend-points', array(
			'methods' => 'POST',
			'callback' => array( $this, 'spend_points' ),
			'permission_callback' => array( $this, 'check_user_permission' ),
		) );
	}
	
	/**
	 * Получение данных бонусов
	 */
	public function get_bonus_data( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		$loyalty_data = $this->get_loyalty_data( $email );
		
		return new WP_REST_Response( $loyalty_data, 200 );
	}
	
	/**
	 * Списание баллов
	 */
	public function spend_points( WP_REST_Request $request ) {
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		$amount = $request->get_param( 'amount' );
		$order_id = $request->get_param( 'order_id' );
		
		if ( ! $amount || ! $order_id ) {
			return new WP_Error( 'missing_params', 'Missing required parameters', array( 'status' => 400 ) );
		}
		
		$result = $this->spend_loyalty_points( $email, $amount, $order_id, 'Order payment' );
		
		if ( $result ) {
			return new WP_REST_Response( array( 'success' => true ), 200 );
		} else {
			return new WP_Error( 'spend_failed', 'Failed to spend points', array( 'status' => 500 ) );
		}
	}
	
	/**
	 * Проверка прав пользователя
	 */
	public function check_user_permission() {
		return is_user_logged_in();
	}
	
	/**
	 * Инициализация платежного шлюза Alean Loyalty
	 */
	public function init_alean_loyalty_gateway() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}
		
		require_once get_stylesheet_directory() . '/inc/gateways/class-wc-alean-loyalty-gateway.php';
	}
	
	/**
	 * Добавление платежного шлюза Alean Loyalty
	 */
	public function add_alean_loyalty_gateway( $methods ) {
		$methods[] = 'WC_Gateway_Alean_Loyalty';
		return $methods;
	}
	
	/**
	 * Получение данных лояльности
	 */
	private function get_loyalty_data( $email ) {
		// Здесь должна быть логика получения данных из API
		// Пока возвращаем тестовые данные
		return array(
			'lp-status' => 'TRUE',
			'total_points' => 1000,
			'available_points' => 800,
		);
	}
	
	/**
	 * Списание баллов лояльности
	 */
	private function spend_loyalty_points( $email, $sum, $order_id, $comment ) {
		// Здесь должна быть логика списания баллов через API
		// Пока возвращаем true для тестирования
		return true;
	}
}

// Инициализация плагина
new Alean_Loyalty_Plugin();