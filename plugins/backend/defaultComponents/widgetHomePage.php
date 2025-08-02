<?php


add_action( 'dashboard_glance_items' , 'add_right_now_info' );
function add_right_now_info( $items ){

	if( ! current_user_can('edit_posts') ) return $items; 


	$args = array( 'public' => true, '_builtin' => false );

	$post_types = get_post_types( $args, 'object', 'and' );

	foreach( $post_types as $post_type ){
		$num_posts = wp_count_posts( $post_type->name );
		$num       = number_format_i18n( $num_posts->publish );
		$text      = _n( $post_type->labels->singular_name, $post_type->labels->name, intval( $num_posts->publish ) );

		$items[] = "<a href=\"edit.php?post_type=$post_type->name\">$text : $num</a>";
	}


	$taxonomies = get_taxonomies( $args, 'object', 'and' );

	foreach( $taxonomies as $taxonomy ){
		$num_terms = wp_count_terms( $taxonomy->name );
		$num       = number_format_i18n( $num_terms );
		$text      = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ) );

		$items[] = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num $text</a>";
	}


	global $wpdb;

	$num  = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users");
	$text = _n( 'User', 'Users', $num );

	$items[] = "<a href='users.php'>$num $text</a>";

	return $items;
}


## Удаление виджетов из Консоли WordPress
add_action( 'wp_dashboard_setup', 'clear_dash', 99 );
function clear_dash(){
	$side   = & $GLOBALS['wp_meta_boxes']['dashboard']['side']['core'];
	$normal = & $GLOBALS['wp_meta_boxes']['dashboard']['normal']['core'];


	$remove = array(
		'dashboard_primary',  
	);
	foreach( $remove as $id ){
		unset( $side[$id], $normal[$id] ); 
	}

	
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}

#########################################################

add_filter("admin_footer_text", "footer_admin_func");
function footer_admin_func()
{
	echo 'Разработка: <a href="https://www.alean.ru/" target="_blank">Национальный туроператор «Алеан»</a>.';
}
########################################################

function usefulLinksWidget() {
    wp_add_dashboard_widget(
        'usefulLinksWidget_id', // Уникальный ID
        'Полезные сервисы', // Заголовок виджета
        'usefulLinksWidget_display' // Функция для отображения содержимого виджета
    );
}

function usefulLinksWidget_display() {
    echo '
<strong>Работа с контентом:</strong>
<ul>
  <li><a href="https://unsplash.com/" target="_blank" rel="noopener noreferrer">Подбор изображений для новостей</a></li>
  <li><a href="https://www.iloveimg.com/ru/compress-image" target="_blank" rel="noopener noreferrer">Работа с изображениями</a></li>
</ul>

<strong>Поисковые системы:</strong>
<ul>
  <li><a href="https://metrika.yandex.ru/overview?id=71015518" target="_blank" rel="noopener noreferrer">Яндекс-метрика</a></li>
  <li><a href="https://search.google.com/u/0/search-console?resource_id=https://rst.ru/" target="_blank" rel="noopener noreferrer">Вебмастер Гугл</a></li>
  <li><a href="https://webmaster.yandex.ru/site/https:rst.ru:443/dashboard/" target="_blank" rel="noopener noreferrer">Вебмастер Яндекс</a></li>
  <li><a href="https://yandex.ru/sprav/companies" target="_blank" rel="noopener noreferrer">Яндекс cправочник</a></li>
</ul>



    ';
}

add_action('wp_dashboard_setup', 'usefulLinksWidget');
