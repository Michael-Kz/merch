<?php

//Внешний вид страницы входа ==========================================|







function aleanAdminStyles() {
    wp_enqueue_style('aleanAdminStyles', plugins_url('assets/css/aleanAdminStyles.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'aleanAdminStyles');

//

function aleanAdminLoginPageStyles() {
    echo '<link rel="stylesheet" href="' . plugin_dir_url(__FILE__) . 'assets/css/adminLoginPageStyles.css" />';
}
add_action('login_enqueue_scripts', 'aleanAdminLoginPageStyles');

//


function alter_login_headerurl() {
return '/'; 
}
add_action('login_headerurl','alter_login_headerurl');
//

// ====================================================================|





## Изменение внутреннего логотипа админки. Для версий с dashicons
add_action('add_admin_bar_menus', 'reset_admin_wplogo');
function reset_admin_wplogo(  ){
	remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 ); // удаляем стандартную панель (логотип)

	add_action( 'admin_bar_menu', 'my_admin_bar_wp_menu', 10 ); // добавляем свою
}
function my_admin_bar_wp_menu( $wp_admin_bar ) {
	$wp_admin_bar->add_menu( array(
		'id'    => 'wp-logo',
		'title' => '<img style="max-width:30px;height:auto;" src="/wp-content/uploads/logo.png" alt="" >', // иконка dashicon
				 // можно вставить картинку 
		'href'  => 'https://www.alean.ru/',
		'meta'  => array(
			'title' => 'Национальный туроператор «Алеан»',
		),
	) );
}

########ПУНКТ ССЫЛКИ############
add_filter( 'pre_option_link_manager_enabled', '__return_true' );
