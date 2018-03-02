<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 09.02.18
 * Time: 14:25
 */
class xCORE_Layout {
    var $template_path = '';

    function __construct() {
        $this->template_path = get_template_directory();
    }

    /**
     * Function to locate templates
     *
     * @param $template_name
     * @return mixed
     */
    private function locate_template( $template_name ) {
        $template = locate_template(
            array(
                trailingslashit( $this->template_path ) . $template_name,
                $template_name,
            )
        );

        return $template;
    }

    /**
     * Function to get template
     *
     * @param $template_name
     * @param array $args
     */
    private function get_template($template_name, $args = array()) {
        if ( ! empty( $args ) && is_array( $args ) ) {
            extract( $args ); // @codingStandardsIgnoreLine
        }

        $located = $this->locate_template( $template_name );

        if ( ! file_exists( $located ) ) {
            return;
        }


        include $located;
    }

    /**
     * Function to get template as html
     *
     * @param $template_name
     * @param array $args
     * @return string
     */
    private function get_template_html($template_name, $args = array()) {
        ob_start();
        $this->get_template($template_name, $args);
        return ob_get_clean();
    }

    public function option($type = '', $field = '', $default = false) {
        $value = get_field('design_' . $type . '_' . $field, 'options');

        if($value) {
            return $value;
        }

        return $default;
    }

    public function wrapper_class($type = '', $field, $default = '') {
        $value = $this->option($type, $field, $default);

        if($value == 'fixed') {
            return 'wrapped';
        }

        return false;
    }

    public function container_class($type = '', $field, $default = '') {
        $value = $this->option($type, $field, $default);

        if($value && $value == 'fluid') {
            return 'container-fluid';
        }

        return 'container';
    }

    /**
     * Function to get topbar layout
     *
     * @param bool $output
     * @return bool|string
     */
    public function topbar($output = true) {
        $layout = get_field('design_layout_topbar_style', 'options');

        if($layout) {
            if($output) {
                return $this->get_template_html($layout);
            } else {
                return true;
            }
        }

        return false;
    }
}