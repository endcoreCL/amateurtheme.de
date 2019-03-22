<?php
if ( ! function_exists( 'xcore_shortcode_toast' ) ) {
	add_shortcode( 'toast', 'xcore_shortcode_toast' );
	/**
	 * Toast shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/toasts/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_toast( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => '',
					'img'           => '',
					'icon'          => '',
					'headline'      => '',
					'hint'          => 'false',
					'close'         => 'false',
					'position'      => '',
					'autohide'      => 'true',
					'fade'          => 'false',
					'text-align'    => '',
				),
				$atts
			)
		);

		// vars
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : '' );
		$img            = ( $atts['img'] ? $atts['img'] : '' );
		$icon           = ( $atts['icon'] ? $atts['icon'] : '' );
		$headline       = ( $atts['headline'] ? $atts['headline'] : '' );
		$hint           = ( $atts['hint'] ? $atts['hint'] : '' );
		$close          = ( $atts['close'] ? $atts['close'] : 'false' );
		$position       = ( $atts['position'] ? $atts['position'] : '' );
		$autohide       = ( $atts['autohide'] ? $atts['autohide'] : 'true' );
		$fade           = ( $atts['fade'] ? $atts['fade'] : 'false' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'xcore-toast', 'toast', 'show' ),
			'role' => 'alert',
			'aria-live' => 'assertive',
			'aria-atomic' => 'true',
			'data-autohide' => $autohide,
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'bg-' . $style;
		}

		if ( $position ) {
			$attributes['style'][] = 'position: absolute;';
			$attributes['style'][] = $position;
		}

		if ( $fade == 'true' ) {
			$attributes['class'][] = 'fade';
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= '<div class="toast-header">';
				if ( $img ) {
					$output .= '<img src="' . $img . '" class="rounded mr-2" />';
				}

				if ( $icon ) {
					$output .= '<i class="' . $icon  . ' mr-2"></i>';
				}

				if ( $headline ) {
					$output .= '<strong class="mr-auto">' . $headline . '</strong>';
				}

				if ( $hint ) {
					$output .= '<small>' . $hint . '</small>';
				}

				if ( $close == 'true' ) {
					$output .= '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
				}
			$output .= '</div>';

			$output .= '<div class="toast-body">' . do_shortcode( $content ) . '</div>';
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_toasts' ) ) {
	add_shortcode( 'toasts', 'xcore_shortcode_toasts' );
	/**
	 * Toasts shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/toasts/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_toasts( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'position'      => ''
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$position       = ( $atts['position'] ? $atts['position'] : '' );

		$attributes = array(
			'class' => array( 'toasts' ),
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $position ) {
			$attributes['style'][] = 'position: absolute;';
			$attributes['style'][] = $position;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode ( $content );
		$output .= '</div>';

		return $output;
	}
}