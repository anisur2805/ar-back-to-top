<?php
/**
 * Settings Page
 *
 * @package AR_Back_To_Top
 */

/**
 * Settings Sub menu class
 */
class AR_Settings_Menu {

	/**
	 * Singleton instance.
	 *
	 * @var AR_Settings_Menu|null
	 */
	private static $instance = null;

	/**
	 * Constructor (private for singleton).
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings_init' ) );
	}

	/**
	 * Prevent cloning the instance.
	 */
	protected function __clone() {}

	/**
	 * Prevent unserializing the instance.
	 *
	 * @throws \Exception If attempted.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Get singleton instance.
	 *
	 * @return AR_Settings_Menu
	 */
	public static function get_instance(): AR_Settings_Menu {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register submenu page.
	 *
	 * @return void
	 */
	public function settings_page() {
		add_submenu_page(
			'arbtt',
			__( 'Single Post Settings', 'ar-back-to-top' ),
			__( 'Single Post', 'ar-back-to-top' ),
			'manage_options',
			'arbtt-settings',
			array( $this, 'settings_render' )
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function settings_render() {
		?>
		<div class="wrap ar-btt-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<input type="hidden" name="my_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arbtt_setting_nonce' ) ); ?>">
				<?php
				settings_fields( 'arbtt_settings' );
				do_settings_sections( 'arbttp_setting_sections' );
				submit_button( __( 'Save Settings', 'ar-back-to-top' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register plugin settings.
	 *
	 * @return void
	 */
	public function register_settings_init() {
		register_setting(
			'arbtt_settings',
			'arbtt_word_count',
			array(
				'sanitize_callback' => 'absint',
			)
		);

		register_setting(
			'arbtt_settings',
			'arbtt_char_counts',
			array(
				'sanitize_callback' => 'absint',
			)
		);

		register_setting(
			'arbtt_settings',
			'arbtt_read_time',
			array(
				'sanitize_callback' => 'absint',
			)
		);

		register_setting(
			'arbtt_settings',
			'arbtt_view_count',
			array(
				'sanitize_callback' => 'absint',
			)
		);

		register_setting(
			'arbtt_settings',
			'arbtt_meta_position',
			array(
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		// Add settings section.
		add_settings_section(
			'arbttp_setting_sections',
			'',
			'__return_false',
			'arbttp_setting_sections'
		);

		// Fields.
		add_settings_field(
			'word_count',
			__( 'Enable Word Count', 'ar-back-to-top' ),
			array( $this, 'word_count_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'characters_count',
			__( 'Enable Character Count', 'ar-back-to-top' ),
			array( $this, 'characters_count_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'read_time',
			__( 'Enable Estimated Reading Time', 'ar-back-to-top' ),
			array( $this, 'read_time_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'view_count',
			__( 'Enable Post View Counter', 'ar-back-to-top' ),
			array( $this, 'view_post_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'meta_position',
			__( 'Meta Display Position', 'ar-back-to-top' ),
			array( $this, 'meta_position_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);
	}

	/**
	 * Word Count checkbox.
	 */
	public function word_count_callback() {
		?>
		<label class="ar-btt-toggle" for="arbtt_word_count">
			<input type="checkbox" name="arbtt_word_count" id="arbtt_word_count" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_word_count' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Character Count checkbox.
	 */
	public function characters_count_callback() {
		?>
		<label class="ar-btt-toggle" for="arbtt_char_counts">
			<input type="checkbox" name="arbtt_char_counts" id="arbtt_char_counts" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_char_counts' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Read Time checkbox.
	 */
	public function read_time_callback() {
		?>
		<label class="ar-btt-toggle" for="arbtt_read_time">
			<input type="checkbox" name="arbtt_read_time" id="arbtt_read_time" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_read_time' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Post View Count checkbox.
	 */
	public function view_post_callback() {
		?>
		<label class="ar-btt-toggle" for="arbtt_view_count">
			<input type="checkbox" name="arbtt_view_count" id="arbtt_view_count" value="1"<?php checked( '1', esc_attr( get_option( 'arbtt_view_count' ) ) ); ?> class="ar-btt-toggle-checkbox">
			<div class="ar-btt-toggle-switch"></div>
		</label>
		<?php
	}

	/**
	 * Meta Position select dropdown.
	 */
	public function meta_position_callback() {
		$meta_position = array(
			'top'    => __( 'Top', 'ar-back-to-top' ),
			'bottom' => __( 'Bottom', 'ar-back-to-top' ),
			'both'   => __( 'Both', 'ar-back-to-top' ),
		);
		?>
		<select name="arbtt_meta_position" class="regular-text">
		<?php
		foreach ( $meta_position as $key => $option ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $key ),
				selected( $key, esc_attr( get_option( 'arbtt_meta_position' ) ), false ),
				esc_html( $option )
			);
		}
		?>
		</select>
		<?php
	}
}

/**
 * Kickoff plugin settings page.
 *
 * @return void
 */
function setting_kickoff() {
	AR_Settings_Menu::get_instance();
}
setting_kickoff();
