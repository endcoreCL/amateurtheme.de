<?php
/**
 * schema org markups
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if( ! function_exists( 'xcore_markup_post_single' ) ) {
    /**
     * Single post markup
     */
    add_action( 'wp_footer', 'xcore_markup_post_single', 133337 );
    function xcore_markup_post_single() {
        if ( is_singular( 'post' ) ) {
            $logo           = get_field( 'design_general_logo', 'options' );
            $title          = get_the_title();
            $author         = esc_attr( get_the_author() );
            $date           = get_the_date( 'Y-m-d H:i:s' );
            $date_modified  = get_the_modified_date( 'Y-m-d H:i:s' );
            $image          = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );

            echo PHP_EOL . '<script type="application/ld+json">' . PHP_EOL;
            echo '{' . PHP_EOL;
                echo '"@context": "http://schema.org/",' . PHP_EOL;
                echo '"@type": "Article",' . PHP_EOL;
                echo '"name": "' . $title . '",' . PHP_EOL;
                echo '"headline": "' . $title . '",' . PHP_EOL;
                echo '"author": {"@type" : "Person", "name" : "' . $author . '"},' . PHP_EOL;
                echo '"publisher": {"@type" : "Organization", "name" : "' . get_bloginfo('name') . '"' . ( $logo ? ',"logo" : {"@type" : "ImageObject", "url" : "' . $logo["url"] . '", "width" : "' . $logo["width"] . 'px", "height" : "' . $logo["height"] . 'px"}' : '' ) . '},' . PHP_EOL;
                echo '"datePublished": "' . $date . '",' . PHP_EOL;
                echo '"dateModified": "' . $date_modified . '",' . PHP_EOL;
                echo '"mainEntityOfPage": "' . get_permalink() . '"' . PHP_EOL;
                echo ( $image ? ',"image": {"@type" : "ImageObject", "url" : "' . $image[0] . '", "width" : "' . $image[1] . 'px", "height" : "' . $image[2] . 'px"}' . PHP_EOL : '' );
            echo '}' . PHP_EOL;
            echo '</script>' . PHP_EOL;
        }
    }
}