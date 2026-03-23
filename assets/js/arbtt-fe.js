/**
 * AR Back To Top - Frontend Script (Vanilla JS)
 *
 * @package AR_Back_To_Top
 */
( function() {
	'use strict';

	var visibleAfter = parseInt( arbtt_obj.btn_visible_after, 10 ) || 100;
	var fadeDuration = parseInt( arbtt_obj.fade_in, 10 ) || 950;
	var btn = document.querySelector( '.arbtt' );

	if ( ! btn ) {
		return;
	}

	var ticking = false;

	/**
	 * Handle scroll visibility with requestAnimationFrame throttle.
	 */
	function onScroll() {
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				if ( window.scrollY > visibleAfter ) {
					btn.style.display = 'flex';
					btn.style.opacity = '';
				} else {
					btn.style.display = 'none';
				}
				ticking = false;
			} );
			ticking = true;
		}
	}

	/**
	 * Smooth scroll to top on button click.
	 */
	btn.addEventListener( 'click', function( e ) {
		e.preventDefault();
		window.scrollTo( {
			top: 0,
			behavior: 'smooth'
		} );
	} );

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	/* ----- Scroll Progress Indicator ----- */
	var progressPath = document.querySelector( '.progress-svg path' );

	if ( progressPath && typeof progressPath.getTotalLength === 'function' ) {
		var pathLength = progressPath.getTotalLength();

		progressPath.style.strokeDasharray = pathLength;
		progressPath.style.strokeDashoffset = pathLength;

		var progressTicking = false;

		/**
		 * Update the circular progress indicator.
		 */
		function updateProgress() {
			var scrollTop  = window.scrollY;
			var docHeight  = document.documentElement.scrollHeight;
			var winHeight  = window.innerHeight;
			var scrollPercent = scrollTop / ( docHeight - winHeight );
			var drawLength = pathLength * scrollPercent;

			drawLength = Math.max( 0, Math.min( pathLength, drawLength ) );
			progressPath.style.strokeDashoffset = pathLength - drawLength;
		}

		updateProgress();

		window.addEventListener( 'scroll', function() {
			if ( ! progressTicking ) {
				window.requestAnimationFrame( function() {
					updateProgress();
					progressTicking = false;
				} );
				progressTicking = true;
			}
		}, { passive: true } );

		window.addEventListener( 'resize', function() {
			window.requestAnimationFrame( updateProgress );
		}, { passive: true } );
	}
} )();
