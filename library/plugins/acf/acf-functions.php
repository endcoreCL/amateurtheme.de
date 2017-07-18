<?php
/**
 * ACF Funktionen
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	acf
 */

define( 'ACF_LITE' , false );

add_filter('acf/settings/path', 'at_acf_settings_path');
function at_acf_settings_path( $path ) {
	$path = at_PLUGINS . '/acf/core/';
	return $path;
	
}

add_filter('acf/settings/dir', 'at_acf_settings_dir');
function at_acf_settings_dir( $dir ) {
	$dir = get_template_directory_uri() . '/library/plugins/acf/core/';
	return $dir;
}

add_filter('acf/settings/load_json', 'at_acf_json_load_point');
function at_acf_json_load_point( $paths ) {
	unset($paths[0]);
	$paths[] = get_template_directory() . '/library/plugins/acf/json';
	return $paths;
}

add_filter('acf/settings/save_json', 'at_acf_json_save_point');
function at_acf_json_save_point( $path ) {
	$path = get_template_directory() . '/library/plugins/acf/json';
	return $path;
}

/*
 * Update-Vorgang deaktivieren
 */
add_filter('acf/settings/show_updates', 'at_acf_settings_show_updates');
function at_acf_settings_show_updates( $path ) {
	return false;
}

if(function_exists('acf_add_options_page')) { 
	acf_add_options_page(array('page_title' => 'Optionen', 'menu_title' => 'Optionen', 'icon_url' => 'dashicons-heart', 'capability' => 'manage_options'));
	acf_add_options_sub_page(array('title' => 'Allgemein', 'parent' => 'acf-options-optionen'));
	acf_add_options_sub_page(array('title' => 'Design', 'parent' => 'acf-options-optionen'));
	acf_add_options_sub_page(array('title' => 'Blog', 'parent' => 'acf-options-optionen'));
}


/*
 * Add Meta Box to Option Panel

add_action( 'acf/input/admin_head', 'at_register_support_metabox' );
function at_register_support_metabox() {
	if(function_exists('add_meta_box')) {
		add_meta_box('at_support_box', __('Wichtige Links & Support', 'amateurtheme'), 'at_show_support_metabox', 'acf_options_page', 'side', 'low');
	}
}

function at_show_support_metabox( $post ) {
	?>
	<img src="<?php echo get_template_directory_uri(); ?>/library/helper/images/backend-mini-teaser.png"/>

	<p>
		<?php _e('Du hast Fragen zur Installation, Konfiguration oder Anpassung des Themes?
		Dann wirf einen Blick auf die Folgenden Informationen:', 'amateurtheme'); ?>
	</p>

	<p>
		<a href="https://www.datingtheme.io/" target="_blank"><?php _e('Webseite', 'amateurtheme'); ?></a><br>
		<a href="https://www.datingtheme.io/dokumentation/" target="_blank"><?php _e('Dokumentation', 'amateurtheme'); ?></a><br>
		<a href="https://www.youtube.com/playlist?list=PLcOtQDnVBlDkH6EMwiZlHif267O8MuiWi" target="_blank"><?php _e('Video Tutorials', 'amateurtheme'); ?></a><br>
		<a href="https://www.datingtheme.io/changelog/" target="_blank"><?php _e('Changelog', 'amateurtheme'); ?></a>
	</p>

	<p>
		<?php printf(__('Zur einwandfreien Nutzung des Themes benötigst du einen VX-CASH Account.
		Du hast noch keinen VX-CASH Webmaster Account? <a href="%s" target="_blank">Hier registrieren.</a>', 'amateurtheme'), 'https://www.vxcash.net/'); ?>
	</p>

	<p>
		<?php printf(__('Copyright &copy; 2016 <a href="%s" target="_blank">datingtheme.io</a>', 'amateurtheme'), 'https://www.datingtheme.io/'); ?>
	</p>
	<?php
} */
?>