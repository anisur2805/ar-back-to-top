<style type="text/css">
	#arbtt-container {
		display: <?php echo esc_attr( $display ); ?>;
	}

	.arbtt {
		width: <?php echo esc_attr( $btnwidth ); ?>px;
		height: <?php echo esc_attr( $btnheight ); ?>px;
		line-height: <?php echo esc_attr( $btnheight ); ?>px;
		padding: <?php echo esc_attr( $btnpadding ); ?>px;
		text-align: center;
		font-weight: bold;
		color: <?php echo esc_attr( $arbtt_clr ); ?> !important;
		text-decoration: none !important;
		position: fixed;
		bottom: 75px;
		<?php echo esc_attr( $arbtt_btnps ); ?>: 40px;
		display: none;
		background-color: <?php echo esc_attr( $arbtt_bgc ); ?> !important;
		opacity: <?php echo esc_attr( $arbtt_btnoc ); ?>;
		border-radius: <?php echo esc_attr( $arbtt_bdrd ); ?>px;
		z-index: 9999;
	}

	.arbtt:hover {
		opacity: 0.7;
		cursor: pointer;
	}

	.arbtt .fa {
		line-height: <?php echo esc_attr( $btnheight ); ?>px;
		font-size: <?php echo esc_attr( $arbtt_fz ); ?>px;
		height: <?php echo esc_attr( $btnheight ); ?>px;
		width: <?php echo esc_attr( $btnwidth ); ?>px;
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
		margin: -4px 0 0;
		padding: 0;
		vertical-align: middle;
	}

	<?php if ( '1' === $arbtt_hophone ) : ?>
		@media (max-width: <?php echo esc_attr( $arbtt_pwidth ); ?>px) {
			#arbtt-container {
				display: none;
			}
		}
	<?php endif; ?>
</style>
