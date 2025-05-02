<?php
/**
 * Frontend display handler for ARBTT.
 *
 * @package ARBTT
 */

class AR_Frontend {

	/**
	 * Singleton instance.
	 *
	 * @var AR_Frontend|null
	 */
	private static $instance = null;

	/**
	 * Private constructor to enforce singleton.
	 */
	private function __construct() {
		add_filter( 'the_content', array( $this, 'arbtt_display_post_data' ) );
	}

	/**
	 * Prevent cloning.
	 */
	protected function __clone() {}

	/**
	 * Prevent unserializing.
	 *
	 * @throws \Exception When unserialized.
	 */
	public function __wakeup() {
		throw new \Exception( 'Cannot unserialize a singleton.' );
	}

	/**
	 * Get singleton instance.
	 *
	 * @return Frontend Instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Display meta info on frontend single posts.
	 *
	 * @param string $content Post content.
	 * @return string Modified post content.
	 */
	public function arbtt_display_post_data( $content ) {
		if ( ! is_single() || ! is_main_query() ) {
			return $content;
		}

		$this->increment_post_views( get_the_ID() );

		$post_data  = '<div class="arbtt-post-data arbtt-stats">';
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

		if ( '1' === get_option( 'arbtt_word_count', '0' ) ) {
			$word_count = str_word_count( wp_strip_all_tags( $content ) );
			$post_data .= sprintf(
				'<span class="arbtt-word-count"><strong>%s:</strong> %s</span>',
				esc_html__( 'Word Count', 'arbtt' ),
				esc_html( $word_count )
			);
		}

		if ( '1' === get_option( 'arbtt_char_counts', '0' ) ) {
			$char_count = mb_strlen( wp_strip_all_tags( $content ) );
			$post_data .= sprintf(
				'<span class="arbtt-char-count"><strong>%s:</strong> %s</span>',
				esc_html__( 'Character Count', 'arbtt' ),
				esc_html( $char_count )
			);
		}

		if ( '1' === get_option( 'arbtt_read_time', '0' ) ) {
			$word_count  = str_word_count( wp_strip_all_tags( $content ) );
			$minutes     = ceil( $word_count / 200 );
			$time_string = ( 1 === $minutes )
				? esc_html__( 'minute', 'arbtt' )
				: esc_html__( 'minutes', 'arbtt' );

			$post_data .= sprintf(
				'<span class="arbtt-read-time"><strong>%s:</strong> %s %s</span>',
				esc_html__( 'Estimated Reading Time', 'arbtt' ),
				esc_html( $minutes ),
				esc_html( $time_string )
			);
		}

		if ( '1' === get_option( 'arbtt_view_count', '0' ) ) {
			$views = (int) get_post_meta( get_the_ID(), '_arbtt_post_views', true );
			$post_data .= sprintf(
				'<span class="arbtt-view-count"><strong>%s:</strong> %s</span>',
				esc_html__( 'Total Views', 'arbtt' ),
				esc_html( $views )
			);
		}

		$meta_position = get_option( 'arbtt_meta_position', '0' );

		switch ( $meta_position ) {
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
	 * Increment post view count.
	 *
	 * @param int $post_id The post ID.
	 */
	public function increment_post_views( $post_id ) {
		$current_views = get_post_meta( $post_id, '_arbtt_post_views', true );

		if ( empty( $current_views ) || ! is_numeric( $current_views ) ) {
			$current_views = 0;
		}

		update_post_meta( $post_id, '_arbtt_post_views', ++$current_views );
	}
}

/**
 * Kickstart the frontend logic.
 */
function frontend_kickoff() {
	AR_Frontend::get_instance();
}
frontend_kickoff();
