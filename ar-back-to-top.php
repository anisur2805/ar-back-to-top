<?php
/*
 * Plugin Name: AR Back To Top
 * Plugin URI: https://github.com/anisur2805/ar-back-to-top
 * Description: AR Back To Top is a standard WordPress plugin for back to top. AR Back To Top plugin will help them who don't wants to write code. For use this plugin simply download or add new plugin from WordPress plugin directory.
 * Version: 1.0.3
 * Author: Anisur Rahman
 * Author URI: https://github.com/anisur2805
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: arbtt
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function arbtt_admin_page() {
	add_menu_page( __('AR Back 2 top', 'arbtt'), __( 'AR Back To Top', 'arbtt' ), "manage_options", "arbtt", "arbtt_mp_cb", "dashicons-arrow-up-alt", 100 );
}
add_action("admin_menu", "arbtt_admin_page");
function arbtt_mp_cb(){
	?>
	<div class="wrap">
		<?php // settings_errors(); ?>
		<h1><?php echo __('Back to Top Options', 'arbtt') ?></h1>
		<form method="post" action="options.php" id="arbtt">
			<?php
			settings_fields("arbtt_ssection_id");
			do_settings_sections("arbtt");
			submit_button(); ?>
		</form>
	</div>
	<?php
}
function addFieldRegister($id,$labelTxt,$slug,$idCallback,$registerId){

	add_settings_field($id,
		'<label for="'.$id.'">'
		.__($labelTxt , $slug ).
		'</label>', 
		$idCallback,
		$slug,
		$registerId);

	register_setting($registerId, $id);
}
function callTest(){
	addFieldRegister('arbtt_enable','Enable Back to top','arbtt','arbtt_enable_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_bgc','Button Background Color','arbtt','arbtt_bgc_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_clr','Button Color','arbtt','arbtt_clr_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_bdrd','Button Border Radius','arbtt','arbtt_bdrd_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btnps','Button Position','arbtt','arbtt_btnps_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btnapr','Button Appear In Scroll After','arbtt','arbtt_btnapr_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btndm','Button Dimension','arbtt','arbtt_btndm_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btnoc','Button Opacity','arbtt','arbtt_btnoc_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_fadein','Fade In','arbtt','arbtt_fadein_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_fz','Font Size','arbtt','arbtt_fz_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_hophone',__('Hide On Phone', 'arbtt'),'arbtt','arbtt_hophone_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_pwidth',__('Phone Width', 'arbtt'),'arbtt','arbtt_pwidth_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btnst',__('Button Style', 'arbtt'),'arbtt','arbtt_btnst_cb','arbtt_ssection_id');

	addFieldRegister('arbtt_fi',__('Font Icon', 'arbtt'),'arbtt','arbtt_fi_cb','arbtt_ssection_id');
	addFieldRegister('arbtt_btntx',__('Button Text', 'arbtt'),'arbtt','arbtt_btntx_cb','arbtt_ssection_id');

	addFieldRegister('arbtt_btnimg',__('Choose Button Image', 'arbtt'),'arbtt','arbtt_btnimg_cb','arbtt_ssection_id');

	addFieldRegister('arbtt_btnimg_url',__('External Image Url', 'arbtt'),'arbtt','arbtt_btnimg_url_cb','arbtt_ssection_id');
}
add_action("admin_init", "callTest");

function arbtt_display() {
	add_settings_section('arbtt_ssection_id', __('Choose Your Option', 'arbtt'), 'arbtt_idss', 'arbtt');
}
function arbtt_idss(){echo "";}
function arbtt_enable_cb(){ ?>
	<input type="checkbox" name="arbtt_enable" id="arbtt_enable" value="1"<?php checked('1', get_option('arbtt_enable'));?>>
	<?php
}
function arbtt_bgc_cb(){ ?>
	<input type="text" name="arbtt_bgc" class="arcs" id="arbtt_bgc" placeholder="#000" value="<?php echo get_option('arbtt_bgc');?>"/>
	<?php
}
function arbtt_clr_cb(){ ?>
	<input type="text" name="arbtt_clr" class="arcs" id="arbtt_clr" class="arbtt_clr" placeholder="#f5f5f5" value="<?php echo get_option('arbtt_clr') ?>"/>
	<?php
}
function arbtt_bdrd_cb(){ ?>
	<input type="number" name="arbtt_bdrd" class="aras" id="arbtt_bdrd" class="arbtt_bdrd" placeholder="1" value="<?php echo get_option('arbtt_bdrd') ?>"/>
	<span class="description"><?php echo __('Widthout Pixels', 'arbtt'); ?></span>
<?php }
function arbtt_btnps_cb(){ ?>
	<select name="arbtt_btnps" id="arbtt_btnps">
		<option value="left"<?php selected( "left", get_option( "arbtt_btnps" ));?>><?php echo __('Left Side', 'arbtt') ?></option>
		<option value="right"<?php selected( "right", get_option( "arbtt_btnps" ));?>><?php echo __('Right Side', 'arbtt') ?></option>
	</select>
<?php }
function arbtt_btnapr_cb(){ ?>
	<input type="number" name="arbtt_btnapr" class="aras arbtt_btnapr" id="arbtt_btnapr" placeholder="100" value="<?php echo get_option('arbtt_btnapr');?>"/>
	<span class="description"><?php echo __('Widthout Pixels', 'arbtt') ?></span>
<?php }

function arbtt_btndm_cb(){
	$arbtt_btndm = get_option('arbtt_btndm');
	global $arbtt_btndm; ?>
	<input type="number" min="40" name="arbtt_btndm['w']" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="40" value="<?php echo $arbtt_btndm['w'];?>"/>
	X
	<input type="number" min="40" name="arbtt_btndm['h']" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="40" value="<?php echo $arbtt_btndm['h'];?>"/>
	<span class="description"><?php echo __('Width & Height (Widthout Pixels)', 'arbtt') ?></span>
<?php }
function arbtt_btnoc_cb(){ ?>
	<input type="text" min="0.0" max="1.0" name="arbtt_btnoc" class="aras arbtt_btnoc" id="arbtt_btnoc" placeholder="0.6" value="<?php echo get_option('arbtt_btnoc');?>"/>
	<span class="description"><?php echo __('Min 0.0 - Max 1.0', 'arbtt') ?></span>
<?php }


function arbtt_fi_cb(){ ?>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="angle-up"<?php checked( 'angle-up', get_option( 'arbtt_fi' ));?>"/>
	<i class="fa fa-angle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-circle-o-up"<?php checked( 'arrow-circle-o-up', get_option( 'arbtt_fi' )); ?>"/>
	<i class="fa fa-arrow-circle-o-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-circle-up"<?php checked( 'arrow-circle-up', get_option( 'arbtt_fi' ));?>"/>
	<i class="fa fa-arrow-circle-up arw"></i>
	<input type="radio" name="arbtt_fi" class="" id="arbtt_fi" value="arrow-up"<?php checked( 'arrow-up', get_option( 'arbtt_fi' ));?>"/>
	<i class="fa fa-arrow-up arw"></i>
<?php }

function arbtt_btntx_cb(){
	echo '<input type="text" name="arbtt_btntx" class="aras arbtt_btntx" id="arbtt_btntx" placeholder="Top" value="'.get_option("arbtt_btntx").'"/>
	<span class="description"> '. __("This will show as your button text", "arbtt") .' </span>';
}

// function arbtt_btnimg_cb(){
// 	$images = array(
// 		'0'=> 'arbtt.png',
// 		'1'=> 'arbtt2.png',
// 		'2'=> 'arbtt3.png',
// 		'3'=> 'arbtt4.png',
// 		'4'=> 'arbtt5.png',
// 		'5'=> 'arbtt6.png',
// 	);
// 	foreach ($images as $image) {
// 		?>
 		<!-- <input type="radio" name="arbtt_btnimg" class="" id="arbtt_btnimg" value=" '. $image .' "<?php checked( $image, get_option( 'arbtt_btnimg' ))?>"/><img width="25" height="25" src="<?php echo plugins_url( 'assets/images/'.$image, __FILE__ )?>">&nbsp;&nbsp;&nbsp;  -->
 		<?php
// 	}
// }

function arbtt_btnimg_url_cb(){ ?>
	<?php $external_img_url = (get_option('arbtt_btnimg_url')) ? (get_option('arbtt_btnimg_url')) : 'http://trantor.sheridanc.on.ca/student/nasirmuh/Images/Back-to-Top.png'; ?>
	<input type="text" name="arbtt_btnimg_url" class="aras arbtt_btnimg_url" id="arbtt_btnimg_url" placeholder="http://trantor.sheridanc.on.ca/student/nasirmuh/Images/Back-to-Top.png" value="<?php echo get_option('arbtt_btnimg_url');?>"/>

	<img src="<?php echo $external_img_url; ?>" width="25" height="25"/><br/>
	<span class="description"><?php echo __('Url for external image', 'arbtt') ?></span>
<?php }

function arbtt_fadein_cb(){ ?>
	<input type="number" name="arbtt_fadein" class="aras arbtt_fadein" id="arbtt_fadein" placeholder="800" value="<?php echo get_option('arbtt_fadein');?>"/>
	<span class="description"><?php echo __('Mili-second', 'arbtt') ?></span>
<?php }
function arbtt_fz_cb(){ ?>
	<input type="number" min="14" name="arbtt_fz" class="aras arbtt_fz" id="arbtt_fz" placeholder="24px" value="<?php echo get_option('arbtt_fz');?>"/>
	<span class="description"><?php echo __('Widthout Pixels', 'arbtt') ?></span>
<?php }
function arbtt_hophone_cb(){ ?>
	<input type="checkbox" name="arbtt_hophone" id="arbtt_hophone" value="1"<?php checked('1', get_option('arbtt_hophone'));?>><span class="description"><?php echo __('Checked if you want to hide icon on phone')?> </span> <?php 
}
function arbtt_pwidth_cb(){
	global $arbtt_pwidth;
	$arbtt_pwidth = get_option('arbtt_pwidth'); ?>
	<input type="number" name="arbtt_pwidth" class="aras arbtt_pwidth" id="arbtt_pwidth" placeholder="767" value="<?php echo get_option('arbtt_pwidth');?>"/>
	<span class="description"><?php echo __('Widthout Pixels', 'arbtt') ?></span>
	<?php
}
function arbtt_btnst_cb(){ ?>
	<select name="arbtt_btnst" id="arbtt_btnst">
		<option value="" selected="selected"><?php echo __('Select Option', 'arbtt'); ?></option>
		<option value="fa"<?php selected( "fa", get_option( "arbtt_btnst" ));?>><?php echo __('Font Awesome Icon', 'arbtt'); ?></option>
		<option value="txt"<?php selected( "txt", get_option( "arbtt_btnst" ));?>><?php echo __('Text', 'arbtt'); ?></option>
		<option value="img"<?php selected( "img", get_option( "arbtt_btnst" ));?>><?php echo __('Image', 'arbtt'); ?></option>
	</select>

<?php 

	//var_dump(get_option( "arbtt_btnst" ));

}

add_action("admin_init", "arbtt_display");

add_action( "admin_enqueue_scripts", "arbtt_enqueue" );
function arbtt_enqueue($hook) {	
	wp_enqueue_style('arbtt_admin', plugins_url('assets/css/admin-style.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_style('jquery_minicolors', plugins_url('assets/minicolors/jquery.minicolors.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_style('arbtt_fa', plugins_url('assets/css/font-awesome.min.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_script('jquery');
	wp_enqueue_script('arbtt_minucolor_js', plugins_url('assets/minicolors/jquery.minicolors.min.js', __FILE__ ), array('jquery'), '1.0', true);
	wp_enqueue_script('arbtt_custom_js', plugins_url('assets/js/arbtt-main.js', __FILE__ ), array('jquery'), '1.0', true);
}

add_action( "wp_enqueue_scripts", "arbtt_enqueue_frontend");
function arbtt_enqueue_frontend() {
	wp_enqueue_style('arbtt_fe_admin', plugins_url('assets/css/style.css', __FILE__ ), array(), '1.0', 'all' );
	wp_enqueue_style('arbtt_fa', plugins_url('assets/css/font-awesome.min.css', __FILE__ ), array(), '4.7.0', 'all' );
	wp_enqueue_script('jquery');
	wp_enqueue_script('arbtt_custom_js', plugins_url('assets/js/arbtt-fe.js', __FILE__ ), array('jquery'), '1.0', true);
	$dataToPass = (get_option('arbtt_btnapr')) ? get_option('arbtt_btnapr') : '100';
	$arbtt_fadein = (get_option('arbtt_fadein')) ? get_option('arbtt_fadein') : '1200';
	$arobj_array = array('a_value' => $dataToPass, 'sctoptime'=> $arbtt_fadein);
	wp_localize_script( 'arbtt_custom_js', 'object_name', $arobj_array );
}
add_action( 'wp_footer', 'arbtt_top' );
function arbtt_top() {
	global $arbtt_btndm;
	global $arbtt_pwidth;

	$arbtt_bgc = (get_option('arbtt_bgc')) ? get_option('arbtt_bgc') : '#000';
	$arbtt_fz = (get_option('arbtt_fz')) ? get_option('arbtt_fz') : '20';
	$arbtt_clr = (get_option('arbtt_clr')) ? get_option('arbtt_clr') : '#fff';
	$arbtt_btnps = (get_option('arbtt_btnps')) ? get_option('arbtt_btnps') : 'right';
	$arbtt_btnps = (get_option('arbtt_btnps')) ? get_option('arbtt_btnps') : 'right';
	$arbtt_btnoc = (get_option('arbtt_btnoc')) ? get_option('arbtt_btnoc') : '0.5';

	$arbtt_bdrd = (get_option('arbtt_bdrd')) ? get_option('arbtt_bdrd') : '0';
	$arbtt_btndmw = (get_option($arbtt_btndm['w'])) ? get_option($arbtt_btndm['w']) : '40';
	$arbtt_btndmh = (get_option($arbtt_btndm['h'])) ? get_option($arbtt_btndm['h']) : '40';
	$arbtt_enable = get_option('arbtt_enable');

	$arbtt_hophone = get_option('arbtt_hophone');
	$arbtt_pwidth = (get_option($arbtt_pwidth)) ? get_option($arbtt_pwidth) : '767';
	$abc = get_option('arbtt_pwidth');

	$arbtt_btntx = (get_option('arbtt_btntx')) ? get_option('arbtt_btntx') : 'Top';
	$arbtt_fi = (get_option('arbtt_fi')) ? get_option('arbtt_fi') : 'arrow-up';
	$arbtt_btnst = (get_option('arbtt_btnst')) ? get_option('arbtt_btnst') : 'txt';

	// $arbtt_btnimage = (get_option('arbtt_btnimg')) ? get_option('arbtt_btnimg') : 'ggg';

	$display = ($arbtt_enable) ? "block" : "none"; 

	?>
	<style type="text/css">
	#arbtt-container{ display: <?php echo $display; ?>; }
	.arbtt {width:<?php echo $arbtt_btndmw; ?>px; height:<?php echo $arbtt_btndmh; ?>px;line-height:<?php echo $arbtt_btndmh; ?>px;padding: 0;text-align:center;font-weight: bold;color:<?php echo $arbtt_clr; ?>!important;text-decoration: none!important;position: fixed;bottom:75px; <?php echo $arbtt_btnps; ?> :40px;display:none; background-color: <?php echo $arbtt_bgc; ?> !important;opacity: <?php echo $arbtt_btnoc; ?>;border-radius: <?php echo $arbtt_bdrd; ?>px;z-index: 9999;}
	.arbtt:hover {opacity: 0.7;cursor: pointer;}
	.arbtt .fa{line-height: <?php echo $arbtt_btndmh; ?>px;font-size: <?php echo $arbtt_fz; ?>px;height: <?php echo $arbtt_btndmh; ?>px;width:<?php echo $arbtt_btndmw; ?>px;display: block;}
	.arbtt:visited, .arbtt:focus{color: #fff;outline: 0;}
	<?php if($arbtt_hophone=='1'){ ?>@media(max-width: <?php echo $abc; ?>px){ #arbtt-container{display:none; }} <?php } ?>
</style>
<div class="arbtt-container" id="arbtt-container"> 
	<a class="arbtt" id="arbtt">
		<?php if($arbtt_btnst=='fa' ): ?>
			<span class="fa fa-<?php echo $arbtt_fi; ?>"></span>
			<?php elseif($arbtt_btnst=='txt' ): ?>
			<?php echo $arbtt_btntx; ?>
		<?php elseif($arbtt_btnst=='img'): ?>

		<!-- <img src="http://localhost/arfvwp/wp-content/plugins/ar-back-to-top/assets/images/<?php echo $arbtt_btnimage ?>"/> -->

		<?php endif; ?>

</a>
</div>
<?php }

register_activation_hook( __FILE__, 'arbtt_activation' );
function arbtt_activation(){
	$arbtt_option = get_option('arbtt_option');
	add_option('arbtt_do_activation_redirect', true);
}
add_action('admin_init', 'arbtt_redirect');

function arbtt_redirect(){
	if(get_option('arbtt_do_activation_redirect', false)){
		delete_option( 'arbtt_do_activation_redirect' );
		
		wp_safe_redirect( 'options-general.php?page=arbtt' );
		
	}
}

// remove option on uninstall
function arbtt_uninstall_hook(){

	if( ! current_user_can( 'activate_plugins' ) ) return;

	delete_option( 'arbtt' );
}
register_uninstall_hook( __FILE__, 'arbtt_uninstall_hook' );

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'arbtt_action_links' );
function arbtt_action_links( $links ) {
	$links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=arbtt') ) .'">Settings</a>';
	$links[] = '<a href="https://github.com/anisur2805/ar-back-to-top" target="_blank">Support</a>';
	return $links;
}