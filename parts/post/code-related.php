<?php
$options = get_field( 'blog_single_related_options', 'options' );
$layout  = $options['layout'];
$cats    = wp_get_post_categories( get_the_ID(), array( 'fields' => 'ids' ) );
$tags    = wp_get_post_tags( get_the_ID(), array( 'fields' => 'ids' ) );

$args = array(
    'posts_per_page' => $options['posts_per_page'],
    'orderby'        => $options['orderby'],
    'order'          => $options['order'],
    'post__not_in'   => array( get_the_ID() )
);

// filter
if ( $options['filter'] == 'category' ) {
    $args['category__in'] = $cats;
} elseif ( $options['filter'] == 'tag' ) {
    $args['tag__in'] = $tags;
} elseif ( $options['filter'] == 'both' ) {
    $args['category__in'] = $cats;
    $args['tag__in']      = $tags;
}

$related = new WP_Query( $args );

if ( $related->have_posts() ) {
    ?>
    <hr class="hr-transparent">

    <div class="post-related">
        <?php
        if ( $options['headline'] ) {
            ?>
            <h4>
                <?php echo $options['headline']; ?>
            </h4>
            <?php

            if ( strpos( $layout, 'card' ) !== false ) {
                echo '<div class="' . $layout . '">';

                $layout = 'card'; // overwrite for post layout
            }

            while ( $related->have_posts() ) :
                $related->the_post();

                get_template_part( 'parts/post/loop', $layout );
            endwhile;

            if ( strpos( $layout, 'card' ) !== false ) {
                echo '</div>';
            }
        }
        ?>
    </div>
    <?php

    wp_reset_query();
}