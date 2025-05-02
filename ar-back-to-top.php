<?php
/**
 * Plugin Name: AR Back To Top
 * Plugin URI: https://github.com/anisur2805/ar-back-to-top
 * Description: AR Back To Top is a standard WordPress plugin for smooth back to top. AR Back To Top plugin will help those who don't want to write code. To use this plugin, simply download or add it from the WordPress plugin directory.
 * Version: 2.10.0
 * Author: Anisur Rahman
 * Author URI: https://github.com/anisur2805
 * Requires at least: 6.2
 * Tested up to: 6.3.1
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: arbtt
 *
 * @package AR_Back_To_Top
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_ar_back_to_top() {

	$client = new Appsero\Client( 'ca204cce-aa47-48b5-8f69-a5fb08fc49b3', 'AR Back To Top', __FILE__ );

	// Active insights.
	$client->insights()->init();

	// Active automatic updater.
	$client->updater();
}

appsero_init_tracker_ar_back_to_top();

/**
 * Class AR_Back_To_Top
 *
 * Main plugin class implementing Singleton pattern
 */
class AR_Back_To_Top {
	/**
	 * Singleton instance
	 *
	 * @var AR_Back_To_Top
	 */
	private static $instance = null;

	/**
	 * Plugin options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Get singleton instance
	 *
	 * @return AR_Back_To_Top
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Private constructor to prevent direct instantiation
	 */
	private function __construct() {
		$this->init_hooks();
		$this->define_constants();
		$this->load_class_files();
	}

	/**
	 * Prevent cloning of the instance
	 *
	 * @return void
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing of the instance
	 *
	 * @return void
	 */
	private function __wakeup() {}

