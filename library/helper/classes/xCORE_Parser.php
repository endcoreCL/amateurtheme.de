<?php
/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 09.02.18
 * Time: 09:45
 */
class xCORE_Parser {
    private $stylesheet;
    private $theme_root;
    private $cache_hash;
    private static $cache_expiration = 1800;

    public function __construct() {
        $this->stylesheet = get_stylesheet();
        $this->theme_root = WP_CONTENT_DIR . '/themes';
        $this->cache_hash = md5( $this->theme_root . '/' . $this->stylesheet );
    }

    private function cache_get( $key ) {
        return wp_cache_get( $key . '-' . $this->cache_hash, 'themes' );
    }

    private function cache_add( $key, $data ) {
        return wp_cache_add( $key . '-' . $this->cache_hash, $data, 'themes', self::$cache_expiration );
    }

    public function get_topbar_templates() {
        $parts = 'design/topbar';
        $this->stylesheet .= '/' . $parts;
        $wp_theme = new WP_Theme($this->stylesheet, $this->theme_root);

        $templates = $this->cache_get( 'topbar_templates' );

        if ( ! is_array( $templates ) ) {
            $templates = array();
            $files = (array) $wp_theme->get_files( 'php', 3, true);

            foreach ( $files as $file => $full_path ) {
                if ( ! preg_match( '|Topbar Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
                    continue;
                }

                $templates[ $parts . '/' . $file ] = _cleanup_header_comment( $header[1] );
            }

            $this->cache_add( 'topbar_templates', $templates );
        }

        return $templates;
    }

    public function get_header_templates() {
        $parts = 'design/header';
        $this->stylesheet .= '/' . $parts;
        $wp_theme = new WP_Theme($this->stylesheet, $this->theme_root);

        $templates = $this->cache_get( 'header_templates' );

        if ( ! is_array( $templates ) ) {
            $templates = array();
            $files = (array) $wp_theme->get_files( 'php', 3, true);

            foreach ( $files as $file => $full_path ) {
                if ( ! preg_match( '|Header Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
                    continue;
                }

                $templates[ $parts . '/' . $file ] = _cleanup_header_comment( $header[1] );
            }

            $this->cache_add( 'header_templates', $templates );
        }

        return $templates;
    }

    public function get_teaser_templates() {
        $parts = 'design/teaser';
        $this->stylesheet .= '/' . $parts;
        $wp_theme = new WP_Theme($this->stylesheet, $this->theme_root);

        $templates = $this->cache_get( 'teaser_templates' );

        if ( ! is_array( $templates ) ) {
            $templates = array();
            $files = (array) $wp_theme->get_files( 'php', 3, true);

            foreach ( $files as $file => $full_path ) {
                if ( ! preg_match( '|Teaser Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
                    continue;
                }

                $templates[ $parts . '/' . $file ] = _cleanup_header_comment( $header[1] );
            }

            $this->cache_add( 'teaser_templates', $templates );
        }

        return $templates;
    }

    public function get_content_templates() {
        $parts = 'design/content';
        $this->stylesheet .= '/' . $parts;
        $wp_theme = new WP_Theme($this->stylesheet, $this->theme_root);

        $templates = $this->cache_get( 'content_templates' );

        if ( ! is_array( $templates ) ) {
            $templates = array();
            $files = (array) $wp_theme->get_files( 'php', 3, true);

            foreach ( $files as $file => $full_path ) {
                if ( ! preg_match( '|Content Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
                    continue;
                }

                $templates[ $parts . '/' . $file ] = _cleanup_header_comment( $header[1] );
            }

            $this->cache_add( 'content_templates', $templates );
        }

        return $templates;
    }

    public function get_footer_templates() {
        $parts = 'design/footer';
        $this->stylesheet .= '/' . $parts;
        $wp_theme = new WP_Theme($this->stylesheet, $this->theme_root);

        $templates = $this->cache_get( 'footer_templates' );

        if ( ! is_array( $templates ) ) {
            $templates = array();
            $files = (array) $wp_theme->get_files( 'php', 3, true);

            foreach ( $files as $file => $full_path ) {
                if ( ! preg_match( '|Footer Name:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
                    continue;
                }

                $templates[ $parts . '/' . $file ] = _cleanup_header_comment( $header[1] );
            }

            $this->cache_add( 'footer_templates', $templates );
        }

        return $templates;
    }
}