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
	add_menu_page("AR Back 2 top","Back To Top","manage_options","arbtt","arbtt_mp_cb","dashicons-arrow-up-alt", 100);
}
function arbtt_mp_cb(){?>
	<div class="wrap">
<?php settings_errors();?>
		<h1>Back to Top Options</h1>
		<form method="post" action="options.php" id="arbtt" enctype="multipart/form-data">
<?php
			settings_fields("arbtt_ssection_id");
			do_settings_sections("arbtt");
			submit_button(); ?>
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
	// add_settings_field('arbtt_bdr', '<label for="arbtt_bdr">'.__('Button Border' , 'arbtt_bdr' ).'</label>', 'arbtt_bdr_cb', 'arbtt', 'arbtt_ssection_id');
	// register_setting("arbtt_ssection_id", "arbtt_bdr");
	// Border Color
	// add_settings_field('arbtt_bdclr', '<label for="arbtt_bdclr">'.__('Border Color' , 'arbtt_bdclr' ).'</label>', 'arbtt_bdclr_cb', 'arbtt', 'arbtt_ssection_id');
	// register_setting("arbtt_ssection_id", "arbtt_bdclr");
	// Button Position
	add_settings_field('arbtt_btnps', '<label for="arbtt_btnps">'.__('Button Position' , 'arbtt_btnps' ).'</label>', 'arbtt_btnps_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_btnps");
	// Button Appear In
	add_settings_field('arbtt_btnapr', '<label for="arbtt_btnapr">'.__('Button Appear In Scroll After' , 'arbtt_btnapr' ).'</label>', 'arbtt_btnapr_cb', 'arbtt', 'arbtt_ssection_id');
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
	// Fade In
	add_settings_field('arbtt_fadein', '<label for="arbtt_fadein">'.__('Font Icon' , 'arbtt_fadein' ).'</label>', 'arbtt_fadein_cb', 'arbtt', 'arbtt_ssection_id');
	register_setting("arbtt_ssection_id", "arbtt_fadein");
}

function arbtt_idss(){echo "";}

function arbtt_enable_cb() { ?>
	<input type="checkbox" name="arbtt_enable" id="arbtt_enable" value="1"<?php checked('1', get_option('arbtt_enable')); ?>>
<?php
}

function arbtt_bgc_cb(){ ?>
	<input type="text" name="arbtt_bgc" class="arcs" id="arbtt_bgc" placeholder="#000" value="<?php echo get_option('arbtt_bgc'); ?>"/> 
<?php
}
function arbtt_clr_cb(){ ?>
	<input type="text" name="arbtt_clr" class="arcs" id="arbtt_clr" class="arbtt_clr" placeholder="#f5f5f5" value="<?php echo get_option('arbtt_clr') ?>"/>
<?php
}
function arbtt_bdr_cb(){ ?>
	<!-- <input type="number" name="arbtt_bdr" class="aras" id="arbtt_bdr" class="arbtt_bdr" placeholder="2" value="<?php echo get_option('arbtt_bdr') ?>"/><span class="description">Pixels</span> -->
<?php
}
function arbtt_bdclr_cb(){ ?>
	<!-- <input type="text" name="arbtt_bdclr" class="arcs arbtt_bdclr" id="arbtt_bdclr" placeholder="#000" value="<?php echo get_option('arbtt_bdclr'); ?>"/><span class="description">Pixels</span> -->
<?php
}
function arbtt_btnps_cb(){ ?>
	<select name="arbtt_btnps" id="arbtt_btnps">
		<option value="left"<?php selected( "left", get_option( "arbtt_btnps" )); ?>>Left Side</option>
		<option value="right"<?php selected( "right", get_option( "arbtt_btnps" )); ?>>Right Side</option>
	</select>
<?php
}
function arbtt_btnapr_cb(){ ?>
	<input type="text" name="arbtt_btnapr" class="aras arbtt_btnapr" id="arbtt_btnapr" placeholder="100" value="<?php echo get_option('arbtt_btnapr'); ?>"/><span class="description">Pixels</span>
<?php
}
$arbtt_btndm = get_option('arbtt_btndm');
function arbtt_btndm_cb(){
	global $arbtt_btndm; ?>
	<input type="text" name="arbtt_btndm[w]" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="60" value="<?php echo $arbtt_btndm['w']; ?>"/> X <input type="text" name="arbtt_btndm[h]" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="60" value="<?php echo $arbtt_btndm['h']; ?>"/><span class="description">Width & Height (Pixels)</span>
<?php
}
function arbtt_btnoc_cb(){ ?>
	<input type="text" min="0.0" max="1.0" name="arbtt_btnoc" class="aras arbtt_btnoc" id="arbtt_btnoc" placeholder="0.6" value="<?php echo get_option('arbtt_btnoc'); ?>"/><span class="description">Min 0.0 - Max 1.0</span>
<?php
}
function arbtt_fi_cb(){ ?>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="angle-up"<?php checked( 'angle-up', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-angle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-circle-o-up"<?php checked( 'arrow-circle-o-up', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-circle-o-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-circle-up"<?php checked( 'arrow-circle-up', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-circle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-up"<?php checked( 'arrow-up', get_option( 'arbtt_fi' )); ?>"/><i class="fa fa-arrow-up arw"></i>
<?php
}
function arbtt_fadein_cb(){?>
	<input type="text" name="arbtt_fadein" class="aras arbtt_fadein" id="arbtt_fadein" placeholder="800" value="<?php echo get_option('arbtt_fadein'); ?>"/><span class="description">Mili-second</span>
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
}
add_action( "wp_enqueue_scripts", "arbtt_enqueue_frontend");
function arbtt_enqueue_frontend() {
	wp_enqueue_style('arbtt_fe_admin', plugins_url('assets/css/style.css', __FILE__ ) );
	wp_enqueue_style('arbtt_fa', plugins_url('assets/css/font-awesome.min.css', __FILE__ ) );
	wp_enqueue_script('arbtt_js', plugins_url('assets/js/jquery.min.js', __FILE__ ), null, true);

	wp_enqueue_script('arbtt_custom_js', plugins_url('assets/js/arbtt-fe.js', __FILE__ ), array('jquery'), true);

	$dataToPass = (get_option('arbtt_btnapr')) ? get_option('arbtt_btnapr') : '100';
	$arbtt_fadein = (get_option('arbtt_fadein')) ? get_option('arbtt_fadein') : '1200';
	$translation_array = array('a_value' => $dataToPass, 'sctoptime'=> $arbtt_fadein);

	wp_localize_script( 'arbtt_custom_js', 'object_name', $translation_array );
}
$arbtt_enable = get_option('arbtt_enable');
$display = ($arbtt_enable) ? "block" : "none"; ?>
<style type="text/css" media="screen">
#arbtt-container{
	display:<?php echo $display; ?>;
}
</style>	
<?php
$arbtt_bgc = (get_option('arbtt_bgc')) ? get_option('arbtt_bgc') : '#000';
$arbtt_clr = (get_option('arbtt_clr')) ? get_option('arbtt_clr') : '#fff';
$arbtt_btnps = (get_option('arbtt_btnps')) ? get_option('arbtt_btnps') : 'right';
// $arbtt_btnapr = (get_option('arbtt_btnapr')) ? get_option('arbtt_btnapr') : '100';
$arbtt_btnoc = (get_option('arbtt_btnoc')) ? get_option('arbtt_btnoc') : '0.5';
$arbtt_fi = (get_option('arbtt_fi')) ? get_option('arbtt_fi') : 'arrow-up'; ?>

<div class="arbtt-container" id="arbtt-container">
	<a href="#" class="arbtt" id="arbtt"><span class="fa fa-<?php print_r($arbtt_fi); ?>"></span></a>
</div>

<style type="text/css">
.arbtt {width: <?php echo $arbtt_btndm['w']; ?>px; height: <?php echo $arbtt_btndm['h']; ?>px;line-height: 40px;padding: 0;text-align:center;font-weight: bold;color: <?php echo $arbtt_clr;?>;text-decoration: none;position: fixed;bottom:75px;<?php echo $arbtt_btnps;?> :40px;display:none; background-color: <?php echo $arbtt_bgc;?>;opacity: <?php echo $arbtt_btnoc;?>;}
.arbtt:hover {color: <?php //echo $arbtt_bgc;?> !important;background-color: <?php //echo $arbtt_clr;?>!important;opacity: 0.7;}
.arbtt .fa{line-height: inherit;font-size: inherit;height: inherit;width: inherit;}
.arbtt:visited, .arbtt:focus{color: #fff;outline: 0;}
.form-table input#arbtt_btndm.ardm {width: 72px;}
</style>