	/**
	 * Define constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'ARBTTOP_VERSION', '2.10.0' );
		define( 'ARBTTOP_FILE', __FILE__ );
		define( 'ARBTTOP_PATH', __DIR__ );
		define( 'ARBTTOP_URL', plugins_url( '', __FILE__ ) );
		define( 'ARBTTOP_ASSETS', ARBTTOP_URL . '/assets' );
		define( 'ARBTTOP_INCLUDES', ARBTTOP_URL . '/inc' );
	}

	public function load_class_files() {
		$class_files = array(
			'inc/Settings.php',
			'inc/Frontend.php',
			'inc/class-assets.php',
		);

		foreach ( $class_files as $class_file ) {
			if ( file_exists( __DIR__ . '/' . $class_file ) ) {
				require_once __DIR__ . '/' . $class_file;
			}
		}
	}

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_init', array( $this, 'add_settings_section' ) );
		add_action( 'wp_footer', array( $this, 'render_back_to_top' ) );
		add_action( 'admin_init', array( $this, 'handle_activation_redirect' ) );

		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		register_uninstall_hook( __FILE__, array( __CLASS__, 'plugin_uninstall' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_action_links' ) );
	}

	/**
	 * Register admin page
	 *
	 * @return void
	 */
	public function register_admin_page() {
		add_menu_page(
			__( 'AR Back To Top', 'arbtt' ),
			__( 'Back To Top', 'arbtt' ),
			'manage_options',
			'arbtt',
			array( $this, 'render_admin_page' ),
			'dashicons-arrow-up-alt',
			100
		);

		add_submenu_page(
			'arbtt',
			__( 'AR Back To Top', 'arbtt' ),
			__( 'Dashboard', 'arbtt' ),
			'manage_options',
			'arbtt',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render admin page
	 *
	 * @return void
	 */
	public function render_admin_page() {
		?>
		<div class="wrap ar-btt-wrap">
			<h1><?php esc_html_e( 'Back To Top Options', 'arbtt' ); ?></h1>
			<form method="post" action="options.php" id="arbtt">
				<?php
				settings_fields( 'arbtt_ssection_id' );
				do_settings_sections( 'arbtt' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings fields
	 *
	 * @return void
	 */
	public function register_settings() {
		$this->register_field( 'arbtt_enable', 'Enable Back To Top', 'arbtt', array( $this, 'render_enable_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnst', __( 'Button Style', 'arbtt' ), 'arbtt', array( $this, 'render_btnst_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fi', __( 'Font Icon', 'arbtt' ), 'arbtt', array( $this, 'render_fi_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btntx', __( 'Button Text', 'arbtt' ), 'arbtt', array( $this, 'render_btntx_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnimg', __( 'Choose Button Image', 'arbtt' ), 'arbtt', array( $this, 'render_btnimg_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnimg_url', __( 'External Image Url', 'arbtt' ), 'arbtt', array( $this, 'render_btnimg_url_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bgc', 'Button Background Color', 'arbtt', array( $this, 'render_bgc_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_clr', 'Button Color', 'arbtt', array( $this, 'render_clr_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bdrd', 'Button Border Radius', 'arbtt', array( $this, 'render_bdrd_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnps', 'Button Position', 'arbtt', array( $this, 'render_btnps_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnapr', 'Button Appear In Scroll After', 'arbtt', array( $this, 'render_btnapr_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btndm', 'Button Dimension', 'arbtt', array( $this, 'render_btndm_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_padding', 'Button Padding', 'arbtt', array( $this, 'render_btn_padding_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnoc', 'Button Opacity', 'arbtt', array( $this, 'render_btnoc_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fadein', 'Fade In', 'arbtt', array( $this, 'render_fadein_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fz', 'Font Size', 'arbtt', array( $this, 'render_fz_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_hophone', __( 'Hide On Phone', 'arbtt' ), 'arbtt', array( $this, 'render_hophone_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_pwidth', __( 'Phone Width', 'arbtt' ), 'arbtt', array( $this, 'render_pwidth_field' ), 'arbtt_ssection_id' );
	}

	/**
	 * Register a field
	 *
	 * @param string $id Field ID.
	 * @param string $text Field label.
	 * @param string $slug Field slug.
	 * @param callable $callback Field callback.
	 * @param string $register_id Register section ID.
	 * @return void
	 */
	private function register_field( $id, $text, $slug, $callback, $register_id ) {
		$label = sprintf( '<label for="%s">%s</label>', $id, $text );

		add_settings_field(
			$id,
			$label,
			$callback,
			$slug,
			$register_id
		);

		register_setting( $register_id, $id );
	}

	/**
	 * Add settings section
	 *
	 * @return void
	 */
	public function add_settings_section() {
		add_settings_section(
			'arbtt_ssection_id',
			__( 'Choose Your Option', 'arbtt' ),
			array( $this, 'render_section_description' ),
			'arbtt'
		);
	}

	/**
	 * Render section description
	 *
	 * @return void
	 */
	public function render_section_description() {
		echo '';
	}

	/**
	 * Render enable field
	 *
	 * @return void
	 */
	public function render_enable_field() {
		?>
		<label class="ar-btt-toggle" for="arbtt_enable">
			<input type="checkbox" name="arbtt_enable" id="arbtt_enable" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_enable' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Render background color field
	 *
	 * @return void
	 */
	public function render_bgc_field() {
		?>
		<input type="text" name="arbtt_bgc" class="arcs" id="arbtt_bgc" placeholder="#000" value="<?php echo esc_attr( get_option( 'arbtt_bgc' ) ); ?>"/>
		<?php
	}

	/**
	 * Render color field
	 *
	 * @return void
	 */
	public function render_clr_field() {
		?>
		<input type="text" name="arbtt_clr" class="arcs" id="arbtt_clr" placeholder="#f5f5f5" value="<?php echo esc_attr( get_option( 'arbtt_clr' ) ); ?>"/>
		<?php
	}

	/**
	 * Render border radius field
	 *
	 * @return void
	 */
	public function render_bdrd_field() {
		?>
		<input type="number" name="arbtt_bdrd" class="aras" id="arbtt_bdrd" placeholder="5" value="<?php echo esc_attr( get_option( 'arbtt_bdrd' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button position field
	 *
	 * @return void
	 */
	public function render_btnps_field() {
		?>
		<select name="arbtt_btnps" id="arbtt_btnps">
			<option value="left"<?php selected( 'left', esc_attr( get_option( 'arbtt_btnps' ) ) ); ?>><?php echo __( 'Left Side', 'arbtt' ); ?></option>
			<option value="right"<?php selected( 'right', esc_attr( get_option( 'arbtt_btnps' ) ) ); ?>><?php echo __( 'Right Side', 'arbtt' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Render button appear field
	 *
	 * @return void
	 */
	public function render_btnapr_field() {
		?>
		<input type="number" name="arbtt_btnapr" class="aras arbtt_btnapr" id="arbtt_btnapr" placeholder="100" value="<?php echo esc_attr( get_option( 'arbtt_btnapr' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button dimension field
	 *
	 * @return void
	 */
	public function render_btndm_field() {
		$arbtt_btndm = get_option( 'arbtt_btndm' );
		$btn_width   = isset( $arbtt_btndm['w'] ) && ! empty( $arbtt_btndm['w'] ) ? $arbtt_btndm['w'] : 40;
		$btn_height  = isset( $arbtt_btndm['h'] ) && ! empty( $arbtt_btndm['h'] ) ? $arbtt_btndm['h'] : 40;
		?>
		<input type="number" name="arbtt_btndm[w]" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="40" value="<?php echo esc_attr( $btn_width ); ?>"/> X <input type="number" name="arbtt_btndm[h]" class="aras arbtt_btndm ardm" id="arbtt_btndm" placeholder="40" value="<?php echo esc_attr( $btn_height ); ?>"/>
		<span class="description"><?php echo __( 'Width & Height (px)', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button padding field
	 *
	 * @return void
	 */
	public function render_btn_padding_field() {
		$arbtt_btn_padding = get_option( 'arbtt_btn_padding' );
		$btn_padding       = isset( $arbtt_btn_padding['p'] ) && ! empty( $arbtt_btn_padding['p'] ) ? $arbtt_btn_padding['p'] : 10;
		?>
		<input type="number" name="arbtt_btn_padding[p]" class="aras arbtt_btn_padding ardm" id="arbtt_btn_padding" placeholder="10" value="<?php echo esc_attr( $btn_padding ); ?>"/>
		<span class="description"><?php echo __( 'Padding (px)', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button opacity field
	 *
	 * @return void
	 */
	public function render_btnoc_field() {
		?>
		<input type="text" min="0.0" max="1.0" name="arbtt_btnoc" class="aras arbtt_btnoc" id="arbtt_btnoc" placeholder="0.6" value="<?php echo esc_attr( get_option( 'arbtt_btnoc' ) ); ?>"/>
		<span class="description"><?php echo __( 'Min 0.0 - Max 1.0', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render font icon field
	 *
	 * @return void
	 */
	public function render_fi_field() {
		?>
		<label for="arbtt_fi_0">
			<input type="radio" name="arbtt_fi" class="arbtt_fi" id="arbtt_fi_0" value="angle-up"<?php checked( 'angle-up', esc_attr( get_option( 'arbtt_fi' ) ) ); ?>"/>
			<i class="fa fa-angle-up arw"></i>
		</label>
		<label for="arbtt_fi_1">
			<input type="radio" name="arbtt_fi" class="arbtt_fi" id="arbtt_fi_1" value="arrow-circle-o-up"<?php checked( 'arrow-circle-o-up', esc_attr( get_option( 'arbtt_fi' ) ) ); ?>"/>
			<i class="fa fa-arrow-circle-o-up arw"></i>
		</label>
		<label for="arbtt_fi_2">
			<input type="radio" name="arbtt_fi" class="arbtt_fi" id="arbtt_fi_2" value="arrow-circle-up"<?php checked( 'arrow-circle-up', esc_attr( get_option( 'arbtt_fi' ) ) ); ?>"/>
			<i class="fa fa-arrow-circle-up arw"></i>
		</label>
		<label for="arbtt_fi_3">
			<input type="radio" name="arbtt_fi" class="arbtt_fi" id="arbtt_fi_3" value="arrow-up"<?php checked( 'arrow-up', esc_attr( get_option( 'arbtt_fi' ) ) ); ?>"/>
			<i class="fa fa-arrow-up arw"></i>
		</label>
		<?php
	}

	/**
	 * Render button text field
	 *
	 * @return void
	 */
	public function render_btntx_field() {
		echo '<input type="text" name="arbtt_btntx" class="aras arbtt_btntx" id="arbtt_btntx" placeholder="Top" value="' . esc_attr( get_option( 'arbtt_btntx' ) ) . '"/>
        <span class="description"> ' . __( 'Button Text', 'arbtt' ) . ' </span>';
	}

	/**
	 * Render button image URL field
	 *
	 * @return void
	 */
	public function render_btnimg_url_field() {
		$external_img_url = ( get_option( 'arbtt_btnimg_url' ) ) ? ( get_option( 'arbtt_btnimg_url' ) ) : ARBTTOP_ASSETS . '/images/Back-to-Top.png';
		?>
		<input type="text" name="arbtt_btnimg_url" class="aras arbtt_btnimg_url" id="arbtt_btnimg_url" placeholder="Enter external image url here" value="<?php echo esc_attr( get_option( 'arbtt_btnimg_url' ) ); ?>"/>
		<img src="<?php echo esc_url( $external_img_url ); ?>" width="25" height="25"/><br/>
		<span class="description"><?php esc_html_e( 'External Image URL', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button image field
	 *
	 * @return void
	 */
	public function render_btnimg_field() {
		$images = array(
			'0' => 'arbtt.png',
			'1' => 'arbtt2.png',
			'2' => 'arbtt3.png',
			'3' => 'arbtt4.png',
			'4' => 'arbtt5.png',
			'5' => 'arbtt6.png',
		);
		foreach ( $images as $key => $image ) {
			?>
			<div class="arbtt-image">
				<label for="arbtt_btnimg_<?php echo $key; ?>">
					<input type="radio" name="arbtt_btnimg" id="arbtt_btnimg_<?php echo $key; ?>" class="arbtt_btnimg" value="<?php echo $image; ?>"<?php checked( $image, esc_attr( get_option( 'arbtt_btnimg' ) ) ); ?>/>
					<img width="25" height="25" src="<?php echo esc_url( ARBTTOP_ASSETS . '/images/' . $image ); ?>">
				</label>
			</div>
			<?php
		}
	}

	/**
	 * Render fade in field
	 *
	 * @return void
	 */
	public function render_fadein_field() {
		?>
		<input type="number" name="arbtt_fadein" class="aras arbtt_fadein" id="arbtt_fadein" placeholder="950" value="<?php echo esc_attr( get_option( 'arbtt_fadein' ) ); ?>"/>
		<span class="description"><?php echo __( 'Mili-second', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render font size field
	 *
	 * @return void
	 */
	public function render_fz_field() {
		?>
		<input type="number" min="14" name="arbtt_fz" class="aras arbtt_fz" id="arbtt_fz" placeholder="24px" value="<?php echo esc_attr( get_option( 'arbtt_fz' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render hide on phone field
	 *
	 * @return void
	 */
	public function render_hophone_field() {
		?>
		<label class="ar-btt-toggle" for="arbtt_hophone">
			<input type="checkbox" name="arbtt_hophone" id="arbtt_hophone" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_hophone' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
			<span class="description"><?php echo __( 'Checked for hide icon on phone' ); ?> </span>
		</label>
		<?php
	}

	/**
	 * Render phone width field
	 *
	 * @return void
	 */
	public function render_pwidth_field() {
		?>
		<input type="number" name="arbtt_pwidth" class="aras arbtt_pwidth" id="arbtt_pwidth" placeholder="767" value="<?php echo esc_attr( get_option( 'arbtt_pwidth' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button style field
	 *
	 * @return void
	 */
	public function render_btnst_field() {
		?>
		<select name="arbtt_btnst" id="arbtt_btnst">
			<option value="" selected="selected"><?php echo __( 'Select Option', 'arbtt' ); ?></option>
			<option value="fa"<?php selected( 'fa', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Font Awesome Icon', 'arbtt' ); ?></option>
			<option value="txt"<?php selected( 'txt', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Text', 'arbtt' ); ?></option>
			<option value="img"<?php selected( 'img', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Image', 'arbtt' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Render back to top button
	 *
	 * @return void
	 */
	public function render_back_to_top() {
		$defaults = array(
			'bgc'    => '#000',
			'fz'     => '20',
			'clr'    => '#fff',
			'btnps'  => 'right',
			'btnoc'  => '0.5',
			'bdrd'   => '5',
			'btntx'  => 'Top',
			'fi'     => 'arrow-up',
			'btnst'  => 'txt',
			'btnimg' => 'arbtt6.png',
			'pwidth' => '767',
			'enable' => '0',
		);

		// Get scalar options with fallback
		$arbtt_bgc     = get_option( 'arbtt_bgc' ) ? get_option( 'arbtt_bgc' ) : $defaults['bgc'];
		$arbtt_enable  = get_option( 'arbtt_enable' ) ? get_option( 'arbtt_enable' ) : $defaults['enable'];
		$arbtt_fz      = get_option( 'arbtt_fz' ) ? get_option( 'arbtt_fz' ) : $defaults['fz'];
		$arbtt_clr     = get_option( 'arbtt_clr' ) ? get_option( 'arbtt_clr' ) : $defaults['clr'];
		$arbtt_btnps   = get_option( 'arbtt_btnps' ) ? get_option( 'arbtt_btnps' ) : $defaults['btnps'];
		$arbtt_btnoc   = get_option( 'arbtt_btnoc' ) ? get_option( 'arbtt_btnoc' ) : $defaults['btnoc'];
		$arbtt_bdrd    = get_option( 'arbtt_bdrd' ) ? get_option( 'arbtt_bdrd' ) : $defaults['bdrd'];
		$arbtt_btntx   = get_option( 'arbtt_btntx' ) ? get_option( 'arbtt_btntx' ) : $defaults['btntx'];
		$arbtt_fi      = get_option( 'arbtt_fi' ) ? get_option( 'arbtt_fi' ) : $defaults['fi'];
		$arbtt_btnst   = get_option( 'arbtt_btnst' ) ? get_option( 'arbtt_btnst' ) : $defaults['btnst'];
		$arbtt_btnimg  = get_option( 'arbtt_btnimg' ) ? get_option( 'arbtt_btnimg' ) : $defaults['btnimg'];
		$arbtt_pwidth  = get_option( 'arbtt_pwidth' ) ? get_option( 'arbtt_pwidth' ) : $defaults['pwidth'];
		$arbtt_hophone = get_option( 'arbtt_hophone' );

		// Handle button dimensions
		$arbtt_btndm = get_option( 'arbtt_btndm', array() );
		$btnwidth    = ! empty( $arbtt_btndm['w'] ) ? (int) $arbtt_btndm['w'] : 40;
		$btnheight   = ! empty( $arbtt_btndm['h'] ) ? (int) $arbtt_btndm['h'] : 40;

		// Handle button padding
		$arbtt_btn_padding = get_option( 'arbtt_btn_padding', '10' );
		$btnpadding        = ! empty( $arbtt_btn_padding ) ? (int) $arbtt_btn_padding : 10;

		// Final output toggle
		$display = $arbtt_enable ? 'block' : 'none';

		if ( '1' !== $arbtt_enable ) {
			return;
		}

		?>
		<style type="text/css">
		#arbtt-container{ display: <?php echo esc_attr( $display ); ?>; }
		.arbtt {width:<?php echo esc_attr( $btnwidth ); ?>px; height:<?php echo esc_attr( $btnheight ); ?>px;line-height:<?php echo esc_attr( $btnheight ); ?>px;padding: <?php echo esc_attr( $btnpadding ); ?>px;text-align:center;font-weight: bold;color:<?php echo esc_attr( $arbtt_clr ); ?>!important;text-decoration: none!important;position: fixed;bottom:75px; <?php echo esc_attr( $arbtt_btnps ); ?> :40px;display:none; background-color: <?php echo esc_attr( $arbtt_bgc ); ?> !important;opacity: <?php echo esc_attr( $arbtt_btnoc ); ?>;border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px;z-index: 9999;}
		.arbtt:hover {opacity: 0.7;cursor: pointer;}
		.arbtt .fa{line-height: <?php echo esc_attr( $btnheight ); ?>px;font-size: <?php echo esc_attr( $arbtt_fz ); ?>px;height: <?php echo esc_attr( $btnheight ); ?>px;width:<?php echo esc_attr( $btnwidth ); ?>px;display: block;}
		.arbtt:visited, .arbtt:focus{color: #fff;outline: 0;}
		.arbtt img {height: calc(<?php echo esc_attr( $btnheight ); ?>px - 10px);width:  calc(<?php echo esc_attr( $btnwidth ); ?>px - 10px);margin: -4px 0 0;padding: 0;vertical-align: middle;}
		<?php
		if ( '1' === $arbtt_hophone ) {
			?>
			@media(max-width: <?php echo esc_attr( $arbtt_pwidth ); ?>px){ #arbtt-container{display:none; }} 
		<?php } ?>
		</style>
		<div class="arbtt-container" id="arbtt-container"> 
			<a class="arbtt" id="arbtt">
				<?php if ( 'fa' === $arbtt_btnst ) : ?>
				<span class="fa fa-<?php echo esc_attr( $arbtt_fi ); ?>"></span>
				<?php elseif ( 'txt' === $arbtt_btnst ) : ?>
					<?php echo esc_attr( $arbtt_btntx ); ?>
				<?php elseif ( 'img' === $arbtt_btnst ) : ?>
					<img src='<?php echo esc_url( ARBTTOP_ASSETS . "/images/$arbtt_btnimg" ); ?>'/>
				<?php endif; ?>
			</a>
			</div>
		<?php
	}

	/**
	 * Plugin activation
	 *
	 * @return void
	 */
	public function plugin_activation() {
		add_option( 'arbtt_do_activation_redirect', true );
	}

	/**
	 * Handle activation redirect
	 *
	 * @return void
	 */
	public function handle_activation_redirect() {
		if ( get_option( 'arbtt_do_activation_redirect', false ) ) {
			delete_option( 'arbtt_do_activation_redirect' );
		}
	}

	/**
	 * Plugin uninstall
	 *
	 * @return void
	 */
	public static function plugin_uninstall() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		delete_option( 'arbtt' );
	}

	/**
	 * Add action links
	 *
	 * @param array $links Action links.
	 * @return array
	 */
	public function add_action_links( $links ) {
		$links[] = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=arbtt' ) ) . '">Settings</a>';
		$links[] = '<a href="https://github.com/anisur2805/ar-back-to-top" target="_blank">Support</a>';
		return $links;
	}
}

// Initialize the plugin
AR_Back_To_Top::get_instance();
