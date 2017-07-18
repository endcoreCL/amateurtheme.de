<p class="post-meta">
	<span class="post-meta-author">
		<?php echo __('Veröffentlicht von', 'amateurtheme'); ?> <a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo esc_attr(get_the_author()); ?></a>
	</span>
	
	<span class="post-meta-date"><?php the_time('d.m.Y'); ?></span>
	
	<?php 
	if(get_the_category_list()) {
		printf('<span class="post-meta-cats">' . __('Kategorie(n): %s', 'amateurtheme') . '</span>', get_the_category_list(', '));
	} 
	?>
	
	<?php echo get_the_tag_list('<span class="post-meta-tags">'. __('Schlagwörter: ', 'amateurtheme'), ', ', '</span>'); ?>
	
	<?php if(comments_open()) echo '<span class="post-meta-comments"><a href="' . get_permalink() . '#comments">'; comments_number( 'Keine Kommentare', '1 Kommentar', '% Kommentare' ); echo '</a></span>'; ?>
</p>