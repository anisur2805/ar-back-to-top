/**
 * AR Back To Top - Frontend Script (Vanilla JS)
 *
 * @package AR_Back_To_Top
 */
( function() {
	'use strict';

	var visibleAfter   = parseInt( arbtt_obj.btn_visible_after, 10 ) || 100;
	var fadeDuration   = parseInt( arbtt_obj.fade_in, 10 ) || 950;
	var autoHide       = arbtt_obj.auto_hide || false;
	var autoHideAfter  = ( parseInt( arbtt_obj.auto_hide_after, 10 ) || 3 ) * 1000;
	var scrollEasing   = arbtt_obj.scroll_easing || 'ease-in-out';
	var btnAnimation    = arbtt_obj.button_animation || 'none';
	var smartVisibility = arbtt_obj.smart_visibility === '1' || arbtt_obj.smart_visibility === true;
	var btn             = document.querySelector( '.arbtt' );

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

	/* ----- Scroll To Bottom Button ----- */
	var bottomBtn = document.getElementById( 'arbtt-bottom' );

	if ( bottomBtn ) {
		bottomBtn.addEventListener( 'click', function( e ) {
			e.preventDefault();

			var duration = parseInt( arbtt_obj.scroll_duration, 10 ) || 500;
			var easing   = easingFunctions[ scrollEasing ] || easingFunctions[ 'ease-in-out' ];
			var startY   = window.scrollY;
			var docHeight = document.documentElement.scrollHeight;
			var winHeight = window.innerHeight;
			var targetY  = docHeight - winHeight;
			var distance = targetY - startY;
			var startTime = performance.now();

			function step( currentTime ) {
				var elapsed  = currentTime - startTime;
				var progress = Math.min( elapsed / duration, 1 );
				var easedProgress = easing( progress );

				window.scrollTo( 0, startY + distance * easedProgress );

				if ( progress < 1 ) {
					window.requestAnimationFrame( step );
				}
			}

			window.requestAnimationFrame( step );
		} );
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
		if ( btnAnimation !== 'none' ) {
			btn.classList.add( 'arbtt-animate-' + btnAnimation );
		}
		if ( bottomBtn ) {
			bottomBtn.classList.add( 'arbtt-bottom-visible' );
			if ( btnAnimation !== 'none' ) {
				bottomBtn.classList.add( 'arbtt-animate-' + btnAnimation );
			}
		}
	}

	/**
	 * Hide the button.
	 */
	function hideButton() {
		btn.classList.remove( 'arbtt-visible' );
		if ( btnAnimation !== 'none' ) {
			btn.classList.remove( 'arbtt-animate-' + btnAnimation );
		}
		if ( bottomBtn ) {
			bottomBtn.classList.remove( 'arbtt-bottom-visible' );
			if ( btnAnimation !== 'none' ) {
				bottomBtn.classList.remove( 'arbtt-animate-' + btnAnimation );
			}
		}
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
	var lastScrollY = window.scrollY;

	function onScroll() {
		if ( ! ticking ) {
			window.requestAnimationFrame( function() {
				var currentScrollY = window.scrollY;

				if ( currentScrollY > visibleAfter ) {
					if ( smartVisibility ) {
						if ( currentScrollY > lastScrollY ) {
							showButton();
							resetAutoHideTimer();
						} else {
							hideButton();
							if ( hideTimer ) {
								clearTimeout( hideTimer );
							}
						}
					} else {
						showButton();
						resetAutoHideTimer();
					}
				} else {
					hideButton();
					if ( hideTimer ) {
						clearTimeout( hideTimer );
					}
				}

				lastScrollY = currentScrollY;
				ticking = false;
			} );
			ticking = true;
		}
	}

	/**
	 * Track button click via AJAX.
	 */
	function trackClick() {
		if ( arbtt_obj.enable_analytics === '1' || arbtt_obj.enable_analytics === true ) {
			var data = new FormData();
			data.append( 'action', 'arbtt_track_click' );
			data.append( 'nonce', arbtt_obj.track_nonce );

			fetch( arbtt_obj.ajax_url, {
				method: 'POST',
				body: data
			} ).catch( function() {} );
		}
	}

	/**
	 * Smooth scroll to top on button click with configured easing.
	 */
	btn.addEventListener( 'click', function( e ) {
		e.preventDefault();
		trackClick();
		var easingFn = easingFunctions[ scrollEasing ] || easingFunctions['ease-in-out'];
		scrollToTop( fadeDuration, easingFn );
	} );

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	/* ----- Keyboard Shortcut (Home key) ----- */
	if ( arbtt_obj.enable_keyboard === '1' || arbtt_obj.enable_keyboard === true ) {
		document.addEventListener( 'keydown', function( e ) {
			if ( e.key === 'Home' && ! e.ctrlKey && ! e.altKey && ! e.shiftKey && ! e.metaKey ) {
				var target = e.target.tagName.toLowerCase();
				if ( target === 'input' || target === 'textarea' || target === 'select' || target === 'contenteditable' ) {
					return;
				}

				e.preventDefault();
				trackClick();
				var easingFn = easingFunctions[ scrollEasing ] || easingFunctions['ease-in-out'];
				scrollToTop( fadeDuration, easingFn );
			}
		} );
	}

	/* ----- Touch Gesture (Swipe Up) ----- */
	if ( arbtt_obj.enable_touch === '1' || arbtt_obj.enable_touch === true ) {
		var touchStartY = 0;
		var touchStartX = 0;
		var swipeThreshold = 100;

		document.addEventListener( 'touchstart', function( e ) {
			touchStartY = e.touches[0].clientY;
			touchStartX = e.touches[0].clientX;
		}, { passive: true } );

		document.addEventListener( 'touchend', function( e ) {
			var touchEndY = e.changedTouches[0].clientY;
			var touchEndX = e.changedTouches[0].clientX;
			var diffY = touchStartY - touchEndY;
			var diffX = Math.abs( touchStartX - touchEndX );

			if ( diffY > swipeThreshold && diffX < 50 ) {
				trackClick();
				var easingFn = easingFunctions[ scrollEasing ] || easingFunctions['ease-in-out'];
				scrollToTop( fadeDuration, easingFn );
			}
		}, { passive: true } );
	}

	/* ----- Scroll Progress Indicator (Ring) ----- */
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

	/* ----- Reading Progress Bar ----- */
	var readingBar = document.getElementById( 'arbtt-reading-progress' );

	if ( readingBar ) {
		// Position below admin bar if visible.
		var adminBar = document.getElementById( 'wpadminbar' );
		if ( adminBar && adminBar.offsetHeight > 0 ) {
			readingBar.style.top = adminBar.offsetHeight + 'px';
		}

		var readingTicking = false;

		function updateReadingProgress() {
			var scrollTop    = window.scrollY;
			var docHeight    = document.documentElement.scrollHeight;
			var winHeight    = window.innerHeight;
			var scrollPercent = ( scrollTop / ( docHeight - winHeight ) ) * 100;

			scrollPercent = Math.max( 0, Math.min( 100, scrollPercent ) );
			readingBar.style.width = scrollPercent + '%';
		}

		updateReadingProgress();

		window.addEventListener( 'scroll', function() {
			if ( ! readingTicking ) {
				window.requestAnimationFrame( function() {
					updateReadingProgress();
					readingTicking = false;
				} );
				readingTicking = true;
			}
		}, { passive: true } );

		window.addEventListener( 'resize', function() {
			window.requestAnimationFrame( updateReadingProgress );
		}, { passive: true } );
	}
} )();
