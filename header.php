<?php do_action('at_init'); global $xcore_layout; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<title><?php wp_title(); ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	    <?php do_action('at_after_body'); ?>

		<div id="wrapper-fluid">
            <?php
            if( get_field( 'design_topbar', 'options' ) ) {
	            get_template_part( 'parts/topbar/code', 'topbar' );
            }

            get_template_part( 'parts/header/code', 'header' );

            if ( function_exists('yoast_breadcrumb') && ('top' == get_field('design_breadcrumbs_pos', 'option') ) ) {
                get_template_part( 'parts/stuff/code', 'breadcrumbs' );
			}