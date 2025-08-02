<?php
/**
 * WC Alean Loyalty Gateway
 * 
 * @package Astra Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Платежный шлюз Alean Loyalty
 */
class WC_Gateway_Alean_Loyalty extends WC_Payment_Gateway {
	
	/**
	 * Конструктор
	 */
	public function __construct() {
		$this->id = 'alean_loyalty';
		$this->icon = '';
		$this->has_fields = true;
		$this->method_title = 'Alean Loyalty';
		$this->method_description = 'Оплата баллами лояльности Alean';
		
		$this->supports = array(
			'products',
			'refunds',
		);
		
		$this->init_form_fields();
		$this->init_settings();
		
		$this->title = $this->get_option( 'title' );
		$this->description = $this->get_option( 'description' );
		
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
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
				'type' => 'text',
				'description' => 'URL API для получения данных лояльности',
				'default' => 'https://api.alean.ru/loyalty',
				'desc_tip' => true,
			),
		);
	}
	
	/**
	 * Поля формы оплаты
	 */
	public function payment_fields() {
		if ( ! is_user_logged_in() ) {
			echo '<p>Для оплаты баллами необходимо войти в систему.</p>';
			return;
		}
		
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		// Получение баланса баллов
		$balance_data = $this->get_loyalty_balance( $email );
		
		if ( $balance_data['lp-status'] !== 'TRUE' ) {
			echo '<p>Бонусная программа недоступна.</p>';
			return;
		}
		
		$total = WC()->cart->get_total( 'edit' );
		$available_points = $balance_data['total_points'];
		
		echo '<div class="alean-loyalty-payment-info">';
		echo '<p>Ваш баланс баллов: <strong>' . esc_html( $available_points ) . '</strong></p>';
		echo '<p>Стоимость заказа: <strong>' . esc_html( $total ) . '</strong></p>';
		
		if ( $available_points < $total ) {
			echo '<p class="error">Недостаточно баллов для оплаты заказа.</p>';
		} else {
			echo '<p class="success">Достаточно баллов для оплаты заказа.</p>';
		}
		echo '</div>';
		
		// Добавляем скрытые поля для AJAX
		echo '<input type="hidden" name="alean_loyalty_email" value="' . esc_attr( $email ) . '">';
		echo '<input type="hidden" name="alean_loyalty_balance" value="' . esc_attr( $available_points ) . '">';
	}
	
	/**
	 * Валидация полей
	 */
	public function validate_fields() {
		if ( ! is_user_logged_in() ) {
			wc_add_notice( 'Для оплаты баллами необходимо войти в систему.', 'error' );
			return false;
		}
		
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		$balance_data = $this->get_loyalty_balance( $email );
		$total = WC()->cart->get_total( 'edit' );
		
		if ( $balance_data['lp-status'] !== 'TRUE' || $balance_data['total_points'] < $total ) {
			wc_add_notice( 'Недостаточно баллов для оплаты заказа.', 'error' );
			return false;
		}
		
		return true;
	}
	
	/**
	 * Обработка платежа
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		$total = $order->get_total();
		
		// Проверка баланса
		$balance_data = $this->get_loyalty_balance( $email );
		
		if ( $balance_data['lp-status'] !== 'TRUE' || $balance_data['total_points'] < $total ) {
			return array(
				'result' => 'failure',
				'redirect' => '',
			);
		}
		
		// Списание баллов через REST API
		$response = wp_remote_post( rest_url( 'alean/v1/spend-points' ), array(
			'headers' => array(
				'Content-Type' => 'application/json',
				'X-WP-Nonce' => wp_create_nonce( 'wp_rest' ),
			),
			'body' => json_encode( array(
				'amount' => $total,
				'order_id' => $order_id,
			) ),
		) );
		
		if ( is_wp_error( $response ) ) {
			$order->add_order_note( 'Ошибка при списании баллов: ' . $response->get_error_message() );
			return array(
				'result' => 'failure',
				'redirect' => '',
			);
		}
		
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );
		
		if ( $data && isset( $data['success'] ) && $data['success'] ) {
			$order->payment_complete();
			$order->add_order_note( 'Оплата баллами Alean Loyalty выполнена успешно. Списано баллов: ' . $total );
			
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		} else {
			$order->add_order_note( 'Ошибка при списании баллов Alean Loyalty' );
			
			return array(
				'result' => 'failure',
				'redirect' => '',
			);
		}
	}
	
	/**
	 * Получение баланса лояльности
	 */
	private function get_loyalty_balance( $email ) {
		// Здесь должна быть логика получения баланса из API
		// Пока возвращаем тестовые данные
		return array(
			'lp-status' => 'TRUE',
			'total_points' => 1000,
		);
	}
}