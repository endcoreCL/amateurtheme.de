<?php
$tags    = get_field( 'video_single_tags', 'options' );
$options = get_field( 'video_single_tags_options', 'options' );

if ( $tags ) {
    ?>
    <hr class="hr-transparent">

    <div class="video-tags">
        <?php
        if ( $options['headline'] ) {
            ?>
            <h2><?php echo $options['headline']; ?></h2>
            <?php
        }

        echo get_the_term_list( get_the_ID(), 'video_tags', '<span class="badge badge-dark">', '</span> <span class="badge badge-dark">', '</span>' );
        ?>
    </div>
    <?php
}