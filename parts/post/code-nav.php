<?php
$prev_post = get_previous_post( true );
$next_post = get_next_post( true );
?>
<hr class="transparent">

<div class="post-nav">
    <nav>
        <ul class="pager list-inline">
            <?php
            if($prev_post) {
                ?>
                <li class="previous list-inline-item">
                    <a rel="prev" href="<?php echo get_permalink( $prev_post->ID ); ?>" title="<?php _e( 'zum Vorherigen Artikel', 'xcore' ); ?>">
                        <small><?php _e( 'Vorheriger Artikel', 'xcore' ); ?></small>
                        <?php echo get_the_title( $prev_post->ID ); ?>
                    </a>
                </li>
                <?php
            }

            if($next_post) {
                ?>
                <li class="next list-inline-item text-right">
                    <a rel="next" href="<?php echo get_permalink( $next_post->ID ); ?>" title="<?php _e( 'zum Nächsten Artikel', 'xcore' ); ?>">
                        <small><?php _e( 'Nächster Artikel', 'xcore' ); ?></small>
                        <?php echo get_the_title( $next_post->ID ); ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </nav>
</div>