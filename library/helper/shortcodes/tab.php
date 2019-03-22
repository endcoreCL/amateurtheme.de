<?php
if ( ! function_exists( 'xcore_shortcode_tabs' ) ) {
	add_shortcode( 'tabs', 'xcore_shortcode_tabs' );
	/**
	 * Tabs shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/navs/#javascript-behavior
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_tabs( $atts, $content_inner = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'style'         => '',
					'position'      => 'top',
					'type'          => 'tabs',
					'space'         => '',
					'align'         => '',
					'text-align'    => ''
				),
				$atts
			)
		);

		// vars
		$rand           = rand(0, 1337);
		$id             = ( $atts['id'] ? $atts['id'] : 'accordion' . $rand );
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$style          = ( $atts['style'] ? $atts['style'] : '' );
		$position       = ( $atts['position'] ? $atts['position'] : 'top' );
		$type           = ( $atts['type'] ? $atts['type'] : 'tabs' );
		$space          = ( $atts['space'] ? $atts['space'] : '' );
		$align          = ( $atts['align'] ? $atts['align'] : '' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'xcore-tabs' ),
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $style ) {
			/**
			 * @TODO Korrekte Klassen setzen
			 */
			$attributes['class'][] = 'tabs-' . $style;
		}

		if ( $text_align ) {
			$attributes['class'][] = 'text-' . $text_align;
		}

		$tabs = at_shortcode_get_all_attributes ('tab', $content_inner );

		if ( $tabs ) {
			// create navigation
			$attributes_nav = array(
				'class' => array( 'nav' ),
				'id' => $id,
				'role' => 'tablist'
			);

			if ( $position == 'left' || $position == 'right' ) {
				$attributes_nav['class'][] = 'flex-column';
			}

			if ( $type ) {
				if ( $type == 'card-tabs' ) {
					$attributes_nav['class'][] = 'card-header-tabs';
					$attributes_nav['class'][] = 'nav-tabs';
				} else if ( $type == 'card-pills' ) {
					$attributes_nav['class'][] = 'card-header-pills';
					$attributes_nav['class'][] = 'nav-pills';
				} else {
					$attributes_nav['class'][] = 'nav-' . $type;
				}
			}

			if ( $space ) {
				$attributes_nav['class'][] = 'nav-' . $space;
			}

			if ( $align ) {
				$attributes_nav['class'][] = $align;
			}

			$nav = '<ul ' . at_attribute_array_html( $attributes_nav ) . '>';
				foreach ( $tabs as $tab ) {
					$nav .= '<li class="nav-item">';
						if ( $tab['link'] ) {
							$nav .= '<a class="nav-link" href="' . $tab['link'] . '" target="' . ( $tab['target'] ? $tab['target'] : '_self' ) . '">';
								$nav .= $tab['headline'];
							$nav .= '</a>';
						} else {
							$nav .= '<a class="nav-link' . ( $tab['expanded'] == 'true' ? ' active' : '' ) . '" id="' . $tab['id'] . '-tab" data-toggle="tab" href="#' . $tab['id'] . '" role="tab" aria-controls="' . $tab['id'] . '" aria-selected="' . ( $tab['expanded'] == 'true' ? 'true' : 'false' ) . '">';
								$nav .= $tab['headline'];
							$nav .= '</a>';
						}
					$nav .= '</li>';
				}
			$nav .= '</ul>';

			// create content
			$content = '<div class="tab-content">';
				$content .= do_shortcode( $content_inner );
			$content .= '</div>';

			// create output
			$output = '<div ' . at_attribute_array_html( $attributes ) . '>';
				if ( strpos( $type, 'card-' ) !== false ) {
					$output .= '<div class="card">';
					$nav = '<div class="card-header">' . $nav . '</div>';
					$content = '<div class="card-body">' . $content . '</div>';
				}

				if ( $position == 'top' || $position == 'bottom' ) {
					if ( $position == 'top' ) {
						$output .= $nav;
					}

					$output .= $content;

					if ( $position == 'bottom' ) {
						$output .= $nav;
					}
				} else if ( $position == 'left' || $position == 'right' ) {
					$output .= '<div class="row">';
						if ( $position == 'left' ) {
							$output .= '<div class="col-3">' . $nav . '</div>';
						}

						$output .= '<div class="col-9">' . $content . '</div>';

						if ( $position == 'right' ) {
							$output .= '<div class="col-3">' . $nav . '</div>';
						}
					$output .= '</div>';
				}
			$output .= '</div>';
		}

		return $output;
	}
}

if ( ! function_exists( 'xcore_shortcode_tab' ) ) {
	add_shortcode( 'tab', 'xcore_shortcode_tab' );
	/**
	 * Tab shortcode
	 *
	 * @doc https://getbootstrap.com/docs/4.3/components/navs/#javascript-behavior
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	function xcore_shortcode_tab( $atts, $content = null ) {
		extract(
			shortcode_atts(
				array(
					'id'            => '',
					'class'         => '',
					'headline'      => '',
					'expanded'      => 'false',
					'fade'          => 'false',
					'text-align'    => '',
				),
				$atts
			)
		);

		// vars
		$rand           = rand(0, 1337);
		$id             = ( $atts['id'] ? $atts['id'] : 'collapse' . $rand);
		$class          = ( $atts['class'] ? $atts['class'] : '' );
		$expanded       = ( $atts['expanded'] ? $atts['expanded'] : 'false');
		$fade           = ( $atts['fade'] ? $atts['fade'] : 'false' );
		$text_align     = ( $atts['text-align'] ? $atts['text-align'] : '' );

		$attributes = array(
			'class' => array( 'xcore-tab', 'tab-pane' ),
			'id' => $id,
			'role' => 'tabpanel',
			'aria-labelledby' => $id . '-tab'
		);

		if ( $class ) {
			$attributes['class'][] = $class;
		}

		if ( $expanded == 'true' ) {
			$attributes['class'][] = 'show active';
		}

		if ( $fade == 'true' ) {
			$attributes['class'][] = 'fade';
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