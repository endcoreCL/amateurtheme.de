<?php
if ( ! function_exists( 'xcore_shortcode_badge' ) ) {
	add_shortcode( 'badge', 'xcore_shortcode_badge' );
	/**
	 * Badge shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/badge/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_badge( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => 'primary',
					'rounded'       => 'false',
					'link'          => '',
					'target'        => ''
				),
				$atts
			)
		);

		// vars
		$type           = 'span';
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : 'primary' );
		$rounded        = ( $atts['rounded'] ? $atts['rounded'] : 'false' );
		$link           = ( $atts['link'] ? $atts['link'] : '' );
		$target         = ( $atts['target'] ? $atts['target'] : '' );

		$attributes = array(
			'class' => array( 'xcore-badge', 'badge' ),
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'badge-' . $style;
		}

		if ( $rounded == 'true' ) {
			$attributes['class'][] = 'badge-pill';
		}

		if ( $link ) {
			$type = 'a';
			$attributes['href'][] = $link;

			if ( $target ) {
				$attributes['target'][] = $target;
			}
		}

		$output = '<' . $type . ' ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode( $content );
		$output .= '</' . $type . '>';

		return $output;
	}
}