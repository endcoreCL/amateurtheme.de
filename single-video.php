<?php

get_header();

// Vars
$video  = new AT_Video( get_the_ID() );
$ad_top = get_field( 'video_single_ad_top', 'options' );
$prg    = get_field( 'prg_activate', 'options' );
$source = get_field( 'video_source' );

// classes
$col = array( 'content' => 'col-sm-12', 'sidebar' => 'col-sm-12' );

if ( $ad_top ) {
	$col['content'] = 'col-sm-9';
	$col['sidebar'] = 'col-sm-3 d-none d-sm-block';
}
?>

<div id="main" data-post-id="<?php the_ID(); ?>">
	<div class="container">
		<div id="content">
			<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				?>
				<div class="video-summary">
					<div class="row">
						<div class="<?php echo $col['content']; ?>">
							<div class="video-thumb-container card-video">
								<?php
								if ( $source == 'embed' ) {
									echo get_field( 'video_embed' );
								} else {
									// preview videos
									$preview_webm = $video->preview_video( 'webm' );
									$preview_mp4  = $video->preview_video( 'mp4' );

									if ( $preview_webm || $preview_mp4 ) {
										$poster = $video->thumbnail_url();
										?>
										<div class="embed-responsive embed-responsive-16by9">
											<video poster="<?php echo $poster; ?>" id="player" playsinline controls data-plyr-config='{"loadSprite" : "<?php echo get_template_directory_uri(); ?>/assets/img/plyr.svg"}'>
												<?php
												if ( $preview_mp4 ) {
													?>
													<source src="<?php echo $preview_mp4; ?>" type="video/mp4">
													<?php
												}
												if ( $preview_webm ) {
													?>
													<source src="<?php echo $preview_webm; ?>" type="video/webm">
													<?php
												}
												?>
											</video>
										</div>

										<div class="video-cta text-center py-3">
											<?php
											$external_url = $video->external_url();
											if ( $external_url ) {
												if ( $prg ) {
													?>
													<span class="btn btn-primary redir-link" data-submit="<?php echo base64_encode( $external_url ); ?>" title="<?php $video->title(); ?>" data-target="_blank">
													<?php
												} else {
													?>
													<a class="btn btn-primary" href="<?php echo $external_url; ?>" title="<?php $video->title(); ?>" target="_blank" rel="nofollow">
													<?php
												}

												echo '<i class="fas fa-play mr-3"></i> ' . __( 'Video jetzt in voller Länge ansehen', 'amateurthene' );

												if ( $prg ) {
													?>
													</span>
													<?php
												} else {
													?>
													</a>
													<?php
												}
											}
											?>
										</div>
										<?php
									} else {
										// thumbnail
										$external_url = $video->external_url();
										if ( $external_url ) {
											if ( $prg ) {
												?>
												<span class="redir-link card-link-img card-play-icon" data-submit="<?php echo base64_encode( $external_url ); ?>" title="<?php $video->title(); ?>" data-target="_blank">
												<?php
											} else {
												?>
												<a href="<?php echo $external_url; ?>" class="card-link-img card-play-icon" title="<?php $video->title(); ?>" target="_blank" rel="nofollow">
												<?php
											}

											echo $video->thumbnail(
												'full',
												array(
													'class' => 'img-fluid',
													'style' => 'width: 100%; height: auto;'
												)
											);

											if ( $prg ) {
												?>
												</span>
												<?php
											} else {
												?>
												</a>
												<?php
											}
										} else {
											echo $video->thumbnail(
												'full',
												array(
													'class' => 'img-fluid',
													'style' => 'width: 100%; height: auto;'
												)
											);
										}
									}
								}
								?>
							</div>

							<div class="video-info">
								<h1><?php echo $video->title(); ?></h1>

								<?php
								get_template_part( 'parts/video/code', 'meta' );

								the_content();
								?>
							</div>
						</div>

						<?php
						if ( $ad_top && ! wp_is_mobile() ) {
							?>
							<div class="<?php echo $col['sidebar']; ?>">
								<?php echo $ad_top; ?>
							</div>
							<?php
						}
						?>
					</div>

					<?php
					get_template_part( 'parts/video/code', 'categories' );

					get_template_part( 'parts/video/code', 'related' );

					if ( $ad_top && wp_is_mobile() ) {
						?>
						<div class="d-block d-sm-none">
							<?php echo $ad_top; ?>
						</div>
						<?php
					}

					get_template_part( 'parts/video/code', 'tags' );

					get_template_part( 'parts/video/code', 'top-categories' );

					get_template_part( 'parts/video/code', 'ad-bottom' );
					?>
				</div>

			<?php
			endwhile; endif;
			?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
