<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	astra_content_after();

	astra_footer_before();

	astra_footer();

	astra_footer_after();
?>
	</div><!-- #page -->
<?php
	//astra_body_bottom();
	wp_footer();
?>

<footer class="footer" itemscope itemtype="http://schema.org/WPFooter">
    <div class="footer__menu-container">
        <div class="footer__social social" itemscope
            itemtype="http://schema.org/Organization">
            <!-- noindex -->
            <a href="https://www.alean.ru/" class="footer__logo logo" itemscope
                itemtype="http://schema.org/ImageObject"
                rel="nofollow noopener noreferrer" target="_blank">
                <img decoding="async" loading="lazy"
                    src="/wp-content/uploads/Logo.png" class="logo__image"
                    alt="Национальный туроператор Алеан" itemprop="image">
                <meta content="Логотип национального туроператора Алеан"
                    itemprop="name">
                <meta
                    content="Экстранет Алеан - Продать номер стало еще проще. В ТОПе OTA продаж"
                    itemprop="description">
            </a>

            <div class="social__wrapper" itemscope
                itemtype="http://schema.org/Organization">
                <link itemprop="url" href="https://www.alean.ru/">
                <meta itemprop="name" content="Алеан Мерч">
                <meta itemprop="description"
                    content="Официальный магазин мерча Алеан">


                <a href="https://web.telegram.org/a/"
                    class="social__link social__link--telegram"
                    itemprop="sameAs">

                    <svg class="social__icon" viewBox="0 0 16 16"  fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.8552 3.15112C13.649 2.97633 13.3253 2.95132 12.9906 3.08571H12.99C12.638 3.22697 3.02475 7.35038 2.63341 7.51884C2.56223 7.54358 1.94059 7.77552 2.00462 8.29219C2.06178 8.75801 2.56141 8.95093 2.62242 8.97319L5.06639 9.81002C5.22854 10.3498 5.82627 12.3411 5.95846 12.7665C6.04091 13.0317 6.1753 13.3802 6.41082 13.4519C6.61748 13.5316 6.82305 13.4588 6.95606 13.3544L8.45026 11.9685L10.8624 13.8496L10.9198 13.884C11.0836 13.9565 11.2405 13.9928 11.3903 13.9928C11.506 13.9928 11.617 13.9711 11.7231 13.9277C12.0845 13.7793 12.229 13.4349 12.2442 13.3959L14.0459 4.03082C14.1558 3.53065 14.003 3.27616 13.8552 3.15112ZM7.22181 10.1448L6.39735 12.3433L5.57289 9.59511L11.8938 4.92316L7.22181 10.1448Z"
                            fill="currentColor" />
                    </svg>

                </a>
                <a href="https://m.vk.com/"
                    class="social__link social__link--vk" itemprop="sameAs">
                    <svg  class="social__icon" viewBox="0 0 16 16"  fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M14.3604 4.65458C14.4532 4.34554 14.3604 4.11841 13.9193 4.11841H12.4606C12.0897 4.11841 11.9188 4.3146 11.8261 4.531C11.8261 4.531 11.0842 6.33903 10.0334 7.51347C9.6934 7.85345 9.53888 7.96149 9.35342 7.96149C9.26069 7.96149 9.12649 7.85345 9.12649 7.54442V4.65458C9.12649 4.28366 9.01886 4.11841 8.70973 4.11841H6.41753C6.18581 4.11841 6.0464 4.29053 6.0464 4.4537C6.0464 4.80534 6.57173 4.8864 6.62591 5.87538V8.02349C6.62591 8.49443 6.54089 8.57987 6.35543 8.57987C5.86094 8.57987 4.65816 6.76361 3.94466 4.68552C3.80483 4.28157 3.66459 4.11841 3.2918 4.11841H1.83312C1.41636 4.11841 1.33301 4.3146 1.33301 4.531C1.33301 4.91734 1.8276 6.83363 3.63563 9.36797C4.84112 11.0986 6.53922 12.0369 8.08458 12.0369C9.01188 12.0369 9.12649 11.8285 9.12649 11.4696V10.1615C9.12649 9.74472 9.21433 9.66158 9.50783 9.66158C9.72434 9.66158 10.0952 9.76973 10.9607 10.6042C11.9497 11.5931 12.1126 12.0369 12.669 12.0369H14.1277C14.5444 12.0369 14.7528 11.8285 14.6326 11.4173C14.5011 11.0074 14.0289 10.4129 13.4023 9.70794C13.0623 9.30608 12.5523 8.87337 12.3979 8.65707C12.1815 8.37888 12.2433 8.25531 12.3979 8.00817C12.3979 8.00817 14.175 5.50457 14.3604 4.65458Z"
                            fill="currentColor" />
                    </svg>


                </a>
                <a href="https://www.youtube.com/"
                    class="social__link social__link--youtube"
                    itemprop="sameAs">

                    <svg class="social__icon" viewBox="0 0 16 16"  fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8 3.16211C8.39032 3.16211 11.8413 3.17027 12.8271 3.43457C13.1926 3.53291 13.5264 3.72559 13.7939 3.99316C14.0613 4.26065 14.2533 4.59379 14.3516 4.95898L14.3994 5.15723C14.6209 6.19813 14.623 7.92151 14.623 7.99902C14.623 8.08305 14.6198 10.0371 14.3516 11.0391C14.2532 11.4045 14.0606 11.7383 13.793 12.0059C13.5255 12.2732 13.1923 12.4652 12.8271 12.5635C11.8421 12.8278 8.39033 12.8359 8 12.8359C7.60946 12.8359 4.15815 12.8288 3.17285 12.5645C2.80745 12.4663 2.47459 12.2734 2.20703 12.0059C1.93953 11.7384 1.7466 11.4054 1.64844 11.04C1.37986 10.0393 1.37696 8.08839 1.37695 8C1.37695 7.91734 1.37986 5.96239 1.64844 4.95996C1.74685 4.5945 1.9394 4.26079 2.20703 3.99316C2.47465 3.72556 2.80839 3.53298 3.17383 3.43457C4.15937 3.17036 7.60952 3.16211 8 3.16211ZM6.96191 6.00684C6.90355 6.00682 6.84646 6.0226 6.7959 6.05176C6.74517 6.08104 6.70303 6.12306 6.67383 6.17383C6.64462 6.22462 6.6287 6.28223 6.62891 6.34082V9.6582C6.62891 9.74645 6.66427 9.83108 6.72656 9.89355C6.78907 9.95607 6.87449 9.99121 6.96289 9.99121C7.02115 9.99105 7.07829 9.97611 7.12891 9.94727L10.002 8.28809C10.0526 8.25883 10.0948 8.21669 10.124 8.16602C10.1532 8.11549 10.1689 8.05832 10.1689 8C10.1689 7.94149 10.1533 7.88368 10.124 7.83301C10.0948 7.78236 10.0526 7.74019 10.002 7.71094L7.12891 6.05176C7.07834 6.02239 7.0204 6.00694 6.96191 6.00684Z"
                            fill="currentColor" />
                    </svg>

                </a>
                <a href="https://www.whatsapp.com/"
                    class="social__link social__link--whatsapp"
                    itemprop="sameAs">
                    <svg class="social__icon" viewBox="0 0 16 16"  fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.40585 4.85871C8.19873 4.94161 8.81571 5.61197 8.816 6.42609V6.42999C8.81223 7.19964 8.25923 7.83684 7.52987 7.97394C7.74404 8.37034 8.03275 8.70236 8.35604 8.96613C8.5052 9.08777 8.6583 9.18822 8.80722 9.27472C8.93317 8.526 9.58393 7.9575 10.3678 7.95929L10.3707 7.95831L10.3697 7.95929C11.0103 7.96056 11.5871 8.34725 11.8307 8.93976C12.074 9.53248 11.9363 10.214 11.481 10.6653C11.1802 10.9636 10.7799 11.12 10.3726 11.1214C9.43425 11.264 8.29101 10.8486 7.4078 10.1282C6.4338 9.33363 5.64337 8.07058 5.64999 6.42316L5.65389 6.34601C5.65718 6.31522 5.66555 6.28572 5.67245 6.25617C5.75761 5.46355 6.4304 4.84766 7.24472 4.84992L7.40585 4.85871Z"
                            fill="currentColor" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.78964 3.0755C6.89703 1.35863 9.87886 1.17014 12.193 2.63312L12.4127 2.77863C14.6483 4.32499 15.6593 7.13601 14.898 9.76593C14.1119 12.4806 11.6266 14.3482 8.80038 14.3489C7.75159 14.3505 6.72152 14.0897 5.80038 13.595L2.566 14.3314C2.29231 14.3934 2.00641 14.2972 1.82577 14.0823C1.64539 13.8674 1.60023 13.5688 1.70858 13.3099L2.92343 10.4056C1.90496 7.92031 2.55931 5.04389 4.58847 3.2464L4.78964 3.0755ZM11.3912 3.9007C9.62384 2.78343 7.34645 2.92744 5.7369 4.23859L5.58261 4.36945C3.96701 5.80076 3.49179 8.12815 4.41659 10.0784C4.50781 10.2708 4.51334 10.4934 4.43124 10.6898L3.65389 12.5443L5.74667 12.0687L5.89022 12.0501C6.03327 12.0457 6.17602 12.0828 6.30038 12.1575C7.05408 12.6118 7.91839 12.8512 8.79843 12.8499L9.00155 12.846C11.0759 12.7599 12.8759 11.3575 13.4576 9.34894C14.039 7.34034 13.2666 5.19311 11.5592 4.01203L11.3912 3.9007Z"
                            fill="currentColor" />
                    </svg>

                </a>
                <a href="https://rutube.ru/"
                    class="social__link social__link--rutube" itemprop="sameAs">
                    <svg class="social__icon" viewBox="0 0 16 16"  fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.8574 3.79834H1.83301V13.5353H4.34476V10.3675H9.15776L11.3538 13.5353H14.1663L11.7448 10.3529C12.4968 10.2362 13.0383 9.95876 13.3692 9.52084C13.7 9.08292 13.8655 8.38217 13.8655 7.44792V6.71801C13.8655 6.16326 13.8054 5.72534 13.7001 5.38959C13.5948 5.05384 13.4143 4.76184 13.1586 4.49909C12.8878 4.25092 12.5871 4.07576 12.2261 3.95901C11.8651 3.85684 11.4138 3.79834 10.8574 3.79834ZM10.4513 8.22167H4.34476V5.94426H10.4513C10.7972 5.94426 11.0378 6.00259 11.1582 6.10484C11.2785 6.20701 11.3538 6.39676 11.3538 6.67417V7.49167C11.3538 7.78359 11.2785 7.97334 11.1582 8.07551C11.0378 8.17767 10.7973 8.22151 10.4513 8.22151V8.22167Z"
                            fill="currentColor" />
                        <path
                            d="M13.6606 3.63151C14.1206 3.63151 14.4938 3.25843 14.4938 2.79818C14.4938 2.33793 14.1206 1.96484 13.6606 1.96484C13.2003 1.96484 12.8271 2.33793 12.8271 2.79818C12.8271 3.25843 13.2003 3.63151 13.6606 3.63151Z"
                            fill="currentColor" />
                    </svg>

                </a>
                <!-- /noindex -->
            </div>
            <p class="copyright">
              Copyright &copy; <?php echo date_i18n('Y'); ?> | Alean
            </p>
        </div>
        <div class="footer__text-wrapper">
            <div class="footer__title h4" itemprop="name">Алеан Мерч</div>
            <div class="footer__description" itemprop="description">
                Qui dolore ipsum quia dolor sit amet, consec tetur adipisci
                velit, sed quia non numquam eius modi tempora incidunt lores ta
                porro ame.
            </div>
        </div>
        <div class="footer__menu menu">
            <nav class="menu__body">
                <ul class="menu__list" itemscope
                    itemtype="http://schema.org/SiteNavigationElement">
                    <li class="menu__item" itemprop="name">
                        <a href="/" class="menu__link"
                            itemprop="url">Главная</a>
                    </li>
                    <li class="menu__item" itemprop="name">
                        <a href="/" class="menu__link"
                            itemprop="url">Магазин</a>
                    </li>
                    <li class="menu__item" itemprop="name">
                        <a href="/" class="menu__link" itemprop="url">О нас</a>
                    </li>
                    <li class="menu__item" itemprop="name">
                        <a href="/" class="menu__link"
                            itemprop="url">Контакты</a>
                    </li>
                </ul>
            </nav>
        </div>
