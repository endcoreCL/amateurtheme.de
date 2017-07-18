<?php
get_header();

/*
 * VARS
 */
$sidebar = at_get_sidebar('blog', 'archive');
$sidebar_size = at_get_sidebar_size('blog', 'archive');
$layout = at_get_post_layout('archive');
?>

<div id="main" class="<?php echo at_get_section_layout_class('content'); ?>">
	<div class="container">
		<div class="row">
			<div class="col-sm-<?php if($sidebar == 'none') : echo '12'; else: echo $sidebar_size['content']; endif; ?>">
				<div id="content">
					<h1>
						<?php
						if ( is_day() ) :
							printf( __( 'Tagesarchiv: %s', 'amateurtheme' ), get_the_date() );
						elseif ( is_month() ) :
							printf( __( 'Monatsarchiv: %s', 'amateurtheme' ), get_the_date( _x( 'F Y', 'monatliches datum format', 'amateurtheme' ) ) );
						elseif ( is_year() ) :
							printf( __( 'Jahresarchiv: %s', 'amateurtheme' ), get_the_date( _x( 'Y', 'jaehrliches datum format', 'amateurtheme' ) ) );
						else :
							_e( 'Archiv', 'amateurtheme' );
						endif;
						?>
					</h1>

					<hr>

					<?php
					echo ($layout == 'masonry' ? '<div class="row row-masonry">' : '');

					if (have_posts()) : while (have_posts()) : the_post();
						get_template_part('parts/post/loop', at_get_post_layout('archive'));
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
