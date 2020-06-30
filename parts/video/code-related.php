<?php
$show    = get_field( 'video_single_related', 'options' );
$options = get_field( 'video_single_related_options', 'options' );

if ( $show ) {
    $related = at_video_related_videos( get_the_ID(), 1 );

    if ( is_object( $related ) && $related->have_posts() ) {
        ?>
        <hr class="hr-transparent">

        <div class="video-related"<?php echo( $options['pagination'] ? ' data-pagination="true"' : '' ); ?>>
            <div id="video-list">
                <?php
                if ( $options['headline'] ) {
                    ?>
                    <h2><?php echo $options['headline']; ?></h2>
                    <?php
                }
                ?>

                <div class="inner">
                    <div class="card-deck">
                        <?php
                        while ( $related->have_posts() ) {
                            $related->the_post();

                            get_template_part( 'parts/video/loop', 'card' );
                        }
                        ?>
                    </div>

                    <hr class="hr-transparent">

                    <?php
                    if ( $options['pagination'] ) {
                        $max_pages = ( $related->max_num_pages > 8 ? 8 : $related->max_num_pages );

                        echo at_pagination( $max_pages, 8, 1 );
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        wp_reset_query();
    }
}