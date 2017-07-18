<article <?php post_class('post-grid col-sm-4 col-xs-6 col-xxs-12'); ?>>
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo at_post_thumbnail($post->ID, 'at_thumbnail', array('class' => 'img-responsive')); ?>
	</a>
	
	<h2>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_title(); ?>
		</a>
	</h2>
	
	<div class="clearfix"></div>
</article>