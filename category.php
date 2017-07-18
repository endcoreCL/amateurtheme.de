<?php
get_header();

/*
 * VARS
 */
$sidebar = at_get_sidebar('blog', 'category');
$sidebar_size = at_get_sidebar_size('blog', 'category');
$layout = at_get_post_layout('category');
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<h1><?php single_cat_title(); ?></h1>

					<?php
					if(category_description() && !is_paged()) { echo category_description() . '<hr>'; }

					echo ($layout == 'masonry' ? '<div class="row row-masonry">' : '');

					if (have_posts()) : while (have_posts()) : the_post();
						get_template_part('parts/post/loop', at_get_post_layout('category'));
					endwhile; echo ($layout == 'masonry' ? '</div>' : ''); echo at_pagination(3); endif;
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
