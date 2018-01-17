<?php
/*
 * Registrieren der Sidebars
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	widgets
 */

register_sidebar(array(
    'name' => __('Allgemeine Sidebar', 'amateurtheme'),
    'id' => 'standard',
    'description' => __('Allgemeine Sidebar.', 'amateurtheme'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<p class="h1">',
    'after_title' => '</p>',
));