<div class="col-xxs12 col-xs-6 col-md-6 col-md-4 col-lg-3">
    <article <?php post_class('video-grid'); ?>>
        <a href="<?php the_permalink(); ?>">
            <?php
            /**
             * @TODO: Bildgröße anpassen
             */
            the_post_thumbnail('full', array('class' => 'img-responsive'));
            ?>
        </a>
    </article>
</div>