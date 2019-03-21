<div class="card term-<?php echo $term->term_id; ?>">
	<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
		<?php
		// @TODO: Placeholder
		$image_url = 'https://placehold.it/300x180/?text=' . $term->name;
		$image_alt = $term->name;

		$category_image = get_field( 'category_image', $term );
		if ( $category_image ) {
			$image_url = $category_image['url'];
			if ( $category_image['alt'] ) {
				$image_alt = $category_image['alt'];
			}
		}

		echo '<img src="' . $image_url . '" class="card-img-top img-fluid" alt="' . $image_alt . '" />';
		?>
	</a>

	<div class="card-body">
		<h5 class="card-title">
			<a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
				<?php echo $term->name; ?>
			</a>
		</h5>
		<p class="card-text mb-0"><?php printf( _n( '%s Video', '%s Videos', $term->count, 'amateurtheme' ), $term->count ); ?></p>
	</div>
</div>