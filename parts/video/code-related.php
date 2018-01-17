<?php
$video_actor = wp_get_post_terms(get_the_ID(), 'video_actor', array('fields' => 'ids'));
$video_category = wp_get_post_terms(get_the_ID(), 'video_category', array('fields' => 'ids'));

$args = array(
    'post_type' => 'video',
    'posts_per_page' => 12,
    'tax_query' => array(),
    'orderby' => 'rand'
);

if($video_actor) {
    $args['tax_query'][] = array(
        'taxonomy' => 'video_actor',
        'field'    => 'term_id',
        'terms' => $video_actor,
    );
}

if($video_category) {
    $args['tax_query'][] = array(
        'taxonomy' => 'video_category',
        'field'    => 'term_id',
        'terms' => $video_category,
    );
}

$related = new WP_Query($args);

if($related->have_posts()) {
    echo '<div class="video-related">';
        echo '<div class="row">';
            while($related->have_posts()) {
                $related->the_post();

                get_template_part('parts/video/loop', 'grid-small');
            }
        echo '</div>';
    echo '</div>';

    wp_reset_query();
}