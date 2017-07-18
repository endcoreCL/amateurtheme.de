<?php 
get_header(); 

/*
 * VARS
 */
$sidebar = at_get_sidebar('blog', 'home');
$sidebar_size = at_get_sidebar_size('blog', 'home');
$posts_page_id = get_option( 'page_for_posts');
$layout = at_get_post_layout('home');;
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<?php
					if($paged < 2 && $posts_page_id != "0" && get_post_field('post_content', $posts_page_id)) echo apply_filters('the_content', get_post_field('post_content', $posts_page_id)) . '<hr>';

					echo ($layout == 'masonry' ? '<div class="row row-masonry">' : '');

					if (have_posts()) : while (have_posts()) : the_post();
						get_template_part('parts/post/loop', $layout);
					endwhile; echo at_pagination(3); endif;

					echo ($layout == 'masonry' ? '</div>' : '');
					?>
				</div>
			</div>

			<?php if('left' == $sidebar || 'right' == $sidebar) { ?>
				<div class="col-sm-<?php echo $sidebar_size['sidebar']; ?>">
					<div id="sidebar">
						<?php get_sidebar(); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
