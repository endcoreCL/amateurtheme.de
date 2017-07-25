<?php
/**
 * Kirki Funktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	kirki
 */

add_filter( 'kirki/config', 'at_customizer_config' );
function at_customizer_config() {
    $args = array(
        'logo_image'   => 'https://datingtheme.io/cdn/customize.png',
        'url_path' => get_template_directory_uri() . '/library/plugins/kirki/core/',
        'i18n' => array(
            'background-color'      => esc_attr__( 'Hintergrundfarbe', 'datingtheme' ),
            'background-image'      => esc_attr__( 'Hintergrundbild', 'datingtheme' ),
            'no-repeat'             => esc_attr__( 'No Repeat', 'datingtheme' ),
            'repeat-all'            => esc_attr__( 'Repeat All', 'datingtheme' ),
            'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'datingtheme' ),
            'repeat-y'              => esc_attr__( 'Repeat Vertically', 'datingtheme' ),
            'inherit'               => esc_attr__( 'Inherit', 'datingtheme' ),
            'background-repeat'     => esc_attr__( 'Background Repeat', 'datingtheme' ),
            'cover'                 => esc_attr__( 'Cover', 'datingtheme' ),
            'contain'               => esc_attr__( 'Contain', 'datingtheme' ),
            'background-size'       => esc_attr__( 'Background Size', 'datingtheme' ),
            'fixed'                 => esc_attr__( 'Fixed', 'datingtheme' ),
            'scroll'                => esc_attr__( 'Scroll', 'datingtheme' ),
            'background-attachment' => esc_attr__( 'Background Attachment', 'datingtheme' ),
            'left-top'              => esc_attr__( 'Left Top', 'datingtheme' ),
            'left-center'           => esc_attr__( 'Left Center', 'datingtheme' ),
            'left-bottom'           => esc_attr__( 'Left Bottom', 'datingtheme' ),
            'right-top'             => esc_attr__( 'Right Top', 'datingtheme' ),
            'right-center'          => esc_attr__( 'Right Center', 'datingtheme' ),
            'right-bottom'          => esc_attr__( 'Right Bottom', 'datingtheme' ),
            'center-top'            => esc_attr__( 'Center Top', 'datingtheme' ),
            'center-center'         => esc_attr__( 'Center Center', 'datingtheme' ),
            'center-bottom'         => esc_attr__( 'Center Bottom', 'datingtheme' ),
            'background-position'   => esc_attr__( 'Background Position', 'datingtheme' ),
            'background-opacity'    => esc_attr__( 'Background Opacity', 'datingtheme' ),
            'on'                    => esc_attr__( 'An', 'datingtheme' ),
            'off'                   => esc_attr__( 'Aus', 'datingtheme' ),
            'all'                   => esc_attr__( 'All', 'datingtheme' ),
            'cyrillic'              => esc_attr__( 'Cyrillic', 'datingtheme' ),
            'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'datingtheme' ),
            'devanagari'            => esc_attr__( 'Devanagari', 'datingtheme' ),
            'greek'                 => esc_attr__( 'Greek', 'datingtheme' ),
            'greek-ext'             => esc_attr__( 'Greek Extended', 'datingtheme' ),
            'khmer'                 => esc_attr__( 'Khmer', 'datingtheme' ),
            'latin'                 => esc_attr__( 'Latin', 'datingtheme' ),
            'latin-ext'             => esc_attr__( 'Latin Extended', 'datingtheme' ),
            'vietnamese'            => esc_attr__( 'Vietnamese', 'datingtheme' ),
            'hebrew'                => esc_attr__( 'Hebrew', 'datingtheme' ),
            'arabic'                => esc_attr__( 'Arabic', 'datingtheme' ),
            'bengali'               => esc_attr__( 'Bengali', 'datingtheme' ),
            'gujarati'              => esc_attr__( 'Gujarati', 'datingtheme' ),
            'tamil'                 => esc_attr__( 'Tamil', 'datingtheme' ),
            'telugu'                => esc_attr__( 'Telugu', 'datingtheme' ),
            'thai'                  => esc_attr__( 'Thai', 'datingtheme' ),
            'serif'                 => _x( 'Serif', 'font style', 'datingtheme' ),
            'sans-serif'            => _x( 'Sans Serif', 'font style', 'datingtheme' ),
            'monospace'             => _x( 'Monospace', 'font style', 'datingtheme' ),
            'font-family'           => esc_attr__( 'Schriftart', 'datingtheme' ),
            'font-size'             => esc_attr__( 'Schriftgröße', 'datingtheme' ),
            'font-weight'           => esc_attr__( 'Schriftstärke', 'datingtheme' ),
            'line-height'           => esc_attr__( 'Lininehöhe', 'datingtheme' ),
            'font-style'            => esc_attr__( 'Schriftstil', 'datingtheme' ),
            'letter-spacing'        => esc_attr__( 'Zeichenabstand', 'datingtheme' ),
            'top'                   => esc_attr__( 'Top', 'datingtheme' ),
            'bottom'                => esc_attr__( 'Bottom', 'datingtheme' ),
            'left'                  => esc_attr__( 'Left', 'datingtheme' ),
            'right'                 => esc_attr__( 'Right', 'datingtheme' ),
            'color'                 => esc_attr__( 'Schriftfarbe', 'datingtheme' ),
            'add-image'             => esc_attr__( 'Add Image', 'datingtheme' ),
            'change-image'          => esc_attr__( 'Change Image', 'datingtheme' ),
            'remove'                => esc_attr__( 'Remove', 'datingtheme' ),
            'no-image-selected'     => esc_attr__( 'No Image Selected', 'datingtheme' ),
            'select-font-family'    => esc_attr__( 'Schriftart wählen', 'datingtheme' ),
            'variant'               => esc_attr__( 'Varianten', 'datingtheme' ),
            'subsets'               => esc_attr__( 'Subset', 'datingtheme' ),
            'size'                  => esc_attr__( 'Größe', 'datingtheme' ),
            'height'                => esc_attr__( 'Height', 'datingtheme' ),
            'spacing'               => esc_attr__( 'Spacing', 'datingtheme' ),
            'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'datingtheme' ),
            'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'datingtheme' ),
            'light'                 => esc_attr__( 'Light 200', 'datingtheme' ),
            'light-italic'          => esc_attr__( 'Light 200 Italic', 'datingtheme' ),
            'book'                  => esc_attr__( 'Book 300', 'datingtheme' ),
            'book-italic'           => esc_attr__( 'Book 300 Italic', 'datingtheme' ),
            'regular'               => esc_attr__( 'Normal 400', 'datingtheme' ),
            'italic'                => esc_attr__( 'Normal 400 Italic', 'datingtheme' ),
            'medium'                => esc_attr__( 'Medium 500', 'datingtheme' ),
            'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'datingtheme' ),
            'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'datingtheme' ),
            'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'datingtheme' ),
            'bold'                  => esc_attr__( 'Bold 700', 'datingtheme' ),
            'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'datingtheme' ),
            'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'datingtheme' ),
            'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'datingtheme' ),
            'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'datingtheme' ),
            'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'datingtheme' ),
            'invalid-value'         => esc_attr__( 'Invalid Value', 'datingtheme' ),
            'reset-with-icon'       => sprintf( esc_attr__( '%s Reset', 'kirki' ), '<span class="dashicons dashicons-image-rotate"></span>' )
        )
    );

    return $args;
}

add_action( 'customize_register', 'at_clean_customizer' );
function at_clean_customizer( $wp_customize ) {
    $wp_customize->remove_panel('widgets');
    $wp_customize->remove_section('nav');
    $wp_customize->remove_section('title_tagline');
    $wp_customize->remove_section('static_front_page');
}

//add_action('wp_footer', 'at_kirki_preview_css');
function at_kirki_preview_css() {
    if(is_customize_preview()) {
        echo '
        <style>
            .kirki-customizer-loading-wrapper {  background-image: url("' . get_template_directory_uri() . '/_/img/loading.gif") !important; }
            .kirki-customizer-loading-wrapper .kirki-customizer-loading { background: none !important; width: 64px !important; height: 64px !important; margin: -32px !important; -webkit-animation: none !important; animation: none !important; }
        </style>
        ';
    }
}