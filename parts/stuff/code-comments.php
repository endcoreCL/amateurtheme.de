<?php if(comments_open()) { ?>
	<hr>
	<div class="post-comments" id="comments">
		<?php if(get_comments_number($post->ID) == 0) { echo '<p class="h2">'.__('Keine Kommentare vorhanden', 'amateurtheme').'</p>'; } else {echo '<p class="h2">'.__('Kommentare', 'amateurtheme').'</p>'; } ?>
		<?php comments_template(); ?>
	</div>
<?php } ?>