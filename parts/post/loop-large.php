<article <?php post_class('loop-large'); ?>>
    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php
                /**
                 * @TODO: Image size
                 */
                the_post_thumbnail( 'full', array( 'class' => 'img-fluid' ) );
                ?>
            </a>
        </div>
       <?php
    }
    ?>

    <div class="post-body">
        <?php get_template_part( 'parts/post/code', 'meta' ); ?>

        <h2>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php the_title(); ?>
            </a>
        </h2>

        <?php the_excerpt(); ?>

        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="btn btn-primary">
            <?php _e( 'weiterlesen', 'amateurtheme' ); ?>
        </a>
    </div>
</article>