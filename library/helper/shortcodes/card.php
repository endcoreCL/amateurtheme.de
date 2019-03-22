<?php
if ( ! function_exists( 'xcore_shortcode_cards' ) ) {
	add_shortcode( 'cards', 'xcore_shortcode_cards' );
	/**
	 * Cards shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/card/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_cards( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'type'          => 'card-deck',
					'column-count'  => ''
				),
				$atts
			)
		);

		// vars
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$type           = ( $atts['type'] ? $atts['type'] : 'card-deck' );
		$column_count   = ( $atts['column-count'] ? $atts['column-count'] : '' );

		$attributes = array(
			'class' => array( 'xcore-cards' ),
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if  ( $type ) {
			$attributes['class'][] = 'card-' . $type;
		}

		if ( $column_count ) {
			$column_count_classes = explode( ',', $column_count );

			if ( $column_count_classes ) {
				foreach ( $column_count_classes as $item ) {
					$attributes['class'][] = 'column-count-' . trim ( $item );
				}
			}
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode ( $content );
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_card' ) ) {
	add_shortcode( 'card', 'xcore_shortcode_card' );
	/**
	 * Card shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/card/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_card( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'style'         => '',
					'border'        => '',
					'class'         => '',
					'bg-color'      => '',
					'img'           => '',
					'img-position'  => 'top',
					'text-align'    => '',
					'text-color'    => ''
				),
				$atts
			)
		);

		// vars
		$style          = ( $atts['style'] ? $atts['style'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$border         = ( $atts['border'] ? $atts['border'] : '' );
		$bg_color       = ( $atts['bg-color'] ? $atts['bg-color'] : '' );
		$img            = ( $atts['img'] ? $atts['img'] : '' );
		$img_html       = '';
		$img_position   = ( $atts['img-position'] ? $atts['img-position'] : 'top' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );
		$text_color     = ( $atts['text-color'] ? $atts['text-color'] : '' );

		$attributes = array(
			'class' => array( 'card' ),
		);

		if ( $style ) {
			$attributes['class'][] = 'bg-' . $style;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if  ( $border ) {
			$attributes['class'][] = 'border-' . $border;
		}

		if ( $bg_color ) {
			$attributes['class'][] = 'bg-' . $bg_color;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		if ( $text_color ) {
			$attributes['class'][] = 'text-' . $text_color;
		}

		if ( $img ) {
			$classes = array();

			if ( $img_position ) {
				if ( $img_position == 'left' || $img_position == 'right' ) {
					$classes[] = 'card-img';
				}

				$classes[] = 'card-img-' . $img_position;
			}

			$img_html = '<img src="' . $img . '" class="' . implode( ' ', $classes ) . '">';
		}

		$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
			if ( $img_position == 'top' || $img_position == 'bottom' ) {
				if ( $img_html && $img_position == 'top' ) {
					$output .= $img_html;
				}

				$output .= do_shortcode( $content );

				if ( $img_html && $img_position == 'bottom' ) {
					$output .= $img_html;
				}
			} else {
				$output .= '<div class="row no-gutters">';
					if ( $img_position == 'left' ) {
						$output .= '<div class="col-md-4">' . $img_html . '</div>';
					}

					$output .= '<div class="col-sm-8">' . do_shortcode( $content ) . '</div>';

					if ( $img_position == 'right' ) {
						$output .= '<div class="col-md-4">' . $img_html . '</div>';
					}
				$output .= '</div>';
			}
		$output .= '</div>';

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_card_header' ) ) {
	add_shortcode( 'card_header', 'xcore_shortcode_card_header' );
	/**
	 * Card header shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/card/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_card_header( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'text-align'    => ''
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'card-header' ),
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

if ( ! function_exists( 'xcore_shortcode_card_body' ) ) {
	add_shortcode( 'card_body', 'xcore_shortcode_card_body' );
	/**
	 * Card body shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/card/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_card_body( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'overlay'       => 'false',
					'class'         => '',
					'text-align'    => ''
				),
				$atts
			)
		);

		// vars
		$overlay        = ( $atts['overlay'] ? $atts['overlay'] : 'false' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( '' ),
		);

		if ( $overlay == 'true' ) {
			$attributes['class'][] = 'card-img-overlay';
		} else {
			$attributes['class'][] = 'card-body';
		}

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

if ( ! function_exists( 'xcore_shortcode_card_footer' ) ) {
	add_shortcode( 'card_footer', 'xcore_shortcode_card_footer' );
	/**
	 * Card footer shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/card/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_card_footer( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'class'         => '',
					'text-align'    => ''
				),
				$atts
			)
		);

		// vars
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'card-footer' ),
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