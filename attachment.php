<?php 
get_header(); 

/*
 * VARS
 */
$sidebar = at_get_sidebar('single');
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<?php if($sidebar == 'left') { ?>
				<div class="col-sm-4 col-lg-3 col-lg-offset-1">
					<div id="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>
						
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo '8'; endif; ?>">
				<div id="content">
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<article <?php post_class(); ?>>
							<h1><?php the_title(); ?></h1>
							
							<?php $attachment_link = wp_get_attachment_link($post->ID, array(450, 800)); ?>
							<p><?php echo $attachment_link; ?></p>
							
							<?php the_content(); ?>
						</article>
					<?php endwhile; endif; ?>
				</div>
			</div>
			
			<?php if($sidebar == 'right') { ?>
				<div class="col-sm-4 col-lg-3 col-lg-offset-1">
					<div id="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
