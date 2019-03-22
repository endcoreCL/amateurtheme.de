<?php
if ( ! function_exists( 'xcore_shortcode_button' ) ) {
	add_shortcode( 'button', 'xcore_shortcode_button' );
	/**
	 * Button shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/buttons/
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_button( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'                => '',
					'class'             => '',
					'style'             => 'primary',
					'type'              => 'a',
					'outline'           => 'false',
					'size'              => '',
					'block'             => 'false',
					'link'              => '',
					'target'            => '',
					'toggle'            => '',
					'rounded'           => '',
					'stretched-link'    => 'false'
				),
				$atts
			)
		);

		// vars
		$id             = ( $atts['id'] ? $atts['id'] : '' );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : 'primary' );
		$type           = ( $atts['type'] ? $atts['type'] : 'a' );
		$outline        = ( $atts['outline'] ? $atts['outline'] : 'false' );
		$size           = ( $atts['size'] ? $atts['size'] : '' );
		$block          = ( $atts['block'] ? $atts['block'] : 'false' );
		$link           = ( $atts['link'] ? $atts['link'] : '' );
		$target         = ( $atts['target'] ? $atts['target'] : '' );
		$toggle         = ( $atts['toggle'] ? $atts['toggle'] : '' );
		$rounded        = ( $atts['rounded'] || $atts['rounded'] == 0 ? $atts['rounded'] : '' );
		$stretched_link = ( $atts['stretched-link'] ? $atts['stretched-link'] : 'false');

		$attributes = array(
			'class' => array( 'xcore-button', 'btn' ),
		);

		if ( $id ) {
			$attributes['id'][] = $id;
		}

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $outline == 'true' ) {
			if ( $style ) {
				$attributes['class'][] = 'btn-outline-' . $style;
			}
		} else {
			if ( $style ) {
				$attributes['class'][] = 'btn-' . $style;
			}
		}

		if ( $size ) {
			$attributes['class'][] = 'btn-' . $size;
		}

		if ( $block == 'true' ) {
			$attributes['class'][] = 'btn-block';
		}

		if ( $link ) {
			$attributes['href'][] = $link;
		}

		if ( $target && ! $toggle  ) {
			$attributes['target'][] = $target;
		}

		if ( $toggle ) {
			$attributes['role'][] = 'button';
			$attributes['data-toggle'][] = $toggle;

			if ( $toggle == 'collapse' ) {
				$attributes['aria-controls'][] = $toggle;
				$attributes['aria-expanded'][] = 'false';
			}

			if ( $type == 'button' ) {
				$attributes['data-target'] = '#' . $target;
			} else {
				$attributes['href'][] = '#' . $target;
			}
		} else {
			if ( $type == 'button' ) {
				$attributes['type'][] = 'button';
				$attributes['href']   = array();

				if ( $link ) {
					if ( $target == '_blank' ) {
						$attributes['onclick'][] = 'window.open(\'' . $link . '\');';
					} else {
						$attributes['onclick'][] = 'location.href=\'' . $link . '\';';
					}
				}
			}
		}

		if ( $rounded ) {
			$attributes['class'][] = 'rounded-' . ( $rounded == 'none' ? 0 : $rounded );
		}

		if ( $stretched_link == 'true' ) {
			$attributes['class'][] = 'stretched_link';
		}

		$output = '<' . $type . ' ' . at_attribute_array_html( $attributes ) . '>';
			$output .= do_shortcode ( $content );
		$output .= '</' . $type . '>';

		return $output;
	}
}