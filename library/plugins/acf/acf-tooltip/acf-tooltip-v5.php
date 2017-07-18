<?php

function my_enqueue() {
    wp_enqueue_script( 'acf_tooltip_script', get_template_directory_uri() . '/library/plugins/acf/acf-tooltip/js/acf-tooltip-v5.js', array('jquery-qtip') );
    wp_enqueue_style( 'acf_tooltip_css',get_template_directory_uri() . '/library/plugins/acf/acf-tooltip/css/acf-tooltip.css' );
    wp_enqueue_script( 'jquery-qtip', get_template_directory_uri() . '/library/plugins/acf/acf-tooltip/js/jquery.qtip.min.js' );
	wp_enqueue_style( 'jquery-qtip.js', get_template_directory_uri() . '/library/plugins/acf/acf-tooltip/css/jquery.qtip.min.css' );
}
add_action( 'acf/input/admin_head', 'my_enqueue' );

?>
