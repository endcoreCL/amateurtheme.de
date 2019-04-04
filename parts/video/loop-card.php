<?php
global $post;
$video = new AT_Video($post->ID);
?>
<div class="card card-video card-video-grid">
    <?php
    $duration = $video->duration();
    if($duration) { ?>
        <span class="badge badge-dark">
            <?php echo $duration; ?>
        </span>
    <?php } ?>

    <a href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>" class="card-link-img">
        <?php the_post_thumbnail('video_grid', array('class' => 'img-fluid card-img-top')); ?>
    </a>

    <div class="card-body">
        <h3 class="card-title">
            <a href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
                <?php echo $video->title(); ?>
            </a>
        </h3>
	</div>

	<div class="card-footer">
		<?php get_template_part( 'parts/video/code', 'meta' ); ?>
	</div>

</div>

