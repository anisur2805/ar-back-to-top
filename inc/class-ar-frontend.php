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

		$meta_position = get_option( 'arbtt_meta_position', 'bottom' );
		$meta_output   = '';

		// Start output buffer for meta section.
		ob_start();
		?>
		<div class="arbtt-post-data arbtt-stats">
			<style>
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
			</style>
	
			<?php if ( '1' === esc_attr( get_option( 'arbtt_word_count', '0' ) ) ) : ?>
				<span class="arbtt-word-count">
					<strong><?php esc_html_e( 'Word Count', 'arbtt' ); ?>:</strong>
					<?php echo esc_html( str_word_count( wp_strip_all_tags( $content ) ) ); ?>
				</span>
			<?php endif; ?>
	
			<?php if ( '1' === esc_attr( get_option( 'arbtt_char_counts', '0' ) ) ) : ?>
				<span class="arbtt-char-count">
					<strong><?php esc_html_e( 'Character Count', 'arbtt' ); ?>:</strong>
					<?php echo esc_html( mb_strlen( wp_strip_all_tags( $content ) ) ); ?>
				</span>
			<?php endif; ?>
	
			<?php
			if ( '1' === esc_attr( get_option( 'arbtt_read_time', '0' ) ) ) :
				$words   = str_word_count( wp_strip_all_tags( $content ) );
				$minutes = ceil( $words / 200 );
				$unit    = ( 1 === $minutes ) ? esc_html__( 'minute', 'arbtt' ) : esc_html__( 'minutes', 'arbtt' );
				?>
				<span class="arbtt-read-time">
					<strong><?php esc_html_e( 'Estimated Reading Time', 'arbtt' ); ?>:</strong>
					<?php echo esc_html( $minutes . ' ' . $unit ); ?>
				</span>
			<?php endif; ?>
	
			<?php if ( '1' === esc_attr( get_option( 'arbtt_view_count', '0' ) ) ) : ?>
				<span class="arbtt-view-count">
					<strong><?php esc_html_e( 'Total Views', 'arbtt' ); ?>:</strong>
					<?php echo esc_html( (int) get_post_meta( get_the_ID(), '_arbtt_post_views', true ) ); ?>
				</span>
			<?php endif; ?>
		</div>
		<?php
		$meta_output = ob_get_clean();

		// Return based on position setting.
		switch ( esc_attr( $meta_position ) ) {
			case 'bottom':
				return $content . $meta_output;

			case 'both':
				return $meta_output . $content . $meta_output;

			case 'top':
			default:
				return $meta_output . $content;
		}
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
