/**
 * AR Back To Top - Pure Utility Functions
 *
 * These functions are framework-agnostic, have no side effects,
 * and are fully unit-testable with Jest.
 *
 * @package AR_Back_To_Top
 */

/**
 * Determine whether the back-to-top button should be visible.
 *
 * @param {number} scrollTop    Current vertical scroll position in px.
 * @param {number} threshold    Scroll depth (px) at which button appears.
 * @returns {boolean}
 */
export function shouldShowButton( scrollTop, threshold = 300 ) {
	return scrollTop >= threshold;
}

/**
 * Determine scroll direction.
 *
 * @param {number} currentScrollY
 * @param {number} lastScrollY
 * @returns {'up'|'down'|'none'}
 */
export function getScrollDirection( currentScrollY, lastScrollY ) {
	if ( currentScrollY > lastScrollY ) return 'down';
	if ( currentScrollY < lastScrollY ) return 'up';
	return 'none';
}

/**
 * Calculate scroll progress as a percentage (0–100).
 *
 * @param {number} scrollTop  Current scroll position in px.
 * @param {number} docHeight  Total document height in px.
 * @param {number} winHeight  Viewport height in px.
 * @returns {number} Integer between 0 and 100.
 */
export function getScrollPercent( scrollTop, docHeight, winHeight ) {
	const scrollable = docHeight - winHeight;
	if ( scrollable <= 0 ) return 0;
	return Math.round( ( scrollTop / scrollable ) * 100 );
}

/**
 * Calculate SVG stroke-dashoffset for circular progress ring.
 *
 * @param {number} scrollTop
 * @param {number} docHeight
 * @param {number} winHeight
 * @param {number} pathLength Total SVG path length in px.
 * @returns {number}
 */
export function getProgressOffset( scrollTop, docHeight, winHeight, pathLength ) {
	const scrollable = docHeight - winHeight;
	if ( scrollable <= 0 ) return pathLength;
	const percent = Math.min( 1, Math.max( 0, scrollTop / scrollable ) );
	return pathLength - pathLength * percent;
}

/**
 * Calculate reading progress bar width percentage (0–100), clamped.
 *
 * @param {number} scrollTop
 * @param {number} docHeight
 * @param {number} winHeight
 * @returns {number}
 */
export function getReadingProgress( scrollTop, docHeight, winHeight ) {
	const scrollable = docHeight - winHeight;
	if ( scrollable <= 0 ) return 0;
	const percent = ( scrollTop / scrollable ) * 100;
	return Math.max( 0, Math.min( 100, percent ) );
}

/**
 * Easing functions for scroll animation.
 * Each takes a progress value t ∈ [0, 1] and returns an eased value.
 */
export const easingFunctions = {
	linear:     ( t ) => t,
	'ease-in':  ( t ) => t * t,
	'ease-out': ( t ) => t * ( 2 - t ),
	'ease-in-out': ( t ) => t < 0.5 ? 2 * t * t : -1 + ( 4 - 2 * t ) * t,
};

/**
 * Get easing function by name, falling back to ease-in-out.
 *
 * @param {string} name
 * @returns {Function}
 */
export function getEasingFunction( name ) {
	return easingFunctions[ name ] || easingFunctions[ 'ease-in-out' ];
}

/**
 * Determine whether a swipe gesture qualifies as an upward swipe.
 *
 * @param {number} startY
 * @param {number} endY
 * @param {number} startX
 * @param {number} endX
 * @param {number} threshold   Minimum vertical distance in px (default 100).
 * @param {number} maxHorizontal Maximum horizontal drift allowed in px (default 50).
 * @returns {boolean}
 */
export function isSwipeUp( startY, endY, startX, endX, threshold = 100, maxHorizontal = 50 ) {
	const diffY = startY - endY;
	const diffX = Math.abs( startX - endX );
	return diffY > threshold && diffX < maxHorizontal;
}

/**
 * Check whether the keyboard event target is an editable field.
 * Used to prevent scroll shortcut from firing inside inputs.
 *
 * @param {string} tagName  Lowercase tag name of the event target.
 * @param {string} contentEditable  Value of contenteditable attribute.
 * @returns {boolean}
 */
export function isEditableTarget( tagName, contentEditable ) {
	const editableTags = [ 'input', 'textarea', 'select' ];
	if ( editableTags.includes( tagName ) ) return true;
	if ( contentEditable === 'true' ) return true;
	return false;
}

/**
 * Build the animation class name for the button.
 *
 * @param {string} animation  Animation slug (e.g. 'fade', 'none').
 * @returns {string|null}     Class name or null when animation is 'none'.
 */
export function getAnimationClass( animation ) {
	if ( ! animation || animation === 'none' ) return null;
	return `arbtt-animate-${ animation }`;
}

export function createButton( { label = 'Back to top', position = 'right' } = {} ) {
    const btn = document.createElement( 'button' );
    btn.className = `ar-btt ar-btt--${ position }`;
    btn.setAttribute( 'aria-label', label );
    btn.textContent = '↑';
    return btn;
}
