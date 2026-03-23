<style type="text/css">
	#arbtt-container {
		display: <?php echo esc_attr( $display ); ?>;
	}

	.arbtt {
		<?php if ( ! in_array( $arbtt_btnst, array( 'both' ), true ) ) : ?>
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
		<?php if ( 'center' === $arbtt_btnps ) : ?>
		left: 50%;
		transform: translateX(-50%);
		<?php else : ?>
		<?php echo esc_attr( $arbtt_btnps ); ?>: <?php echo esc_attr( 'left' === $arbtt_btnps ? $arbtt_btn_offset_left : $arbtt_btn_offset_right ); ?>px;
		<?php endif; ?>
		display: none;
		align-items: center;
		justify-content: center;
		background-color: <?php echo esc_attr( $arbtt_bgc ); ?> !important;
		opacity: <?php echo esc_attr( $arbtt_btnoc ); ?>;

		<?php if ( 'circle' === $arbtt_btn_shape ) : ?>
		border-radius: 50%;
		<?php elseif ( 'square' === $arbtt_btn_shape ) : ?>
		border-radius: 0;
		<?php elseif ( 'rounded' === $arbtt_btn_shape ) : ?>
		border-radius: 8px;
		<?php elseif ( 'both' !== $arbtt_btnst && '1' === $arbtt_enable_scroll_progress ) : ?>
		border-radius: 50%;
		<?php else : ?>
		border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px;
		<?php endif; ?>
		z-index: <?php echo absint( $arbtt_zindex ); ?>;
		border: <?php echo esc_attr( $arbtt_bdr ); ?>px solid <?php echo esc_attr( $arbtt_bdr_color ); ?>;
		transition: all 0.3s ease-in-out;
		box-sizing: border-box;
	}

	<?php if ( 'right' === $arbtt_btn_img_position ) : ?>
	.arbtt.arbtt-icon-pos-right {
		flex-direction: row-reverse;
	}
	.arbtt.arbtt-icon-pos-right img.both-img {
		margin-right: 0px;
		margin-left: 10px;
	}
	<?php endif; ?>

	<?php if ( 'both' !== $arbtt_btnst && '1' === $arbtt_enable_scroll_progress ) : ?>
	.progress-svg {
		position: absolute;
		top: -<?php echo esc_attr( $arbtt_bdr + 1 ); ?>px;
		left: -<?php echo esc_attr( $arbtt_bdr + 1 ); ?>px;
		width: <?php echo esc_attr( $btnwidth + 2 ); ?>px;
		height: <?php echo esc_attr( $btnheight + 2 ); ?>px;
		pointer-events: none;
		/* transform: rotate(-90deg); */
		overflow: visible;
	}
	.progress-svg path {
		stroke: <?php echo esc_attr( $arbtt_progress_color ); ?>;
		stroke-width: <?php echo esc_attr( $arbtt_enable_scroll_progress_size ); ?>;
		fill: none;
		stroke-linecap: round;
		transition: stroke-dashoffset 0.2s linear;
	}
	<?php endif; ?>

	.arbtt:hover {
		opacity: 1;
		cursor: pointer;
		background-color: <?php echo esc_attr( $arbtt_bgc_hover ); ?> !important;
		color: <?php echo esc_attr( $arbtt_clr_hover ); ?> !important;
		border-color: <?php echo esc_attr( $arbtt_bdr_color_hover ); ?> !important;
	}

	.arbtt .fa,
	.arbtt .fa-solid,
	.arbtt .fa-regular {
		line-height: <?php echo esc_attr( $btnheight ) / 2; ?>px;
		font-size: <?php echo esc_attr( $arbtt_fz ); ?>px;
		display: block;
	}

	.arbtt:visited {
		color: #fff;
	}

	.arbtt:focus-visible {
		outline: 2px solid <?php echo esc_attr( $arbtt_clr ); ?>;
		outline-offset: 2px;
	}

	button.arbtt {
		cursor: pointer;
		font-family: inherit;
	}

	.screen-reader-text {
		border: 0;
		clip: rect(1px, 1px, 1px, 1px);
		clip-path: inset(50%);
		height: 1px;
		margin: -1px;
		overflow: hidden;
		padding: 0;
		position: absolute;
		width: 1px;
		word-wrap: normal !important;
	}

	.arbtt img {
		/* height: calc(<?php echo esc_attr( $arbtt_fz ); ?>px - 10px);
		width: calc(<?php echo esc_attr( $arbtt_fz ); ?>px - 10px);
		margin: -4px 0 0; */
		height: <?php echo esc_attr( $arbtt_fz ); ?>px;
		width: <?php echo esc_attr( $arbtt_fz ); ?>px;
		padding: 0;
		vertical-align: middle;
	}

	.arbtt img.both-img {
		margin-right: 10px;
	}

	<?php
	// Convert boolean flags and integer values
	$hide_on_desktop = isset( $arbtt_hide_on_desktop ) ? $arbtt_hide_on_desktop === '1' : get_option( 'arbtt_hide_on_desktop' ) === '1';
	$desktop_width   = isset( $arbtt_dwidth ) ? (int) $arbtt_dwidth : (int) get_option( 'arbtt_dwidth', 1025 );
	$hide_on_tablet  = $arbtt_hide_on_tablet === '1';
	$hide_on_phone   = $arbtt_hide_on_phone === '1';
	$phone_width     = (int) $arbtt_pwidth;
	$tablet_width    = (int) $arbtt_twidth;

	if ( $hide_on_desktop ) :
		?>
		/* Hide on desktop screens */
		@media (min-width: <?php echo esc_attr( $desktop_width ); ?>px) {
			#arbtt-container {
				display: none !important;
			}
		}
		<?php
	endif;

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

	// Mobile-specific positioning overrides
	$mobile_offset_bottom = get_option( 'arbtt_mobile_offset_bottom', '' );
	$mobile_offset_side   = get_option( 'arbtt_mobile_offset_side', '' );

	if ( '' !== $mobile_offset_bottom || '' !== $mobile_offset_side ) :
		?>
		/* Mobile positioning overrides */
		@media (max-width: <?php echo esc_attr( $phone_width ); ?>px) {
			.arbtt {
				<?php if ( '' !== $mobile_offset_bottom ) : ?>
				bottom: <?php echo absint( $mobile_offset_bottom ); ?>px !important;
				<?php endif; ?>
				<?php if ( '' !== $mobile_offset_side && 'center' !== $arbtt_btnps ) : ?>
				<?php echo esc_attr( $arbtt_btnps ); ?>: <?php echo absint( $mobile_offset_side ); ?>px !important;
				<?php endif; ?>
			}
		}
		<?php
	endif;
	?>

</style>
