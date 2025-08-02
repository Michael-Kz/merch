<?php
/**
 * Theme Assets Management Class
 * 
 * @package Astra Child
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Класс для управления ресурсами темы
 */
class Theme_Assets {
	
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
		// Подключение стилей и скриптов
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		
		// Локализация скриптов
		add_action( 'wp_enqueue_scripts', array( $this, 'localize_scripts' ) );
		
		// Оптимизация загрузки
		add_action( 'wp_head', array( $this, 'add_preload_links' ) );
		add_action( 'wp_footer', array( $this, 'add_defer_scripts' ) );
	}
	
	/**
	 * Подключение основных ресурсов
	 */
	public function enqueue_assets() {
		// Основные стили
		wp_enqueue_style(
			'astra-child-main',
			get_stylesheet_directory_uri() . '/assets/css/main.css',
			array(),
			filemtime( get_stylesheet_directory() . '/assets/css/main.css' )
		);
		
		// Стили для WooCommerce
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style(
				'astra-child-woocommerce',
				get_stylesheet_directory_uri() . '/assets/css/woocommerce.css',
				array( 'astra-child-main' ),
				filemtime( get_stylesheet_directory() . '/assets/css/woocommerce.css' )
			);
		}
		
		// Основные скрипты
		wp_enqueue_script(
			'astra-child-main',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			array( 'jquery' ),
			filemtime( get_stylesheet_directory() . '/assets/js/main.js' ),
			true
		);
		
		// Скрипты для лояльности
		if ( is_user_logged_in() ) {
			wp_enqueue_script(
				'astra-child-loyalty',
				get_stylesheet_directory_uri() . '/assets/js/loyalty.js',
				array( 'jquery', 'astra-child-main' ),
				filemtime( get_stylesheet_directory() . '/assets/js/loyalty.js' ),
				true
			);
		}
		
		// Swiper для слайдеров
		if ( $this->should_load_swiper() ) {
			wp_enqueue_style(
				'swiper-css',
				'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css',
				array(),
				'8.0.0'
			);
			
			wp_enqueue_script(
				'swiper-js',
				'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js',
				array(),
				'8.0.0',
				true
			);
		}
	}
	
	/**
	 * Подключение административных ресурсов
	 */
	public function enqueue_admin_assets( $hook ) {
		// Стили для административной панели
		wp_enqueue_style(
			'astra-child-admin',
			get_stylesheet_directory_uri() . '/assets/css/admin.css',
			array(),
			filemtime( get_stylesheet_directory() . '/assets/css/admin.css' )
		);
		
		// Скрипты для административной панели
		wp_enqueue_script(
			'astra-child-admin',
			get_stylesheet_directory_uri() . '/assets/js/admin.js',
			array( 'jquery' ),
			filemtime( get_stylesheet_directory() . '/assets/js/admin.js' ),
			true
		);
	}
	
	/**
	 * Локализация скриптов
	 */
	public function localize_scripts() {
		// Локализация для основного скрипта
		wp_localize_script( 'astra-child-main', 'astraChild', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'astra_child_nonce' ),
			'isLoggedIn' => is_user_logged_in(),
			'currentUser' => wp_get_current_user()->user_email,
		) );
		
		// Локализация для скрипта лояльности
		if ( is_user_logged_in() ) {
			wp_localize_script( 'astra-child-loyalty', 'aleanLoyalty', array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'alean_loyalty_nonce' ),
				'restUrl' => rest_url( 'alean/v1/' ),
				'apiUrl' => rest_url( 'alean/v1/bonus' ),
			) );
		}
	}
	
	/**
	 * Добавление preload ссылок
	 */
	public function add_preload_links() {
		// Preload для критических ресурсов
		echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/assets/css/main.css" as="style">';
		echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/assets/js/main.js" as="script">';
		
		// Preload для шрифтов
		echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/assets/fonts/main-font.woff2" as="font" type="font/woff2" crossorigin>';
	}
	
	/**
	 * Добавление defer для скриптов
	 */
	public function add_defer_scripts() {
		// Добавляем defer для некритических скриптов
		?>
		<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Инициализация ленивой загрузки изображений
			if ('IntersectionObserver' in window) {
				const imageObserver = new IntersectionObserver((entries, observer) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const img = entry.target;
							img.src = img.dataset.src;
							img.classList.remove('lazy');
							imageObserver.unobserve(img);
						}
					});
				});
				
				document.querySelectorAll('img[data-src]').forEach(img => {
					imageObserver.observe(img);
				});
			}
		});
		</script>
		<?php
	}
	
	/**
	 * Проверка необходимости загрузки Swiper
	 */
	private function should_load_swiper() {
		// Проверяем, есть ли на странице слайдеры
		global $post;
		
		if ( $post && has_shortcode( $post->post_content, 'swiper' ) ) {
			return true;
		}
		
		// Проверяем по типу страницы
		if ( is_front_page() || is_home() ) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Получение версии файла для кэширования
	 */
	public static function get_file_version( $file_path ) {
		if ( file_exists( $file_path ) ) {
			return filemtime( $file_path );
		}
		return '1.0.0';
	}
	
	/**
	 * Минификация CSS
	 */
	public static function minify_css( $css ) {
		// Удаляем комментарии
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );
		
		// Удаляем лишние пробелы
		$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );
		
		return $css;
	}
	
	/**
	 * Минификация JavaScript
	 */
	public static function minify_js( $js ) {
		// Простая минификация - удаление комментариев и лишних пробелов
		$js = preg_replace( '/\/\*.*?\*\//s', '', $js );
		$js = preg_replace( '/\/\/.*$/m', '', $js );
		$js = preg_replace( '/\s+/', ' ', $js );
		
		return trim( $js );
	}
}

// Инициализация класса
new Theme_Assets();