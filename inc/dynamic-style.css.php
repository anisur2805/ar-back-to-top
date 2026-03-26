<?php defined( 'ABSPATH' ) || exit; ?>
<style type="text/css">
	#arbtt-container {
		display: block;
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

		<?php
		// Scroll progress requires circle shape.
		if ( 'both' !== $arbtt_btnst && '1' === $arbtt_enable_scroll_progress ) :
			?>
		border-radius: 50% !important;
		<?php elseif ( 'circle' === $arbtt_btn_shape ) : ?>
		border-radius: 50% !important;
		<?php elseif ( 'square' === $arbtt_btn_shape ) : ?>
		border-radius: 0 !important;
		<?php elseif ( 'rounded' === $arbtt_btn_shape ) : ?>
		border-radius: 8px !important;
		<?php else : ?>
		border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px !important;
		<?php endif; ?>

		z-index: <?php echo absint( $arbtt_zindex ); ?>;
		border: <?php echo esc_attr( $arbtt_bdr ); ?>px solid <?php echo esc_attr( $arbtt_bdr_color ); ?>;
		transition: all 0.3s ease-in-out;
		box-sizing: border-box;
	}

	/* Shape - high specificity to override theme button styles */
	#arbtt-container button#arbtt.arbtt {
		<?php
		if ( 'both' !== $arbtt_btnst && '1' === $arbtt_enable_scroll_progress ) :
			?>
		border-radius: 50% !important;
		<?php elseif ( 'circle' === $arbtt_btn_shape ) : ?>
		border-radius: 50% !important;
		<?php elseif ( 'square' === $arbtt_btn_shape ) : ?>
		border-radius: 0 !important;
		<?php elseif ( 'rounded' === $arbtt_btn_shape ) : ?>
		border-radius: 8px !important;
		<?php else : ?>
		border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px !important;
		<?php endif; ?>
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

	.arbtt.arbtt-visible {
		display: flex;
	}

	.arbtt:hover {
		opacity: 1;
		cursor: pointer;
		background-color: <?php echo esc_attr( $arbtt_bgc_hover ); ?> !important;
		color: <?php echo esc_attr( $arbtt_clr_hover ); ?> !important;
		border-color: <?php echo esc_attr( $arbtt_bdr_color_hover ); ?> !important;
	}

	.arbtt .arbtt-svg-icon {
		width: <?php echo esc_attr( $arbtt_fz ); ?>px;
		height: <?php echo esc_attr( $arbtt_fz ); ?>px;
		display: block;
		fill: currentColor;
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
		height: <?php echo esc_attr( $arbtt_fz ); ?>px;
		width: <?php echo esc_attr( $arbtt_fz ); ?>px;
		padding: 0;
		vertical-align: middle;
	}

	.arbtt img.both-img {
		margin-right: 10px;
	}

	<?php
	// Convert boolean flags and integer values.
	$arbtt_is_hide_desktop = $arbtt_hide_on_desktop === '1';
	$arbtt_desktop_px      = (int) $arbtt_dwidth;
	$arbtt_is_hide_tablet  = $arbtt_hide_on_tablet === '1';
	$arbtt_is_hide_phone   = $arbtt_hide_on_phone === '1';
	$arbtt_phone_px        = (int) $arbtt_pwidth;
	$arbtt_tablet_px       = (int) $arbtt_twidth;

	if ( $arbtt_is_hide_desktop ) :
		?>
		/* Hide on desktop screens */
		@media (min-width: <?php echo esc_attr( $arbtt_desktop_px ); ?>px) {
			#arbtt-container {
				display: none !important;
			}
		}
		<?php
	endif;

	// Only output styles if at least one device type is selected for hiding.
	if ( $arbtt_is_hide_tablet || $arbtt_is_hide_phone ) :
		if ( $arbtt_is_hide_tablet && $arbtt_is_hide_phone ) {
			?>
			/* Hide on mobile devices up to the specified width */
			@media (max-width: <?php echo esc_attr( $arbtt_phone_px ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}

			/* Hide on tablet devices */
			@media (min-width: <?php echo esc_attr( $arbtt_phone_px + 1 ); ?>px) and (max-width: <?php echo esc_attr( $arbtt_tablet_px ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		} elseif ( $arbtt_is_hide_phone ) {
			?>
			@media (max-width: <?php echo esc_attr( $arbtt_phone_px ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		} elseif ( $arbtt_is_hide_tablet ) {
			?>
			@media (max-width: <?php echo esc_attr( $arbtt_tablet_px ); ?>px) and (min-width: <?php echo esc_attr( $arbtt_phone_px + 1 ); ?>px) {
				#arbtt-container {
					display: none !important;
				}
			}
			<?php
		}
	endif;

	// Mobile-specific positioning overrides.
	$arbtt_mob_offset_bottom = get_option( 'arbtt_mobile_offset_bottom', '' );
	$arbtt_mob_offset_side   = get_option( 'arbtt_mobile_offset_side', '' );

	if ( '' !== $arbtt_mob_offset_bottom || '' !== $arbtt_mob_offset_side ) :
		?>
		/* Mobile positioning overrides */
		@media (max-width: <?php echo esc_attr( $arbtt_phone_px ); ?>px) {
			.arbtt {
				<?php if ( '' !== $arbtt_mob_offset_bottom ) : ?>
				bottom: <?php echo absint( $arbtt_mob_offset_bottom ); ?>px !important;
				<?php endif; ?>
				<?php if ( '' !== $arbtt_mob_offset_side && 'center' !== $arbtt_btnps ) : ?>
				<?php echo esc_attr( $arbtt_btnps ); ?>: <?php echo absint( $arbtt_mob_offset_side ); ?>px !important;
				<?php endif; ?>
			}
		}
		<?php
	endif;
	?>

</style>
