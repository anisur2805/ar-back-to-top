<style type="text/css">
	#arbtt-container {
		display: <?php echo esc_attr( $display ); ?>;
	}

	.arbtt {
		<?php if ( ! in_array( $arbtt_btnst, array( 'txt', 'both' ), true ) ) : ?>
		width: <?php echo esc_attr( $btnwidth ); ?>px;
		height: <?php echo esc_attr( $btnheight ); ?>px;
		<?php endif; ?>
		line-height: <?php echo esc_attr( $btnheight ); ?>px;
		padding: <?php echo esc_attr( $btnpadding ); ?>px;
		text-align: center;
		font-weight: bold;
		color: <?php echo esc_attr( $arbtt_clr ); ?> !important;
		text-decoration: none !important;
		position: fixed;
		bottom: <?php echo esc_attr( $arbtt_btn_offset_bottom ); ?>px;
		<?php echo esc_attr( $arbtt_btnps ); ?>: <?php echo esc_attr( 'left' === $arbtt_btnps ? $arbtt_btn_offset_left : $arbtt_btn_offset_right ); ?>px;
		display: none;
		align-items: center;
		justify-content: center;
		background-color: <?php echo esc_attr( $arbtt_bgc ); ?> !important;
		opacity: <?php echo esc_attr( $arbtt_btnoc ); ?>;

		<?php if ( 'both' !== $arbtt_btnst && 'txt' !== $arbtt_btnst && $arbtt_enable_scroll_progress ) : ?>
		border-radius: 100px;
		<?php else : ?>
		border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px;

		<?php endif; ?>
		z-index: 9999;
		border: <?php echo esc_attr( $arbtt_bdr ); ?>px solid <?php echo esc_attr( $arbtt_bdr_color ); ?>;
		transition: all 0.3s ease-in-out;
		box-sizing: border-box;
	}

	<?php if ( 'both' !== $arbtt_btnst && 'txt' !== $arbtt_btnst && $arbtt_enable_scroll_progress ) : ?>
	.progress-svg {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		pointer-events: none;
		transform: rotate(-90deg);
	}
	.progress-svg path {
		stroke: green;
		stroke-width: <?php echo esc_attr( $arbtt_bdr ); ?>;
		fill: none;
		stroke-linejoin: round;
		transition: stroke-dashoffset 0.3s linear;
	}
	<?php endif; ?>

	.arbtt:hover {
		opacity: 1;
		cursor: pointer;
		background-color: <?php echo esc_attr( $arbtt_bgc_hover ); ?> !important;
		color: <?php echo esc_attr( $arbtt_clr_hover ); ?> !important;
		border-color: <?php echo esc_attr( $arbtt_bdr_color_hover ); ?> !important;
	}

	.arbtt .fa {
		line-height: <?php echo esc_attr( $btnheight ) / 2; ?>px;
		font-size: <?php echo esc_attr( $arbtt_fz ); ?>px;
		display: block;
	}

	.arbtt:visited,
	.arbtt:focus {
		color: #fff;
		outline: 0;
	}

	.arbtt img {
		height: calc(<?php echo esc_attr( $btnheight ); ?>px - 10px);
		width: calc(<?php echo esc_attr( $btnwidth ); ?>px - 10px);
		/* margin: -4px 0 0; */
		padding: 0;
		vertical-align: middle;
	}

	.arbtt img.both-img {
		margin-right: 10px;
	}

	<?php
	// Convert boolean flags and integer values
	$hide_on_tablet = $arbtt_hide_on_tablet === '1';
	$hide_on_phone  = $arbtt_hide_on_phone === '1';
	$phone_width    = (int) $arbtt_pwidth; // Custom mobile width from settings
	$tablet_width   = (int) $arbtt_twidth; // Standard tablet breakpoint

	// Only output styles if at least one device type is selected for hiding
	if ( $hide_on_tablet || $hide_on_phone ) :
		// Create appropriate media queries based on selections
		if ( $hide_on_tablet && $hide_on_phone ) {
			// Both device types are selected - create two separate media queries
			?>
			/* Hide on mobile devices up to the specified width */
			@media (max-width: <?php echo esc_attr( $phone_width ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			
			/* Hide on tablet devices up to 768px */
			@media (min-width: <?php echo esc_attr( $phone_width + 1 ); ?>px) and (max-width: <?php echo esc_attr( $tablet_width ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		} elseif ( $hide_on_phone ) {
			// Only hide on mobile devices
			?>
			@media (max-width: <?php echo esc_attr( $phone_width ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		} elseif ( $hide_on_tablet ) {
			// Only hide on tablet devices
			?>
			@media (max-width: <?php echo esc_attr( $tablet_width ); ?>px) and (min-width: <?php echo esc_attr( $phone_width + 1 ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		}
endif;
	?>

</style>
