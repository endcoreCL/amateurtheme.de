<?php global $post; $video = new AT_Video($post->ID); ?>

	<div class="card card-video card-video-grid">
		<?php
		$duration = $video->duration();
		if($duration) { ?>
			<span class="badge badge-dark">
				<?php echo $duration; ?>
			</span>
		<?php } ?>

		<a href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
			<?php the_post_thumbnail('video_grid', array('class' => 'img-fluid card-img-top')); ?>
		</a>

		<div class="card-body">
			<h3 class="card-title">
				<a href="<?php echo $video->permalink(); ?>" title="<?php echo $video->title(); ?>">
					<?php echo $video->title(); ?>
				</a>
			</h3>
			<ul class="list-inline list-meta">
				<li class="list-inline-item">
					<i class="fa fa-eye" aria-hidden="true"></i> <?php echo $video->views(); ?>
				</li>
				<li class="list-inline-item">
					<i class="fa fa-star" aria-hidden="true"></i> <?php echo $video->rating(); ?>
				</li>
			</ul>
		</div>
	</div>

