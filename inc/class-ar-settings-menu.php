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
			__( 'Single Post Settings', 'arbtt' ),
			__( 'Single Post', 'arbtt' ),
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
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<input type="hidden" name="my_nonce" value="<?php echo esc_attr( wp_create_nonce( 'arbtt_setting_nonce' ) ); ?>">
				<?php
				settings_fields( 'arbtt_settings' );
				do_settings_sections( 'arbttp_setting_sections' );
				submit_button( __( 'Save Settings', 'arbtt' ) );
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
		// Register options.
		register_setting( 'arbtt_settings', 'arbtt_word_count' );
		register_setting( 'arbtt_settings', 'arbtt_char_counts' );
		register_setting( 'arbtt_settings', 'arbtt_read_time' );
		register_setting( 'arbtt_settings', 'arbtt_view_count' );
		register_setting( 'arbtt_settings', 'arbtt_meta_position' );

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
			__( 'Show Word Count', 'arbtt' ),
			array( $this, 'word_count_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'characters_count',
			__( 'Show Characters Count', 'arbtt' ),
			array( $this, 'characters_count_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'read_time',
			__( 'Show Read Time', 'arbtt' ),
			array( $this, 'read_time_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'view_count',
			__( 'Show View Count', 'arbtt' ),
			array( $this, 'view_post_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'meta_position',
			__( 'Meta Info Position', 'arbtt' ),
			array( $this, 'meta_position_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);
	}

	/**
	 * Word Count checkbox.
	 */
	public function word_count_callback() {
		$word_count = get_option( 'arbtt_word_count', '' );
		echo '<input type="checkbox" name="arbtt_word_count" value="1" ' . checked( 1, $word_count, false ) . ' />';
	}

	/**
	 * Character Count checkbox.
	 */
	public function characters_count_callback() {
		$characters_count = get_option( 'arbtt_char_counts', '' );
		echo '<input type="checkbox" name="arbtt_char_counts" value="1" ' . checked( 1, $characters_count, false ) . ' />';
	}

	/**
	 * Read Time checkbox.
	 */
	public function read_time_callback() {
		$read_time = get_option( 'arbtt_read_time', '' );
		echo '<input type="checkbox" name="arbtt_read_time" value="1" ' . checked( 1, $read_time, false ) . ' />';
	}

	/**
	 * Post View Count checkbox.
	 */
	public function view_post_callback() {
		$view_count = get_option( 'arbtt_view_count', '' );
		echo '<input type="checkbox" name="arbtt_view_count" value="1" ' . checked( 1, $view_count, false ) . ' />';
	}

	/**
	 * Meta Position select dropdown.
	 */
	public function meta_position_callback() {
		$selected = get_option( 'arbtt_meta_position', 'Top' );
		$options  = array( 'Top', 'Bottom', 'Both' );

		echo '<select name="arbtt_meta_position">';
		foreach ( $options as $option ) {
			printf(
				'<option value="%s" %s>%s</option>',
				esc_attr( $option ),
				selected( $selected, $option, false ),
				esc_html( $option )
			);
		}
		echo '</select>';
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
