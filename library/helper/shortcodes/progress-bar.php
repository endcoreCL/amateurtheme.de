<?php
if ( ! function_exists( 'xcore_shortcode_progress_bars' ) ) {
	add_shortcode( 'progress', 'xcore_shortcode_progress_bars' );
	add_shortcode( 'progress-bars', 'xcore_shortcode_progress_bars' );
	/**
	 * Progress shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/progress/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_progress_bars( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
				),
				$atts
			)
		);

		// vars
		$class         = ( $atts['class'] ? $atts['class'] : '' );

		$attributes = array(
			'class' => array( 'xcore-progress', 'progress' ),
		);

		if ( $class ) {
			$attributes_inner['class'][] = $class;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode( $content );
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_progress_bar' ) ) {
	add_shortcode( 'progress-bar', 'xcore_shortcode_progress_bar' );
	/**
	 * Progress bar shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/progress/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_progress_bar( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'style'         => 'primary',
					'width'         => '',
					'height'        => '',
					'striped'       => 'false',
					'animated'      => 'false',
					'multiple'      => 'false'
				),
				$atts
			)
		);

		// vars
		$style         = ( $atts['style'] ? $atts['style'] : 'primary' );
		$class         = ( $atts['class'] ? $atts['class'] : '' );
		$width         = ( $atts['width'] ? $atts['width'] : '' );
		$height        = ( $atts['height'] ? $atts['height'] : '' );
		$striped       = ( $atts['striped'] ? $atts['striped'] : 'false' );
		$animated      = ( $atts['animated'] ? $atts['animated'] : 'false' );
		$multiple      = ( $atts['multiple'] ? $atts['multiple'] : 'false' );

		$attributes = array(
			'class' => array( 'xcore-progress', 'progress' ),
		);

		$attributes_inner = array(
			'class' => array( 'progress-bar' ),
			'aria-valuemin' => '0',
			'aria-valuemax' => '100',
			'role' => 'progress-bar'
		);

		if ( $style ) {
			$attributes_inner['class'][] = 'bg-' . $style;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $width ) {
			$attributes_inner['style'][] = 'width: ' . $width . '%;';
			$attributes_inner['aria-valuenow'][] = $width;
		}

		if ( $height ) {
			$attributes['style'][] = 'height: ' . $height . 'px;';
		}

		if ( $striped == 'true' ) {
			$attributes_inner['class'][] = 'progress-bar-striped';
		}

		if ( $animated == 'true' ) {
			$attributes_inner['class'][] = 'progress-bar-animated';
		}

		if ( $multiple == 'false') { $output = '<div ' . at_attribute_array_html( $attributes ) . '>'; }
			$output .= '<div ' . at_attribute_array_html( $attributes_inner ) . '>';
				$output .= do_shortcode( $content );
			$output .= '</div>';
		if ( $multiple == 'false') { $output .= '</div>'; }

		return $output;
	}
}