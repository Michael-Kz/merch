<?php

/*
* Plugin Name: Разрешение загрузки SVG
* Version: 1.0
* Author: alean.ru
* Author URI: https://alean.ru/
* Text Domain: https://alean.ru/
* Description: Плагин для загрузки SVG
*/

add_filter( 'upload_mimes', 'svg_upload_allow' );

function svg_upload_allow( $mimes ) {
    $mimes[ 'svg' ]  = 'image/svg+xml';

    return $mimes;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ) {

    if ( version_compare( $GLOBALS[ 'wp_version' ], '5.1.0', '>=' ) ) {
        $dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
    } else {
        $dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
    }

    if ( $dosvg ) {

        if ( current_user_can( 'manage_options' ) ) {

            $data[ 'ext' ]  = 'svg';
            $data[ 'type' ] = 'image/svg+xml';
        } else {
            $data[ 'ext' ]  = false;
            $data[ 'type' ] = false;
        }

    }

    return $data;
}

?>