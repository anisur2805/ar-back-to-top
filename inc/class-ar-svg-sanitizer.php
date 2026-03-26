<?php
/**
 * SVG Sanitizer — strips dangerous elements and attributes from uploaded SVGs.
 *
 * @package AR_Back_To_Top
 */

defined( 'ABSPATH' ) || exit;

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
		$svg_content = file_get_contents( $file_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( false === $svg_content || empty( $svg_content ) ) {
			return false;
		}

		$clean_svg = self::sanitize( $svg_content );

		if ( false === $clean_svg ) {
			return false;
		}

		file_put_contents( $file_path, $clean_svg ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents

		return true;
	}

	/**
	 * Sanitize SVG content string.
	 *
	 * @param string $svg_content Raw SVG content.
	 * @return string|false Sanitized SVG or false on failure.
	 */
	public static function sanitize( $svg_content ) {
		// Remove XML processing instructions.
		$svg_content = preg_replace( '/<\?xml.*?\?>/si', '', $svg_content );

		// Remove DOCTYPE declarations.
		$svg_content = preg_replace( '/<!DOCTYPE[^>]*>/si', '', $svg_content );

		// Remove CDATA sections.
		$svg_content = preg_replace( '/<!\[CDATA\[.*?\]\]>/s', '', $svg_content );

		// Remove comments.
		$svg_content = preg_replace( '/<!--.*?-->/s', '', $svg_content );

		// Disable external entity loading.
		$prev_value = libxml_disable_entity_loader( true ); // phpcs:ignore PHPCompatibility.FunctionUse.RemovedFunctions.libxml_disable_entity_loaderDeprecated
		libxml_use_internal_errors( true );

		$dom = new DOMDocument();
		$dom->formatOutput      = false;
		$dom->preserveWhiteSpace = true;
		$dom->strictErrorChecking = false;

		$loaded = $dom->loadXML( $svg_content, LIBXML_NONET | LIBXML_NOENT );

		// Restore previous state.
		if ( function_exists( 'libxml_disable_entity_loader' ) ) {
			libxml_disable_entity_loader( $prev_value ); // phpcs:ignore PHPCompatibility.FunctionUse.RemovedFunctions.libxml_disable_entity_loaderDeprecated
		}
		libxml_clear_errors();

		if ( ! $loaded ) {
			return false;
		}

		// Verify root element is <svg>.
		$root = $dom->documentElement;
		if ( ! $root || 'svg' !== strtolower( $root->nodeName ) ) {
			return false;
		}

		// Recursively sanitize all nodes.
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
		// Collect child nodes to remove (can't modify while iterating).
		$nodes_to_remove = array();

		if ( $node->hasChildNodes() ) {
			foreach ( $node->childNodes as $child ) {
				if ( XML_ELEMENT_NODE === $child->nodeType ) {
					$tag_name = strtolower( $child->nodeName );

					// Remove disallowed elements entirely.
					if ( ! in_array( $tag_name, self::$allowed_elements, true ) ) {
						$nodes_to_remove[] = $child;
						continue;
					}

					// Sanitize attributes of allowed elements.
					self::sanitize_attributes( $child );

					// Recurse into children.
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

			// Remove event handler attributes (onclick, onload, etc.).
			if ( 0 === strpos( $attr_name, 'on' ) ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}

			// Remove attributes not in the allowlist.
			if ( ! in_array( $attr_name, self::$allowed_attributes, true ) ) {
				$attrs_to_remove[] = $attr->nodeName;
				continue;
			}

			// Check attribute values for dangerous content.
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
		$value = strtolower( trim( $value ) );

		// Remove whitespace between characters to catch obfuscation.
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
