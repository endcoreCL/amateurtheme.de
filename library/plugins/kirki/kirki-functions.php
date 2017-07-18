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
            'background-color'      => esc_attr__( 'Hintergrundfarbe', 'amateurtheme' ),
            'background-image'      => esc_attr__( 'Hintergrundbild', 'amateurtheme' ),
            'no-repeat'             => esc_attr__( 'No Repeat', 'amateurtheme' ),
            'repeat-all'            => esc_attr__( 'Repeat All', 'amateurtheme' ),
            'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'amateurtheme' ),
            'repeat-y'              => esc_attr__( 'Repeat Vertically', 'amateurtheme' ),
            'inherit'               => esc_attr__( 'Inherit', 'amateurtheme' ),
            'background-repeat'     => esc_attr__( 'Background Repeat', 'amateurtheme' ),
            'cover'                 => esc_attr__( 'Cover', 'amateurtheme' ),
            'contain'               => esc_attr__( 'Contain', 'amateurtheme' ),
            'background-size'       => esc_attr__( 'Background Size', 'amateurtheme' ),
            'fixed'                 => esc_attr__( 'Fixed', 'amateurtheme' ),
            'scroll'                => esc_attr__( 'Scroll', 'amateurtheme' ),
            'background-attachment' => esc_attr__( 'Background Attachment', 'amateurtheme' ),
            'left-top'              => esc_attr__( 'Left Top', 'amateurtheme' ),
            'left-center'           => esc_attr__( 'Left Center', 'amateurtheme' ),
            'left-bottom'           => esc_attr__( 'Left Bottom', 'amateurtheme' ),
            'right-top'             => esc_attr__( 'Right Top', 'amateurtheme' ),
            'right-center'          => esc_attr__( 'Right Center', 'amateurtheme' ),
            'right-bottom'          => esc_attr__( 'Right Bottom', 'amateurtheme' ),
            'center-top'            => esc_attr__( 'Center Top', 'amateurtheme' ),
            'center-center'         => esc_attr__( 'Center Center', 'amateurtheme' ),
            'center-bottom'         => esc_attr__( 'Center Bottom', 'amateurtheme' ),
            'background-position'   => esc_attr__( 'Background Position', 'amateurtheme' ),
            'background-opacity'    => esc_attr__( 'Background Opacity', 'amateurtheme' ),
            'on'                    => esc_attr__( 'An', 'amateurtheme' ),
            'off'                   => esc_attr__( 'Aus', 'amateurtheme' ),
            'all'                   => esc_attr__( 'All', 'amateurtheme' ),
            'cyrillic'              => esc_attr__( 'Cyrillic', 'amateurtheme' ),
            'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'amateurtheme' ),
            'devanagari'            => esc_attr__( 'Devanagari', 'amateurtheme' ),
            'greek'                 => esc_attr__( 'Greek', 'amateurtheme' ),
            'greek-ext'             => esc_attr__( 'Greek Extended', 'amateurtheme' ),
            'khmer'                 => esc_attr__( 'Khmer', 'amateurtheme' ),
            'latin'                 => esc_attr__( 'Latin', 'amateurtheme' ),
            'latin-ext'             => esc_attr__( 'Latin Extended', 'amateurtheme' ),
            'vietnamese'            => esc_attr__( 'Vietnamese', 'amateurtheme' ),
            'hebrew'                => esc_attr__( 'Hebrew', 'amateurtheme' ),
            'arabic'                => esc_attr__( 'Arabic', 'amateurtheme' ),
            'bengali'               => esc_attr__( 'Bengali', 'amateurtheme' ),
            'gujarati'              => esc_attr__( 'Gujarati', 'amateurtheme' ),
            'tamil'                 => esc_attr__( 'Tamil', 'amateurtheme' ),
            'telugu'                => esc_attr__( 'Telugu', 'amateurtheme' ),
            'thai'                  => esc_attr__( 'Thai', 'amateurtheme' ),
            'serif'                 => _x( 'Serif', 'font style', 'amateurtheme' ),
            'sans-serif'            => _x( 'Sans Serif', 'font style', 'amateurtheme' ),
            'monospace'             => _x( 'Monospace', 'font style', 'amateurtheme' ),
            'font-family'           => esc_attr__( 'Schriftart', 'amateurtheme' ),
            'font-size'             => esc_attr__( 'Schriftgröße', 'amateurtheme' ),
            'font-weight'           => esc_attr__( 'Schriftstärke', 'amateurtheme' ),
            'line-height'           => esc_attr__( 'Lininehöhe', 'amateurtheme' ),
            'font-style'            => esc_attr__( 'Schriftstil', 'amateurtheme' ),
            'letter-spacing'        => esc_attr__( 'Zeichenabstand', 'amateurtheme' ),
            'top'                   => esc_attr__( 'Top', 'amateurtheme' ),
            'bottom'                => esc_attr__( 'Bottom', 'amateurtheme' ),
            'left'                  => esc_attr__( 'Left', 'amateurtheme' ),
            'right'                 => esc_attr__( 'Right', 'amateurtheme' ),
            'color'                 => esc_attr__( 'Schriftfarbe', 'amateurtheme' ),
            'add-image'             => esc_attr__( 'Add Image', 'amateurtheme' ),
            'change-image'          => esc_attr__( 'Change Image', 'amateurtheme' ),
            'remove'                => esc_attr__( 'Remove', 'amateurtheme' ),
            'no-image-selected'     => esc_attr__( 'No Image Selected', 'amateurtheme' ),
            'select-font-family'    => esc_attr__( 'Schriftart wählen', 'amateurtheme' ),
            'variant'               => esc_attr__( 'Varianten', 'amateurtheme' ),
            'subsets'               => esc_attr__( 'Subset', 'amateurtheme' ),
            'size'                  => esc_attr__( 'Größe', 'amateurtheme' ),
            'height'                => esc_attr__( 'Height', 'amateurtheme' ),
            'spacing'               => esc_attr__( 'Spacing', 'amateurtheme' ),
            'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'amateurtheme' ),
            'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'amateurtheme' ),
            'light'                 => esc_attr__( 'Light 200', 'amateurtheme' ),
            'light-italic'          => esc_attr__( 'Light 200 Italic', 'amateurtheme' ),
            'book'                  => esc_attr__( 'Book 300', 'amateurtheme' ),
            'book-italic'           => esc_attr__( 'Book 300 Italic', 'amateurtheme' ),
            'regular'               => esc_attr__( 'Normal 400', 'amateurtheme' ),
            'italic'                => esc_attr__( 'Normal 400 Italic', 'amateurtheme' ),
            'medium'                => esc_attr__( 'Medium 500', 'amateurtheme' ),
            'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'amateurtheme' ),
            'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'amateurtheme' ),
            'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'amateurtheme' ),
            'bold'                  => esc_attr__( 'Bold 700', 'amateurtheme' ),
            'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'amateurtheme' ),
            'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'amateurtheme' ),
            'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'amateurtheme' ),
            'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'amateurtheme' ),
            'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'amateurtheme' ),
            'invalid-value'         => esc_attr__( 'Invalid Value', 'amateurtheme' ),
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