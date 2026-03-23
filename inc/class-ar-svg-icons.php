<?php
/**
 * SVG Icon Registry — inline SVG icons for zero external dependencies.
 *
 * @package AR_Back_To_Top
 */

defined( 'ABSPATH' ) || exit;

class AR_SVG_Icons {

	/**
	 * Get all available icons.
	 *
	 * @return array Associative array of icon_id => array( 'label' => string, 'svg' => string ).
	 */
	public static function get_icons() {
		return array(
			'angle-up'         => array(
				'label' => 'Angle Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/></svg>',
			),
			'angles-up'        => array(
				'label' => 'Angles Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M246.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L224 109.3 361.4 246.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160zm160 352l-160-160c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L224 301.3 361.4 438.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3z"/></svg>',
			),
			'arrow-up'         => array(
				'label' => 'Arrow Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M214.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-160 160c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L160 141.2V448c0 17.7 14.3 32 32 32s32-14.3 32-32V141.2l105.4 105.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-160-160z"/></svg>',
			),
			'circle-arrow-up'  => array(
				'label' => 'Circle Arrow Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm11.3-395.3l112 112c4.6 4.6 5.9 11.5 3.5 17.4s-8.3 9.9-14.8 9.9H304v96c0 17.7-14.3 32-32 32H240c-17.7 0-32-14.3-32-32V256H144c-6.5 0-12.3-3.9-14.8-9.9s-1.1-12.9 3.5-17.4l112-112c6.2-6.2 16.4-6.2 22.6 0z"/></svg>',
			),
			'chevron-up'       => array(
				'label' => 'Chevron Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/></svg>',
			),
			'caret-up'         => array(
				'label' => 'Caret Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>',
			),
			'arrow-up-long'    => array(
				'label' => 'Arrow Up Long',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M134.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-80 80c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L96 93.3V480c0 17.7 14.3 32 32 32s32-14.3 32-32V93.3l41.4 41.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-80-80z"/></svg>',
			),
			'turn-up'          => array(
				'label' => 'Turn Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M178.3 3.7c-6.2-6.2-16.4-6.2-22.6 0l-128 128c-6.2 6.2-6.2 16.4 0 22.6s16.4 6.2 22.6 0L144 60.9V360c0 57.4 46.6 104 104 104h64c8.8 0 16-7.2 16-16s-7.2-16-16-16H248c-39.8 0-72-32.2-72-72V60.9l93.7 93.7c6.2 6.2 16.4 6.2 22.6 0s6.2-16.4 0-22.6l-128-128z"/></svg>',
			),
			'sort-up'          => array(
				'label' => 'Sort Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M182.6 41.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/></svg>',
			),
			'upload'           => array(
				'label' => 'Upload',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M288 109.3V352c0 17.7-14.3 32-32 32s-32-14.3-32-32V109.3l-73.4 73.4c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L288 109.3zM64 352H192c0 35.3 28.7 64 64 64s64-28.7 64-64H448c35.3 0 64 28.7 64 64v32c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V416c0-35.3 28.7-64 64-64zM432 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"/></svg>',
			),
			'square-caret-up'  => array(
				'label' => 'Square Caret Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm52.7 247.4l128-128c12.5-12.5 32.8-12.5 45.3 0l128 128c9.2 9.2 11.9 22.9 6.9 34.9s-16.6 19.8-29.6 19.8H146.3c-12.9 0-24.6-7.8-29.6-19.8s-2.2-25.7 6.9-34.9z"/></svg>',
			),
			'backward-step'    => array(
				'label' => 'Backward Step',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path fill="currentColor" d="M267.5 440.6c9.5 7.9 22.8 9.7 34.1 4.4s18.4-16.6 18.4-29V96c0-12.4-7.2-23.7-18.4-29s-24.5-3.6-34.1 4.4l-192 160L64 241V96c0-17.7-14.3-32-32-32S0 78.3 0 96V416c0 17.7 14.3 32 32 32s32-14.3 32-32V271l11.5 9.6 192 160z"/></svg>',
			),
			'eject'            => array(
				'label' => 'Eject',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M224 32c-8.5 0-16.6 3.4-22.6 9.4l-174 174c-9.3 9.3-11.9 23.3-6.5 35.2S34.7 268 48 268H400c13.3 0 25.2-7.6 30.9-19.4s2.8-25.9-6.5-35.2l-174-174c-6-6-14.1-9.4-22.6-9.4zM48 416c-26.5 0-48 21.5-48 48s21.5 48 48 48H400c26.5 0 48-21.5 48-48s-21.5-48-48-48H48z"/></svg>',
			),
			'backward-fast'    => array(
				'label' => 'Backward Fast',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M493.6 445c-11.2 5.3-24.5 3.6-34.1-4.4L288 297.7V416c0 12.4-7.2 23.7-18.4 29s-24.5 3.6-34.1-4.4L64 297.7V416c0 17.7-14.3 32-32 32s-32-14.3-32-32V96C0 78.3 14.3 64 32 64s32 14.3 32 32V214.3L235.5 71.4c9.5-7.9 22.8-9.7 34.1-4.4S288 83.6 288 96V214.3L459.5 71.4c9.5-7.9 22.8-9.7 34.1-4.4S512 83.6 512 96V416c0 12.4-7.2 23.7-18.4 29z"/></svg>',
			),
			'hand-point-up'    => array(
				'label' => 'Hand Point Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M32 32C32 14.3 46.3 0 64 0S96 14.3 96 32V240H66.7c-11 0-21.6 2-31.3 5.8C34 225.7 32 204.9 32 184V32zm0 296.5C45.8 321.1 62.4 316 80 316h16c0-17.7 14.3-32 32-32s32 14.3 32 32h16c0-17.7 14.3-32 32-32s32 14.3 32 32h32c0-17.7 14.3-32 32-32s32 14.3 32 32v64c0 17.7-14.3 32-32 32H304v0H176v0H80c-44.2 0-80-35.8-80-80v-1.5c0-26.5 12.7-52 32-67.5z"/></svg>',
			),
			'hand-back-fist'   => array(
				'label' => 'Hand Back Fist',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M144 0C117.5 0 96 21.5 96 48V96H64c-17.7 0-32 14.3-32 32v36.8C12.3 175.3 0 195.7 0 220v28c0 24.3 12.3 44.7 32 56.2V416c0 53 43 96 96 96H320c53 0 96-43 96-96V224c0-17.7-14.3-32-32-32H352V160c0-17.7-14.3-32-32-32H288V48c0-26.5-21.5-48-48-48H144z"/></svg>',
			),
			'circle-chevron-up' => array(
				'label' => 'Circle Chevron Up',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM377 271c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-87-87-87 87c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9L239 167c9.4-9.4 24.6-9.4 33.9 0L377 271z"/></svg>',
			),
			'up-long'          => array(
				'label' => 'Up Long',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512"><path fill="currentColor" d="M134.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-80 80c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L96 93.3V480c0 17.7 14.3 32 32 32s32-14.3 32-32V93.3l41.4 41.4c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3l-80-80z"/></svg>',
			),
			'rocket'           => array(
				'label' => 'Rocket',
				'svg'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M156.6 384.9L125.7 354c-8.5-8.5-11.5-20.8-7.7-32.2c3-8.9 7.7-17.5 13.3-25.7c-52.8-4.4-96.3-36.2-96.3-75.5c0-29.3 23.9-56 61.2-71.5L93 86.9C59.3 99.8 32 129.4 32 168.4c0 46.4 41.8 88 92.3 107.4l32.3 109.1zM256 16c-75.7 0-165.3 77.3-165.3 168.4c0 81.4 101.7 180.8 152.4 220.3c5.7 4.5 13.7 4.5 19.4 0c50.7-39.5 152.4-138.9 152.4-220.3C415.3 93.3 325.7 16 256 16zm0 240a72 72 0 1 0 0-144 72 72 0 1 0 0 144z"/></svg>',
			),
		);
	}

