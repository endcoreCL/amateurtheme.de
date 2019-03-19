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

if( ! function_exists( 'xcore_markup_video_single' ) ) {
    /**
     * Single post markup
     */
    add_action( 'wp_footer', 'xcore_markup_video_single', 133337 );
    function xcore_markup_video_single() {
        if ( is_singular( 'video' ) ) {
            $title              = get_the_title();
            $date               = get_the_date( 'Y-m-d H:i:s' );
            $image              = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
            $video_duration     = get_field( 'video_duration' );
            $video_duration_str = '';
            $video_views        = get_field( 'video_views' );
            $description        = get_the_content();

            if ( $video_duration ) {
                // format
                $video_duration_tmp = explode( ':',  $video_duration );

                if ( $video_duration_tmp ) {
                    $video_duration_str = 'PT' . $video_duration_tmp[1] . 'M' . $video_duration_tmp[2] . 'S';
                }
            }

            echo PHP_EOL . '<script type="application/ld+json">' . PHP_EOL;
            echo '{' . PHP_EOL;
            echo '"@context": "http://schema.org/",' . PHP_EOL;
            echo '"@type": "VideoObject",' . PHP_EOL;
            echo '"name": "' . $title . '",' . PHP_EOL;
            echo '"uploadDate": "' . $date . '",' . PHP_EOL;
            if ( $video_duration_str ) { echo '"duration": "' . $video_duration_str . '",' . PHP_EOL; }
            if ( $image ) { echo '"thumbnailURL": "' . $image[0] . '",' . PHP_EOL; }
            if ( $video_views ) { echo '"interactionCount": "' . $video_views . '",' . PHP_EOL; }
            if ( $description ) { echo '"description": "' . $description . '",' . PHP_EOL; }
            echo '"embedURL": "' . get_permalink() . '"' . PHP_EOL;
            echo '}' . PHP_EOL;
            echo '</script>' . PHP_EOL;
        }
    }
}