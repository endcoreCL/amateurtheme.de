<?php
/**
 * Template Name: Page Builder
 */
get_header(); ?>

<div id="main">
	<div class="container">
		<div id="page-builder">
			<?php
			if( have_rows('page_builder') ):
				ob_start();

			    $i = 0;
                while ( have_rows('page_builder') ) : the_row();

                    /**
                     * Feld: Textarea
                     */
                    if( get_row_layout() == 'page_builder_textarea' ) :
			            $items = get_sub_field( 'editor' );

	                    $attributes = array(
		                    'id' => array( get_sub_field( 'id' ) ),
		                    'class' => array( 'section', 'textarea', 'textarea-row-' . get_sub_field( 'rows' ), 'item-' . $i ),
		                    'style' => array(),
	                    );

	                    if( get_sub_field( 'class' ) ) {
		                    $attributes['class'][] = get_sub_field( 'class' );
	                    }

	                    if( get_sub_field( 'padding' ) == '1' ) {
		                    $attributes['class'][] = 'no-padding';
	                    }

	                    if( get_sub_field('id' ) ) {
		                    $attributes['class'][] = 'id-' . get_sub_field( 'id' );
	                    }

	                    if( get_sub_field( 'bgcolor' ) ) {
		                    $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
	                    }

                        if( $items ) {
	                        $count = count( $items );
	                        ?>
                            <div <?php echo at_attribute_array_html( $attributes ); ?>>
                                <div class="row">
                                    <?php
                                    foreach( $items as $item ) {
                                        echo at_pb_render_editor( $item, $count );
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    endif;

	                /**
	                 * Feld: Videos
	                 */
	                if( get_row_layout() == 'page_builder_videos' ) :
                        $text = get_sub_field( 'text' );
                        $type = get_sub_field( 'type' );

		                $attributes = array(
			                'id' => array( get_sub_field( 'id' ) ),
			                'class' => array( 'section', 'videos', 'item-' . $i ),
			                'style' => array(),
		                );

		                if( get_sub_field( 'class' ) ) {
			                $attributes['class'][] = get_sub_field( 'class' );
		                }

		                if( get_sub_field( 'padding' ) == '1' ) {
			                $attributes['class'][] = 'no-padding';
		                }

		                if( get_sub_field( 'id' ) ) {
			                $attributes['class'][] = 'id-' . get_sub_field( 'id' );
		                }

		                if( get_sub_field( 'bgcolor' ) ) {
			                $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
		                }

		                $args = array(
                            'post_type' => 'video',
                            'posts_per_page' => 9,
                            'tax_query' => array()
                        );

		                if( $type == 'latest' ) {
                            $count = get_sub_field( 'latest_count' );
                            $actor = get_sub_field( 'latest_actor' );
                            $category = get_sub_field( 'latest_category' );
                            $tags = get_sub_field( 'latest_tags' );

                            if( $count ) {
                                $args['posts_per_page'] = $count;
                            }

                            if( $actor ) {
                                $args['tax_query'][] = array(
                                    'taxonomy' => 'video_actor',
                                    'terms' => $actor,
                                    'field' => 'term_id'
                                );
                            }

			                if( $category ) {
				                $args['tax_query'][] = array(
					                'taxonomy' => 'video_category',
					                'terms' => $category,
					                'field' => 'term_id'
				                );
			                }

			                if( $tags ) {
				                $args['tax_query'][] = array(
					                'taxonomy' => 'video_tags',
					                'terms' => $tags,
					                'field' => 'term_id'
				                );
			                }
                        } else if( $type == 'post__in' ) {
		                    $ids = get_sub_field( 'post__in_video' );

		                    if( $ids ) {
		                        $args['post__in'] = $ids;
		                        $args['posts_per_page'] = count( $ids );
                            }
                        }

                        $videos = new WP_Query( $args );

		                if( $videos->have_posts() ) {
			                ?>
                            <div <?php echo at_attribute_array_html( $attributes ); ?>>
                                <?php
                                if( $text ) {
                                    echo $text;
                                }
                                ?>
                                <div id="video-list">
                                    <div class="card-deck">
                                        <?php
                                        while( $videos->have_posts() ) {
	                                        $videos->the_post();

                                            get_template_part( 'parts/video/loop', 'card' );
                                        }

                                        wp_reset_query();
                                        ?>
                                    </div>
                                </div>
                            </div>
			                <?php
		                }
	                endif;

                    $i++;
			    endwhile;

				$output = ob_get_contents();
				ob_end_clean();

				remove_filter( 'the_content', 'wpautop' );
				echo apply_filters(' the_content', $output );
				add_filter( 'the_content', 'wpautop' );
            endif;
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>