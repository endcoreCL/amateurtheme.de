<?php

class xCORE_Custom_SCSS
{
    private $folder;
    private $filename;
    private $variables;

    public function __construct ()
    {
        $this->folder   = 'sass';
        $this->filename = '_custom.scss';
        $this->fields   = $this->get_fields();

        add_action( 'acf/save_post', array( $this, 'generate_scss' ) );
    }

    public function get_file_path ()
    {
        $theme_dir = get_template_directory() . '/assets';
        $css_url   = trailingslashit( $theme_dir ) . $this->folder . '/' . $this->filename;

        return $css_url;
    }

    public function get_fields ()
    {
        $acf_fields = get_field_objects( 'options' );
        $fields     = array();

        if ( $acf_fields ) {
            // filter for needed fields
            $catch = array(
                'colors_',
                'typography_'
            );

            foreach ( $acf_fields as $k => $v ) {
                $match = ( str_replace( $catch, '', $k ) != $k );
                if ( $match ) {
                    $name = $k;
                    // clean the name
                    foreach ( $catch as $c ) {
                        if ( strpos( $name, $c ) === 0 ) {
                            $name = substr( $k, strlen( $c ) );
                        }
                    }
                    $fields[$name] = ( isset( $v['value'] ) ? $v['value'] : $v['default_value'] );
                }
            }
        }

        return $fields;
    }

    public function font_weight ( $font )
    {
        $weight = $font['variants'];
        $weight = preg_replace( '/[A-Za-z]+/', '', $weight );

        if ( $weight == 'regular' || $weight == '' ) {
            $weight = 400;
        }

        return $weight;
    }

    public function font_style ( $font )
    {
        $style = $font['variants'];
        $style = preg_replace( '/[0-9]+/', '', $style );

        if ( $style == 'regular' || $style == '' ) {
            return 'normal';
        }

        return $style;
    }

    public function generate_scss ( $post_id )
    {
        if ( $post_id != 'options' ) {
            return;
        }

        $recompile = $_POST['acf']['field_5e4e6e37256e8'];

        if ( ! $recompile ) {
            return;
        }

        $this->fields = $this->get_fields();

        ob_start();
        if ( $this->fields ) {
            ?>
            // VARIABLES: GRAYS
            $white:       #ffffff;
            $gray-100:    <?php echo $this->fields['grays_gray-100']; ?>;   // near white
            $gray-200:    darken($gray-100, 5%);                            // border
            $gray-300:    <?php echo $this->fields['grays_gray-300']; ?>;
            $gray-400:    darken($gray-300, 5%);
            $gray-500:    darken($gray-300, 20%);
            $gray-600:    <?php echo $this->fields['grays_gray-600']; ?>;   // gray-light
            $gray-700:    <?php echo $this->fields['grays_gray-700']; ?>;   // gray
            $gray-800:    <?php echo $this->fields['grays_gray-800']; ?>;   // gray-dark
            $gray-900:    darken($gray-800, 5%);
            $black:       darken($gray-800, 10%);

            // VARIABLES: COLORS
            $primary:      <?php echo $this->fields['theme-colors_primary']; ?>;
            $secondary:    <?php echo $this->fields['theme-colors_secondary']; ?>;
            $success:      <?php echo $this->fields['theme-colors_success']; ?>;
            $info:         <?php echo $this->fields['theme-colors_info']; ?>;
            $warning:      <?php echo $this->fields['theme-colors_warning']; ?>;
            $danger:       <?php echo $this->fields['theme-colors_danger']; ?>;
            $light:        $gray-100;
            $dark:         $gray-800;

            // VARIABLES: OPTIONS
            $enable-rounded:        true <?php // @TODO NEUES JA/NEIN FIELD - true ist default (gibt an, ob buttons, alerts, etc. rund sein sollen oder eckig) ?>;
            $enable-shadows:        false <?php // @TODO NEUES JA/NEIN FIELD - false ist default (gibt an, ob bestimmte Elemente einen Shadow bekomme - kp wo) ?>;
            $enable-gradients:      false <?php // @TODO NEUES JA/NEIN FIELD - false ist default (gibt an, ob farbige Elemente einen Verlauf bekommen. ?>;
            $enable-transitions:    true <?php // @TODO NEUES JA/NEIN FIELD - true ist default (gibt an, ob div. Elemente eine Verzögerung haben oder nicht) ?>;

            // VARIABLES: FONTS (BASE)
            $font-family-base:      '<?php echo $this->fields['font_paragraphs']['font']; ?>';
            $font-weight-normal:    <?php echo $this->font_weight( $this->fields['font_paragraphs'] ); ?>;
            $line-height-base:      1.75 <?php // @TODO NEUES NUMMERN FIELD - 1.75 ist default ?>;
            $at-font-color-base:    <?php echo $this->fields['color_paragraphs']; ?>;
            $at-font-style-base:    <?php echo $this->font_style( $this->fields['font_paragraphs'] ); ?>;

            // VARIABLES: FONTS (HEADLINES)
            $headings-font-family:      '<?php echo $this->fields['font_headlines']['font']; ?>';
            $headings-font-weight:      <?php echo $this->font_weight( $this->fields['font_headlines'] ); ?>;
            $headings-line-height:      1.25 <?php // @TODO NEUES NUMMERN FIELD - 1.25 ist default ?>;
            $headings-color:            <?php echo $this->fields['color_headlines']; ?>;
            $at-headings-font-style:    <?php echo $this->font_style( $this->fields['font_headlines'] ); ?>;

            // VARIABLES: CUSTOM
            $at-body-bg:    #e9ecef <?php // @TODO NEUES COLOR FIELD - e9ecef ist default ?>;
            $box-shadow:    0 5px 15px rgba($gray-800, 0.15);
            $transition:    all 0.25s; <?php // @TODO NEUES JA/NEIN FIELD ?>
            $border-radius:    5px;  <?php // @TODO NEUES JA/NEIN FIELD ?>
            <?php
        }
        $output = ob_get_clean();

        // reset recompile setting
        update_field( 'field_5e4e6e37256e8', 0, 'options' );

        $this->write( $output );
    }

    public function write ( $content )
    {
        global $wp_filesystem;

        if ( empty( $wp_filesystem ) ) {
            require_once( ABSPATH . '/wp-admin/includes/file.php' );
            WP_Filesystem();
        }

        if ( ! $wp_filesystem->put_contents( $this->get_file_path(), $content, FS_CHMOD_FILE ) ) {
            // Fail!
            error_log( 'AT: Cannot create Kriki scss file. (path: ' . $this->get_file_path() . ')' );

            return false;
        }
    }
}

$xCORE_Custom_SCSS = new xCORE_Custom_SCSS();