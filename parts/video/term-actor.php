<div class="card card-term term-<?php echo $term->term_id; ?>">
	<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>" class="card-link-img">
		<?php
		// @TODO: Placeholder
		$image_url = get_template_directory_uri() . '/assets/img/placeholder-543x543.jpg';
		$image_alt = $term->name;

		$actor_image = get_field( 'actor_image', $term );
		if ( $actor_image ) {
			$image_url = $actor_image['sizes']['term_grid'];
			if ( $actor_image['alt'] ) {
				$image_alt = $actor_image['alt'];
			}
		}

		echo '<img src="' . $image_url . '" class="card-img-top img-fluid" alt="' . $image_alt . '" />';
		?>
	</a>

	<div class="card-body">
		<h3 class="card-title card-title-1">
			<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
				<?php echo $term->name; ?>
			</a>
		</h3>
	</div>

	<div class="card-footer">
		<p class="text-muted"><?php printf( _n( '%s Video', '%s Videos', $term->count, 'amateurtheme' ), $term->count ); ?></p>
	</div>

</div>