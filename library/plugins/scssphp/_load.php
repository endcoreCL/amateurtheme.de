<?php
include_once 'core/scss.inc.php';
include_once 'class.xCORE_SCSS.php';

$xcore_scss_settings = array(
    'scss_dir'      =>  get_template_directory() . '/assets/sass/',
    'css_dir'       =>  get_template_directory() . '/',
    'compiling'     =>  'Leafo\ScssPhp\Formatter\Expanded',
    'errors'        =>  'show-logged-in',
    'enqueue'       =>  0,
    'development'   =>  false
);

$xcore_scss_compiler = new xCORE_SCSS(
    $xcore_scss_settings['scss_dir'],
    $xcore_scss_settings['css_dir'],
    $xcore_scss_settings['compiling'],
    $xcore_scss_settings['development']
);

add_action('wp_head', 'xcore_scss_needs_compiling');
function xcore_scss_needs_compiling() {
    global $xcore_scss_compiler;
    $needs_compiling = apply_filters('xcore_scss_needs_compiling', $xcore_scss_compiler->needs_compiling());
    if ( $needs_compiling ) {
        xcore_scss_compile();
        xcore_scss_handle_errors();
    }
}

function xcore_scss_compile() {
    global $xcore_scss_compiler;
    $variables = apply_filters('xcore_scss_variables', array());
    foreach ($variables as $variable_key => $variable_value) {
        if (strlen(trim($variable_value)) == 0) {
            unset($variables[$variable_key]);
        }
    }
    $xcore_scss_compiler->set_variables($variables);
    $xcore_scss_compiler->compile();
}

function xcore_scss_settings_show_errors($errors) {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<h6>Sass Compiling Error</h6>';

    foreach( $errors as $error) {
        echo '<p class="sass_error">';
        echo '<strong>'. $error['file'] .'</strong> <br/><em>"'. $error['message'] .'"</em>';
        echo '<p class="sass_error">';
    }

    echo '</div>';

    add_action('wp_print_styles', 'xcore_scss_error_styles');
}

function xcore_scss_handle_errors() {
    global $xcore_scss_compiler;
    if ( !is_admin() && !empty($_COOKIE[LOGGED_IN_COOKIE]) && count($xcore_scss_compiler->compile_errors) > 0) {
        xcore_scss_settings_show_errors($xcore_scss_compiler->compile_errors);
    }
}