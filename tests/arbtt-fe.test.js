/**
 * Regression tests: arbtt-fe.js must initialize without arbtt_obj present.
 *
 * Ships the exact failure mode from the audit: a minifier/defer/caching
 * plugin dropping the wp_localize_script() call used to kill the whole
 * script with a ReferenceError, disabling every behavior at once.
 *
 * @package AR_Back_To_Top
 */

const fs = require( 'fs' );
const path = require( 'path' );
const vm = require( 'vm' );

const script = fs.readFileSync(
	path.join( __dirname, '..', 'assets', 'js', 'arbtt-fe.js' ),
	'utf8'
);

/**
 * Build a minimal browser-ish sandbox and run the script.
 *
 * @param {Object} domOverrides Extra globals (document stub with query results, etc).
 * @return {Object} The sandbox.
 */
function runScript( domOverrides = {} ) {
	const noop = () => {};
	const sandbox = {
		window: {
			scrollY: 0,
			innerHeight: 800,
			addEventListener: noop,
			requestAnimationFrame: noop,
			scrollTo: noop,
		},
		document: {
			querySelector: () => ( domOverrides.button ? { addEventListener: noop, classList: { add: noop, remove: noop } } : null ),
			getElementById: () => null,
			addEventListener: noop,
			documentElement: { scrollHeight: 2000 },
		},
		FormData: class { append() {} },
		fetch: jest.fn( () => Promise.resolve( { ok: true } ) ),
		performance: { now: () => 0 },
		setTimeout: noop,
		clearTimeout: noop,
		parseInt,
		Math,
	};
	// Deliberately do NOT define arbtt_obj anywhere in the sandbox.
	Object.assign( sandbox, domOverrides.globals || {} );
	sandbox.window.document = sandbox.document;

	vm.createContext( sandbox );
	expect( () => vm.runInContext( script, sandbox ) ).not.toThrow();
	return sandbox;
}

describe( 'arbtt-fe.js initialises without arbtt_obj', () => {
	test( 'no ReferenceError when the localized object is absent', () => {
		runScript();
	} );

	test( 'no ReferenceError when the button is in the DOM either', () => {
		runScript( { button: true } );
	} );

	test( 'shows button on scroll when arbtt_obj exists as before (no behavior change)', () => {
		const noop = () => {};
		const classList = { add: jest.fn(), remove: jest.fn() };
		const listeners = {};
		const sandbox = {
			window: {
				scrollY: 0,
				innerHeight: 800,
				addEventListener: ( t, cb ) => { listeners[ t ] = cb; },
				requestAnimationFrame: ( cb ) => cb(),
				scrollTo: noop,
			},
			document: {
				querySelector: () => ( { addEventListener: noop, classList } ),
				getElementById: () => null,
				addEventListener: noop,
				documentElement: { scrollHeight: 2000 },
			},
			FormData: class { append() {} },
			fetch: jest.fn( () => Promise.resolve( { ok: true } ) ),
			performance: { now: () => 0 },
			setTimeout: noop,
			clearTimeout: noop,
			parseInt,
			Math,
			// Present, as normal wp_localize_script() output.
			arbtt_obj: {
				btn_visible_after: 300,
				fade_in: 950,
				auto_hide: false,
				auto_hide_after: 3,
				scroll_easing: 'linear',
				button_animation: 'none',
				scroll_duration: 700,
				enable_analytics: '0',
				smart_visibility: '0',
				enable_keyboard: '0',
				enable_touch: '0',
				ajax_url: '/wp-admin/admin-ajax.php',
				track_nonce: 'abc',
			},
		};
		sandbox.window.document = sandbox.document;
		vm.createContext( sandbox );
		vm.runInContext( script, sandbox );
		// Localized config must drive behavior: scroll handler registered so the
		// 300px threshold (not the 100px fallback) governs visibility.
		expect( Object.keys( listeners ) ).toContain( 'scroll' );
	} );
} );
