<?php do_action('xcore_init'); ?>
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
        <?php do_action('xcore_after_body'); ?>

        <header>
            <div class="container">
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <a class="navbar-brand" href="#"><?php bloginfo('name'); ?></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                        <?php
                        wp_nav_menu(
                            array(
                                'menu' => 'main',
                                'theme_location' => 'navigation_main',
                                'container'       => 'div',
                                'container_id'    => 'bs4navbar',
                                'container_class' => 'collapse navbar-collapse',
                                'menu_id'         => false,
                                'menu_class'      => 'navbar-nav mr-auto',
                                'depth'           => 4,
                                'fallback_cb'     => 'xcore_nav_walker::fallback',
                                'walker'          => new xcore_nav_walker()
                            )
                        );
                        ?>
                </nav>
            </div>
        </header>

