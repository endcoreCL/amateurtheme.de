<?php
if ( ! function_exists( 'xcore_shortcode_modal' ) ) {
	add_shortcode( 'modal', 'xcore_shortcode_modal' );
	/**
	 * Modal shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/modal/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_modal( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => 'primary',
					'align'         => '',
					'scrollable'    => 'false',
					'size'          => '',
					'fade'          => 'false',
					'text-align'    => '',
					'centered'      => 'false'
				),
				$atts
			)
		);

		// vars
		$rand           = rand ( 0, 1337 );
		$style          = ( $atts['style'] ? $atts['style'] : 'primary' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$id             = ( $atts['id'] ? $atts['id'] : 'Modal' . $rand );
		$align          = ( $atts['align'] ? $atts['align'] : '' );
		$scrollable     = ( $atts['scrollable'] ? $atts['scrollable'] : 'false' );
		$size           = ( $atts['size'] ? $atts['size'] : '' );
		$fade           = ( $atts['fade'] ? $atts['fade'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );
		$centered       = ( $atts['centered'] ? $atts['centered'] : 'false' );

		$attributes = array(
			'id' => $id,
			'class' => array( 'xcore-modal', 'modal' ),
			'role' => 'dialog',
			'aria-hidden' => 'true'
		);

		$attributes_inner = array(
			'class' => array( 'modal-dialog' ),
			'role' => array( 'document' )
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'media-' . $style;
		}

		if ( $fade == 'true' ) {
			$attributes['class'][] = 'fade';
		}

		if ( $id ) {
			$attributes['aria-labelledby'][] = $id;
		}

		if ( $align ) {
			$attributes_inner['class'][] = 'modal-dialog-' . $align;
		}

		if ( $scrollable == 'true' ) {
			$attributes_inner['class'][] = 'modal-dialog-scrollable';
		}

		if ( $size ) {
			$attributes_inner['class'][] = 'modal-' . $size;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		if ( $centered == 'true' ) {
			$attributes_inner['class'][] = 'modal-dialog-centered';
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= '<div ' . at_attribute_array_html( $attributes_inner ) . '>';
				$output .= '<div class="modal-content">';
					$output .= do_shortcode ( $content );
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_modal_header' ) ) {
	add_shortcode( 'modal_header', 'xcore_shortcode_modal_header' );
	/**
	 * Modal header shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/modal/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_modal_header( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'close'         => 'false',
					'text-align'    => '',
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$close          = ( $atts['close'] ? $atts['close'] : 'false' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'modal-header' ),
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= '<h5 class="modal-title">' . do_shortcode ( $content ) . '</h5>';

			if ( $close == 'true' ) {
				$output .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			}
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_modal_body' ) ) {
	add_shortcode( 'modal_body', 'xcore_shortcode_modal_body' );
	/**
	 * Modal body shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/modal/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_modal_body( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'text-align'    => '',
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'modal-body' ),
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode ( $content );
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_modal_footer' ) ) {
	add_shortcode( 'modal_footer', 'xcore_shortcode_modal_footer' );
	/**
	 * Modal footer shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/modal/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_modal_footer( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'text-align'    => '',
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'modal-footer' ),
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode ( $content );
		$output .= '</div>';

		return $output;
	}
}