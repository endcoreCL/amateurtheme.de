<?php
global $sidebar, $sidebar_size;
$args = array(
	'class' 		=> 'img-responsive alignleft post-thumbnail',
	'sidebar' 		=> (isset($sidebar) ? $sidebar : ''),
	'sidebar_size' 	=> (isset($sidebar_size['sidebar']) ? $sidebar_size['sidebar'] : '')
);
?>
<article <?php post_class('post-small'); ?>>
	<h2>
		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php the_title(); ?>
		</a>
	</h2>
	
	<?php
	if('1' == get_field('blog_single_show_meta', 'option')) {
		get_template_part('parts/stuff/code', 'postmeta');
	}
	?>
	
	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
		<?php echo at_post_thumbnail($post->ID, 'at_thumbnail', $args); ?>
	</a>
	
	<?php the_excerpt() ?> 
	
	<div class="clearfix"></div>
</article>