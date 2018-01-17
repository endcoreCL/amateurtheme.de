<?php global $post; $video = new AT_Video($post->ID); ?>
<div class="col-xxs12 col-xs-6 col-md-4 col-md-4">
    <article <?php post_class('video-grid'); ?>>
        <div class="video-thumb-container">
            <a href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
                <?php
                /**
                 * @TODO: Bildgröße anpassen
                 */
                the_post_thumbnail('video_grid', array('class' => 'img-responsive'));

                $duration = $video->duration();
                if($duration) {
                    ?>
                    <div class="video-duration"><?php echo $duration; ?></div>
                    <?php
                }
                ?>
            </a>
        </div>

        <div class="video-info">
            <a class="video-title" href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
                <?php echo $video->title(); ?>
            </a>
            <ul class="list-inline">
                <li class="list-inline-item video-views">
                    <i class="fa fa-eye"  aria-hidden="true"></i> <?php echo $video->views(); ?>
                </li>
                <li class="list-inline-item video-rating">
                    <i class="fa fa-star" aria-hidden="true"></i> <?php echo $video->rating(); ?>
                </li>
            </ul>

        </div>
    </article>
</div>