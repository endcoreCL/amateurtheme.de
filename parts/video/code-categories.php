<?php
$categories = get_field( 'video_single_category', 'options' );
$options = get_field( 'video_single_category_options', 'options' );

if ( $categories ) {
	?>
	<div class="video-categories">
		<?php
		if ( $options['headline'] ) {
			?>
			<h2><?php echo $options['headline']; ?></h2>
			<?php
		}

		echo get_the_term_list( get_the_ID(), 'video_category', '<span class="badge badge-dark">', '</span> <span class="badge badge-dark">', '</span>' );
		?>
	</div>
	<?php
}