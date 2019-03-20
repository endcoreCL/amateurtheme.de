<?php
$show = get_field( 'video_single_related', 'options' );
$options = get_field( 'video_single_related_options', 'options' );

if ( $show ) {
	$video_actor = wp_get_post_terms(get_the_ID(), 'video_actor', array('fields' => 'ids'));
	$video_category = wp_get_post_terms(get_the_ID(), 'video_category', array('fields' => 'ids'));

	$args = array(
	    'post_type' => 'video',
	    'posts_per_page' => ( $options['posts_per_page'] ? $options['posts_per_page'] : 12 ),
	    'tax_query' => array(),
	    'orderby' => 'rand'
	);

	if($video_actor) {
	    $args['tax_query'][] = array(
	        'taxonomy' => 'video_actor',
	        'field'    => 'term_id',
	        'terms' => $video_actor,
	    );
	}

	if($video_category) {
	    $args['tax_query'][] = array(
	        'taxonomy' => 'video_category',
	        'field'    => 'term_id',
	        'terms' => $video_category,
	    );
	}

	$related = new WP_Query($args);

	if($related->have_posts()) {
	    ?>
		<div id="video-list" class="video-related">
			<?php
			if ( $options['headline'] ) {
				?>
				<h2><?php echo $options['headline']; ?></h2>
				<?php
			}
			?>
	        <div class="card-deck">
		        <?php
	            while( $related->have_posts() ) {
	                $related->the_post();

	                get_template_part('parts/video/loop', 'card');
	            }
	            ?>
	        </div>
	    </div>
		<?php
	    wp_reset_query();
	}
}