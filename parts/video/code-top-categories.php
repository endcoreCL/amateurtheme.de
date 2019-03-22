<?php
$categories = get_field( 'video_single_top_categories', 'options' );
$options = get_field( 'video_single_top_categories_options', 'options' );

if ( $categories ) {
	?>
	<hr class="hr-transparent">

	<div class="video-top-categories">
		<?php
		if ( $options['headline'] ) {
			?>
			<h2><?php echo $options['headline']; ?></h2>
			<?php
		}

		$args = array(
			'taxonomy' => 'video_category',
		);

		if ( $options['categories'] ) {
			$args['include'] = $options['categories'];
			$args['orderby'] = 'include';
		} else {
			$args['number'] = ( $options['number'] ? $options['number'] : 8 );
			$args['orderby'] = 'count';
			$args['order'] = 'DESC';
		}

		$terms = get_terms( $args );

		if ( $terms ) {
			?>
			<div class="card-deck">
				<?php
				foreach ( $terms as $term ) {
					include( locate_template( 'parts/video/term-category.php' ) );
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}