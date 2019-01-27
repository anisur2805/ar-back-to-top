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

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once ( plugin_dir_path( __FILE__ ) . 'ar-front-end.php' );


function arbtt_admin_page() {
	add_menu_page("AR Back 2 top","Back To Top","manage_options","arbtt","arbtt_mp_cb","dashicons-arrow-up-alt", 100 );
}

function arbtt_mp_cb() { ?>
	<div class="wrap">
		<?php settings_errors(); ?>
		<h1>Back to Top Options</h1>
		<form method="post" action="options.php" id="arbtt" enctype="multipart/form-data">
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

	// Border Radius
	add_settings_field('arbtt_bdr', '<label for="arbtt_bdr">'.__('Button Border' , 'arbtt_bdr' ).'</label>', 'arbtt_bdr_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_bdr");

	// Border Color
	add_settings_field('arbtt_bdclr', '<label for="arbtt_bdclr">'.__('Border Color' , 'arbtt_bdclr' ).'</label>', 'arbtt_bdclr_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_bdclr");

	// Button Position
	add_settings_field('arbtt_btnps', '<label for="arbtt_btnps">'.__('Button Position' , 'arbtt_btnps' ).'</label>', 'arbtt_btnps_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_btnps");

	// Button Appear In
	add_settings_field('arbtt_btnapr', '<label for="arbtt_btnapr">'.__('Button Appear In' , 'arbtt_btnapr' ).'</label>', 'arbtt_btnapr_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_btnapr");

	// Button Dimentsion In
	add_settings_field('arbtt_btndm', '<label for="arbtt_btndm">'.__('Button Dimension' , 'arbtt_btndm' ).'</label>', 'arbtt_btndm_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_btndm");

	// Button Opacity
	add_settings_field('arbtt_btnoc', '<label for="arbtt_btnoc">'.__('Button Opacity' , 'arbtt_btnoc' ).'</label>', 'arbtt_btnoc_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_btnoc");

	// Font Icon
	add_settings_field('arbtt_fi', '<label for="arbtt_fi">'.__('Font Icon' , 'arbtt_fi' ).'</label>', 'arbtt_fi_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_fi");

}

function arbtt_idss(){echo "";}

function arbtt_enable_cb() {
	?> 
	<input type="checkbox" name="arbtt_enable" id="arbtt_enable" value="1" <?php checked('1', get_option('arbtt_enable')); ?>> 
	<?php
}

function arbtt_bgc_cb() {
	 ?> 
	<input type="text" name="arbtt_bgc" class="arcs" id="arbtt_bgc" placeholder="#000" value="<?php echo get_option('arbtt_bgc'); ?>"/> 
	<?php
}
function arbtt_clr_cb() {
	?> 
	<input type="text" name="arbtt_clr" class="arcs" id="arbtt_clr" class="arbtt_clr" placeholder="#f5f5f5" value="<?php echo get_option('arbtt_clr') ?>"/>
	<?php
}
function arbtt_bdr_cb() {
	 ?> 
	<input type="text" name="arbtt_bdr" class="aras" id="arbtt_bdr" class="arbtt_bdr" placeholder="2px" value="<?php echo get_option('arbtt_bdr') ?>"/><span class="description">In Pixels</span>
	<?php
}
function arbtt_bdclr_cb() {
	?> 
	<input type="text" name="arbtt_bdclr" class="arcs arbtt_bdclr" id="arbtt_bdclr" placeholder="#000" value="<?php echo get_option('arbtt_bdclr'); ?>"/><span class="description">In Pixels</span>
	<?php
}
function arbtt_btnps_cb() { ?>
	<select name="arbtt_btnps" id="arbtt_btnps">
		<option value="left" <?php echo get_option('arbtt_btnps'); ?>>Left Side</option>
		<option value="right" <?php echo get_option('arbtt_btnps'); ?>>Right Side</option>
	</select>
	<?php
}
function arbtt_btnapr_cb() {
	?> 
	<input type="text" name="arbtt_btnapr" class="aras arbtt_btnapr" id="arbtt_btnapr" placeholder="100px" value="<?php echo get_option('arbtt_btnapr'); ?>"/><span class="description">After Top Scrolling (In Pixels )</span>
	<?php
}
function arbtt_btndm_cb() {
	?> 
	<input type="text" name="arbtt_btndm" class="aras arbtt_btndm" id="arbtt_btndm" placeholder="60px X 60px" value="<?php echo get_option('arbtt_btndm'); ?>"/><span class="description">Width & Height (In Pixels )</span>
	<?php
}
function arbtt_btnoc_cb() {	
	?> 
	<input type="text" name="arbtt_btnoc" class="aras arbtt_btnoc" id="arbtt_btnoc" placeholder="0.6" value="<?php echo get_option('arbtt_btnoc'); ?>"/>
	<?php
}
function arbtt_fi_cb() {
	?> 
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="1" <?php checked( '1', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-angle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="2" <?php checked( '2', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-circle-o-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="3" <?php checked( '3', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-circle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="3" <?php checked( '4', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-circle-up arw"></i>

	<?php
}

add_action("admin_init", "arbtt_display");

add_action( "admin_enqueue_scripts", "arbtt_enqueue" );
function arbtt_enqueue($hook) {
	// if($hook != 'arbtt') {
	// 	return;
	// }

	wp_enqueue_style('arbtt_admin', plugins_url('assets/css/admin-style.css', __FILE__ ) );
	wp_enqueue_style('jquery_minicolors', plugins_url('node_modules/@claviska/jquery-minicolors/jquery.minicolors.css', __FILE__ ) );
	wp_enqueue_style('arbtt_fa', plugins_url('assets/css/font-awesome.min.css', __FILE__ ) );

	wp_enqueue_script('arbtt_js', plugins_url('assets/js/jquery.min.js', __FILE__ ), null, true);
	wp_enqueue_script('arbtt_minucolor_js', plugins_url('assets/js/jquery.minicolors.min.js', __FILE__ ), null, true);
	wp_enqueue_script('arbtt_custom_js', plugins_url('assets/js/arbtt-main.js', __FILE__ ), array('jquery'), true);
	// }
}

add_action( "wp_enqueue_scripts", "arbtt_enqueue_frontend" );
function arbtt_enqueue_frontend( ) {
	wp_enqueue_style('arbtt_fe_admin', plugins_url('assets/css/style.css', __FILE__ ) );
	wp_enqueue_style('arbtt_fa', plugins_url('assets/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_script('arbtt_js', plugins_url('assets/js/jquery.min.js', __FILE__ ), null, true);
	wp_enqueue_script('arbtt_custom_js', plugins_url('assets/js/arbtt-fe.js', __FILE__ ), array('jquery'), true);
}

$arbtt_enable = get_option('arbtt_enable');
$arbtt_bgc = get_option('arbtt_bgc');
$arbtt_clr = get_option('arbtt_clr');
$arbtt_btnps = get_option('arbtt_btnps');
$arbtt_clr = get_option('arbtt_clr'); 
$arbtt_bdr = get_option('arbtt_bdr');
$arbtt_bdclr = get_option('arbtt_bdclr');
$arbtt_btnapr = get_option('arbtt_btnapr'); 
$arbtt_btndm = get_option('arbtt_btndm'); 
$arbtt_btnoc = get_option('arbtt_btnoc');
$arbtt_fi = get_option('arbtt_fi');

?>

<style type="text/css">
	.arbtt {
		position: <?php echo $arbtt_btnps; ?> !important;
		/*<?php //echo $arbtt; ?>: 40px !important;*/
		background-color: <?php echo $arbtt_bgc; ?> !important;
		color: <?php echo $arbtt_clr; ?> !important;
		border: <?php echo $arbtt_bdr; ?> solid <?php echo $arbtt_bdclr; ?>;
	}
</style>