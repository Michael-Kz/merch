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
require_once get_stylesheet_directory() . '/inc/class-alean-loyalty-plugin.php';
require_once get_stylesheet_directory() . '/inc/class-theme-assets.php';
require_once get_stylesheet_directory() . '/inc/class-cart-template.php';