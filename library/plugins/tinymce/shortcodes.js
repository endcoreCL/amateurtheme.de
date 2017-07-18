(function(){
	tinymce.PluginManager.add('at_tinymce_addbuttons', function( editor, url ) {
		editor.addButton( 'at_tinymce_addbuttons', {
			text: 'Shortcodes',
			icon: false,
			onclick: function() {
				var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 992 < width ) ? 992 : width;
				W = W - 80;
				H = H - 84;
				tb_show( 'Shortcodes', ajaxurl + '?action=at_shortcodes_form&width=' + W + '&height=' + H + '&inlineId=endcore-shortcodes-form' );

				setTimeout(function(){
					var shortcode_window = jQuery('#TB_window .endcore-shortcodes-form-fields');
					var tb_window = jQuery('#TB_window');
					var new_height = tb_window.height() - 130;

					shortcode_window.height(new_height + 'px');
				}, 1000);
			}
		});
	});
})()