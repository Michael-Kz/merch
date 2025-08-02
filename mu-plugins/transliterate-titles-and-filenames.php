<?php
/*
Plugin Name: Transliterate Titles and Filenames
Description: Транслитерация заголовков и имен файлов по ГОСТ или ISO стандарту
Version: 1.0
*/

if (!defined('ABSPATH')) exit;


$gost = array(
   "Є"=>"EH","І"=>"I","і"=>"i","№"=>"#","є"=>"eh",
   "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g","Д"=>"d",
   "Е"=>"e","Ё"=>"e","Ж"=>"zh",
   "З"=>"z","И"=>"i","Й"=>"j","К"=>"k","Л"=>"l",
   "М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"r",
   "С"=>"s","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
   "Ц"=>"c","Ч"=>"ch","Ш"=>"sh","Щ"=>"shch","Ъ"=>"",
   "Ы"=>"y","Ь"=>"","Э"=>"eh","Ю"=>"yu","Я"=>"ya",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"jo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shch","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya","«"=>"","»"=>"","—"=>"-"
);

$iso = array(
   "Є"=>"ye","І"=>"i","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
   "А"=>"a","Б"=>"b","В"=>"V","Г"=>"G","Д"=>"D",
   "Е"=>"e","Ё"=>"е","Ж"=>"zh",
   "З"=>"z","И"=>"i","Й"=>"j","К"=>"k","Л"=>"L",
   "М"=>"m","Н"=>"n","О"=>"o","П"=>"p","Р"=>"R",
   "С"=>"s","Т"=>"t","У"=>"u","Ф"=>"f","Х"=>"h",
   "Ц"=>"c","Ч"=>"ch","Ш"=>"sh","Щ"=>"shch","Ъ"=>"'",
   "Ы"=>"y","Ь"=>"","Э"=>"E","Ю"=>"yu","Я"=>"ya",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"yo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shch","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya","«"=>"","»"=>"","—"=>"-"
);


function sanitize_title_with_translit($title) {
    global $gost, $iso;
    $rtl_standard = get_option('rtl_standard');
    switch ($rtl_standard) {
        case 'off':
            return $title;        
        case 'gost':
            return strtr($title, $gost);
        default: 
            return strtr($title, $iso);
    }
}
add_action('sanitize_title', 'sanitize_title_with_translit', 0);


function transliterate_uploaded_filename($filename) {
    global $gost, $iso;
    

    $parts = pathinfo($filename);
    $name = $parts['filename'];
    $extension = isset($parts['extension']) ? '.' . $parts['extension'] : '';
    

    $rtl_standard = get_option('rtl_standard');
    

    switch ($rtl_standard) {
        case 'off':
            $transliterated = $name;
            break;
        case 'gost':
            $transliterated = strtr($name, $gost);
            break;
        default: 
            $transliterated = strtr($name, $iso);
    }
    

    $transliterated = preg_replace('/[^\w\-]+/u', '-', $transliterated);
    $transliterated = preg_replace('/\-+/', '-', $transliterated);
    $transliterated = trim($transliterated, '-');
    

    return $transliterated . $extension;
}
add_filter('sanitize_file_name', 'transliterate_uploaded_filename', 10, 1);


function translit_settings_init() {
    register_setting('general', 'rtl_standard', array(
        'default' => 'iso',
        'show_in_rest' => true,
    ));
    
    add_settings_field(
        'rtl_standard',
        'Стандарт транслитерации',
        'translit_settings_callback',
        'general',
        'default',
        array(
            'label_for' => 'rtl_standard'
        )
    );
}
add_action('admin_init', 'translit_settings_init');

function translit_settings_callback() {
    $option = get_option('rtl_standard');
    ?>
    <select name="rtl_standard" id="rtl_standard">
        <option value="iso" <?php selected($option, 'iso'); ?>>ISO</option>
        <option value="gost" <?php selected($option, 'gost'); ?>>GOST</option>
        <option value="off" <?php selected($option, 'off'); ?>>Отключено</option>
    </select>
    <p class="description">Выберите стандарт транслитерации для заголовков и имен файлов</p>
    <?php
}