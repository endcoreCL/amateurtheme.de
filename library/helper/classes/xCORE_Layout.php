<?php
class xCORE_Layout {
    var $template_path = '';

    function __construct() {
        $this->template_path = get_template_directory();
    }

    public function logo() {
        $logo = get_field('design_general_logo', 'options');

        if($logo) {
            return '<img src="' . $logo['url'] . '"' . ($logo['width'] ? ' width="' . $logo['width'] . '"' : '') . ($logo['height'] ? ' height="' . $logo['height'] . '"' : '') . ' alt="' . $logo['alt'] . '" title="' . $logo['title'] . '" class="img-fluid" />';
        }

        return get_bloginfo('name');
    }

    public function logo_pos() {
        $logo_pos = (get_field('design_header_logo_pos', 'options') ? get_field('design_header_logo_pos', 'options') : 'top');

        return $logo_pos;
    }

    public function navbar_wrapper_classes() {
        $classes = array('navbar');

        $navbar_color = (get_field('design_header_nav_bg', 'options') ? get_field('design_header_nav_bg', 'options') : 'dark');
        if($navbar_color) {
            switch($navbar_color) {
                case 'bright':
                    $classes[] = 'navbar-light';
                    $classes[] = 'bg-light';
                    break;

                case 'dark':
                    $classes[] = 'navbar-dark';
                    $classes[] = 'bg-dark';
                    break;
            }
        }

        $navbar_trigger = (get_field('design_header_trigger_mobile_nav', 'options') ? get_field('design_header_trigger_mobile_nav', 'options') : 'sm');
        if($navbar_trigger) {
            $classes[] = 'navbar-expand-' . $navbar_trigger;
        }

        return implode(' ', $classes);
    }

    public function navbar_classes() {
        $classes = array('navbar-nav');

        $navbar_align = get_field('design_header_nav_align', 'options');
        if($navbar_align) {
            switch($navbar_align) {
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

        return implode(' ', $classes);
    }

    public function get_sidebar( $section ) {
        $sidebar = get_field( 'blog_' . $section . '_sidebar', 'options' );

        if ( $sidebar ) {
            return $sidebar;
        }

        return false;
    }

    public function get_sidebar_classes( $section ) {
        $classes = array(
            'content' => 'col-md-12',
            'sidebar' => 'col-md-12'
        );

        $sidebar = $this->get_sidebar( $section );

        if ( $sidebar ) {
            $classes['content'] = 'col-md-8';
            $classes['sidebar'] = 'col-md-4';

            if ( $sidebar == 'left' ) {
                $classes['content'] = 'col-md-8 order-md-last';
                $classes['sidebar'] = 'col-md-4 order-md-first';
            }
        }

        return $classes;
    }

    public function get_posts_layout ( $section ) {
        $layout = get_field( 'blog_' . $section . '_posts_layout', 'options' );

        if ( $layout ) {
            return $layout;
        }

        return 'large';
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