<?php get_header(); ?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div id="content">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
				
				<?php 
				if(at_get_social('page'))
					get_template_part('parts/stuff/code', 'social');
				?>
			<?php endwhile; endif; ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