<div class="footer__contacts contacts" itemscope itemtype="http://schema.org/ContactPoint">
    <link itemprop="url" href="https://www.alean.ru/">
    <a href="tel:+79999777000" class="contacts__phone h4" itemprop="telephone">+7 (999) 9-777-000</a>
    <a href="mailto:merch@alean.ru" class="contacts__mail" itemprop="email">merch@alean.ru</a>
    
    <?php

    if (!is_user_logged_in()) {
    ?>
        <a href="<?php echo esc_url(home_url('/?sso_login=1')); ?>" class="footer__button--sso button--sso">

            <span class="button__text">Войти через SSO</span>
        </a>
    <?php
    }
    ?>
</div>
    </div>
</footer>


<style>

</style>
 




<?php








/*


















$settings = get_option('theme_settings');
if (!empty($settings['logo_url'])) {
    echo '<img src="' . esc_url($settings['logo_url']) . '" alt="Логотип" class="logo">';
}



















$settings = get_option('theme_settings');
if (!empty($settings['phone'])) {
    echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $settings['phone'])) . '">' . esc_html($settings['phone']) . '</a>';
}

if (!empty($settings['email'])) {
    echo '<a href="mailto:' . esc_attr($settings['email']) . '">' . esc_html($settings['email']) . '</a>';
}











$settings = get_option('theme_settings');
$socials = [
    'whatsapp' => 'WhatsApp',
    'telegram' => 'Telegram',
    'rutube' => 'Rutube',
    'youtube' => 'YouTube',
    'vk' => 'VK'
];

foreach ($socials as $key => $name) {
    if (!empty($settings[$key])) {
        echo '<a href="' . esc_url($settings[$key]) . '" target="_blank" rel="noopener noreferrer" class="social-link ' . esc_attr($key) . '">' . esc_html($name) . '</a>';
    }
}
*/
?><script>

