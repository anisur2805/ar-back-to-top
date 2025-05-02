<?php

/*
 * Main Assets class for enqueue admin and frontend assets
 */

class AR_Back_To_Top_Assets {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ) );
	}

	public function admin_enqueue( $hook ) {
		if ( 'toplevel_page_arbtt' === $hook ) {
			wp_enqueue_style( 'arbtt_admin', ARBTTOP_ASSETS . '/css/admin-style.css', array(), ARBTTOP_VERSION, 'all' );
			wp_enqueue_style( 'jquery_minicolors', ARBTTOP_ASSETS . '/minicolors/jquery.minicolors.css', array(), '1.0', 'all' );
			wp_enqueue_style( 'arbtt_fa', ARBTTOP_ASSETS . '/css/font-awesome.min.css', array(), '1.0', 'all' );
			wp_enqueue_script( 'arbtt_minucolor_js', ARBTTOP_ASSETS . '/minicolors/jquery.minicolors.min.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'arbtt_custom_js', ARBTTOP_ASSETS . '/js/arbtt-main.js', array( 'jquery' ), ARBTTOP_VERSION, true );
		}
	}

	public static function frontend_enqueue() {
		$extension_enabled = get_option( 'arbtt_enable', false );

		if ( ! $extension_enabled ) {
			return;
		}

		wp_enqueue_style( 'arbtt_fe_admin', ARBTTOP_ASSETS . '/css/style.css', array(), ARBTTOP_VERSION, 'all' );
		wp_enqueue_style( 'arbtt_fa', ARBTTOP_ASSETS . '/css/font-awesome.min.css', array(), '4.7.0', 'all' );
		wp_enqueue_script( 'arbtt_custom_js', ARBTTOP_ASSETS . '/js/arbtt-fe.js', array( 'jquery' ), ARBTTOP_VERSION, true );

		$btn_visible_after = ( get_option( 'arbtt_btnapr' ) ) ? get_option( 'arbtt_btnapr' ) : '100';
		$fade_in           = ( get_option( 'arbtt_fadein' ) ) ? get_option( 'arbtt_fadein' ) : '950';

		$arobj_array = array(
			'btn_visible_after' => $btn_visible_after,
			'fade_in'           => $fade_in,
		);

		wp_localize_script( 'arbtt_custom_js', 'arbtt_obj', $arobj_array );
	}
}

new AR_Back_To_Top_Assets();
