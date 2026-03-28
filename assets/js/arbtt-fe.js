/**
 * AR Back To Top - Frontend Script (Vanilla JS)
 *
 * @package AR_Back_To_Top
 */
( function() {
	'use strict';

	var visibleAfter  = parseInt( arbtt_obj.btn_visible_after, 10 ) || 100;
	var fadeDuration  = parseInt( arbtt_obj.fade_in, 10 ) || 950;
	var autoHide      = arbtt_obj.auto_hide || false;
	var autoHideAfter = ( parseInt( arbtt_obj.auto_hide_after, 10 ) || 3 ) * 1000;
	var scrollEasing  = arbtt_obj.scroll_easing || 'ease-in-out';
	var btn           = document.querySelector( '.arbtt' );

	/**
	 * Easing functions.
	 */
	var easingFunctions = {
		'linear': function( t ) {
			return t;
		},
		'ease-in': function( t ) {
			return t * t;
		},
		'ease-out': function( t ) {
			return t * ( 2 - t );
		},
		'ease-in-out': function( t ) {
			return t < 0.5 ? 2 * t * t : -1 + ( 4 - 2 * t ) * t;
		}
	};

	/**
	 * Animate scroll to top with custom easing.
	 *
	 * @param {number}   duration Duration in ms.
	 * @param {Function} easing   Easing function.
	 */
	function scrollToTop( duration, easing ) {
		var startY    = window.scrollY;
		var startTime = performance.now();

		function step( currentTime ) {
			var elapsed  = currentTime - startTime;
			var progress = Math.min( elapsed / duration, 1 );
			var easedProgress = easing( progress );

			window.scrollTo( 0, startY * ( 1 - easedProgress ) );

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		}

		window.requestAnimationFrame( step );
	}

	if ( ! btn ) {
		return;
	}

	var ticking      = false;
	var hideTimer    = null;

	/**
	 * Show the button.
	 */
	function showButton() {
		btn.classList.add( 'arbtt-visible' );
	}

	/**
	 * Hide the button.
	 */
	function hideButton() {
		btn.classList.remove( 'arbtt-visible' );
	}

	/**
	 * Reset the auto-hide timer.
	 */
	function resetAutoHideTimer() {
		if ( ! autoHide ) {
			return;
		}
		if ( hideTimer ) {
			clearTimeout( hideTimer );
		}
		hideTimer = setTimeout( function() {
			hideButton();
		}, autoHideAfter );
	}

	/**
	 * Handle scroll visibility with requestAnimationFrame throttle.
	 */
	function onScroll() {
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				if ( window.scrollY > visibleAfter ) {
					showButton();
					resetAutoHideTimer();
				} else {
					hideButton();
					if ( hideTimer ) {
						clearTimeout( hideTimer );
					}
				}
				ticking = false;
			} );
			ticking = true;
		}
	}

	/**
	 * Smooth scroll to top on button click with configured easing.
	 */
	btn.addEventListener( 'click', function( e ) {
		e.preventDefault();
		var easingFn = easingFunctions[ scrollEasing ] || easingFunctions['ease-in-out'];
		scrollToTop( fadeDuration, easingFn );
	} );

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	/* ----- Scroll Progress Indicator ----- */
	var progressPath = document.querySelector( '.arbtt-container .progress-svg path' );

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
