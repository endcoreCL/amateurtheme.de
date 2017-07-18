<hr>
<div class="post-author">
	<p class="h2">Über den Autor</p>
	<?php echo str_replace('avatar-80', 'avatar-80 alignleft',get_avatar( get_the_author_meta('user_email'), $size = '80')); ?>
	<p><?php the_author_meta( 'description'); ?></p>
	<p>
		Weitere Artikel von <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>"><?php the_author_meta('display_name'); ?></a>. 
		<?php if(get_the_author_meta('url')) { the_author_meta('display_name'); ?>'s <a href="<?php the_author_meta( 'url'); ?>" target="_blank" rel="nofollow">Webseite</a>. <?php } ?>
		<?php if(get_the_author_meta('facebook')) { the_author_meta('display_name'); ?> auf <a href="<?php the_author_meta( 'facebook'); ?>" target="_blank" rel="nofollow">Facebook</a>. <?php } ?>
		<?php if(get_the_author_meta('googleplus')) { the_author_meta('display_name'); ?> auf <a href="<?php the_author_meta( 'googleplus'); ?>" target="_blank" rel="nofollow">Google+</a>. <?php } ?>
		<?php if(get_the_author_meta('twitter')) { the_author_meta('display_name'); ?> auf <a href="https://twitter.com/<?php the_author_meta( 'twitter'); ?>" target="_blank" rel="nofollow">Twitter</a>. <?php } ?>
	</p>
	<div class="clearfix"></div>
</div>