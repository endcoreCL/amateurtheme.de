<?php 
get_header(); 

/*
 * VARS
 */
$sidebar = at_get_sidebar('blog', 'search');
$sidebar_size = at_get_sidebar_size('blog', 'search');
$layout = at_get_post_layout('search');
global $wp_query;
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<h1 ><?php printf( __( 'Deine Suche nach <span class="highlight">%s</span> ergab <span class="highlight">%s</span> Treffer', 'amateurtheme' ), get_search_query(), $wp_query->found_posts ); ?></h1>
					
					<hr>
									
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						<?php get_template_part('parts/post/loop', at_get_post_layout('search')); ?>
					<?php endwhile; echo at_pagination(3); endif; ?>
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
