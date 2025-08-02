<?php
/**
 * Cart Template Class
 * 
 * @package Astra Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Класс для управления шаблоном корзины
 */
class Cart_Template {
	
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
		// Переопределение шаблона корзины
		add_filter( 'woocommerce_locate_template', array( $this, 'override_cart_template' ), 10, 3 );
		
		// Кастомизация корзины
		add_action( 'woocommerce_before_cart', array( $this, 'add_cart_header' ) );
		add_action( 'woocommerce_after_cart', array( $this, 'add_cart_footer' ) );
		
		// Добавление информации о баллах в корзину
		add_action( 'woocommerce_cart_collaterals', array( $this, 'add_loyalty_info' ), 5 );
		
		// Кастомизация кнопок корзины
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'customize_quantity_input' ), 10, 3 );
		
		// Добавление дополнительных полей в корзину
		add_action( 'woocommerce_after_cart_table', array( $this, 'add_cart_fields' ) );
	}
	
	/**
	 * Переопределение шаблона корзины
	 */
	public function override_cart_template( $template, $template_name, $template_path ) {
		if ( $template_name === 'cart/cart.php' ) {
			$custom_template = get_stylesheet_directory() . '/template-parts/woocommerce/cart/cart.php';
			
			if ( file_exists( $custom_template ) ) {
				return $custom_template;
			}
		}
		
		return $template;
	}
	
	/**
	 * Добавление заголовка корзины
	 */
	public function add_cart_header() {
		?>
		<div class="cart-header">
			<div class="container">
				<h1 class="cart-title">Корзина</h1>
				<div class="cart-steps">
					<div class="step active">
						<span class="step-number">1</span>
						<span class="step-text">Корзина</span>
					</div>
					<div class="step">
						<span class="step-number">2</span>
						<span class="step-text">Оформление</span>
					</div>
					<div class="step">
						<span class="step-number">3</span>
						<span class="step-text">Оплата</span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Добавление информации о лояльности
	 */
	public function add_loyalty_info() {
		if ( ! is_user_logged_in() ) {
			return;
		}
		
		$user_id = get_current_user_id();
		$user = get_userdata( $user_id );
		$email = $user->user_email;
		
		// Получение данных лояльности
		$loyalty_data = $this->get_loyalty_data( $email );
		
		if ( $loyalty_data['lp-status'] === 'TRUE' ) {
			?>
			<div class="loyalty-info">
				<div class="loyalty-header">
					<h3>Ваши баллы лояльности</h3>
					<div class="loyalty-balance">
						<span class="balance-amount"><?php echo esc_html( $loyalty_data['total_points'] ); ?></span>
						<span class="balance-label">баллов</span>
					</div>
				</div>
				<div class="loyalty-actions">
					<button type="button" class="btn btn-secondary" id="use-loyalty-points">
						Использовать баллы
					</button>
					<div class="loyalty-info-text">
						<p>Вы можете использовать баллы для оплаты заказа</p>
					</div>
				</div>
			</div>
			<?php
		}
	}
	
	/**
	 * Кастомизация поля количества
	 */
	public function customize_quantity_input( $product_quantity, $cart_item, $cart_item_key ) {
		$product = $cart_item['data'];
		$product_id = $cart_item['product_id'];
		$quantity = $cart_item['quantity'];
		
		ob_start();
		?>
		<div class="quantity-wrapper">
			<button type="button" class="quantity-btn minus" data-action="minus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
				<svg width="12" height="2" viewBox="0 0 12 2" fill="none">
					<path d="M1 1H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</button>
			<input type="number" 
				   class="quantity-input" 
				   name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" 
				   value="<?php echo esc_attr( $quantity ); ?>" 
				   min="1" 
				   max="<?php echo esc_attr( $product->get_max_purchase_quantity() ); ?>"
				   data-key="<?php echo esc_attr( $cart_item_key ); ?>">
			<button type="button" class="quantity-btn plus" data-action="plus" data-key="<?php echo esc_attr( $cart_item_key ); ?>">
				<svg width="12" height="12" viewBox="0 0 12 12" fill="none">
					<path d="M6 1V11M1 6H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
				</svg>
			</button>
		</div>
		<?php
		return ob_get_clean();
	}
	
	/**
	 * Добавление дополнительных полей в корзину
	 */
	public function add_cart_fields() {
		?>
		<div class="cart-additional-fields">
			<div class="field-group">
				<label for="cart_note">Примечание к заказу</label>
				<textarea id="cart_note" name="cart_note" rows="3" placeholder="Добавьте примечание к заказу..."></textarea>
			</div>
			
			<div class="field-group">
				<label>
					<input type="checkbox" name="gift_wrapping" value="1">
					Подарочная упаковка (+500 ₽)
				</label>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Добавление футера корзины
	 */
	public function add_cart_footer() {
		?>
		<div class="cart-footer">
			<div class="container">
				<div class="cart-actions">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn btn-outline">
						Продолжить покупки
					</a>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn btn-primary">
						Оформить заказ
					</a>
				</div>
			</div>
		</div>
		<?php
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
}

// Инициализация класса
new Cart_Template();