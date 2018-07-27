<?php do_action('xcore_init'); global $xcore_layout; ?>
<!doctype html>
<html lang="en">
	<head>
		<title><?php wp_title(); ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<?php wp_head(); ?>
	</head>
	<body>
	    <?php
        do_action('xcore_after_body');
        ?>
		<div id="wrapper-fluid">
            <?php
            if(get_field('design_topbar', 'options')) {
	            get_template_part( 'parts/topbar/code', 'topbar' );
            }

            get_template_part('parts/header/code', 'header');

            if ( function_exists('yoast_breadcrumb') && ('top' == get_field('design_breadcrumbs_pos', 'option'))) { ?>
                <section id="breadcrumbs">
                    <div class="container">
                        <?php
                        $breadcrumbs = yoast_breadcrumb('<nav><ol class="breadcrumb"><li class="breadcrumb-item">','</li></ol></nav>', false);
                        echo str_replace( '»', '</li><li class="breadcrumb-item">', $breadcrumbs );
                        ?>
                    </div>
                </section>
			<?php
			}