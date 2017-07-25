<?php
/*
 * xCORE Debug Page
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists('at_let_to_num') ) {
	/**
	 * at_let_to_num function.
	 *
	 * @param  boolean $size
	 * @return string
	 */
	function at_let_to_num($size) {
		$l = substr($size, -1);
		$ret = substr($size, 0, -1);
		switch (strtoupper($l)) {
			case 'P':
				$ret *= 1024;
			case 'T':
				$ret *= 1024;
			case 'G':
				$ret *= 1024;
			case 'M':
				$ret *= 1024;
			case 'K':
				$ret *= 1024;
		}
		return $ret;
	}
}

if ( ! function_exists('at_load_debug_page') ) {
	/**
	 * at_load_debug_page function.
	 *
	 */
	add_action('admin_menu', 'at_load_debug_page');
	function at_load_debug_page() {
		add_menu_page('datingtheme.io Debug', 'Debug', 'administrator', 'at_debug', 'at_debug_page', 'dashicons-sos');

		function at_debug_page() {
			$upload_dir = wp_upload_dir();
			$upload_folder = str_replace($_SERVER['DOCUMENT_ROOT'], '', $upload_dir['basedir']);

			// test upload folder
			$test_file = @fopen($upload_dir['path'] . "/chmod-test-file", "a+");
			if ($test_file)
				$upload_folder_access = true;
			else
				$upload_folder_access = false;
			@fclose($test_file);
			@unlink($upload_dir['path'] . "/chmod-test-file");

			?>
			<div class="wrap" id="datingtheme-page">
				<h1>Amateur Theme Debug</h1>
				<table class="datingtheme_debug_table widefat" cellspacing="0" id="status" style="margin-top: 15px;">
					<thead>
					<tr>
						<th colspan="2" data-export-label="WordPress"><?php _e('WordPress', 'amateurtheme'); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td data-export-label="Home URL" width="200"><?php _e('Home URL', 'amateurtheme'); ?>:</td>
						<td><?php echo home_url(); ?></td>
					</tr>
					<tr>
						<td data-export-label="Site URL"><?php _e('Site URL', 'amateurtheme'); ?>:</td>
						<td><?php echo site_url(); ?></td>
					</tr>
					<tr>
						<td><?php _e('Document Root', 'amateurtheme'); ?></td>
						<td><?php echo $_SERVER['DOCUMENT_ROOT']; ?></td>
					</tr>
					<tr>
						<td><?php _e('Theme Details', 'amateurtheme'); ?></td>
						<td>
							<?php
							$current_theme = wp_get_theme('datingtheme');
							if($current_theme) {
								echo $current_theme->get('Name') . ' (Version: ' . $current_theme->get('Version') . ')';
							} else {
								echo '-';
							}
							?>
						</td>
					</tr>
					<tr>
						<td><?php _e('Template URL', 'amateurtheme'); ?></td>
						<td><?php echo get_template_directory_uri(); ?></td>
					</tr>
					<tr>
						<td><?php _e('Stylesheet URL', 'amateurtheme'); ?></td>
						<td><?php echo get_stylesheet_directory_uri(); ?></td>
					</tr>
					<tr>
						<td><?php _e('Template Ordner', 'amateurtheme'); ?></td>
						<td><?php echo get_template_directory(); ?></td>
					</tr>
					<tr>
						<td><?php _e('Stylesheet Ordner', 'amateurtheme'); ?></td>
						<td><?php echo get_stylesheet_directory(); ?></td>
					</tr>
					<tr>
						<td><?php _e('Uploads Ordner', 'amateurtheme'); ?></td>
						<td>
							<?php
							if ($upload_folder_access == false) {
								echo '<mark>' . $upload_dir['basedir'] . ' -  bitte Schreibrechte setzen (chmod 775 / 777)</mark>';
							} else {
								echo $upload_dir['basedir'];
							}
							?>
						</td>
					</tr>
					<tr>
						<td data-export-label="WP Version"><?php _e('WP Version', 'amateurtheme'); ?>:</td>
						<td><?php bloginfo('version'); ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Multisite"><?php _e('WP Multisite', 'amateurtheme'); ?>:</td>
						<td><?php if (is_multisite()) echo '&#10004;'; else echo '&ndash;'; ?></td>
					</tr>
					<tr>
						<td data-export-label="WP Memory Limit"><?php _e('WP Memory Limit', 'amateurtheme'); ?>:</td>
						<td><?php
							$memory = at_let_to_num(WP_MEMORY_LIMIT);

							if ($memory < 67108864) {
								echo '<mark>' . sprintf(__('%s - Damit WordPress reibungslog funktioniert, empfehlen wir ein Memory Limit von min. 64MB. <a href="%s" target="_blank">Mehr Informationen</a>', 'amateurtheme'), size_format($memory), 'http://drwp.de/wordpress-memory-limit/') . '</mark>';
							} else {
								echo size_format($memory);
							}
							?></td>
					</tr>
					<tr>
						<td data-export-label="WP Debug Mode"><?php _e('WP Debug Mode', 'amateurtheme'); ?>:</td>
						<td><?php if (defined('WP_DEBUG') && WP_DEBUG) echo '&#10004;'; else echo '<mark>' . '&ndash;' . '</mark>'; ?></td>
					</tr>
					<tr>
						<td data-export-label="Language"><?php _e('Language', 'amateurtheme'); ?>:</td>
						<td><?php echo get_locale() ?></td>
					</tr>
					</tbody>
				</table>

				&nbsp;

				<table class="datingtheme_debug_table widefat" cellspacing="0" id="status">
					<thead>
					<tr>
						<th colspan="2" data-export-label="Server"><?php _e('Server', 'amateurtheme'); ?></th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td data-export-label="Server Info" width="200"><?php _e('Server', 'amateurtheme'); ?>:</td>
						<td><?php echo esc_html($_SERVER['SERVER_SOFTWARE']); ?></td>
					</tr>
					<tr>
						<td data-export-label="PHP Version"><?php _e('PHP Version', 'amateurtheme'); ?>:</td>
						<td><?php
							if (function_exists('phpversion')) {
								$php_version = phpversion();

								if (version_compare($php_version, '5.4', '<')) {
									echo '<mark>' . sprintf(__('%s - Benutze mind. Version 5.4. <a href="%s" target="_blank">Mehr Informationen</a>', 'amateurtheme'), esc_html($php_version), 'http://docs.woothemes.com/document/how-to-update-your-php-version/') . '</mark>';
								} else {
									echo esc_html($php_version);
								}
							} else {
								_e("PHP Version nicht abrufbar, die Funktion phpversion() existiert nicht.", 'amateurtheme');
							}
							?></td>
					</tr>
					<?php if (function_exists('ini_get')) : ?>
						<tr>
							<td data-export-label="PHP Post Max Size"><?php _e('PHP Post Max Size', 'amateurtheme'); ?>:</td>
							<td><?php echo size_format(at_let_to_num(ini_get('post_max_size'))); ?></td>
						</tr>
						<tr>
							<td data-export-label="PHP Time Limit"><?php _e('PHP Time Limit', 'amateurtheme'); ?>:</td>
							<td><?php echo ini_get('max_execution_time'); ?></td>
						</tr>
						<tr>
							<td data-export-label="PHP Max Input Vars"><?php _e('PHP Max Input Vars', 'amateurtheme'); ?>:</td>
							<td><?php echo ini_get('max_input_vars'); ?></td>
						</tr>
					<?php endif; ?>
					<tr>
						<td data-export-label="MySQL Version"><?php _e('MySQL Version', 'amateurtheme'); ?>:</td>
						<td>
							<?php
							global $wpdb;
							echo $wpdb->db_version();
							?>
						</td>
					</tr>
					<tr>
						<td data-export-label="Max Upload Size"><?php _e('Max Upload Size', 'amateurtheme'); ?>:</td>
						<td><?php echo size_format(wp_max_upload_size()); ?></td>
					</tr>
					</tbody>
				</table>

				&nbsp;

				<table class="datingtheme_debug_table widefat" cellspacing="0" id="status">
					<thead>
					<tr>
						<th colspan="2"
							data-export-label="Plugins (<?php echo count((array)get_option('active_plugins')); ?>)"><?php _e('Plugins', 'amateurtheme'); ?>
							(<?php echo count((array)get_option('active_plugins')); ?>)
						</th>
					</tr>
					</thead>
					<tbody>
					<?php
					$active_plugins = (array)get_option('active_plugins', array());

					if (is_multisite()) {
						$active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
					}

					foreach ($active_plugins as $plugin) {

						$plugin_data = @get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
						$dirname = dirname($plugin);
						$version_string = '';
						$network_string = '';

						if (!empty($plugin_data['Name'])) {

							// link the plugin name to the plugin url if available
							$plugin_name = esc_html($plugin_data['Name']);

							if (!empty($plugin_data['PluginURI'])) {
								$plugin_name = '<a href="' . esc_url($plugin_data['PluginURI']) . '" title="' . __('Besuche die Plugin-Homepage', 'amateurtheme') . '" target="_blank">' . $plugin_name . '</a>';
							}
							?>
							<tr>
								<td width="300"><?php echo $plugin_name; ?></td>
								<td><?php echo sprintf(_x('von %s', 'von', 'amateurtheme'), $plugin_data['Author']) . ' &ndash; ' . esc_html($plugin_data['Version']) . $version_string . $network_string; ?></td>
							</tr>
							<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
			<?php
		}
	}
}