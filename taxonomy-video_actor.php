<?php

get_header();

/**
 * Vars
 */
$sidebar                  = $xcore_layout->get_sidebar( 'video_actor' );
$classes                  = $xcore_layout->get_sidebar_classes( 'video_actor' );
$headline                 = get_field( 'video_actor_headline', 'options' );
$ads                      = get_field( 'video_actor_ad', 'options' );
$queried_object           = get_queried_object();
$term_id                  = $queried_object->term_id;
$actor                    = new AT_Video_Actor( $term_id );
$actor_image              = $actor->image();
$actor_description        = term_description();
$actor_profile_url        = $actor->url();
$video_actor_description  = get_field( 'video_actor_description', 'options' );
$video_actor_details      = get_field( 'video_actor_details', 'options' );
$prg                      = get_field( 'prg_activate', 'options' );
$actor_second_description = get_field( 'actor_second_description', $queried_object );
$has_data                 = false;
?>

<div id="main">
	<div class="container">
		<div class="row">
			<div class="<?php echo $classes['content']; ?>">
				<div id="content">
					<?php if ( ! is_paged() ) { ?>
						<div class="video-actor-header clearfix">
							<h1>
								<?php
								if ( $headline ) {
									printf( $headline, single_term_title( '', false ) );
								} else {
									single_term_title();
								}
								?>
							</h1>

							<?php
							if ( $video_actor_description ) {
								if ( $actor_image ) {
									echo '<img src="' . $actor_image['sizes']['video_thumb'] . '" alt="' . single_term_title( '', false ) . '" title="' . single_term_title( '', false ) . '" class="alignright img-fluid">';
								}

								if ( $actor_description ) {
									echo '<div class="video-actor-description">' . $actor_description . '</div>';
								}

								if ( $actor_profile_url ) {
									echo '<p>';
									if ( $prg ) {
										echo '<span class="redir-link btn btn-primary" data-submit="' . base64_encode( $actor_profile_url ) . '" data-target="_blank">' . __( 'zum Profil', 'amateurtheme' ) . '</span>';
									} else {
										echo '<a href="' . $actor_profile_url . '" target="_blank" rel="nofollow" class="btn btn-primary">' . __( 'zum Profil', 'amateurtheme' ) . '</a>';
									}
									echo '</p>';
								}

								echo '<hr class="hr-transparent">';
							}
							?>
						</div>

						<?php
						if ( $video_actor_details ) {
							$has_data = false;

							$fields = apply_filters( 'at_video_actor_details_fields', array(
								__( 'Geschlecht', 'amateurtheme' )       => 'gender',
								__( 'Körpergröße', 'amateurtheme' )      => 'size',
								__( 'Haarfarbe', 'amateurtheme' )        => 'haircolor',
								__( 'Haarlänge', 'amateurtheme' )        => 'hairlength',
								__( 'Figur', 'amateurtheme' )            => 'bodytype',
								__( 'Erscheinungsbild', 'amateurtheme' ) => 'bodystyle',
								__( 'Augenfarbe', 'amateurtheme' )       => 'eyecolor',
								__( 'PLZ', 'amateurtheme' )              => 'zipcode',
								__( 'Stadt', 'amateurtheme' )            => 'city',
								__( 'Land', 'amateurtheme' )             => 'country',
								__( 'Alter', 'amateurtheme' )            => 'age',
								__( 'Sternzeichen', 'amateurtheme' )     => 'star_sign',
								__( 'Orientierung', 'amateurtheme' )     => 'sex_orientation',
								__( 'Gewicht', 'amat eurtheme' )         => 'weight',
								__( 'Körpchengröße', 'amateurtheme' )    => 'breast_size',
								__( 'Intimrasur', 'amateurtheme' )       => 'shave',
								__( 'Beruf', 'amateurtheme' )            => 'job',
								__( 'Ich bin', 'amateurtheme' )          => 'relationship_status',
								__( 'Ich suche', 'amateurtheme' )        => 'search',
								__( 'für', 'amateurtheme' )              => 'search_for',
								__( 'Raucher', 'amateurtheme' )          => 'smoke',
								__( 'Alkohol', 'amateurtheme' )          => 'alcohol'
							) );

							if ( $fields ) {
								// check if there is any data to output
								foreach ( $fields as $k => $v ) {
									if ( $actor->field( $v ) != '-' ) {
										$has_data = true;
									}
								}
							}

							if ( $has_data ) {
								?>
								<div class="video-actor-details">
									<?php echo '<h2>' . apply_filters( 'at_video_actor_details_headline', __( 'Details', 'amateurtheme' ) ) . '</h2>'; ?>
									<table class="table table-bordered table-details">
										<colgroup>
											<col style="width: 20%">
											<col style="width: 30%">
											<col style="width: 20%">
											<col style="width: 30%">
										</colgroup>
										<tbody>
										<tr>
											<?php
											$i = 0;
											foreach ( $fields as $k => $v ) {
												if ( $i % 2 == 0 && $i != 0 ) {
													echo '</tr><tr>';
												}

												if ( $actor->field( $v ) != '-' ) {
													echo '<td class="td-label">' . $k . '</td><td class="td-value">' . $actor->field( $v ) . '</td>';
													$i++;
												}
											}
											?>
										</tr>
										</tbody>
									</table>
								</div>
								<?php
							}
						}
						?>

						<hr class="hr-transparent">

						<?php
					} else {
						// paged
						?>
						<h1>
							<?php apply_filters( 'at_video_actor_video_paginated_headline', sprintf( __( '%s Videos', 'amateurtheme' ), single_term_title( '', false ) ) ); ?>
						</h1>
						<?php
					}
					?>

					<div id="video-list" class="video-actor">
						<?php
						if ( ! is_paged() && $has_data ) {
							?>
							<h2>
								<?php echo apply_filters( 'at_video_actor_video_headline', __( 'Videos', 'amateurtheme' ) ); ?>
							</h2>
							<?php
						}

						if ( have_posts() ) :
							?>
							<div class="card-deck">
								<?php
								while ( have_posts() ) :
									the_post();

									get_template_part( 'parts/video/loop', 'card' );
								endwhile;
								?>
							</div>
							<hr class="hr-transparent">
							<?php
							echo at_pagination();
						endif;
						?>
					</div>

					<?php
					if ( $ads ) {
						?>
						<div class="video-bnr">
							<?php echo $ads; ?>
						</div>
						<?php
					}

					if ( $actor_second_description && ! is_paged() ) {
						?>
						<div class="second-description">
							<?php echo $actor_second_description; ?>
						</div>
						<?php
					}
					?>
				</div>
			</div>

			<?php
			if ( $sidebar ) {
				?>
				<div class="<?php echo $classes['sidebar']; ?>">
					<div id="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
