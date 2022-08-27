<?php

global $post;
$video = new AT_Video($post->ID);
?>
<div class="card card-video card-video-grid">
    <?php
    $duration = $video->duration();
    if ($duration) {
        ?>
		<span class="badge badge-dark">
            <?= $duration; ?>
        </span>
        <?php
    }
    ?>

	<a href="<?= $video->permalink(); ?>" title="<?= $video->title(); ?>" class="card-link-img card-play-icon">
        <?php the_post_thumbnail('video_grid', array('class' => 'img-fluid card-img-top', 'alt' => get_the_title())); ?>
	</a>

	<div class="card-body">
		<h3 class="card-title">
			<a href="<?= $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
                <?= $video->title(); ?>
			</a>
		</h3>
	</div>

	<div class="card-footer">
        <?php get_template_part('parts/video/code', 'meta'); ?>
	</div>

</div>

