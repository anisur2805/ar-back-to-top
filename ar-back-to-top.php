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
 * Domain Path: /languages
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
			'inc/class-ar-settings-menu.php',
			'inc/class-ar-frontend.php',
			'inc/class-ar-assets.php',
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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'plugin_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load textdomain
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'arbtt', false, plugin_basename( __DIR__ ) . '/languages' );
	}

	/**
	 * Enqueue assets
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_register_style( 'arbtt-style', false );
		wp_enqueue_style( 'arbtt-style' );

		$arbtt_custom_css = get_option( 'arbtt_custom_css' );

		if ( ! empty( $arbtt_custom_css ) ) {
			$sanitized_css = $this->sanitize_custom_css( $arbtt_custom_css );
			wp_add_inline_style( 'arbtt-style', $sanitized_css );
		}
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
			<h1><?php esc_html_e( 'Back To Top', 'arbtt' ); ?></h1>
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
		$this->register_field( 'arbtt_enable_scroll_progress', 'Enable Scroll Progress', 'arbtt', array( $this, 'render_enable_scroll_progress_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_is_async', 'Enable Async', 'arbtt', array( $this, 'render_is_async_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnst', __( 'Button Style', 'arbtt' ), 'arbtt', array( $this, 'render_btnst_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fi', __( 'Font Icon', 'arbtt' ), 'arbtt', array( $this, 'render_fi_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btntx', __( 'Button Text', 'arbtt' ), 'arbtt', array( $this, 'render_btntx_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_img', __( 'Choose Button Image', 'arbtt' ), 'arbtt', array( $this, 'render_btnimg_field' ), 'arbtt_ssection_id' );
		// $this->register_field( 'arbtt_btn_custom_icon', __( 'Custom Icon URL', 'arbtt' ), 'arbtt', array( $this, 'render_btn_custom_icon_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_img_url', __( 'External Image Url', 'arbtt' ), 'arbtt', array( $this, 'render_btnimg_url_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bgc', 'Button Background Color', 'arbtt', array( $this, 'render_bgc_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bgc_hover', 'Button Background Hover Color', 'arbtt', array( $this, 'render_bgc_hover_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_clr', 'Button Color', 'arbtt', array( $this, 'render_clr_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_clr_hover', 'Button Color Hover', 'arbtt', array( $this, 'render_clr_hover_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bdrd', 'Button Border Radius', 'arbtt', array( $this, 'render_bdrd_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bdr', 'Button Border', 'arbtt', array( $this, 'render_bdr_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bdr_color', 'Button Border Color', 'arbtt', array( $this, 'render_bdr_color_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_bdr_color_hover', 'Button Hover Border Color', 'arbtt', array( $this, 'render_bdr_color_hover_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnps', 'Button Position', 'arbtt', array( $this, 'render_btnps_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_offset_bottom', 'Button Offset Bottom', 'arbtt', array( $this, 'render_btn_offset_bottom_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_offset_right', 'Button Offset Right', 'arbtt', array( $this, 'render_btn_offset_right_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_offset_left', 'Button Offset Left', 'arbtt', array( $this, 'render_btn_offset_left_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnapr', 'Button Appear After Scroll', 'arbtt', array( $this, 'render_btnapr_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btndm', 'Button Dimension', 'arbtt', array( $this, 'render_btndm_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btn_padding', 'Button Padding', 'arbtt', array( $this, 'render_btn_padding_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_btnoc', 'Button Opacity', 'arbtt', array( $this, 'render_btnoc_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fadein', 'Scroll Duration', 'arbtt', array( $this, 'render_fadein_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_fz', 'Font Size', 'arbtt', array( $this, 'render_fz_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_hide_on_tablet', __( 'Hide Button on Tablet Devices', 'arbtt' ), 'arbtt', array( $this, 'render_hotablet_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_twidth', __( 'Tablet Breakpoint Width', 'arbtt' ), 'arbtt', array( $this, 'render_twidth_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_hide_on_phone', __( 'Hide Button on Mobile Devices', 'arbtt' ), 'arbtt', array( $this, 'render_hophone_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_pwidth', __( 'Mobile Breakpoint Width', 'arbtt' ), 'arbtt', array( $this, 'render_pwidth_field' ), 'arbtt_ssection_id' );
		$this->register_field( 'arbtt_custom_css', __( 'Custom CSS', 'arbtt' ), 'arbtt', array( $this, 'render_custom_css_field' ), 'arbtt_ssection_id' );
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
			__( 'Back To Top Settings', 'arbtt' ),
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
	 * Render enable scroll progress field
	 *
	 * @return void
	 */
	public function render_enable_scroll_progress_field() {
		?>
		<label class="ar-btt-toggle" for="arbtt_enable_scroll_progress">
			<input type="checkbox" name="arbtt_enable_scroll_progress" id="arbtt_enable_scroll_progress" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_enable_scroll_progress' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Render enable async field
	 *
	 * @return void
	 */
	public function render_is_async_field() {
		?>
		<label class="ar-btt-toggle" for="arbtt_is_async">
			<input type="checkbox" name="arbtt_is_async" id="arbtt_is_async" value="on"<?php checked( 'on', esc_attr( get_option( 'arbtt_is_async' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<p class="description"><?php echo __( 'Enable this option to improve site performance by loading scripts asynchronously. Disable only if it causes compatibility issues with other plugins or scripts.' ); ?></p>
		<?php
	}

	/**
	 * Render background color field
	 *
	 * @return void
	 */
	public function render_bgc_field() {
		?>
		<input type="text" name="arbtt_bgc" class="arcs ar-btt-color" id="arbtt_bgc" placeholder="#000" value="<?php echo esc_attr( get_option( 'arbtt_bgc' ) ); ?>"/>
		<?php
	}

	/**
	 * Render background hover color field
	 *
	 * @return void
	 */
	public function render_bgc_hover_field() {
		?>
		<input type="text" name="arbtt_bgc_hover" class="arcs ar-btt-color" id="arbtt_bgc_hover" placeholder="#000" value="<?php echo esc_attr( get_option( 'arbtt_bgc_hover' ) ); ?>"/>
		<?php
	}

	/**
	 * Render color field
	 *
	 * @return void
	 */
	public function render_clr_field() {
		?>
		<input type="text" name="arbtt_clr" class="arcs ar-btt-color" id="arbtt_clr" placeholder="#f5f5f5" value="<?php echo esc_attr( get_option( 'arbtt_clr' ) ); ?>"/>
		<?php
	}

	/**
	 * Render color hover field
	 *
	 * @return void
	 */
	public function render_clr_hover_field() {
		?>
		<input type="text" name="arbtt_clr_hover" class="arcs ar-btt-color" id="arbtt_clr_hover" placeholder="#f5f5f5" value="<?php echo esc_attr( get_option( 'arbtt_clr_hover' ) ); ?>"/>
		<?php
	}

	/**
	 * Render border field
	 *
	 * @return void
	 */
	public function render_bdr_field() {
		?>
		<input type="number" name="arbtt_bdr" class="aras" id="arbtt_bdr" placeholder="2" value="<?php echo esc_attr( get_option( 'arbtt_bdr' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render border color field
	 *
	 * @return void
	 */
	public function render_bdr_color_field() {
		?>
		<input type="text" name="arbtt_bdr_color" class="arcs ar-btt-color" id="arbtt_bdr_color" placeholder="#000" value="<?php echo esc_attr( get_option( 'arbtt_bdr_color' ) ); ?>"/>
		<?php
	}

	/**
	 * Render border color hover field
	 *
	 * @return void
	 */
	public function render_bdr_color_hover_field() {
		?>
		<input type="text" name="arbtt_bdr_color_hover" class="arcs ar-btt-color" id="arbtt_bdr_color_hover" placeholder="#000" value="<?php echo esc_attr( get_option( 'arbtt_bdr_color_hover' ) ); ?>"/>
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

	public function render_btn_offset_bottom_field() {
		?>
		<input type="number" name="arbtt_btn_offset_bottom" class="aras arbtt_btn_offset_bottom" id="arbtt_btn_offset_bottom" placeholder="100" value="<?php echo esc_attr( get_option( 'arbtt_btn_offset_bottom' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	public function render_btn_offset_right_field() {
		?>
		<input type="number" name="arbtt_btn_offset_right" class="aras arbtt_btn_offset_right" id="arbtt_btn_offset_right" placeholder="100" value="<?php echo esc_attr( get_option( 'arbtt_btn_offset_right' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<?php
	}

	public function render_btn_offset_left_field() {
		?>
		<input type="number" name="arbtt_btn_offset_left" class="aras arbtt_btn_offset_left" id="arbtt_btn_offset_left" placeholder="100" value="<?php echo esc_attr( get_option( 'arbtt_btn_offset_left' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
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
		<p><?php echo __( 'Specify the number of pixels a user must scroll before the button becomes visible.', 'arbtt' ); ?></p>
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
		$btn_padding = get_option( 'arbtt_btn_padding' );
		?>
		<input type="number" name="arbtt_btn_padding" class="aras arbtt_btn_padding ardm" id="arbtt_btn_padding" placeholder="10" value="<?php echo esc_attr( $btn_padding ); ?>"/>
		<span class="description"><?php echo __( '(px)', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render button opacity field
	 *
	 * @return void
	 */
	public function render_btnoc_field() {
		?>
		<input type="text" name="arbtt_btnoc" class="aras arbtt_btnoc" id="arbtt_btnoc" placeholder="0.6" value="<?php echo esc_attr( get_option( 'arbtt_btnoc' ) ); ?>"/>
		<span class="description"><?php echo __( 'Min 0 - Max 1', 'arbtt' ); ?></span>
		<?php
	}

	/**
	 * Render font icon field
	 *
	 * @return void
	 */
	public function render_fi_field() {
		?>
		<?php $selected_icon = esc_attr( get_option( 'arbtt_fi', 'fa-angle-up' ) ); ?>
		<div class="arbtt-fa-picker-wrap">
			<input type="text" id="arbtt_fi_picker" name="arbtt_fi" value="<?php echo $selected_icon; ?>" class="regular-text" readonly />
			<i class="fa <?php echo $selected_icon; ?> arbtt-preview-icon"></i>
			<button type="button" class="button arbtt-open-icon-picker">Select Icon</button>
		</div>

		<div id="arbtt-fa-icon-modal" style="display:none;">
			<div class="arbtt-fa-modal-content">
				<div class="arbtt-fa-modal-header">
					<h2>Font Awesome Icons</h2>
					<button type="button" class="arbtt-close-icon-picker">Close</button>
				</div>
				<input type="text" id="arbtt-fa-search" placeholder="Search icon..." class="regular-text" />
				<div class="arbtt-fa-icon-list"><!-- Icon list will be appended by JS --></div>
			</div>
		</div>

		<?php
	}

	/**
	 * Render button text field
	 *
	 * @return void
	 */
	public function render_btntx_field() {
		echo '<input type="text" name="arbtt_btntx" class="aras arbtt_btntx" id="arbtt_btntx" placeholder="Top" value="' . esc_attr( get_option( 'arbtt_btntx' ) ) . '"/>';
	}

	/**
	 * Render button image URL field
	 *
	 * @return void
	 */
	public function render_btnimg_url_field() {
		$external_img_url = ( get_option( 'arbtt_btn_img_url' ) ) ? ( get_option( 'arbtt_btn_img_url' ) ) : ARBTTOP_ASSETS . '/images/Back-to-Top.png';
		?>
		<input type="text" name="arbtt_btn_img_url" class="aras arbtt_btn_img_url" id="arbtt_btn_img_url" placeholder="Enter external image url here" value="<?php echo esc_attr( get_option( 'arbtt_btn_img_url' ) ); ?>"/>
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
				<label for="arbtt_btn_img_<?php echo $key; ?>">
					<input type="radio" name="arbtt_btn_img" id="arbtt_btn_img_<?php echo $key; ?>" class="arbtt_btn_img" value="<?php echo $image; ?>"<?php checked( $image, esc_attr( get_option( 'arbtt_btn_img' ) ) ); ?>/>
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
		<span class="description"><?php echo __( 'ms', 'arbtt' ); ?></span>
		<p><?php echo __( 'Enter the duration of the scroll-to-top animation in milliseconds', 'arbtt' ); ?></p>
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
		<label class="ar-btt-toggle" for="arbtt_hide_on_phone">
			<input type="checkbox" name="arbtt_hide_on_phone" id="arbtt_hide_on_phone" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_hide_on_phone' ) ) ); ?> class="ar-btt-toggle-checkbox arbtt-toggle-next" data-toggle-next="1">
			<div class="ar-btt-toggle-switch"></div>
			<span class="description"><?php echo __( 'Checked for hide icon on mobile' ); ?> </span>
		</label>
		<?php
	}

	/**
	 * Render hide on tablet field
	 *
	 * @return void
	 */
	public function render_hotablet_field() {
		?>
		<label class="ar-btt-toggle" for="arbtt_hide_on_tablet">
			<input type="checkbox" name="arbtt_hide_on_tablet" id="arbtt_hide_on_tablet" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_hide_on_tablet' ) ) ); ?> class="ar-btt-toggle-checkbox arbtt-toggle-next" data-toggle-next="1">
			<div class="ar-btt-toggle-switch"></div>
			<span class="description"><?php echo __( 'Checked for hide icon on tablet' ); ?> </span>
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
		<p><?php echo __( 'Enter the width of the screen at which the button will be hidden on mobile devices.', 'arbtt' ); ?></p>
		<?php
	}

	/**
	 * Render tablet width field
	 *
	 * @return void
	 */
	public function render_twidth_field() {
		?>
		<input type="number" name="arbtt_twidth" class="aras arbtt_twidth" id="arbtt_twidth" placeholder="1024" value="<?php echo esc_attr( get_option( 'arbtt_twidth' ) ); ?>"/>
		<span class="description"><?php echo __( 'px', 'arbtt' ); ?></span>
		<p><?php echo __( 'Enter the width of the screen at which the button will be hidden on tablet devices.', 'arbtt' ); ?></p>
		<?php
	}

	/**
	 * Render custom CSS field
	 *
	 * @return void
	 */
	public function render_custom_css_field() {
		?>
		<textarea name="arbtt_custom_css" id="arbtt_custom_css" rows="10" cols="50"><?php echo esc_textarea( get_option( 'arbtt_custom_css' ) ); ?></textarea>
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
			<option value="txt"<?php selected( 'txt', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Text Only', 'arbtt' ); ?></option>
			<option value="img"<?php selected( 'img', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Image Only', 'arbtt' ); ?></option>
			<option value="both"<?php selected( 'both', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'Both', 'arbtt' ); ?></option>
			<option value="external"<?php selected( 'external', esc_attr( get_option( 'arbtt_btnst' ) ) ); ?>><?php echo __( 'External Image URL', 'arbtt' ); ?></option>
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
			'bgc'                    => '#000',
			'fz'                     => '20',
			'clr'                    => '#fff',
			'btnps'                  => 'right',
			'btnoc'                  => '0.5',
			'bdrd'                   => '5',
			'btntx'                  => 'Top',
			'fi'                     => 'arrow-up',
			'btnst'                  => 'txt',
			'btnimg'                 => 'arbtt6.png',
			'enable'                 => '0',
			'hophone'                => '0',
			'bdr'                    => '2',
			'bdr_color'              => '#fff',
			'bgc_hover'              => '#fff',
			'clr_hover'              => '#fff',
			'enable_scroll_progress' => '0',
			'hotablet'               => '0',
			'pwidth'                 => '767',
			'twidth'                 => '1024',
			'btn_offset_bottom'      => '100',
			'btn_offset_right'       => '100',
			'btn_offset_left'        => '100',
			'bdr_color_hover'        => '#fff',
			'custom_css'             => '',
		);

		// Get scalar options with fallback
		$arbtt_bgc                    = get_option( 'arbtt_bgc' ) ? get_option( 'arbtt_bgc' ) : $defaults['bgc'];
		$arbtt_bgc_hover              = get_option( 'arbtt_bgc_hover' ) ? get_option( 'arbtt_bgc_hover' ) : $defaults['bgc_hover'];
		$arbtt_enable                 = get_option( 'arbtt_enable' ) ? get_option( 'arbtt_enable' ) : $defaults['enable'];
		$arbtt_enable_scroll_progress = get_option( 'arbtt_enable_scroll_progress' ) ? get_option( 'arbtt_enable_scroll_progress' ) : $defaults['enable_scroll_progress'];
		$arbtt_fz                     = get_option( 'arbtt_fz' ) ? get_option( 'arbtt_fz' ) : $defaults['fz'];
		$arbtt_clr                    = get_option( 'arbtt_clr' ) ? get_option( 'arbtt_clr' ) : $defaults['clr'];
		$arbtt_clr_hover              = get_option( 'arbtt_clr_hover' ) ? get_option( 'arbtt_clr_hover' ) : $defaults['clr_hover'];
		$arbtt_btnps                  = get_option( 'arbtt_btnps' ) ? get_option( 'arbtt_btnps' ) : $defaults['btnps'];
		$arbtt_btn_offset_bottom      = get_option( 'arbtt_btn_offset_bottom' ) ? get_option( 'arbtt_btn_offset_bottom' ) : $defaults['btn_offset_bottom'];
		$arbtt_btn_offset_right       = get_option( 'arbtt_btn_offset_right' ) ? get_option( 'arbtt_btn_offset_right' ) : $defaults['btn_offset_right'];
		$arbtt_btn_offset_left        = get_option( 'arbtt_btn_offset_left' ) ? get_option( 'arbtt_btn_offset_left' ) : $defaults['btn_offset_left'];
		$arbtt_btnoc                  = get_option( 'arbtt_btnoc' ) ? get_option( 'arbtt_btnoc' ) : $defaults['btnoc'];
		$arbtt_bdrd                   = get_option( 'arbtt_bdrd' ) ? get_option( 'arbtt_bdrd' ) : $defaults['bdrd'];
		$arbtt_bdr_color              = get_option( 'arbtt_bdr_color' ) ? get_option( 'arbtt_bdr_color' ) : $defaults['bdr_color'];
		$arbtt_bdr_color_hover        = get_option( 'arbtt_bdr_color_hover' ) ? get_option( 'arbtt_bdr_color_hover' ) : $defaults['bdr_color_hover'];
		$arbtt_bdr                    = get_option( 'arbtt_bdr' ) ? get_option( 'arbtt_bdr' ) : $defaults['bdr'];
		$arbtt_btntx                  = get_option( 'arbtt_btntx' ) ? get_option( 'arbtt_btntx' ) : $defaults['btntx'];
		$arbtt_fi                     = get_option( 'arbtt_fi' ) ? get_option( 'arbtt_fi' ) : $defaults['fi'];
		$arbtt_btnst                  = get_option( 'arbtt_btnst' ) ? get_option( 'arbtt_btnst' ) : $defaults['btnst'];
		$arbtt_btn_img                = get_option( 'arbtt_btn_img' ) ? get_option( 'arbtt_btn_img' ) : $defaults['btnimg'];
		$arbtt_pwidth                 = get_option( 'arbtt_pwidth' ) ? get_option( 'arbtt_pwidth' ) : $defaults['pwidth'];
		$arbtt_twidth                 = get_option( 'arbtt_twidth' ) ? get_option( 'arbtt_twidth' ) : $defaults['twidth'];
		$arbtt_hide_on_phone          = get_option( 'arbtt_hide_on_phone' ) ? get_option( 'arbtt_hide_on_phone' ) : $defaults['hophone'];
		$arbtt_hide_on_tablet         = get_option( 'arbtt_hide_on_tablet' ) ? get_option( 'arbtt_hide_on_tablet' ) : $defaults['hotablet'];
		$arbtt_custom_css             = get_option( 'arbtt_custom_css' ) ? get_option( 'arbtt_custom_css' ) : $defaults['custom_css'];

		// Handle button dimensions
		$arbtt_btndm = get_option( 'arbtt_btndm', array() );
		$btnwidth    = ! empty( $arbtt_btndm['w'] ) ? (int) $arbtt_btndm['w'] : 40;
		$btnheight   = ! empty( $arbtt_btndm['h'] ) ? (int) $arbtt_btndm['h'] : 40;

		// Handle button padding
		$arbtt_btn_padding = get_option( 'arbtt_btn_padding', 10 );
		$btnpadding        = ! empty( $arbtt_btn_padding ) ? (int) $arbtt_btn_padding : 10;

		// Final output toggle
		$display = $arbtt_enable ? 'block' : 'none';

		if ( '1' !== $arbtt_enable ) {
			return;
		}

		require_once ARBTTOP_PATH . '/inc/dynamic-style.css.php';

		?>
		<div class="arbtt-container" id="arbtt-container"> 
			<a class="arbtt" id="arbtt">
				<?php if ( 'fa' === $arbtt_btnst ) : ?>
				<span class="<?php echo esc_attr( $arbtt_fi ); ?>"></span>
				<?php elseif ( 'txt' === $arbtt_btnst ) : ?>
					<?php echo esc_attr( $arbtt_btntx ); ?>
				<?php elseif ( 'img' === $arbtt_btnst ) : ?>
					<img src='<?php echo esc_url( ARBTTOP_ASSETS . "/images/$arbtt_btn_img" ); ?>'/>
				<?php elseif ( 'both' === $arbtt_btnst ) : ?>
					<span class="<?php echo esc_attr( $arbtt_fi ); ?>"></span>
					<?php echo esc_attr( $arbtt_btntx ); ?>
				<?php elseif ( 'external' === $arbtt_btnst ) : ?>
					<img src='<?php echo esc_url( $arbtt_btn_img ); ?>'/>
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

	private function sanitize_custom_css( string $css ): string {
		// Remove comments
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );

		// Remove `expression()` calls
		$css = preg_replace( '/expression\s*\(.*?\)/i', '', $css );

		// Remove `javascript:` usage
		$css = preg_replace( '/javascript\s*:/i', '', $css );

		// Remove imports
		$css = preg_replace( '/@import\s+url\s*\(.*?\)\s*;?/i', '', $css );

		// Strip tags (in case someone tries to inject <style> or <script>)
		$css = strip_tags( $css );

		// Optionally trim and normalize white space
		return trim( $css );
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
