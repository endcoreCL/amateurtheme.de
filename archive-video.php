<?php
get_header();

/**
 * Vars
 */
$sidebar = $xcore_layout->get_sidebar( 'video_archive' );
$classes = $xcore_layout->get_sidebar_classes( 'video_archive' );
$headline = ( get_field( 'video_archive_headline', 'options' ) ? get_field( 'video_archive_headline', 'options' ) : __( 'Alle Videos', 'amateurtheme' ) );
$text_top = get_field( 'video_archive_text_top', 'options' );
$text_bottom = get_field( 'video_archive_text_bottom', 'options' );
?>

<div id="main">
	<div class="container">
		<div class="row">
			<div class="<?php echo $classes['content']; ?>">
				<div id="content">
                    <h1><?php echo $headline; ?></h1>

					<?php
					if ( $text_top && ! is_paged() ) {
						echo $text_top . '<hr class="hr-transparent">';
					}
					?>

					<div id="video-list">
						<?php
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
					if ( $text_bottom && ! is_paged() ) {
						echo '<hr class="hr-transparent">' . $text_bottom;
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
