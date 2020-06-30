<article <?php post_class( 'loop-small' ); ?>>
    <div class="row">
        <?php
        if ( has_post_thumbnail() ) {
            $col_class = 'col-md-8';
            ?>
            <div class="col-md-4">
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
            </div>
            <?php
        } else {
            $col_class = 'col-12';
        }
        ?>

        <div class="<?php echo $col_class; ?>">
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
        </div>
    </div>
</article>