<article <?php post_class('card'); ?>>
    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
            <?php
            /**
             * @TODO: Image size
             */
            the_post_thumbnail( 'full', array( 'class' => 'card-img-top img-fluid' ) );
            ?>
        </a>
        <?php
    }
    ?>
    <div class="card-body">
        <h2 class="card-title">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>

        <p class="card-text"><?php echo get_the_excerpt(); ?></p>
    </div>

    <div class="card-footer">
        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn btn-primary">
            <?php _e('weiterlesen', 'amateurtheme'); ?>
        </a>
    </div>
</article>