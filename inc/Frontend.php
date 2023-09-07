<?php
/**
 * Frontend File
 *
 * @package AR_Back_To_Top
 * @since 2.10.0
 */

/**
 * Frontend class file
 */
class Frontend {

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
		add_filter( 'the_content', array( $this, 'arbtt_display_post_data' ) );
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
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Display meta info to the frontend
	 * Only Single file.
	 *
	 * @param mix $content Post content.
	 * @return mix
	 */
	public function arbtt_display_post_data( $content ) {
		// Only modify the content for main single posts.
		if ( ! is_single() || ! is_main_query() ) {
			return $content;
		}

		// Increment view count.
		$this->increment_post_views( get_the_ID() );

		$post_data  = '<div class="arbtt-post-data arbtt-stats">'; // Starting the container.
		$post_data .= '<style>
			.arbtt-stats {
				display: flex;
				align-items: center;
				flex-wrap: wrap;
				gap: 10px;
				font-size: 14px;
			}
			.arbtt-stats strong {
				margin-right: 5px;
			}
		</style>';

		// Check if the "word count" option is enabled.
		if ( '1' === get_option( 'arbtt_word_count', '0' ) ) {
			$word_count = str_word_count( wp_strip_all_tags( $content ) );
			$post_data .= sprintf( '<span class="arbtt-word-count"><strong>%s:</strong> %s</span>', esc_html__( 'Word Count', 'arbtt' ), esc_html( $word_count ) );
		}

		// Check if the "char count" option is enabled.
		if ( '1' === get_option( 'arbtt_char_counts', '0' ) ) {
			$char_count = mb_strlen( wp_strip_all_tags( $content ) );
			$post_data .= sprintf( '<span class="arbtt-char-count"><strong>%s:</strong> %s</span>', esc_html__( 'Character Count', 'arbtt' ), esc_html( $char_count ) );
		}

		// Calculate total read time.
		if ( '1' === get_option( 'arbtt_read_time', '0' ) ) {
			$word_count = str_word_count( wp_strip_all_tags( $content ) );
			$minutes    = ceil( $word_count / 200 );

			$time_string = ( $minutes === 1 ) ? esc_html__( 'minute', 'arbtt' ) : esc_html__( 'minutes', 'arbtt' );
			$post_data  .= sprintf( '<span class="arbtt-read-time"><strong>%s:</strong> %s %s</span>', esc_html__( 'Estimated Reading Time', 'arbtt' ), esc_html( $minutes ), $time_string );
		}

		// Display post views.
		if ( '1' === get_option( 'arbtt_view_count', '0' ) ) {
			$views       = (int) get_post_meta( get_the_ID(), '_arbtt_post_views', true );
			$post_data  .= sprintf( '<span class="arbtt-view-count"><strong>%s:</strong> %s</span>', esc_html__( 'Total Views', 'arbtt' ), esc_html( $views ) );
		}

		$arbtt_meta_position = get_option( 'arbtt_meta_position', '0' );

		switch ( $arbtt_meta_position ) {
			case 'Bottom':
				$post_data = $content . $post_data;
				break;
			case 'Both':
				$post_data = $post_data . $content . $post_data;
				break;
			default:
				$post_data = $post_data . $content;
				break;
		}

		$post_data .= '</div>';

		return $post_data;
	}

	/**
	 * Count Increment of Post Views
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function increment_post_views( $post_id ) {
		$current_views = get_post_meta( $post_id, '_arbtt_post_views', true );
		if ( empty( $current_views ) || ! is_numeric( $current_views ) ) {
			$current_views = 0;
		}
		// Increment the view count.
		update_post_meta( $post_id, '_arbtt_post_views', ++$current_views );
	}
}

/**
 * The client code.
 */
function frontend_kickoff() {
	Frontend::get_instance();
}

frontend_kickoff();
