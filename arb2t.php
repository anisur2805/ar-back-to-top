<?php
/*
 * Plugin Name: Back 2 Top
 * Plugin URI: https://github.com/anisur2805/
 * Description: Back to Top Buttons
 * Version: 1.0.0
 * Author: Anisur Rahman
 * Author URI: https://github.com/anisur2805
 * License: GPL2
 * Text Domain: arbtt
 * 
*/
?>

<?php
// defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function arbtt_admin_page() {
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page("AR Back 2 top","Back To Top","manage_options","arbtt","arbtt_mp_cb","dashicons-arrow-up-alt", 100 );

}

function arbtt_mp_cb() {
	?>
	<div class="wrap">

		<?php settings_errors(); ?>
		<h1>Back to Top Options</h1>

		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php

			settings_fields("arbtt_ssection_id");
			do_settings_sections("arbtt");
			submit_button(); 

			?>          
		</form>
	</div>
	<?php
}

add_action("admin_menu", "arbtt_admin_page");

function arbtt_display() {
	add_settings_section("arbtt_ssection_id", "Choose Your Option", "arbtt_idss", "arbtt");
	add_settings_field('arbtt_enable', '<label for="arbtt_enable">'.__('Enable Back to top' , 'arbtt_enable' ).'</label>', 'arbtt_enable_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_enable");

	// Button Background
	add_settings_field('arbtt_bgc', '<label for="arbtt_bgc">'.__('Button Background Color' , 'arbtt_bgc' ).'</label>', 'arbtt_bgc_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_bgc");

	// Button Color
	add_settings_field('arbtt_clr', '<label for="arbtt_clr">'.__('Button Color' , 'arbtt_clr' ).'</label>', 'arbtt_clr_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_clr");

}

function arbtt_idss(){echo "";}

function arbtt_enable_cb() {

	$options = get_option('arbtt_enable');
	?> 
	<input type="checkbox" name="arbtt_enable" id="arbtt_enable" value="1" <?php checked(1, get_option('arbtt_enable'), true); ?>> 


	<?php
}

function arbtt_bgc_cb() {

	$options = get_option('arbtt_bgc');
	?> 
	<input type="checkbox" name="arbtt_bgc" id="arbtt_bgc" value="1" <?php checked(1, get_option('arbtt_bgc'), true); ?>> 


	<?php
}

function arbtt_clr_cb() {

	$options = get_option('arbtt_clr');
	?> 
	<input type="text" name="arbtt_clr" id="arbtt_clr" class="arbtt_clr" value="<?php checked(1, get_option('arbtt_clr'), true); ?>"/>

	<?php
}

add_action("admin_init", "arbtt_display");


add_action( 'wp_enqueue_scripts', 'arbtt_enqueue' );
function arbtt_enqueue($hook) {

	global $pagenow;
	if($pagenow=="arbtt"){


		// wp_enqueue_style( 'jquery-minicolors', plugins_url( 'node_modules/@claviska/jquery-minicolors/jquery.minicolors.css', __FILE__ ) );
		// wp_enqueue_style( 'font-awesome.min', plugins_url( 'assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ) );
		// 
		wp_enqueue_style( 'abc', plugins_url('assets/css/style.css'));

		wp_enqueue_script('jqmj',  plugins_url('assets/js/jquery.min.js', __FILE__ ) );
		// wp_enqueue_script( 'jquery-minicolors-js', plugins_url( 'node_modules/@claviska/jquery-minicolors/jquery.minicolors.min.js', __FILE__ ), array('jquery') );
	    wp_enqueue_script( 'arbtt-main', plugins_url('assets/js/arbtt-main.js', __FILE__ ), array('jquery'));
	}
}
