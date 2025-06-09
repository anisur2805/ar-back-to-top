<?php
/**
 * Status Page
 *
 * @package AR_Back_To_Top
 */

/**
 * Status class
 */
final class AR_Status {

	/**
	 * Singleton instance.
	 *
	 * @var AR_Status|null
	 */
	private static $instance = null;

	/**
	 * Constructor (private for singleton).
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'status_page' ) );
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
	 * @return AR_Status
	 */
	public static function get_instance(): AR_Status {
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
	public function status_page() {
		add_submenu_page(
			'arbtt',
			__( 'Status', 'ar-back-to-top' ),
			__( 'Status', 'ar-back-to-top' ),
			'manage_options',
			'arbtt-status',
			array( $this, 'status_render' )
		);
	}

	/**
	 * Render the status page.
	 *
	 * @return void
	 */
	public function status_render() {
		$site_url       = home_url();
		$wp_cache       = defined( 'WP_CACHE' ) && WP_CACHE ? 'Yes' : 'No';
		$debug_mode     = defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Yes' : 'No';
		$wp_version     = get_bloginfo( 'version' );
		$php_version    = phpversion();
		$mysql_version  = $GLOBALS['wpdb']->db_version();
		$web_server     = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
		$max_input_vars = ini_get( 'max_input_vars' );
		$php_mem_limit  = ini_get( 'memory_limit' );
		$memory_limit_bytes = $this->ar_convert_to_bytes( $php_mem_limit );
		$wp_mem_limit   = size_format( WP_MEMORY_LIMIT );
		$theme          = wp_get_theme();
		$theme_name     = $theme->get( 'Name' );
		$theme_version  = $theme->get( 'Version' );
		$plugin_count   = count( get_option( 'active_plugins', array() ) );
		$is_multisite   = is_multisite() ? 'Yes' : 'No';
		?>
			<div class="wrap ar-btt-wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

				<table class="widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Item', 'ar-back-to-top' ); ?></th>
							<th><?php esc_html_e( 'Value', 'ar-back-to-top' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr><td>Site URL</td><td><?php echo esc_url( $site_url ); ?></td></tr>
						<tr><td>Define WP_CACHE</td><td><?php echo esc_html( $wp_cache ); ?></td></tr>
						<tr><td>Define WP_DEBUG</td><td><?php echo esc_html( $debug_mode ); ?></td></tr>
						<tr><td>WordPress Version</td><td><?php echo esc_html( $wp_version ); ?></td></tr>
						<tr><td>PHP Version</td><td><?php echo esc_html( $php_version ); ?></td></tr>
						<tr><td>MySQL Version</td><td><?php echo esc_html( $mysql_version ); ?></td></tr>
						<tr><td>Web Server</td><td><?php echo esc_html( $web_server ); ?></td></tr>
						<tr><td>max_input_vars</td><td><?php echo esc_html( $max_input_vars ); ?></td></tr>
						<tr><td>PHP Memory Limit</td><td><?php echo esc_html( $php_mem_limit ); ?></td></tr>
						<tr><td>WP Memory Limit</td><td><?php echo esc_html( $wp_mem_limit ); ?></td></tr>
						<tr><td>Active Theme</td><td><?php echo esc_html( $theme_name . ' (v' . $theme_version . ')' ); ?></td></tr>
						<tr><td>Active Plugins</td><td><?php echo esc_html( $plugin_count ); ?></td></tr>
						<tr><td>Multisite</td><td><?php echo esc_html( $is_multisite ); ?></td></tr>
						</tbody>
					</table>
				</div>
		<?php
	}

	/**
	 * Convert shorthand memory string (e.g., 256M) to bytes.
	 *
	 * @param string $val Shorthand byte value.
	 * @return int
	 */
	public function ar_convert_to_bytes( $val ): int {
		$val  = trim( $val );
		$last = strtolower( substr( $val, -1 ) );
		$num  = (int) $val;

		switch ( $last ) {
			case 'g':
				$num *= 1024;
				// no break
			case 'm':
				$num *= 1024;
				// no break
			case 'k':
				$num *= 1024;
				// no break
		}
		return $num;
	}
}

/**
 * Kickoff plugin status page.
 *
 * @return void
 */
function status_kickoff() {
	AR_Status::get_instance();
}

status_kickoff();
