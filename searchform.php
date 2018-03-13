<form role="search" method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>">
	<div class="input-group">
		<input type="text" class="form-control" placeholder="<?php _e('Suche...', 'amateurtheme'); ?>" aria-label="<?php _e('Suche', 'amateurtheme'); ?>" name="s" value="<?php echo get_search_query(); ?>" />
		<select class="form-control" name="post_type" id="searchFor">
			<option value="post" <?php echo (isset($_GET['post_type']) && $_GET['post_type'] == 'post' ? 'selected' : ''); ?>><?php _e('Blog', 'amateurtheme'); ?></option>
			<option value="video" <?php echo (isset($_GET['post_type']) && $_GET['post_type'] == 'video' ? 'selected' : ''); ?>><?php _e('Videos', 'amateurtheme'); ?></option>
			<option value="video_actor" <?php echo (isset($_GET['post_type']) && $_GET['post_type'] == 'video_actor' ? 'selected' : ''); ?>><?php _e('Darsteller', 'amateurtheme'); ?></option>
			<option value="video_category" <?php echo (isset($_GET['post_type']) && $_GET['post_type'] == 'video_category' ? 'selected' : ''); ?>><?php _e('Kategorien', 'amateurtheme'); ?></option>
		</select>
		<div class="input-group-append">
			<button class="btn btn-primary" type="submit"><i class="fas fa-search"><i></i></i></button>
		</div>
	</div>
</form>