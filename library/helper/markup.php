<?php
/**
 * schema org markups
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'xcore_markup_video_single' ) ) {
    /**
     * Single post markup
     */
    add_action( 'wp_footer', 'xcore_markup_video_single', 133337 );
    function xcore_markup_video_single ()
    {
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
                $video_duration_tmp = explode( ':', $video_duration );

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
            if ( $video_duration_str ) {
                echo '"duration": "' . $video_duration_str . '",' . PHP_EOL;
            }
            if ( $image ) {
                echo '"thumbnailURL": "' . $image[0] . '",' . PHP_EOL;
            }
            if ( $video_views ) {
                echo '"interactionCount": "' . $video_views . '",' . PHP_EOL;
            }
            if ( $description ) {
                echo '"description": "' . $description . '",' . PHP_EOL;
            }
            echo '"embedURL": "' . get_permalink() . '"' . PHP_EOL;
            echo '}' . PHP_EOL;
            echo '</script>' . PHP_EOL;
        }
    }
}