<?php
add_action( "admin_enqueue_scripts", "arbtt_enqueue" );
function arbtt_enqueue($hook) {
    if( $hook === 'toplevel_page_arbtt' ) {
        wp_enqueue_style('arbtt_admin', ARBTTOP_ASSETS . '/css/admin-style.css', array(), ARBTTOP_VERSION, 'all' );
        wp_enqueue_style('jquery_minicolors', ARBTTOP_ASSETS . '/minicolors/jquery.minicolors.css', array(), '1.0', 'all' );
        wp_enqueue_style('arbtt_fa', ARBTTOP_ASSETS . '/css/font-awesome.min.css', array(), '1.0', 'all' );
        wp_enqueue_script('arbtt_minucolor_js', ARBTTOP_ASSETS . '/minicolors/jquery.minicolors.min.js', array('jquery'), '1.0', true);
        wp_enqueue_script('arbtt_custom_js', ARBTTOP_ASSETS . '/js/arbtt-main.js', array('jquery'), ARBTTOP_VERSION, true);
    }
}

add_action( "wp_enqueue_scripts", "arbtt_enqueue_frontend");
function arbtt_enqueue_frontend() {
	wp_enqueue_style('arbtt_fe_admin', ARBTTOP_ASSETS . '/css/style.css', array(), ARBTTOP_VERSION, 'all' );
	wp_enqueue_style('arbtt_fa', ARBTTOP_ASSETS . '/css/font-awesome.min.css', array(), '4.7.0', 'all' );
	wp_enqueue_script('arbtt_custom_js', ARBTTOP_ASSETS . '/js/arbtt-fe.js', array('jquery'), ARBTTOP_VERSION, true);
	$dataToPass     = (get_option('arbtt_btnapr')) ? get_option('arbtt_btnapr') : '100';
	$arbtt_fadein   = (get_option('arbtt_fadein')) ? get_option('arbtt_fadein') : '950';
	$arobj_array    = array('a_value' => $dataToPass, 'sctoptime'=> $arbtt_fadein);
	wp_localize_script( 'arbtt_custom_js', 'object_name', $arobj_array );
}