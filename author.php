<?php
get_header();

/*
 * VARS
 */
$sidebar = at_get_sidebar('blog', 'author');
$sidebar_size = at_get_sidebar_size('blog', 'author');
$layout = at_get_post_layout('author');
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<h1><?php printf( __('Alle Beiträge von <span>%s</span>', 'amateurtheme'),  get_the_author()); ?></h1>

					<?php
					echo str_replace('avatar-80', 'avatar-80 alignleft',get_avatar( get_the_author_meta('user_email'), $size = '80'));
					if(get_the_author_meta('description') && !is_paged()) { ?>
						<div class="author-description">
							<p class="author-bio">
								<?php the_author_meta( 'description' ); ?>
							</p>
						</div>
					<?php } ?>

					<ul class="list-inline list-author-meta">
						<?php if(get_the_author_meta('url')) { ?><li class="author-meta-url"><?php printf(__('%s\'s <a href="%s" target="_blank" rel="nofollow">Webseite</a>.', 'amateurtheme'), get_the_author_meta('display_name'), get_the_author_meta('url')); ?></li><?php } ?>
						<?php if(get_the_author_meta('facebook')) { ?><li class="author-meta-googleplus"><?php printf(__('%s auf <a href="%s" target="_blank" rel="nofollow">Facebook</a>.', 'amateurtheme'), get_the_author_meta('display_name'), get_the_author_meta('facebook')); ?></li><?php } ?>
						<?php if(get_the_author_meta('googleplus')) { ?><li class="author-meta-facebook"><?php printf(__('%s auf <a href="%s" target="_blank" rel="nofollow">Google+</a>.', 'amateurtheme'), get_the_author_meta('display_name'), get_the_author_meta('googleplus')); ?></li><?php } ?>
						<?php if(get_the_author_meta('twitter')) { ?><li class="author-meta-twitter"><?php printf(__('%s auf <a href="https://twitter.com/%s" target="_blank" rel="nofollow">Twitter</a>.', 'amateurtheme'), get_the_author_meta('display_name'), get_the_author_meta('twitter')); ?></li><?php } ?>
					</ul>

					<div class="clearfix"></div>

					<hr>

					<?php
					echo ($layout == 'masonry' ? '<div class="row row-masonry">' : '');

					if (have_posts()) : while (have_posts()) : the_post();
						get_template_part('parts/post/loop', at_get_post_layout('author'));
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
