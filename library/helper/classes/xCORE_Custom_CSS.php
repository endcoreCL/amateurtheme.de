<?php
class xCORE_Custom_SCSS {
	private $folder;
	private $filename;
	private $variables;

	public function __construct() {
		$this->folder = 'sass';
		$this->filename = '_custom.scss';
		$this->fields = $this->get_fields();

		add_action( 'acf/save_post', array( $this, 'generate_scss' ) );
	}

	public function get_file_path() {
		$theme_dir = get_template_directory() . '/assets';
		$css_url = trailingslashit( $theme_dir ) . $this->folder . '/' . $this->filename;
		return $css_url;
	}

	public function get_fields() {
		$acf_fields = get_field_objects('options');
		$fields = array();

		if($acf_fields) {
			// filter for needed fields
			$catch = array(
				'colors_'
			);

			foreach($acf_fields as $k => $v) {
				$match = (str_replace($catch, '', $k) != $k);
				if($match) {
					$name = $k;
					// clean the name
					foreach($catch as $c) {
						if(strpos($name, $c) === 0) {
							$name = substr($k, strlen($c));
						}
					}
					$fields[$name] = (isset($v['value']) ? $v['value'] : $v['default_value']);
				}
			}
		}

		return $fields;
	}

	public function generate_scss() {
		$output = '';

		if($this->fields) {
			// catch blocks
			$output .= $this->generate_scss_catch_block('grays');
			$output .= $this->generate_scss_catch_block('theme-colors');

			foreach($this->fields as $k => $v) {
				$output .= '$' . $k . ': ' . $v . ';' . "\n";
			}
		}

		$this->write($output);
	}

	public function generate_scss_catch_block($block = '') {
		$output = '';

		// catch block items
		$grays_header = '$' . $block . ': () !default;' . "\n" . '$' . $block . ': map-merge((' . "\n";
		foreach($this->fields as $k => $v) {
			if(strpos($k, $block) !== false) {
				$output .= '"' . str_replace($block . '_', '', $k) . '": ' . $v . ',' . "\n";
				unset($this->fields[$k]);
			}
		}
		$grays_footer = '), $' . $block . ');' . "\n";

		// merge block
		if($output) {
			$output = $grays_header . $output . $grays_footer;
		}

		return $output;
	}

	public function write($content) {
		global $wp_filesystem;

		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		if ( ! $wp_filesystem->put_contents($this->get_file_path(), $content, FS_CHMOD_FILE ) ) {
			// Fail!
			error_log('AT: Cannot create Kriki scss file. (path: ' . $this->get_file_path() . ')');
			return false;
		}
	}
}

$xCORE_Custom_SCSS = new xCORE_Custom_SCSS();