<?php
if ( ! function_exists( 'xcore_shortcode_jumbotron' ) ) {
	add_shortcode( 'jumbotron', 'xcore_shortcode_jumbotron' );
	/**
	 * Jumbotron shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/jumbotron/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_jumbotron( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => '',
					'bg-color'      => '',
					'text-color'    => '',
					'text-align'    => '',
					'fluid'         => '',
				),
				$atts
			)
		);

		// vars
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : '' );
		$bg_color       = ( $atts['bg-color'] ? $atts['bg-color'] : '' );
		$text_color     = ( $atts['text-color'] ? $atts['text-color'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );
		$fluid          = ( $atts['fluid'] ? $atts['fluid'] : '' );

		$attributes = array(
			'class' => array( 'xcore-jumbotron', 'jumbotron' ),
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'jumbotron-' . $style;
		}

		if ( $bg_color ) {
			$attributes['class'][] = 'bg-' . $bg_color;
		}

		if ( $text_color ) {
			$attributes['class'][] = 'text-' . $text_color;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		if ( $fluid == 'true' ) {
			$attributes['class'][] = 'jumbotron-fluid';
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			if ( $fluid == 'true' ) {
				$output .= '<div class="container">';
			}

			$output .= do_shortcode( $content );

			if ( $fluid == 'true' ) {
				$output .= '</div>';
			}
		$output .= '</div>';

		return $output;
	}
}