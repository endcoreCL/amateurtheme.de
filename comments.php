<?php if(!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'])) : ?>
    <?php die('Die Datei "comments.php" kann nicht direkt aufgerufen werden.'); ?>
<?php endif; ?>

<?php $aria_req = ''; $i=0; wp_list_comments(array('callback'=>'at_comments_template','style'=>'div','avatar_size'=>48)); ?>

<hr>		

<div id="comments_reply">
	<?php 
	comment_form(
		array(
			'comment_notes_after' => '',
			'label_submit'=> __('Senden', 'amateurtheme'),
			'title_reply'=>'<p class="h3">' . __('Du hast eine Frage oder eine Meinung zum Artikel? Teile sie mit uns!', 'amateurtheme') . '</p>',
			'comment_notes_before' => '<p class="comment-notes text-muted">' . __('Deine E-Mail-Adresse wird nicht veröffentlicht. Erforderliche Felder sind markiert *', 'amateurtheme') . '</p>',
			'comment_field' =>  '<div class="row"><div class="form-group col-md-12"><label for="comment">' . __( 'Kommentar', 'amateurtheme' ) .
				'</label><textarea id="comment" name="comment" class="form-control" rows="5" aria-required="true">' .
			    '</textarea></div></div>',
			
			'fields' => apply_filters( 'comment_form_default_fields', array(
				'author' => '<div class="row"><div class="form-group col-md-4"><label for="author">' . __('Name', 'amateurtheme') . '</label> ' .( $req ? '<span class="required">*</span>' : '' ) .' <input id="author" name="author" type="text" class="form-control" value="' . (isset($commenter['comment_author']) ? esc_attr( $commenter['comment_author'] ) : '') .
					'"' . $aria_req . ' /></div>',
				 
				'email' => '<div class="form-group col-md-4"><label for="email">'. __('E-Mail Adresse', 'amateurtheme') . '</label> ' .
					( $req ? '<span class="required">*</span>' : '' ) .
					'<input id="email" name="email" type="text" class="form-control" value="' . (isset($commenter['comment_author__mail']) ? esc_attr( $commenter['comment_author__mail'] ) : '') .
					'"' . $aria_req . ' /></div>',
				
				'url' => '<div class="form-group col-md-4"><label for="url">' . __('Webseite', 'amateurtheme') . '</label><input id="url" name="url" type="text" class="form-control" value="' . (isset($commenter['comment_author_url']) ? esc_attr( $commenter['comment_author_url'] ) : '') .'" /></div></div>',
			))
		)
	); ?>
	<div class="clearfix"></div>
</div>