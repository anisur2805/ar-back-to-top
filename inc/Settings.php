<?php
/**
 * Settings Page
 *
 * @package AR_Back_To_Top
 */

/**
 * Settings Sub menu class
 */
class Settings_Menu_Page {

	/**
	 * Singleton pattern
	 *
	 * @var mix $instance
	 */
	private static $instance = null;
	/**
	 * Autoload method
	 *
	 * @return void
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings_init' ) );
	}

	/**
	 * Settings_Menu_Page should not be cloneable.
	 */
	protected function __clone() { }

	/**
	 * Settings_Menu_Page should not be restorable from strings.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Get instance of the class.
	 *
	 * @return Settings_Menu_Page
	 */
	public static function get_instance(): Settings_Menu_Page {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register submenu
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
			array( $this, 'settings_render' ),
		);
	}

	/**
	 * Render submenu
	 *
	 * @return void
	 */
	public function settings_render() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<input type="hidden" name="my_nonce" value="<?php echo wp_create_nonce( 'arbtt_setting_nonce' ); ?>">
				<?php
				settings_fields( 'arbtt_settings' );
				do_settings_sections( 'arbttp_setting_sections' );
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register settings
	 *
	 * @return void
	 */
	public function register_settings_init() {
		register_setting( 'arbtt_settings', 'arbtt_word_count' );
		register_setting( 'arbtt_settings', 'arbtt_char_counts' );
		register_setting( 'arbtt_settings', 'arbtt_read_time' );
		register_setting( 'arbtt_settings', 'arbtt_view_count' );
		register_setting( 'arbtt_settings', 'arbtt_meta_position' );

		add_settings_section(
			'arbttp_setting_sections',
			__( '', 'arbtt' ),
			'',
			'arbttp_setting_sections'
		);
		add_settings_section(
			'arbttp_setting_sections',
			__( '', 'arbtt' ),
			'',
			'arbttp_setting_sections'
		);
		add_settings_section(
			'arbttp_setting_sections',
			__( '', 'arbtt' ),
			'',
			'arbttp_setting_sections'
		);
		add_settings_section(
			'arbttp_setting_sections',
			__( '', 'arbtt' ),
			'',
			'arbttp_setting_sections'
		);

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
			__( 'Show View post', 'arbtt' ),
			array( $this, 'view_post_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);

		add_settings_field(
			'meta_position',
			__( 'Meta Info Show Position', 'arbtt' ),
			array( $this, 'meta_position_callback' ),
			'arbttp_setting_sections',
			'arbttp_setting_sections'
		);
	}

	/**
	 * Word Count Callback
	 *
	 * @return void
	 */
	public function word_count_callback() {
		$word_count = get_option( 'arbtt_word_count', '' );
		echo '<input type="checkbox" name="arbtt_word_count" value="1"' . checked( 1, $word_count, false ) . ' />';
	}

	/**
	 * Characters count callback
	 *
	 * @return void
	 */
	public function characters_count_callback() {
		$characters_count = get_option( 'arbtt_char_counts', '' );
		echo '<input type="checkbox" name="arbtt_char_counts" value="1"' . checked( 1, $characters_count, false ) . ' />';
	}

	/**
	 * Read time callback
	 *
	 * @return void
	 */
	public function read_time_callback() {
		$read_time = get_option( 'arbtt_read_time', '' );
		echo '<input type="checkbox" name="arbtt_read_time" value="1"' . checked( 1, $read_time, false ) . ' />';
	}

	/**
	 * View total post callback
	 *
	 * @return void
	 */
	public function view_post_callback() {
		$arbtt_view_post = get_option( 'arbtt_view_count', '' );
		echo '<input type="checkbox" name="arbtt_view_count" value="1"' . checked( 1, $arbtt_view_post, false ) . ' />';
	}

	/**
	 * Meta info position callback
	 *
	 * @return void
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
 * The client code.
 */
function setting_kickoff() {
	Settings_Menu_Page::get_instance();
}

setting_kickoff();