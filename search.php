<?php get_header(); ?>

<div id="main">
	<div class="container">
		<div id="content">
			<h1><?php printf( __( 'Deine Suche nach <span class="highlight">%s</span>', 'xcore' ), get_search_query()); ?></h1>

			<?php
			$post_type = (isset($_GET['post_type']) ? $_GET['post_type'] : 'video');

			if($post_type == 'video_actor' || $post_type == 'video_category') {
				$terms = get_terms($post_type, array( 'hide_empty' => true, 'name__like' => get_search_query() ));

				if($terms) {
					foreach($terms as $term) {
						include(locate_template('parts/video/loop-term-grid.php'));
					}
				}
			} else {
				$layout = '';

				switch($post_type) {
					case 'video':
						$layout = 'grid';
						break;

					case 'post':
						$layout = 'small';
						break;
				}

				if($layout) {
					if ( have_posts() ) :
                        if($post_type == 'video') {
					        echo '<div id="video-list">';
                        }

                            if ( $layout == 'grid' ) {
                                echo '<div class="card-columns">';
                            }

                                while ( have_posts() ) : the_post();
                                    get_template_part( 'parts/' . $post_type . '/loop', $layout );
                                endwhile;

                            if ( $layout == 'grid' ) {
                                echo '</div>';
                            }

                        if ( $post_type == 'video' ) {
							echo '</div>';
						}

						echo at_pagination();
					else :
						echo '<p>' . __('Deine Suche ergab keine Treffer.', 'amateurtheme') . '</p>';
					endif;
				} else {
					echo '<p>' . __('Ungültiger Beitragstyp.', 'amateurtheme') . '</p>';
				}
			}
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
