<?php
$show = get_field( 'video_single_meta', 'options' );

if ( $show ) {
	$video = new AT_Video($post->ID);
	$video_actor = get_the_term_list( get_the_ID(), 'video_actor', '', ', ', '' );
	?>
	<ul class="list-inline list-meta">
		<?php
		if ( $video_actor ) {
			?>
			<li class="list-inline-item video-actor">
				<i class="fa fa-user" aria-hidden="true"></i> <?php echo $video_actor; ?>
			</li>
			<?php
		}
		?>
		<li class="list-inline-item video-views">
			<i class="fa fa-eye" aria-hidden="true"></i> <?php echo $video->views(); ?>
		</li>
		<li class="list-inline-item video-rating">
			<i class="fa fa-star" aria-hidden="true"></i> <?php echo $video->rating(); ?>
		</li>
	</ul>
	<?php
}