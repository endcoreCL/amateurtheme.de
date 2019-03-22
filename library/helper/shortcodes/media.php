<?php
if ( ! function_exists( 'xcore_shortcode_media' ) ) {
	add_shortcode( 'media', 'xcore_shortcode_media' );
	/**
	 * Media shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/media-object/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_media( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => 'primary',
					'bg-color'      => '',
					'text-color'    => '',
					'text-align'    => '',
					'img'           => '',
					'align'         => '',
					'order'         => 'left'
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
		$img            = ( $atts['img'] ? $atts['img'] : '' );
		$align          = ( $atts['align'] ? $atts['align'] : '' );
		$order          = ( $atts['order'] ? $atts['order'] : 'left' );

		$attributes = array(
			'class' => array( 'xcore-media', 'media' ),
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			$attributes['class'][] = 'media-' . $style;
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

		if ( $img ) {
			$img_classes = array();

			if ( $order == 'left' ) {
				$img_classes[] = 'mr-3';
			} else {
				$img_classes[] = 'ml-3';
			}

			if ( $align ) {
				$img_classes[] = 'align-' . $align;
			}

			$img_html = '<img src="' . $img . '" class="' . implode ( ' ', $img_classes ) . '"/>';
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			if ( $order == 'left' ) {
				$output .= $img_html;
			}

			$output .= '<div class="media-body">';
				$output .= do_shortcode ( $content );
			$output .= '</div>';

			if ( $order == 'right' ) {
				$output .= $img_html;
			}
		$output .= '</div>';

		return $output;
	}
}