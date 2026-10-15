<?php
/**
 * SVG Sanitizer — strips dangerous elements and attributes from uploaded SVGs.
 *
 * @package AR_Back_To_Top
 */

defined( 'ABSPATH' ) || exit;

/**
 * Sanitize uploaded SVG files by removing dangerous elements and attributes.
 */
class AR_SVG_Sanitizer {

	/**
	 * Allowed SVG elements.
	 *
	 * @var array
	 */
	private static $allowed_elements = array(
		'svg',
		'g',
		'path',
		'circle',
		'ellipse',
		'rect',
		'line',
		'polyline',
		'polygon',
		'text',
		'tspan',
		'defs',
		'use',
		'symbol',
		'clippath',
		'lineargradient',
		'radialgradient',
		'stop',
		'mask',
		'pattern',
		'image',
		'title',
		'desc',
		'metadata',
	);

	/**
	 * Allowed SVG attributes.
	 *
	 * @var array
	 */
	private static $allowed_attributes = array(
		'id',
		'class',
		'style',
		'fill',
		'fill-opacity',
		'fill-rule',
		'stroke',
		'stroke-width',
		'stroke-opacity',
		'stroke-linecap',
		'stroke-linejoin',
		'stroke-dasharray',
		'stroke-dashoffset',
		'stroke-miterlimit',
		'opacity',
		'transform',
		'cx',
		'cy',
		'r',
		'rx',
		'ry',
		'x',
		'y',
		'x1',
		'y1',
		'x2',
		'y2',
		'width',
		'height',
		'd',
		'points',
		'viewbox',
		'xmlns',
		'xmlns:xlink',
		'version',
		'preserveaspectratio',
		'clip-path',
		'clip-rule',
		'mask',
		'filter',
		'color',
		'color-interpolation',
		'color-interpolation-filters',
		'font-family',
		'font-size',
		'font-style',
		'font-weight',
		'text-anchor',
		'text-decoration',
		'dominant-baseline',
		'alignment-baseline',
		'letter-spacing',
		'word-spacing',
		'direction',
		'unicode-bidi',
		'dx',
		'dy',
		'offset',
		'stop-color',
		'stop-opacity',
		'gradientunits',
		'gradienttransform',
		'spreadmethod',
		'patternunits',
		'patterntransform',
		'xlink:href',
		'href',
		'marker-start',
		'marker-mid',
		'marker-end',
		'xml:space',
		'aria-hidden',
		'aria-label',
		'role',
		'focusable',
		'tabindex',
		'data-name',
	);

