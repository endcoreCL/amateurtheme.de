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
				'colors_',
                'typography_'
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

	public function font_weight($font) {
	    $weight = $font['variants'];
		$weight = preg_replace('/[A-Za-z]+/', '', $weight);

	    if($weight == 'regular' || $weight == '') {
	        $weight = 400;
        }

        return $weight;
    }

	public function font_style($font) {
		$style = $font['variants'];
		$style = preg_replace('/[0-9]+/', '', $style);

		if($style == 'regular' || $style == '') {
			return 'normal';
		}

		return $style;
	}

	public function generate_scss() {
		ob_start();
		if($this->fields) {
			?>
$white:         #ffffff;
$gray-100:      <?php echo $this->fields['grays_gray-100']; ?>;
$gray-200:      darken($gray-100, 5%);
$gray-300:      <?php echo $this->fields['grays_gray-300']; ?>;
$gray-400:      darken($gray-300, 5%);
$gray-500:      darken($gray-300, 20%);
$gray-600:      <?php echo $this->fields['grays_gray-600']; ?>;
$gray-700:      <?php echo $this->fields['grays_gray-700']; ?>;
$gray-800:      <?php echo $this->fields['grays_gray-800']; ?>;
$gray-900:      darken($gray-800, 5%);
$black:         darken($gray-800, 10%);

$primary:       <?php echo $this->fields['theme-colors_primary']; ?>;
$secondary:     <?php echo $this->fields['theme-colors_secondary']; ?>;
$success:       <?php echo $this->fields['theme-colors_success']; ?>;
$info:          <?php echo $this->fields['theme-colors_info']; ?>;
$warning:       <?php echo $this->fields['theme-colors_warning']; ?>;
$danger:        <?php echo $this->fields['theme-colors_danger']; ?>;
$light:         $gray-100;
$dark:          $gray-800;

$headings-font-family: '<?php echo $this->fields['font_headlines']['font']; ?>';
$headings-font-weight: <?php echo $this->font_weight($this->fields['font_headlines']); ?>;
$headings-color: <?php echo $this->fields['color_headlines']; ?>;
$at-headings-font-style: <?php echo $this->font_style($this->fields['font_headlines']); ?>;
$font-family-base: '<?php echo $this->fields['font_paragraphs']['font']; ?>';
$font-weight-normal: <?php echo $this->font_weight($this->fields['font_paragraphs']); ?>;
$at-font-color-base: <?php echo $this->fields['color_paragraphs']; ?>;
$at-font-style-base: <?php echo $this->font_style($this->fields['font_paragraphs']); ?>;
			<?php
		}
		$output = ob_get_clean();

		$this->write($output);
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