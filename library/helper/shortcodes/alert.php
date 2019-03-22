<?php
if ( ! function_exists( 'xcore_shortcode_alert' ) ) {
	add_shortcode( 'alert', 'xcore_shortcode_alert' );
	/**
	 * Alert shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/alerts/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_alert( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => 'primary',
					'bg-color'      => '',
					'text-color'    => '',
					'text-align'    => '',
					'border'        => '',
					'border-color'  => '',
					'rounded'       => '',
					'fade'          => 'false',
					'close'         => 'false',
				),
				$atts
			)
		);

		// vars
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : 'primary' );
		$bg_color       = ( $atts['bg-color'] ? $atts['bg-color'] : '' );
		$text_color     = ( $atts['text-color'] ? $atts['text-color'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );
		$border         = ( $atts['border'] ? $atts['border'] : '' );
		$border_color   = ( $atts['border-color'] ? $atts['border-color'] : '' );
		$rounded        = ( $atts['rounded'] ? $atts['rounded'] : '' );
		$fade           = ( $atts['fade'] ? $atts['fade'] : 'false' );
		$close          = ( $atts['close'] ? $atts['close'] : 'false' );

		$attributes = array(
			'class' => array( 'xcore-alert', 'alert' ),
			'role'  => array( 'alert' )
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'alert-' . $style;
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

		if ( $border ) {
			$attributes['class'][] = 'border-' . ( $border == 'none' ? 0 : $border );
		}

		if ( $border_color ) {
			$attributes['class'][] = 'border-' . $border_color;
		}

		if ( $rounded ) {
			$attributes['class'][] = 'rounded-' . ( $rounded == 'none' ? 0 : $rounded );
		}

		if ( $fade == 'true' ) {
			$attributes['class'][] = 'fade';
		}

		if ( $close == 'true' ) {
			$attributes['class'][] = 'alert-dismissible show';
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode( $content );

			if ( $close == 'true' ) {
				$output .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			}
		$output .= '</div>';

		return $output;
	}
}