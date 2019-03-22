<?php
if ( ! function_exists( 'xcore_shortcode_collapse' ) ) {
	add_shortcode( 'collapse', 'xcore_shortcode_collapse' );
	/**
	 * Collapse shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/collapse/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_collapse( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => 'primary'
				),
				$atts
			)
		);

		// vars
		$rand           = rand(0, 1337);
		$id             = ( $atts['id'] ? $atts['id'] : 'collapse' . $rand );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : 'primary' );

		$attributes = array(
			'class' => array( 'xcore-collapse', 'collapse' ),
			'id' => $id
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			/**
			 * @TODO Korrekte Klassen setzen
			 */
			$attributes['class'][] = 'collapse-' . $style;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= '<div class="card card-body">' . do_shortcode ( $content ) . '</div>';
		$output .= '</div>';

		return $output;
	}
}