<?php $actor = new AT_Video_Actor($term->term_id); ?>
<div class="card card-video card-video-grid">
	<a href="<?php echo get_term_link($term); ?>" title="<?php echo $term->name; ?>">
		<?php
		$actor_image = $actor->image();
		if($actor_image) {
			echo '<img src="' . $actor_image['url'] . '" alt="' . $actor_image['alt'] . '" class="alignright img-fluid">';
		}
		?>
	</a>

	<div class="card-body">
		<h3 class="card-title">
			<a href="<?php echo get_term_link($term); ?>" title="<?php echo $term->name; ?>">
				<?php echo $term->name; ?>
			</a>
		</h3>
	</div>
</div>

