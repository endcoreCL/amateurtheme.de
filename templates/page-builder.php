<?php 
/*
 * Template Name: Page Builder
 */
get_header(); ?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div id="page-builder">
		<?php 
		if( have_rows('page_builder') ): 
			$i=0;

			while ( have_rows('page_builder') ) : the_row();
				$classes = '';
			
				/*
				 * Feld: Trennlinie
				 */
				if(get_row_layout() == 'page_builder_hr'): 
					echo '<hr>';
				endif;

				/*
				 * Feld: Anmeldeformular
				 */
				if(get_row_layout() == 'page_builder_signup_form'):
					echo '<div class="section signup_form">';
						echo '<div class="container">';
							echo do_shortcode('[signup_form /]');
						echo '</div>';
					echo '</div>';
				endif;

				/*
				 * Feld: Profile
				 */
				if(get_row_layout() == 'page_builder_profiles'):
					$args = array(
						'gender' => get_sub_field('gender'),
						'figure' => get_sub_field('figure'),
						'hair_color' => get_sub_field('hair_color'),
						'age_from' => get_sub_field('age_from'),
						'age_to' => get_sub_field('age_to'),
						'size_from' => get_sub_field('size_from'),
						'size_to' => get_sub_field('size_to'),
						'weight_from' => get_sub_field('weight_from'),
						'weight_to' => get_sub_field('weight_to'),
						'limit' => get_sub_field('limit'),
						'layout' => get_sub_field('layout'),
						'order' => get_sub_field('order'),
						'orderby' => get_sub_field('orderby'),
					);

					if($args) {
						echo '<div class="section profiles">';
							echo '<div class="container">';
								$headline = get_sub_field('headline');

								if($headline) {
									?><h2><?php echo $headline; ?></h2><?php
								}

								$output = '[profiles ';
								foreach($args as $k => $v) {
									if($v != '') {
										$output .= ' ' . $k . '="' . $v . '" ';
									}
								}
								$output .= ' /]';
								echo do_shortcode($output);
							echo '</div>';
						echo '</div>';
					}
				endif;
		
				/*
				 * Feld: Textarea
				 */
				if( get_row_layout() == 'page_builder_textarea' ):
					if(get_sub_field('padding') == '1') $classes .= ' no-padding';
					if(get_sub_field('bgcolor')) { echo '<style>.section.textarea.id-'.$i.'{background-color:'.get_sub_field('bgcolor').';}</style>'; $classes .= ' p15'; } 
		
					if(get_sub_field('rows') == 1) {
						echo '<div class="section textarea textarea-row-1 id-'.$i.''.$classes.'"><div class="container">';
	        				echo get_sub_field('editor_1');
						echo '</div></div>';
					} else if(get_sub_field('rows') == 2) {
						echo '<div class="section textarea textarea-row-2 id-'.$i.''.$classes.'"><div class="container">';
							echo '<div class="row">';
								echo '<div class="col-sm-6">';
									echo get_sub_field('editor_1');
								echo '</div><div class="col-sm-6">';
									echo get_sub_field('editor_2');
								echo '</div>';
							echo '</div>';
						echo '</div></div>';
					} else if(get_sub_field('rows') == 3) {
						echo '<div class="section textarea textarea-row-3 id-'.$i.''.$classes.'"><div class="container">';
							echo '<div class="row">';
								echo '<div class="col-sm-4">';
									echo get_sub_field('editor_1');
								echo '</div><div class="col-sm-4">';
									echo get_sub_field('editor_2');
								echo '</div><div class="col-sm-4">';
									echo get_sub_field('editor_3');
								echo '</div>';
							echo '</div>';
						echo '</div></div>';		
					} else if(get_sub_field('rows') == 4) {
						echo '<div class="section textarea textarea-row-4 id-'.$i.''.$classes.'"><div class="container">';
							echo '<div class="row">';
								echo '<div class="col-sm-3">';
									echo get_sub_field('editor_1');
								echo '</div><div class="col-sm-3">';
									echo get_sub_field('editor_2');
								echo '</div><div class="col-sm-3">';
									echo get_sub_field('editor_3');
								echo '</div><div class="col-sm-3">';
									echo get_sub_field('editor_4');
								echo '</div>';
							echo '</div>';
						echo '</div></div>';		
					}		
					
				endif;
				
				/*
				 * Feld: Slideshow
				 */
				if(get_row_layout() == 'page_builder_slideshow'): 
					global $indicator, $arrows, $interval, $fade, $images;
					$indicator = get_sub_field('indicator');
					$arrows = get_sub_field('arrows');
					$fade = get_sub_field('fade');
					$interval = get_sub_field('interval');
					$images = get_sub_field('bilder');
					
					if($images) {
						echo '<div class="section slideshow">';
							get_template_part('parts/teaser/code', 'teaser'); 
						echo '</div>';
					}
				endif;

                /*
				 * Feld: Top-Städte
				 */
                if(get_row_layout() == 'page_builder_top_locations'):
                    ?>
                    <div class="section top-locations">
                        <div class="container">
                            <?php
                            $locations = get_sub_field('locations');
                            $headline = get_sub_field('headline');

                            if($headline) {
                                ?><h2><?php echo $headline; ?></h2><?php
                            }

                            if($locations) {
                                $citys_diff = round(count($locations) / 4);
                                $i=0;
                                ?>
                                <div class="row" id="location-list-citys">
                                    <div class="col-sm-3">
                                        <ul><?php
                                            foreach($locations as $item) {
                                                if($i%$citys_diff==0 && $i!=0) echo '</ul></div><div class="col-sm-3"><ul>';
                                                ?>
                                                <li><a href="<?php echo get_permalink($item->ID); ?>"><?php echo get_the_title($item->ID); ?></a></li>
                                                <?php
                                                $i++;
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php
                endif;
				
				$i++;
			endwhile;
						
		endif;
		
		if(at_get_social('page'))
			get_template_part('parts/stuff/code', 'social');
		?>
	</div>
</div>

<?php get_footer(); ?>