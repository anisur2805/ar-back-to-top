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
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'status_page' ) );
	}

	/**
	 * Prevent cloning the instance.
	 */
	public function __clone() {}

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

	public function ar_convert_to_bytes( $value ) {
		$value = trim( $value );
		$last  = strtolower( substr( $value, -1 ) );
		$num   = (int) $value;

		switch ( $last ) {
			case 'g':
				$num *= 1024;
				// no break
			case 'm':
				$num *= 1024;
				// no break
			case 'k':
				$num *= 1024;
		}
		return $num;
	}

	/**
	 * Render the status page.
	 *
	 * @return void
	 */
	public function status_render() {
		global $wp_version;

		$wp_mem_limit = WP_MEMORY_LIMIT;
		$wp_max_mem   = WP_MAX_MEMORY_LIMIT;

		$wp_cache       = defined( 'WP_CACHE' ) && WP_CACHE ? 'Yes' : 'No';
		$debug_mode     = defined( 'WP_DEBUG' ) && WP_DEBUG ? 'Yes' : 'No';
		$site_url       = home_url();
		$wp_version_val = get_bloginfo( 'version' );
		$php_version    = phpversion();
		$mysql_version  = $GLOBALS['wpdb']->db_version();
		$web_server     = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
		$max_input_vars = ini_get( 'max_input_vars' );
		$theme          = wp_get_theme();
		$theme_name     = $theme->get( 'Name' );
		$theme_version  = $theme->get( 'Version' );
		$plugin_count   = count( get_option( 'active_plugins', array() ) );
		$is_multisite   = is_multisite() ? 'Yes' : 'No';

		$wp_required  = '6.0';
		$php_required = '7.4';
		$wc_required  = '8.0';

		$wp_version = version_compare( $wp_version_val, $wp_required, '<' )
			? "<td class=\"text-left red\">$wp_version_val <span style='color:#444;font-size: 12px;'>Please upgrade WordPress</span></td>"
			: "<td class=\"text-left green\">$wp_version_val</td>";

		$php_verseion = version_compare( $php_version, $php_required, '<' )
			? "<td class=\"text-left red\">$php_version <span style='color:#444;font-size: 12px;'>Please upgrade PHP</span></td>"
			: "<td class=\"text-left green\">$php_version</td>";

		if ( defined( 'WC_VERSION' ) ) {
			$wc_version_val = WC_VERSION;
			$wc_verseion     = version_compare( $wc_version_val, $wc_required, '<' )
				? "<td class=\"text-left red\">$wc_version_val <span style='color:#444;font-size: 12px;'>Please upgrade WooCommerce</span></td>"
				: "<td class=\"text-left green\">$wc_version_val</td>";
		} else {
			$wc_verseion = "<td class=\"text-left red\">Not Installed <span style='color:#444;font-size: 12px;'>Please install WooCommerce v$wc_required+</span></td>";
		}

		$max_input_vars_val   = (int) ini_get( 'max_input_vars' );
		$max_input_vars_limit = $max_input_vars_val < 1200
			? '<td class="text-left red">' . $max_input_vars_val . ' <span style="color:#444;font-size: 12px;">Please increase to at least 1200.</span></td>'
			: '<td class="text-left green">' . $max_input_vars_val . '</td>';

		?>
		<div class="ar-btt-status-wrapper">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<table class="widefat striped" style="margin-top: 20px;">
				<thead><tr><th>Item</th><th>Value</th></tr></thead>
				<tbody>
					<tr><td>Site URL</td><td class="text-left green"><?php echo esc_url( $site_url ); ?></td></tr>
					<tr><td>Define WP_CACHE</td><td class="text-left green"><?php echo esc_html( $wp_cache ); ?></td></tr>
					<tr><td>Define WP_DEBUG</td><td class="text-left green"><?php echo esc_html( $debug_mode ); ?></td></tr>
				</tbody>
			</table>

			<table class="widefat striped" style="margin-top: 30px;">
				<thead><tr><th>Configuration</th><th>Status</th></tr></thead>
				<tbody>
					<tr><td>WordPress Version</td><?php echo $wp_version; ?></tr>
					<tr><td>PHP Version</td><?php echo $php_verseion; ?></tr>
					<tr><td>WooCommerce Version</td><?php echo $wc_verseion; ?></tr>
					<tr><td>max_input_vars</td><?php echo $max_input_vars_limit; ?></tr>
					<tr><td>Available Memory</td><td class="text-left green"><?php echo esc_html( $wp_max_mem ); ?></td></tr>
					<tr><td>Memory Limit</td><td class="text-left green"><?php echo esc_html( $wp_mem_limit ); ?></td></tr>
				</tbody>
			</table>

			<table class="widefat striped" style="margin-top: 30px;">
				<thead><tr><th>Environment</th><th>Details</th></tr></thead>
				<tbody>
					<tr><td>MySQL Version</td><td class="text-left green"><?php echo esc_html( $mysql_version ); ?></td></tr>
					<tr><td>Web Server</td><td class="text-left green"><?php echo esc_html( $web_server ); ?></td></tr>
					<tr><td>Active Theme</td><td class="text-left green"><?php echo esc_html( $theme_name . ' (v' . $theme_version . ')' ); ?></td></tr>
					<tr><td>Active Plugins</td><td class="text-left green"><?php echo esc_html( $plugin_count ); ?></td></tr>
					<tr><td>Multisite</td><td class="text-left green"><?php echo esc_html( $is_multisite ); ?></td></tr>
				</tbody>
			</table>
		</div>
		<?php
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
