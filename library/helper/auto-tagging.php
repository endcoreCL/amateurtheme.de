<?php
if ( ! function_exists( 'at_auto_tagging_set_tags_on_save_post' ) ) {
    /**
     * Set tags on save a video
     *
     * @param $post_id
     */
    add_action( 'save_post', 'at_auto_tagging_set_tags_on_save_post', 99 );
    function at_auto_tagging_set_tags_on_save_post ( $post_id )
    {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( get_post_type( $post_id ) != 'video' ) {
            return;
        }

        if ( ! get_field( 'autotags_activate', 'options' ) ) {
            return;
        }

        // set tags
        $title   = ( isset ( $_POST['post_title'] ) ? $_POST['post_title'] : '' );
        $content = ( isset ( $_POST['content'] ) ? $_POST['content'] : '' );

        if ( $content || $title ) {
            $text = $title . ' ' . $content;

            $tags = at_auto_tagging_match_tags( $text );

            if ( $tags ) {
                wp_set_object_terms( $post_id, $tags, 'video_tags', true );
            }
        }
    }
}

if ( ! function_exists( 'at_auto_tagging_set_tags_on_import' ) ) {
    /**
     * Set tags on import a video
     *
     * @param $post_id
     */
    add_action( 'wp_insert_post', 'at_auto_tagging_set_tags_on_import', 99, 2 );
    function at_auto_tagging_set_tags_on_import ( $post_id, $post )
    {
        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( get_post_type( $post_id ) != 'video' ) {
            return;
        }

        if ( ! get_field( 'autotags_activate', 'options' ) ) {
            return;
        }

        // set tags
        $title   = ( isset ( $post->post_title ) ? $post->post_title : '' );
        $content = ( isset ( $post->post_content ) ? $post->post_content : '' );

        if ( $content || $title ) {
            $text = $title . ' ' . $content;

            $tags = at_auto_tagging_match_tags( $text );

            if ( $tags ) {
                wp_set_object_terms( $post_id, $tags, 'video_tags', true );
            }
        }
    }
}

if ( ! function_exists( 'at_auto_tagging_match_tags' ) ) {
    /**
     * A function to generate the tags
     *
     * @param $string
     *
     * @return array
     */
    function at_auto_tagging_match_tags ( $string )
    {
        $tags         = array();
        $allowed_tags = str_replace( ', ', ',', get_field( 'autotags_items', 'options' ) );
        $allowed_tags = explode( ',', $allowed_tags );

        $string      = trim( $string ); // trim the string
        $string      = strip_tags( $string ); // remove html codes
        $string      = strip_shortcodes( $string ); // remove shortcodes
        $string      = preg_replace( '/[^a-zA-ZäöüÄÖÜß -]/', ' ', $string ); // only take alphabet characters, but keep the spaces and dashes too…
        $match_words = explode( ' ', $string );

        if ( $match_words ) {
            foreach ( $match_words as $word ) {
                if ( in_array( $word, $allowed_tags ) ) {
                    $tags[] = $word;
                }
            }
        }

        return $tags;
    }
}