document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('cart_phone');
    
    if (phoneInput) {
        // Обработчик ввода
        phoneInput.addEventListener('input', function(e) {
            const input = e.target;
            const position = input.selectionStart;
            const oldValue = input.value;
            
            // Удаляем все нецифровые символы
            let cleanValue = oldValue.replace(/\D/g, '');
            
            // Обработка специальных случаев
            if (cleanValue === '7' || cleanValue === '8') {
                cleanValue = '';
            }
            
            // Форматируем значение
            let formattedValue = '+7 (';
            if (cleanValue.length > 0) {
                // Убираем первую 7 или 8 если есть
                if (cleanValue[0] === '7' || cleanValue[0] === '8') {
                    cleanValue = cleanValue.substring(1);
                }
                
                // Добавляем цифры в маску
                if (cleanValue.length > 0) {
                    formattedValue += cleanValue.substring(0, 3);
                }
                if (cleanValue.length > 3) {
                    formattedValue += ') ' + cleanValue.substring(3, 6);
                }
                if (cleanValue.length > 6) {
                    formattedValue += '-' + cleanValue.substring(6, 8);
                }
                if (cleanValue.length > 8) {
                    formattedValue += '-' + cleanValue.substring(8, 10);
                }
            }
            
            // Устанавливаем новое значение
            input.value = formattedValue;
            
            // Восстанавливаем позицию курсора
            const diff = formattedValue.length - oldValue.length;
            input.setSelectionRange(position + diff, position + diff);
        });
        
        // Обработка ввода +7
        phoneInput.addEventListener('keydown', function(e) {
            if (e.key === '+' && this.value === '') {
                this.value = '+7 (';
                e.preventDefault();
                this.setSelectionRange(4, 4);
            }
        });
    }
});

//====================================================

        </script>
	</body>
</html>
