import { shouldShowButton, getScrollPercent, createButton } from '../src/back-to-top';

// ─────────────────────────────────────────────
// shouldShowButton
// ─────────────────────────────────────────────
describe('shouldShowButton', () => {

	test('returns true when scroll exceeds threshold', () => {
		expect(shouldShowButton(400, 300)).toBe(true);
	});

	test('returns false when scroll is below threshold', () => {
		expect(shouldShowButton(100, 300)).toBe(false);
	});

	test('returns true at exact threshold value', () => {
		expect(shouldShowButton(300, 300)).toBe(true);
	});

	test('uses 300 as default threshold when none provided', () => {
		expect(shouldShowButton(299)).toBe(false);
		expect(shouldShowButton(300)).toBe(true);
	});

	test('returns false at scroll position 0', () => {
		expect(shouldShowButton(0)).toBe(false);
	});
});

// ─────────────────────────────────────────────
// getScrollPercent
// ─────────────────────────────────────────────
describe('getScrollPercent', () => {

	test('returns 50 when halfway down the page', () => {
		// docHeight=1000, winHeight=500 → scrollable=500, scrollTop=250 → 50%
		expect(getScrollPercent(250, 1000, 500)).toBe(50);
	});

	test('returns 0 at top of page', () => {
		expect(getScrollPercent(0, 1000, 500)).toBe(0);
	});

	test('returns 100 at bottom of page', () => {
		// scrollTop = docHeight - winHeight = 500
		expect(getScrollPercent(500, 1000, 500)).toBe(100);
	});

	test('returns 0 when page is not scrollable (docHeight equals winHeight)', () => {
		// division by zero edge case — scrollable = 0
		expect(getScrollPercent(0, 500, 500)).toBe(0);
	});

	test('returns 0 when docHeight is less than winHeight', () => {
		// short page that doesn't need scrolling
		expect(getScrollPercent(0, 300, 500)).toBe(0);
	});

	test('rounds to nearest integer', () => {
		// scrollTop=1, scrollable=3 → 33.33... → rounds to 33
		expect(getScrollPercent(1, 4, 1)).toBe(33);
	});
});

// ─────────────────────────────────────────────
// createButton
// ─────────────────────────────────────────────
describe('createButton', () => {

	test('creates a button element', () => {
		const btn = createButton();
		expect(btn.tagName).toBe('BUTTON');
	});

	test('sets aria-label with default value', () => {
		const btn = createButton();
		expect(btn.getAttribute('aria-label')).toBe('Back to top');
	});

	test('sets custom aria-label when provided', () => {
		const btn = createButton({ label: 'Scroll up' });
		expect(btn.getAttribute('aria-label')).toBe('Scroll up');
	});

	test('adds base class ar-btt', () => {
		const btn = createButton();
		expect(btn.classList.contains('ar-btt')).toBe(true);
	});

	test('adds right position class by default', () => {
		const btn = createButton();
		expect(btn.classList.contains('ar-btt--right')).toBe(true);
	});

	test('adds left position class when position is left', () => {
		const btn = createButton({ position: 'left' });
		expect(btn.classList.contains('ar-btt--left')).toBe(true);
	});

	test('renders the up arrow character as text content', () => {
		const btn = createButton();
		expect(btn.textContent).toBe('↑');
	});
});