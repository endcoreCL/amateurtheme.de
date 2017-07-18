<?php 
/*
 * VARS
 */	
$posts_per_page = (get_field('blog_single_related_numberposts', 'option') ? get_field('blog_single_related_numberposts', 'option') : '3');
$layout = (get_field('blog_single_related_layout', 'option') ? get_field('blog_single_related_layout', 'option') : 'list');
$filter = (get_field('blog_single_related_filter', 'option') ? get_field('blog_single_related_filter', 'option') : '');
$orderby = (get_field('blog_single_related_orderby', 'option') ? get_field('blog_single_related_orderby', 'option') : 'date');
$order = (get_field('blog_single_related_order', 'option') ? get_field('blog_single_related_order', 'option') : 'DESC');
$post_type = $post->post_type;

$args = array(
	'post_type'				=> $post_type,
	'posts_per_page'		=> $posts_per_page,
	'post__not_in'			=> array($post->ID),
	'orderby'				=> $orderby,
	'order'					=> $order
);

if('post' == $post_type) {
	$cats = wp_get_post_categories();
	$tags = wp_get_post_tags();

	if('cat' == $filter) {
		$args['category__and'] = $cats;
	} else if('tag' == $filter) {
		$args['tag__and'] = $tags;
	} else if('cat_tag' == $filter) {
		$args['category__and'] = $cats;
		$args['tag__and'] = $tags;
	} 
}
	
if($related = get_posts($args)) { ?>
	<hr>
	<div class="post-related">
		<h2><?php _e('Ähnliche Beiträge', 'amateurtheme'); ?></h2><?php
		if('grid' == $layout) echo '<div class="row">';
		else if('list' == $layout) echo '<ul>';
		
		foreach($related as $post) {
			setup_postdata($post);
			
			get_template_part('parts/post/loop', $layout);	
		}
	
		if('grid' == $layout) echo '</div>';
		else if('list' == $layout) echo '</ul>';
		?>
	</div>
	<?php
} wp_reset_query();
?>

