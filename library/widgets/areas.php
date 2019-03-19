<?php
/**
 * Register Sidebar Areas
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	widgets
 */

if ( ! function_exists( 'xcore_register_sidebar ' ) ) {
    add_action( 'widgets_init', 'xcore_register_sidebar' );
    function xcore_register_sidebar() {
        // Allgemeine Sidebar
        register_sidebar(
            array(
                'name'          => __( 'Allgemeine Sidebar', 'xcore-backend' ),
                'id'            => 'standard',
                'description'   => '',
                'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                'after_widget'  => '</aside>',
                'before_title'  => '<p class="h1">',
                'after_title'   => '</p>',
            )
        );

        // Footer Sidebars
        $design_footer_widgets = get_field( 'design_footer_widgets', 'option' );
        if ( $design_footer_widgets ) {
            $design_footer_widget_areas = get_field( 'design_footer_widget_areas', 'option' );
            for ( $i = 1; $i <= $design_footer_widget_areas; $i++ ) {
                register_sidebar(
                    array(
                        'name'          => 'Footer #' . $i,
                        'id'            => 'footer_' . $i,
                        'description'   => '',
                        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
                        'after_widget'  => '</aside>',
                        'before_title'  => '<p class="h1">',
                        'after_title'   => '</p>',
                    )
                );
            }
        }
    }
}