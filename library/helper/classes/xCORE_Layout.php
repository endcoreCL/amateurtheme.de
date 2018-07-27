<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 09.02.18
 * Time: 14:25
 */
class xCORE_Layout {
    var $template_path = '';

    function __construct() {
        $this->template_path = get_template_directory();
    }

    public function logo() {
        $logo = get_field('design_general_logo', 'options');

        if($logo) {
            return '<img src="' . $logo['url'] . '"' . ($logo['width'] ? ' width="' . $logo['width'] . '"' : '') . ($logo['height'] ? ' height="' . $logo['height'] . '"' : '') . ' alt="' . $logo['alt'] . '" title="' . $logo['title'] . '" class="img-responsive at-logo" />';
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
}