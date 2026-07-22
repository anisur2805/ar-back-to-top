/**
 * AR Back To Top - Frontend Script (Refactored)
 *
 * DOM interaction and WordPress-dependent logic lives here.
 * Pure utility functions are imported from src/back-to-top.js.
 *
 * @package AR_Back_To_Top
 */
import {
	shouldShowButton,
	getScrollDirection,
	getProgressOffset,
	getReadingProgress,
	getEasingFunction,
	isSwipeUp,
	isEditableTarget,
	getAnimationClass,
} from './back-to-top.js';

( function() {
	'use strict';

	// ── Config from WordPress (wp_localize_script) ──────────────────
	var visibleAfter    = parseInt( arbtt_obj.btn_visible_after, 10 ) || 100;
	var fadeDuration    = parseInt( arbtt_obj.fade_in, 10 ) || 950;
	var autoHide        = arbtt_obj.auto_hide || false;
	var autoHideAfter   = ( parseInt( arbtt_obj.auto_hide_after, 10 ) || 3 ) * 1000;
	var scrollEasing    = arbtt_obj.scroll_easing || 'ease-in-out';
	var btnAnimation    = arbtt_obj.button_animation || 'none';
	var smartVisibility = arbtt_obj.smart_visibility === '1' || arbtt_obj.smart_visibility === true;
	var btn             = document.querySelector( '.arbtt' );

	// ── Scroll to top animator ───────────────────────────────────────
	function scrollToTop( duration, easingFn ) {
		var startY    = window.scrollY;
		var startTime = performance.now();

		function step( currentTime ) {
			var elapsed      = currentTime - startTime;
			var progress     = Math.min( elapsed / duration, 1 );
			var easedProgress = easingFn( progress );

			window.scrollTo( 0, startY * ( 1 - easedProgress ) );

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		}

		window.requestAnimationFrame( step );
	}

	// ── Scroll to bottom button ──────────────────────────────────────
	var bottomBtn = document.getElementById( 'arbtt-bottom' );

	if ( bottomBtn ) {
		bottomBtn.addEventListener( 'click', function( e ) {
			e.preventDefault();

			var duration  = parseInt( arbtt_obj.scroll_duration, 10 ) || 500;
			var easingFn  = getEasingFunction( scrollEasing );
			var startY    = window.scrollY;
			var targetY   = document.documentElement.scrollHeight - window.innerHeight;
			var distance  = targetY - startY;
			var startTime = performance.now();

			function step( currentTime ) {
				var elapsed      = currentTime - startTime;
				var progress     = Math.min( elapsed / duration, 1 );
				var easedProgress = easingFn( progress );

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

	var ticking   = false;
	var hideTimer = null;

	// ── Show / hide helpers ──────────────────────────────────────────
	function showButton() {
		btn.classList.add( 'arbtt-visible' );

		var animClass = getAnimationClass( btnAnimation );
		if ( animClass ) {
			btn.classList.add( animClass );
		}

		if ( bottomBtn ) {
			bottomBtn.classList.add( 'arbtt-bottom-visible' );
			if ( animClass ) {
				bottomBtn.classList.add( animClass );
			}
		}
	}

	function hideButton() {
		btn.classList.remove( 'arbtt-visible' );

		var animClass = getAnimationClass( btnAnimation );
		if ( animClass ) {
			btn.classList.remove( animClass );
		}

		if ( bottomBtn ) {
			bottomBtn.classList.remove( 'arbtt-bottom-visible' );
			if ( animClass ) {
				bottomBtn.classList.remove( animClass );
			}
		}
	}

	// ── Auto-hide timer ──────────────────────────────────────────────
	function resetAutoHideTimer() {
		if ( ! autoHide ) return;
		if ( hideTimer ) clearTimeout( hideTimer );
		hideTimer = setTimeout( hideButton, autoHideAfter );
	}

	// ── Scroll visibility handler ────────────────────────────────────
	var lastScrollY = window.scrollY;

	function onScroll() {
		if ( ticking ) return;

		window.requestAnimationFrame( function() {
			var currentScrollY = window.scrollY;
			var direction      = getScrollDirection( currentScrollY, lastScrollY );
			var aboveThreshold = shouldShowButton( currentScrollY, visibleAfter );

			if ( aboveThreshold ) {
				if ( smartVisibility ) {
					if ( direction === 'down' ) {
						showButton();
						resetAutoHideTimer();
					} else {
						hideButton();
						if ( hideTimer ) clearTimeout( hideTimer );
					}
				} else {
					showButton();
					resetAutoHideTimer();
				}
			} else {
				hideButton();
				if ( hideTimer ) clearTimeout( hideTimer );
			}

			lastScrollY = currentScrollY;
			ticking     = false;
		} );

		ticking = true;
	}

	// ── Analytics click tracking ─────────────────────────────────────
	function trackClick() {
		if ( arbtt_obj.enable_analytics !== '1' && arbtt_obj.enable_analytics !== true ) {
			return;
		}

		var data = new FormData();
		data.append( 'action', 'arbtt_track_click' );
		data.append( 'nonce', arbtt_obj.track_nonce );

		fetch( arbtt_obj.ajax_url, { method: 'POST', body: data } ).catch( function() {} );
	}

	// ── Button click ─────────────────────────────────────────────────
	btn.addEventListener( 'click', function( e ) {
		e.preventDefault();
		trackClick();
		scrollToTop( fadeDuration, getEasingFunction( scrollEasing ) );
	} );

	window.addEventListener( 'scroll', onScroll, { passive: true } );

	// ── Keyboard shortcut (Home key) ─────────────────────────────────
	if ( arbtt_obj.enable_keyboard === '1' || arbtt_obj.enable_keyboard === true ) {
		document.addEventListener( 'keydown', function( e ) {
			if ( e.key !== 'Home' ) return;
			if ( e.ctrlKey || e.altKey || e.shiftKey || e.metaKey ) return;
			if ( isEditableTarget( e.target.tagName.toLowerCase(), e.target.contentEditable ) ) return;

			e.preventDefault();
			trackClick();
			scrollToTop( fadeDuration, getEasingFunction( scrollEasing ) );
		} );
	}

	// ── Touch swipe up ───────────────────────────────────────────────
	if ( arbtt_obj.enable_touch === '1' || arbtt_obj.enable_touch === true ) {
		var touchStartY = 0;
		var touchStartX = 0;

		document.addEventListener( 'touchstart', function( e ) {
			touchStartY = e.touches[ 0 ].clientY;
			touchStartX = e.touches[ 0 ].clientX;
		}, { passive: true } );

		document.addEventListener( 'touchend', function( e ) {
			var endY = e.changedTouches[ 0 ].clientY;
			var endX = e.changedTouches[ 0 ].clientX;

			if ( isSwipeUp( touchStartY, endY, touchStartX, endX ) ) {
				trackClick();
				scrollToTop( fadeDuration, getEasingFunction( scrollEasing ) );
			}
		}, { passive: true } );
	}

	// ── Circular progress ring ───────────────────────────────────────
	var progressPath = document.querySelector( '.arbtt-container .progress-svg path' );

	if ( progressPath && typeof progressPath.getTotalLength === 'function' ) {
		var pathLength = progressPath.getTotalLength();

		progressPath.style.strokeDasharray  = pathLength;
		progressPath.style.strokeDashoffset = pathLength;

		function updateProgress() {
			progressPath.style.strokeDashoffset = getProgressOffset(
				window.scrollY,
				document.documentElement.scrollHeight,
				window.innerHeight,
				pathLength
			);
		}

		updateProgress();

		var progressTicking = false;

		window.addEventListener( 'scroll', function() {
			if ( progressTicking ) return;
			window.requestAnimationFrame( function() {
				updateProgress();
				progressTicking = false;
			} );
			progressTicking = true;
		}, { passive: true } );

		window.addEventListener( 'resize', function() {
			window.requestAnimationFrame( updateProgress );
		}, { passive: true } );
	}

	// ── Reading progress bar ─────────────────────────────────────────
	var readingBar = document.getElementById( 'arbtt-reading-progress' );

	if ( readingBar ) {
		var adminBar = document.getElementById( 'wpadminbar' );
		if ( adminBar && adminBar.offsetHeight > 0 ) {
			readingBar.style.top = adminBar.offsetHeight + 'px';
		}

		function updateReadingProgress() {
			var percent = getReadingProgress(
				window.scrollY,
				document.documentElement.scrollHeight,
				window.innerHeight
			);
			readingBar.style.width = percent + '%';
		}

		updateReadingProgress();

		var readingTicking = false;

		window.addEventListener( 'scroll', function() {
			if ( readingTicking ) return;
			window.requestAnimationFrame( function() {
				updateReadingProgress();
				readingTicking = false;
			} );
			readingTicking = true;
		}, { passive: true } );

		window.addEventListener( 'resize', function() {
			window.requestAnimationFrame( updateReadingProgress );
		}, { passive: true } );
	}

} )();