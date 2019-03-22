<?php
if ( ! function_exists( 'xcore_shortcode_accordions' ) ) {
	add_shortcode( 'accordions', 'xcore_shortcode_accordions' );
	/**
	 * Accordions shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/collapse/#accordion-example
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_accordions( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => '',
				),
				$atts
			)
		);

		// vars
		$rand           = rand(0, 1337);
		$id             = ( $atts['id'] ? $atts['id'] : 'accordion' . $rand);
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : '' );

		$attributes = array(
			'class' => array( 'xcore-accordion', 'accordion' ),
			'id' => $id
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			/**
			 * @TODO Korrekte Klassen setzen
			 */
			$attributes['class'][] = 'accordion-' . $style;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode( $content );
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_accordion' ) ) {
	add_shortcode( 'accordion', 'xcore_shortcode_accordion' );
	/**
	 * Accordion shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/collapse/#accordion-example
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_accordion( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => '',
					'headline'      => '',
					'expanded'      => 'false',
					'parent'        => '',
					'text-align'    => ''
				),
				$atts
			)
		);

		// vars
		$rand           = rand(0, 1337);
		$id             = ( $atts['id'] ? $atts['id'] : 'collapse' . $rand );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : '' );
		$headline       = ( $atts['headline'] ? $atts['headline'] : '');
		$expanded       = ( $atts['expanded'] ? $atts['expanded'] : 'false' . $rand);
		$parent         = ( $atts['parent'] ? $atts['parent'] : '');
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'id' => $id,
			'class' => array( 'xcore-collapse', 'collapse' )
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			/**
			 * @TODO Korrekte Klassen setzen
			 */
			$attributes['class'][] = 'accordion-' . $style;
		}

		if ( $parent ) {
			$attributes['data-parent'][] = '#' . $parent;
		}

		if ( $expanded == 'true' ) {
			$attributes['class'][] = 'show';
		}

		if ( $headline ) {
			$attributes['class'][] = 'heading' . $rand;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$output = '<div class="card">';
			$output .= '<div class="card-header" id="heading' . $rand . '">';
				if ( $headline ) {
					$output .= '<h2 class="mb-0">';
						$output .= '<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' . $id . '" aria-expanded="' .$expanded . '">';
							$output .= $headline;
						$output .= '</button>';
					$output .= '</h2>';
				}
			$output .= '</div>';

			$output .= '<div ' . at_attribute_array_html( $attributes ) . '>';
				$output .= '<div class="card-body">';
					$output .= do_shortcode( $content );
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}