	/**
	 * Get a single icon's SVG markup.
	 *
	 * @param string $icon_id The icon identifier.
	 * @param array  $attrs   Optional HTML attributes (class, style, etc.).
	 * @return string SVG markup or empty string.
	 */
	public static function get_icon( $icon_id, $attrs = array() ) {
		$icons = self::get_icons();

		if ( ! isset( $icons[ $icon_id ] ) ) {
			// Fallback to angle-up.
			$icon_id = 'angle-up';
		}

		$svg = $icons[ $icon_id ]['svg'];

		// Inject attributes into the <svg> tag.
		$attr_string = '';
		foreach ( $attrs as $key => $value ) {
			$attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
		}

		if ( $attr_string ) {
			$svg = str_replace( '<svg ', '<svg ' . $attr_string . ' ', $svg );
		}

		return $svg;
	}

	/**
	 * Map old FA class names to SVG icon IDs.
	 *
	 * @param string $fa_class The Font Awesome class string.
	 * @return string The SVG icon ID.
	 */
	public static function fa_class_to_id( $fa_class ) {
		$map = array(
			'fa-solid fa-angle-up'          => 'angle-up',
			'fa-solid fa-angles-up'         => 'angles-up',
			'fa-solid fa-arrow-up'          => 'arrow-up',
			'fa-solid fa-circle-arrow-up'   => 'circle-arrow-up',
			'fa-solid fa-chevron-up'        => 'chevron-up',
			'fa-solid fa-caret-up'          => 'caret-up',
			'fa-solid fa-arrow-up-long'     => 'arrow-up-long',
			'fa-solid fa-turn-up'           => 'turn-up',
			'fa-solid fa-sort-up'           => 'sort-up',
			'fa-solid fa-upload'            => 'upload',
			'fa-solid fa-square-caret-up'   => 'square-caret-up',
			'fa-solid fa-backward-step'     => 'backward-step',
			'fa-solid fa-eject'             => 'eject',
			'fa-solid fa-backward-fast'     => 'backward-fast',
			'fa-solid fa-hand-point-up'     => 'hand-point-up',
			'fa-solid fa-hand-back-fist'    => 'hand-back-fist',
			'fa-solid fa-circle-chevron-up' => 'circle-chevron-up',
			'fa-solid fa-up-long'           => 'up-long',
			'fa-solid fa-rocket'            => 'rocket',
			// FA 4 legacy.
			'fa fa-angle-up'                => 'angle-up',
			'fa fa-angle-double-up'         => 'angles-up',
			'fa fa-arrow-up'                => 'arrow-up',
			'fa fa-arrow-circle-up'         => 'circle-arrow-up',
			'fa fa-chevron-up'              => 'chevron-up',
			'fa fa-caret-up'                => 'caret-up',
		);

		return isset( $map[ $fa_class ] ) ? $map[ $fa_class ] : 'angle-up';
	}
}