	/**
	 * Sanitize an SVG file.
	 *
	 * @param string $file_path Full path to the SVG file.
	 * @return bool True if sanitized successfully, false on failure.
	 */
	public static function sanitize_file( $file_path ) {
		global $wp_filesystem;

		if ( ! function_exists( 'WP_Filesystem' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		WP_Filesystem();

		if ( ! $wp_filesystem ) {
			return false;
		}

		if ( ! $wp_filesystem->exists( $file_path ) ) {
			return false;
		}

		$svg_content = $wp_filesystem->get_contents( $file_path );

		if ( false === $svg_content || empty( $svg_content ) ) {
			return false;
		}

		$clean_svg = self::sanitize( $svg_content );

		if ( false === $clean_svg ) {
			return false;
		}

		$wp_filesystem->put_contents( $file_path, $clean_svg, FS_CHMOD_FILE );

		return true;
	}

	/**
	 * Sanitize SVG content string.
	 *
	 * @param string $svg_content Raw SVG content.
	 * @return string|false Sanitized SVG or false on failure.
	 */
	public static function sanitize( $svg_content ) {
		$svg_content = preg_replace( '/<\?xml.*?\?>/si', '', $svg_content );
		$svg_content = preg_replace( '/<!DOCTYPE[^>]*>/si', '', $svg_content );
		$svg_content = preg_replace( '/<!\[CDATA\[.*?\]\]>/s', '', $svg_content );
		$svg_content = preg_replace( '/<!--.*?-->/s', '', $svg_content );

		// libxml_disable_entity_loader() exists on PHP 8.x but is deprecated there;
		// external entity loading is disabled by default on libxml >= 2.9, so only
		// call it on PHP 7.x to avoid E_DEPRECATED noise under WP_DEBUG.
		$prev_value = null;
		if ( PHP_VERSION_ID < 80000 && function_exists( 'libxml_disable_entity_loader' ) ) {
			$prev_value = libxml_disable_entity_loader( true );
		}
		libxml_use_internal_errors( true );

		$dom = new DOMDocument();
		$dom->formatOutput        = false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.PropertyNameNotSnakeCase
		$dom->preserveWhiteSpace  = true; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.PropertyNameNotSnakeCase
		$dom->strictErrorChecking = false; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.PropertyNameNotSnakeCase

		$loaded = $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_NOENT );

		if ( PHP_VERSION_ID < 80000 ) {
			libxml_disable_entity_loader( $prev_value ); // phpcs:ignore Generic.PHP.DeprecatedFunctions.Deprecated -- PHP 7.x only, guarded by PHP_VERSION_ID.
		}
		libxml_clear_errors();

		if ( ! $loaded ) {
			return false;
		}

		$root = $dom->documentElement;
		if ( ! $root || 'svg' !== strtolower( $root->nodeName ) ) {
			return false;
		}

		self::sanitize_node( $root );

		$clean = $dom->saveXML( $root );

		if ( false === $clean || empty( $clean ) ) {
			return false;
		}

		return $clean;
	}

	/**
	 * Recursively sanitize a DOM node.
	 *
	 * @param DOMNode $node The node to sanitize.
	 */
	private static function sanitize_node( $node ) {
		$nodes_to_remove = array();

		if ( $node->hasChildNodes() ) {
			foreach ( $node->childNodes as $child ) {
				if ( XML_ELEMENT_NODE === $child->nodeType ) {
					$tag_name = strtolower( $child->nodeName );

					if ( ! in_array( $tag_name, self::$allowed_elements, true ) ) {
						$nodes_to_remove[] = $child;
						continue;
					}

					self::sanitize_attributes( $child );
					self::sanitize_node( $child );
				}
			}
		}

		foreach ( $nodes_to_remove as $remove ) {
			$node->removeChild( $remove );
		}
	}

	/**
	 * Remove disallowed attributes and dangerous values from a node.
	 *
	 * @param DOMElement $element The element to sanitize.
	 */
	private static function sanitize_attributes( $element ) {
		$attrs_to_remove = array();

		if ( ! $element->hasAttributes() ) {
			return;
		}

		foreach ( $element->attributes as $attr ) {
			$attr_name  = strtolower( $attr->nodeName );
			$attr_value = $attr->nodeValue;

			if ( 0 === strpos( $attr_name, 'on' ) ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}

			if ( ! in_array( $attr_name, self::$allowed_attributes, true ) ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}

			if ( self::has_dangerous_value( $attr_value ) ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}
		}

		foreach ( $attrs_to_remove as $attr_name ) {
			$element->removeAttribute( $attr_name );
		}
	}

	/**
	 * Check if an attribute value contains dangerous content.
	 *
	 * @param string $value The attribute value to check.
	 * @return bool True if dangerous.
	 */
	private static function has_dangerous_value( $value ) {
		$value      = strtolower( trim( $value ) );
		$compressed = preg_replace( '/\s+/', '', $value );

		$dangerous_patterns = array(
			'javascript:',
			'data:text/html',
			'vbscript:',
			'expression(',
			'url(javascript',
			'url(data:text/html',
		);

		foreach ( $dangerous_patterns as $pattern ) {
			if ( false !== strpos( $compressed, $pattern ) ) {
				return true;
			}
		}

		return false;
	}
}
