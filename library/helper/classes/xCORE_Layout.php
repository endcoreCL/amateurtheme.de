<?php
class xCORE_Layout {
    var $template_path = '';

    function __construct() {
        $this->template_path = get_template_directory();
    }

    public function logo() {
        $logo = get_field( 'design_general_logo', 'options' );

        if( $logo ) {
            return '<img src="' . $logo['url'] . '"' . ( $logo['width'] ? ' width="' . $logo['width'] . '"' : '' ) . ( $logo['height'] ? ' height="' . $logo['height'] . '"' : '' ) . ' alt="' . $logo['alt'] . '" title="' . $logo['title'] . '" class="img-fluid" />';
        }

        return get_bloginfo( 'name' );
    }

    public function logo_pos() {
        $logo_pos = ( get_field('design_header_logo_pos', 'options') ? get_field( 'design_header_logo_pos', 'options' ) : 'top' );

        return apply_filters( 'at_logo_pos', $logo_pos );
    }

    public function navbar_wrapper_classes() {
        $classes = array( 'navbar' );

        $navbar_color = ( get_field( 'design_header_nav_bg', 'options' ) ? get_field( 'design_header_nav_bg', 'options' ) : 'dark' );
        if( $navbar_color ) {
	        $attributes['class'][] = at_design_bg_classes( 'navbar', $navbar_color );
        }

        $navbar_trigger = ( get_field( 'design_header_trigger_mobile_nav', 'options' ) ? get_field( 'design_header_trigger_mobile_nav', 'options' ) : 'sm' );
        if( $navbar_trigger ) {
            $classes[] = 'navbar-expand-' . $navbar_trigger;
        }

        return apply_filters( 'at_navbar_wrapper_classes', implode( ' ', $classes ) );
    }

    public function navbar_classes() {
        $classes = array( 'navbar-nav' );

        $navbar_align = get_field( 'design_header_nav_align', 'options' );
        if( $navbar_align ) {
            switch( $navbar_align ) {
                case 'left':
                    $classes[] = 'mr-auto';
                    break;

                case 'center':
                    $classes[] = 'mx-auto';
                    break;

                case 'right':
                    $classes[] = 'ml-auto';
                    break;
            }
        }

        return apply_filters( 'at_navbar_classes', implode( ' ', $classes ) );
    }

    public function get_sidebar( $section ) {
        // catch post
        if ( strpos( $section, 'post' ) !== false ) $section = str_replace( 'post', 'blog', $section );

        $sidebar = ( get_field( $section . '_sidebar', 'options' ) ? get_field( $section . '_sidebar', 'options' ) : false );

        return apply_filters( 'at_get_sidebar', $sidebar );
    }

    public function get_sidebar_classes( $section ) {
    	// default sizes
    	$size_content = 8;
    	$size_sidebar = 4;

    	// get sizes from options
    	$sizes = get_field( $section . '_sidebar_size', 'options' );
    	if ( $sizes ) {
    		$sizes_arr = explode( '_', $sizes );

    		if ( $sizes_arr ) {
			    $size_content = $sizes_arr[0];
			    $size_sidebar = $sizes_arr[1];
		    }
    	}

        $classes = array(
            'content' => 'col-md-12',
            'sidebar' => 'col-md-12'
        );

        $sidebar = $this->get_sidebar( $section );

        if ( $sidebar ) {
            $classes['content'] = 'col-md-' . $size_content;
            $classes['sidebar'] = 'col-md-' . $size_sidebar;

            if ( $sidebar == 'left' ) {
                $classes['content'] = 'col-md-' . $size_content . ' order-md-last';
                $classes['sidebar'] = 'col-md-' . $size_sidebar . ' order-md-first';
            }
        }

        return apply_filters( 'at_get_sidebar_classes', $classes );
    }

    public function get_posts_layout ( $section ) {
        $layout = ( get_field( 'blog_' . $section . '_posts_layout', 'options' ) ? get_field( 'blog_' . $section . '_posts_layout', 'options' ) : 'large' );

        return apply_filters( 'at_get_posts_layout', $layout );
    }

    public function get_posts( $section ) {
        $layout = $this->get_posts_layout ( $section );

        if ( have_posts() ) :
            if ( strpos ( $layout, 'card' ) !== false ) {
                echo '<div class="' . $layout . '">';

                $layout = 'card'; // overwrite for post layout
            }

            while ( have_posts() ) :
                the_post();

                get_template_part('parts/post/loop', $layout);
            endwhile;

            if ( strpos ( $layout, 'card' ) !== false ) {
                echo '</div>';
            }

            echo at_pagination();
        endif;
    }
}