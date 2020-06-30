<?php
/**
 * Kirki Sass Compiler
 *
 * Create a CSS file based on a SCSS-file and Kirki variables
 * File gets saved in the public folder with a cache buster.
 */
$variables = Kirki::$fields;

//xcore_debug($variables);

class xCORE_Custom_SCSS
{
    private $folder;
    private $filename;
    private $variables;

    public function __construct ()
    {
        $this->folder    = 'kirki';
        $this->filename  = 'kirki.scss';
        $this->variables = Kirki_Util::get_variables();

        add_action( 'customize_save_after', array( $this, 'generate_scss' ) );
    }

    public function get_file_path ()
    {
        $theme_dir = get_template_directory() . '/assets/sass';
        $css_url   = trailingslashit( $theme_dir ) . $this->folder . '/' . $this->filename;

        return $css_url;
    }

    public function generate_scss ()
    {
        $output = '';

        if ( $this->variables ) {
            // catch blocks
            $output .= $this->generate_scss_catch_block( 'grays' );
            $output .= $this->generate_scss_catch_block( 'theme-colors' );

            foreach ( $this->variables as $k => $v ) {
                $output .= '$' . $k . ': ' . $v . ';' . "\n";
            }
        }

        $this->write( $output );
    }

    public function generate_scss_catch_block ( $block = '' )
    {
        $output = '';

        // catch block items
        $grays_header = '$' . $block . ': () !default; $' . $block . ': map-merge((' . "\n";
        foreach ( $this->variables as $k => $v ) {
            if ( strpos( $k, $block ) !== false ) {
                $output .= '"' . str_replace( $block . '-', '', $k ) . '": ' . $v . ',' . "\n";
                unset( $this->variables[$k] );
            }
        }
        $grays_footer = '), $' . $block . ');' . "\n";

        // merge block
        if ( $output ) {
            $output = $grays_header . $output . $grays_footer;
        }

        return $output;
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

$Kirki_SCSS = new Kirki_SCSS();