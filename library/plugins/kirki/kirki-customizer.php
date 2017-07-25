<?php
/**
 * Kirki Customizer
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	kirki
 */

add_action( 'customize_register', 'at_customizer_sections' );
function at_customizer_sections( $wp_customize ) {
    /**
     * Add panels
     */
    $wp_customize->add_section( 'general', array(
        'priority'    => 10,
        'title'       => __( 'Allgemein', 'kirki' ),
    ) );

    $wp_customize->add_panel( 'typography', array(
        'priority'    => 20,
        'title'       => __( 'Typografie', 'kirki' ),
    ) );

    $wp_customize->add_section( 'topbar', array(
        'priority'    => 30,
        'title'       => __( 'Topbar', 'kirki' ),
    ) );

    $wp_customize->add_panel( 'navbar', array(
        'priority'    => 40,
        'title'       => __( 'Navigation', 'kirki' ),
    ) );

    $wp_customize->add_panel( 'sidebar', array(
        'priority'    => 50,
        'title'       => __( 'Sidebar', 'kirki' ),
    ) );

    $wp_customize->add_panel( 'footer', array(
        'priority'    => 60,
        'title'       => __( 'Footer', 'kirki' ),
    ) );


    /**
     * Add sections
     */
    $wp_customize->add_section( 'typography_headline', array(
        'panel'       => 'typography',
        'priority'    => 30,
        'title'       => __( 'Überschriften', 'kirki' ),
    ) );

    $wp_customize->add_section( 'typography_text', array(
        'panel'       => 'typography',
        'priority'    => 30,
        'title'       => __( 'Text', 'kirki' ),
    ) );

    $wp_customize->add_section( 'typography_links', array(
        'panel'       => 'typography',
        'priority'    => 31,
        'title'       => __( 'Links', 'kirki' ),
    ) );

    $wp_customize->add_section( 'typography_button_primary', array(
        'panel'       => 'typography',
        'priority'    => 42,
        'title'       => __( 'Button (btn-dt)', 'kirki' ),
    ) );

    $wp_customize->add_section( 'typography_button_secondary', array(
        'panel'       => 'typography',
        'priority'    => 43,
        'title'       => __( 'Button (btn-cta)', 'kirki' ),
    ) );

    $wp_customize->add_section( 'typography_stuff', array(
        'panel'       => 'typography',
        'priority'    => 44,
        'title'       => __( 'Sonstiges', 'kirki' ),
    ) );

    $wp_customize->add_section( 'navbar_general', array(
        'panel'       => 'navbar',
        'priority'    => 45,
        'title'       => __( 'Leiste', 'kirki' ),
    ) );

    $wp_customize->add_section( 'navbar_dropdown', array(
        'panel'       => 'navbar',
        'priority'    => 46,
        'title'       => __( 'Dropdown', 'kirki' ),
    ) );

    $wp_customize->add_section( 'sidebar_general', array(
        'panel'       => 'sidebar',
        'priority'    => 47,
        'title'       => __( 'Allgemein', 'kirki' ),
    ) );

    $wp_customize->add_section( 'sidebar_block', array(
        'panel'       => 'sidebar',
        'priority'    => 48,
        'title'       => __( 'Block', 'kirki' ),
    ) );

    $wp_customize->add_section( 'sidebar_inline', array(
        'panel'       => 'sidebar',
        'priority'    => 49,
        'title'       => __( 'Inline', 'kirki' ),
    ) );

    $wp_customize->add_section( 'breadcrumbs', array(
        'priority'    => 50,
        'title'       => __( 'Breadcrumbs', 'kirki' ),
    ) );

    $wp_customize->add_section( 'footer_top', array(
        'panel'       => 'footer',
        'priority'    => 51,
        'title'       => __( 'Oben', 'kirki' ),
    ) );

    $wp_customize->add_section( 'footer_bottom', array(
        'panel'       => 'footer',
        'priority'    => 52,
        'title'       => __( 'Unten', 'kirki' ),
    ) );
}

add_filter( 'kirki/fields', 'at_customizer_fields' );
function at_customizer_fields( $fields ) {
    /***
     * Allgemein
     */

    $fields[] = array(
        'type'          => 'background',
        'settings'      => 'base_bg',
        'label'         => __( 'Hintergrund', 'kirki' ),
        'section'       => 'general',
        'default'       => array(
            'color'     => '#f9f6f6',
            'image'     => '',
            'repeat'    => 'no-repeat',
            'size'      => 'cover',
            'attach'    => 'fixed',
            'position'  => 'left-top',
        ),
        'priority'      => 10,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => 'body',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
        'output'        => array(
            array(
                'element'   => 'body',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'base_bgw',
        'label'         => __( 'Hintergrund (Wrapper)', 'kirki' ),
        'section'       => 'general',
        'default'       => '#ffffff',
        'priority'      => 11,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#wrapper, #wrapper-fluid',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
        'output'        => array(
            array(
                'element'   => '#wrapper, #wrapper-fluid',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color-alpha',
        'settings'      => 'base_bs',
        'label'         => __( 'Schatten', 'kirki' ),
        'section'       => 'general',
        'default'       => 'rgba(30,25,25,0.10)',
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#wrapper, #wrapper-fluid',
                'function'  => 'css',
                'property'  => 'box-shadow',
                'value_pattern' => '0 0 30px $'
            )
        ),
        'output'        => array(
            array(
                'element'   => '#wrapper, #wrapper-fluid',
                'function'  => 'css',
                'property'  => 'box-shadow',
                'value_pattern' => '0 0 30px $'
            )
        ),
    );

    /**
     * Typografie - Überschriften
     */
    $fields[] = array(
        'type'        => 'typography',
        'settings'     => 'typo_h',
        'label'       => __( 'Überschriften', 'kirki' ),
        'description' => '',
        'help'        => '',
        'section'     => 'typography_headline',
        'default'     => array(
            'font-family'    => 'Hind',
            'variant'        => 'regular',
            'color'          => '#1e1919',
            'text-transform' => 'none',
        ),
        'priority'    => 10,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6',
                'function' => 'css',
            ),
        ),
        'output'      => array(
            array(
                'element' => 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6',
            )
        )
    );

    /**
     * Typografie - Text
     */
    $fields[] = array(
        'type'        => 'typography',
        'settings'     => 'typo_t',
        'label'       => __( 'Texte', 'kirki' ),
        'description' => '',
        'help'        => '',
        'section'     => 'typography_text',
        'default'     => array(
            'font-family'    => 'Open Sans',
            'variant'        => 'regular',
            'color'          => '#645f5f',
            'text-transform' => 'none',
        ),
        'priority'    => 10,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => 'body',
                'function' => 'css',
            ),
            array(
                'element' => '.pagination > li > a, .pagination > li > span',
                'function' => 'css'
            ),
            array(
                'element' => 'div[id*="location-list"] ul li a',
                'function' => 'css'
            )
        ),
        'output'      => array(
            array(
                'element'  => 'body',
                'function' => 'css',
            ),
            array(
                'element' => '.pagination > li > a, .pagination > li > span',
                'function' => 'css'
            ),
            array(
                'element' => 'div[id*="location-list"] ul li a',
                'function' => 'css'
            )
        )
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'typo_t_tc_l',
        'label'         => __( 'Schriftfarbe (hell)', 'kirki' ),
        'section'       => 'typography_text',
        'default'       => '#969191',
        'priority'      => 11,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element' => '.wp-caption-text',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.pagination > .disabled > span, .pagination > .disabled > span:hover, .pagination > .disabled > span:focus, .pagination > .disabled > a, .pagination > .disabled > a:hover, .pagination > .disabled > a:focus',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.pager li > a, .pager li > span',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.post-meta',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.comment .media-heading small',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.comment.bypostauthor:after',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'element' => '.media.contact .media-heading a + a',
                'function' => 'css',
                'property' => 'background-color'
            ),
        ),
        'output'        => array(
            array(
                'element' => '.wp-caption-text',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.pagination > .disabled > span, .pagination > .disabled > span:hover, .pagination > .disabled > span:focus, .pagination > .disabled > a, .pagination > .disabled > a:hover, .pagination > .disabled > a:focus',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.pager li > a, .pager li > span',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.post-meta',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.comment .media-heading small',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'element' => '.comment.bypostauthor:after',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'element' => '.media.contact .media-heading a + a',
                'function' => 'css',
                'property' => 'background-color'
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'typography_text',
        'default'     => array(
            'link'    => '#d31c13',
            'hover'   => '#db4942',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice' => 'link',
                'element' => 'a',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice'   => 'hover',
                'element' => 'a:hover, a:focus, a:active',
                'function' => 'css',
                'property' => 'color'
            ),
        ),
        'output'        => array(
            array(
                'choice' => 'link',
                'element' => 'a',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice'   => 'hover',
                'element' => 'a:hover, a:focus, a:active',
                'function' => 'css',
                'property' => 'color'
            ),
        ),
    );

    /**
     * Typografie - Button (.btn-dt)
     */
    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_btn_p_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'typography_button_primary',
        'default'     => array(
            'normal'    => '#d31c13',
            'hover'   => '#d31c13',
        ),
        'choices'     => array(
            'normal'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-dt',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-dt.btn-outline',
                'function' => 'css',
                'property' => 'border-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-dt.btn-outline, .btn-dt.btn-outline:hover, .btn-dt.btn-outline:focus, .btn-dt.btn-outline:active',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-dt:hover, .btn-dt:focus, .btn-dt:active',
                'function' => 'css',
                'property' => 'background-color'
            )
        ),
        'output'        => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-dt',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-dt.btn-outline',
                'function' => 'css',
                'property' => 'border-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-dt.btn-outline, .btn-dt.btn-outline:hover, .btn-dt.btn-outline:focus, .btn-dt.btn-outline:active',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-dt:hover, .btn-dt:focus, .btn-dt:active',
                'function' => 'css',
                'property' => 'background-color'
            )
        ),
    );

   $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_btn_p_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'typography_button_primary',
        'default'     => array(
            'normal'    => '#ffffff',
            'hover'   => '#ffffff',
        ),
        'choices'     => array(
            'normal'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-dt',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-dt:hover, .btn-dt:focus, .btn-dt:active',
                'function' => 'css',
                'property' => 'color'
            )
        ),
        'output'        => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-dt',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-dt:hover, .btn-dt:focus, .btn-dt:active',
                'function' => 'css',
                'property' => 'color'
            ),
        ),
    );

    /**
     * Typografie - Button (.btn-cta)
     */
    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_btn_s_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'typography_button_secondary',
        'default'     => array(
            'normal'    => '#15a9b4',
            'hover'   => '#43bac3',
        ),
        'choices'     => array(
            'normal'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-cta',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-cta.btn-outline',
                'function' => 'css',
                'property' => 'border-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-cta.btn-outline, .btn-cta.btn-outline:hover, .btn-cta.btn-outline:focus, .btn-cta.btn-outline:active',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.contact .label-online',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-cta:hover, .btn-cta:focus, .btn-cta:active',
                'function' => 'css',
                'property' => 'background-color'
            ),
        ),
        'output'        => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-cta',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-cta.btn-outline',
                'function' => 'css',
                'property' => 'border-color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.btn-cta.btn-outline, .btn-cta.btn-outline:hover, .btn-cta.btn-outline:focus, .btn-cta.btn-outline:active',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'normal',
                'element' => '.contact .label-online',
                'function' => 'css',
                'property' => 'background-color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-cta:hover, .btn-cta:focus, .btn-cta:active',
                'function' => 'css',
                'property' => 'background-color'
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_btn_s_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'typography_button_secondary',
        'default'     => array(
            'normal'    => '#ffffff',
            'hover'   => '#ffffff',
        ),
        'choices'     => array(
            'normal'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 12,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-cta',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-cta:hover, .btn-cta:focus, .btn-cta:active',
                'function' => 'css',
                'property' => 'color'
            ),
        ),
        'output'        => array(
            array(
                'choice' => 'normal',
                'element' => '.btn-cta',
                'function' => 'css',
                'property' => 'color'
            ),
            array(
                'choice' => 'hover',
                'element' => '.btn-cta:hover, .btn-cta:focus, .btn-cta:active',
                'function' => 'css',
                'property' => 'color'
            ),
        ),
    );

    /**
     *  Typografie - Sonstiges
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'custom_1',
        'label'         => __( 'Custom Color #1', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'       => '#d31c13',
        'priority'      => 13,
        'help'          => __('Farbliches Highlight', 'kirki'),
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#header',
                'function'  => 'css',
                'property'  => 'border-top',
                'value_pattern' => '5px solid $'
            ),
            array(
                'element'   => '.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.post > h2 > a:hover, .post > h2 > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.search #content .page > h2 > a:hover, .search #content .page > h2 > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.post-meta a:hover, .post-meta a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading a:hover, .comment .media-heading a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.media.contact .media-heading a:hover, .media.contact .media-heading a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.container > form#signup_form',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '::selection',
                'function'  => 'css',
                'property'  => 'background-color'
            ),
            array(
                'element'   => '::-moz-selection',
                'function'  => 'css',
                'property'  => 'background-color'
            ),
            array(
                'element'   => '.location-list-city-nav li a:hover, .location-list-city-nav li a:focus, .location-list-city-nav li.current a',
                'function'  => 'css',
                'property'  => 'background'
            ),
            array(
                'element'   => '.contact .h2 a:hover, .contact .h2 a:focus',
                'function'  => 'css',
                'property'  => 'color'
            ),
            array(
                'element'   => '#ContactModal .media-body > h5 span',
                'function'  => 'css',
                'property'  => 'color'
            ),
            array(
                'element'   => '.single-location .carousel-caption span',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
        'output'        => array(
            array(
                'element'   => '#header',
                'function'  => 'css',
                'property'  => 'border-top',
                'value_pattern' => '5px solid $'
            ),
            array(
                'element'   => '.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.pagination > .active > a, .pagination > .active > span, .pagination > .active > a:hover, .pagination > .active > span:hover, .pagination > .active > a:focus, .pagination > .active > span:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.post > h2 > a:hover, .post > h2 > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.search #content .page > h2 > a:hover, .search #content .page > h2 > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.post-meta a:hover, .post-meta a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading a:hover, .comment .media-heading a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.media.contact .media-heading a:hover, .media.contact .media-heading a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.container > form#signup_form',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '::selection',
                'function'  => 'css',
                'property'  => 'background-color'
            ),
            array(
                'element'   => '::-moz-selection',
                'function'  => 'css',
                'property'  => 'background-color'
            ),
            array(
                'element'   => '.location-list-city-nav li a:hover, .location-list-city-nav li a:focus, .location-list-city-nav li.current a',
                'function'  => 'css',
                'property'  => 'background'
            ),
            array(
                'element'   => '.contact .h2 a:hover, .contact .h2 a:focus',
                'function'  => 'css',
                'property'  => 'color'
            ),
            array(
                'element'   => '#ContactModal .media-body > h5 span',
                'function'  => 'css',
                'property'  => 'color'
            ),
            array(
                'element'   => '.single-location .carousel-caption span',
                'function'  => 'css',
                'property'  => 'background'
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'custom_2',
        'label'         => __( 'Custom Color #2', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'       => '#1e1919',
        'priority'      => 14,
        'help'          => __('Überschriften im Blog etc.', 'kirki'),
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '.post > h2 > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.search #content .page > h2 > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.pager li > a:hover, .pager li > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .comment-reply-link',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.media.contact .media-heading a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.container > form#signup_form .btn',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.container > form#signup_form .btn',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.location-list-city-nav',
                'function'  => 'css',
                'property'  => 'background',
            ),
            array(
                'element'   => '.contact .h2 a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '#ContactModal .media-body > h5',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '.post > h2 > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.search #content .page > h2 > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.pager li > a:hover, .pager li > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .media-heading a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.comment .comment-reply-link',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.media.contact .media-heading a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.container > form#signup_form .btn',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.container > form#signup_form .btn',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.location-list-city-nav',
                'function'  => 'css',
                'property'  => 'background',
            ),
            array(
                'element'   => '.contact .h2 a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '#ContactModal .media-body > h5',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'custom_bl',
        'label'         => __( 'Custom Black', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'       => '#1e1919',
        'priority'      => 15,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '.btn-black',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.btn-black',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.btn-black.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.btn-black:hover, .btn-black:focus, .btn-black:active, .btn-black:active:hover, .btn-black:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.btn-black:hover, .btn-black:focus, .btn-black:active, .btn-black:active:hover, .btn-black:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '.btn-black',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.btn-black',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.btn-black.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '.btn-black:hover, .btn-black:focus, .btn-black:active, .btn-black:active:hover, .btn-black:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'element'   => '.btn-black:hover, .btn-black:focus, .btn-black:active, .btn-black:active:hover, .btn-black:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'custom_gr',
        'label'         => __( 'Custom Gray', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'     => array(
            'normal'    => '#645f5f',
            'light'     => '#969191',
            'nw'        => '#f9f6f6'
        ),
        'choices'     => array(
            'normal'    => esc_attr__( 'Normal', 'kirki' ),
            'light'   => esc_attr__( 'Light', 'kirki' ),
            'nw'   => esc_attr__( 'Near White', 'kirki' ),
        ),
        'priority'      => 16,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'normal',
                'element'   => '.btn-link, .btn-link:hover, .btn-link:focus, .btn-link:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd:hover, .btn-grayd:focus, .btn-grayd:active, .btn-grayd:active:hover, .btn-grayd:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd:hover, .btn-grayd:focus, .btn-grayd:active, .btn-grayd:active:hover, .btn-grayd:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default:active:hover, .btn-default:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray:hover, .btn-gray:focus, .btn-gray:active, .btn-gray:active:hover, .btn-gray:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray:hover, .btn-gray:focus, .btn-gray:active, .btn-gray:active:hover, .btn-gray:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default:active:hover, .btn-default:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '#ContactModal .media-body > h4',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.pager li > a > small',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.contact .h2 > span',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.pager li > a:hover, .pager li > a:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'normal',
                'element'   => '.btn-link, .btn-link:hover, .btn-link:focus, .btn-link:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd:hover, .btn-grayd:focus, .btn-grayd:active, .btn-grayd:active:hover, .btn-grayd:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayd:hover, .btn-grayd:focus, .btn-grayd:active, .btn-grayd:active:hover, .btn-grayd:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default:active:hover, .btn-default:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray.btn-outline',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray:hover, .btn-gray:focus, .btn-gray:active, .btn-gray:active:hover, .btn-gray:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.btn-gray:hover, .btn-gray:focus, .btn-gray:active, .btn-gray:active:hover, .btn-gray:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-grayl:hover, .btn-grayl:focus, .btn-grayl:active, .btn-grayl:active:hover, .btn-grayl:active:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default:active:hover, .btn-default:active:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '#ContactModal .media-body > h4',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'normal',
                'element'   => '.btn-grayl',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.pager li > a > small',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'light',
                'element'   => '.contact .h2 > span, .contact .h2 > span.contact-city a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'nw',
                'element'   => '.pager li > a:hover, .pager li > a:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'typo_hr',
        'label'         => __( 'Rahmenfarbe', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'       => '#efefef',
        'priority'      => 16,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => 'hr',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.post-large',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.post-small',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.pagination > li > a, .pagination > li > span',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.comment .media-body',
                'function'  => 'css',
                'property'  => 'border',
                'value_pattern' => '2px solid $'
            ),
            array(
                'element'   => '.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.pagination > .disabled > span, .pagination > .disabled > span:hover, .pagination > .disabled > span:focus, .pagination > .disabled > a, .pagination > .disabled > a:hover, .pagination > .disabled > a:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '#sidebar .widget .h1',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '2px solid $'
            ),
            array(
                'element'   => '#sidebar .widget_block ul li a, #sidebar .widget_inline  ul li',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.contact',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.contact-list .media-body .quote, div[id*="location-list"]',
                'function'  => 'css',
                'property'  => 'border',
                'value_pattern' => '2px solid $'
            )
        ),
        'output'        => array(
            array(
                'element'   => 'hr',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.post-large',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.post-small',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.pagination > li > a, .pagination > li > span',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.comment .media-body',
                'function'  => 'css',
                'property'  => 'border',
                'value_pattern' => '2px solid $'
            ),
            array(
                'element'   => '.pagination > li > a:hover, .pagination > li > span:hover, .pagination > li > a:focus, .pagination > li > span:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '.pagination > .disabled > span, .pagination > .disabled > span:hover, .pagination > .disabled > span:focus, .pagination > .disabled > a, .pagination > .disabled > a:hover, .pagination > .disabled > a:focus',
                'function'  => 'css',
                'property'  => 'border-color',
            ),
            array(
                'element'   => '#sidebar .widget .h1',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '2px solid $'
            ),
            array(
                'element'   => '#sidebar .widget_block ul li a, #sidebar .widget_inline  ul li',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.contact',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '.contact-list .media-body .quote, div[id*="location-list"]',
                'function'  => 'css',
                'property'  => 'border',
                'value_pattern' => '2px solid $'
            )
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'typo_sb',
        'label'         => __( 'Social Buttons', 'kirki' ),
        'section'       => 'typography_stuff',
        'default'     => array(
            'background'    => '#c1bfbf',
            'color'     => '#ffffff',
        ),
        'choices'     => array(
            'background'    => esc_attr__( 'Hintergrund', 'kirki' ),
            'color'   => esc_attr__( 'Schriftfarbe', 'kirki' ),
        ),
        'priority'      => 16,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'background',
                'element'   => '.btn-social',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'color',
                'element'   => '.btn-social, .btn-social:hover, .btn-social:focus, .btn-social:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'background',
                'element'   => '.btn-social',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
            array(
                'choice'    => 'color',
                'element'   => '.btn-social, .btn-social:hover, .btn-social:focus, .btn-social:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    /**
     * Topbar
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'tbar_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'topbar',
        'default'       => '#1e1919',
        'priority'      => 17,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#topbar',
                'function'  => 'css',
                'property'  => 'background',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#topbar',
                'function'  => 'css',
                'property'  => 'background',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'tbar_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'topbar',
        'default'       => '#c1bfbf',
        'priority'      => 18,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#topbar',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#topbar',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'tbar_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'topbar',
        'default'     => array(
            'link'    => '#c1bfbf',
            'hover'     => '#ffffff',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 19,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#topbar a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#topbar a:hover, #topbar a:focus, #topbar a:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => 'div[id*="location-list"] ul li a:hover, div[id*="location-list"] ul li a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#topbar a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#topbar a:hover, #topbar a:focus, #topbar a:active',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => 'div[id*="location-list"] ul li a:hover, div[id*="location-list"] ul li a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    /**
     * Navigation
     */
    $fields[] = array(
        'type'        => 'typography',
        'settings'     => 'navi_ff',
        'label'       => __( 'Schriftart', 'kirki' ),
        'description' => '',
        'help'        => '',
        'section'     => 'navbar_general',
        'default'     => array(
            'font-family'    => 'Hind',
            'variant'        => 'regular',
            'text-transform' => 'none',
        ),
        'priority'    => 20,
        'transport' => 'postMessage',
        'js_vars'   => array(
            array(
                'element'  => '#navigation .navbar .navbar-nav > li > a',
                'function' => 'css',
            ),
        ),
        'output'      => array(
            array(
                'element' => '#navigation .navbar .navbar-nav > li > a',
            )
        )
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'navi_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'navbar_general',
        'default'     => array(
            'link'    => '#1e1919',
            'hover'     => '#d31c13',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 21,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#navigation .navbar .navbar-nav > li > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .navbar .navbar-toggle .icon-bar',
                'function'  => 'css',
                'property'  => 'background-color',
                'media_query' => '@media (max-width: 767px)'
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .navbar .navbar-nav > li > a:hover, #navigation .navbar .navbar-nav > li > a:focus, #navigation .navbar .navbar-nav > li:hover > a, #navigation .navbar .navbar-nav > .open > a, #navigation .navbar .navbar-nav > .open > a:hover, #navigation .navbar .navbar-nav > .open > a:focus, #navigation .navbar .navbar-nav > .current_page_item > a:hover, #navigation .navbar .navbar-nav > .current_page_item > a:focus, #navigation .navbar .navbar-nav > .current_page_parent > a:hover, #navigation .navbar .navbar-nav > .current_page_parent > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .navbar .navbar-toggle:hover .icon-bar, #navigation .navbar .navbar-toggle:focus .icon-bar',
                'function'  => 'css',
                'property'  => 'background-color',
                'media_query' => '@media (max-width: 767px)'
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#navigation .navbar .navbar-nav > li > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .navbar .navbar-toggle .icon-bar',
                'function'  => 'css',
                'property'  => 'background-color',
                'media_query' => '@media (max-width: 767px)'
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .navbar .navbar-nav > li > a:hover, #navigation .navbar .navbar-nav > li > a:focus, #navigation .navbar .navbar-nav > li:hover > a, #navigation .navbar .navbar-nav > .open > a, #navigation .navbar .navbar-nav > .open > a:hover, #navigation .navbar .navbar-nav > .open > a:focus, #navigation .navbar .navbar-nav > .current_page_item > a:hover, #navigation .navbar .navbar-nav > .current_page_item > a:focus, #navigation .navbar .navbar-nav > .current_page_parent > a:hover, #navigation .navbar .navbar-nav > .current_page_parent > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .navbar .navbar-toggle:hover .icon-bar, #navigation .navbar .navbar-toggle:focus .icon-bar',
                'function'  => 'css',
                'property'  => 'background-color',
                'media_query' => '@media (max-width: 767px)'
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'navi_drop_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'navbar_dropdown',
        'default'       => '#ffffff',
        'priority'      => 22,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#navigation .dropdown-menu',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#navigation .dropdown-menu',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'navi_drop_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'navbar_dropdown',
        'default'     => array(
            'link'    => '#645f5f',
            'hover'     => '#d31c13',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 23,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-menu > li > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-submenu > a:after',
                'function'  => 'css',
                'property'  => 'border-left-color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .dropdown-menu > li:hover > a, #navigation .dropdown-menu > li.open > a, #navigation .dropdown-menu > li > a:hover, #navigation .dropdown-menu > li > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-submenu:hover > a:after, #navigation .dropdown-submenu.open > a:after, #navigation .dropdown-submenu > a:hover:after, #navigation .dropdown-submenu > a:focus:after',
                'function'  => 'css',
                'property'  => 'border-left-color',
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-menu > li > a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-submenu > a:after',
                'function'  => 'css',
                'property'  => 'border-left-color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#navigation .dropdown-menu > li:hover > a, #navigation .dropdown-menu > li.open > a, #navigation .dropdown-menu > li > a:hover, #navigation .dropdown-menu > li > a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'link',
                'element'   => '#navigation .dropdown-submenu:hover > a:after, #navigation .dropdown-submenu.open > a:after, #navigation .dropdown-submenu > a:hover:after, #navigation .dropdown-submenu > a:focus:after',
                'function'  => 'css',
                'property'  => 'border-left-color',
            ),
        ),
    );

    /**
     *  Sidebar - Allgemein
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'sbar_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'sidebar_general',
        'default'       => '#645f5f',
        'priority'      => 24,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#sidebar .widget .textwidget, #sidebar .widget_calendar #wp-calendar th, #sidebar .widget_calendar #wp-calendar caption, #sidebar .widget label.screen-reader-text',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#sidebar .widget .textwidget, #sidebar .widget_calendar #wp-calendar th, #sidebar .widget_calendar #wp-calendar caption, #sidebar .widget label.screen-reader-text',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'sbar_tc_l',
        'label'         => __( 'Schriftfarbe (hell)', 'kirki' ),
        'section'       => 'sidebar_general',
        'default'       => '#969191',
        'priority'      => 25,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#sidebar .widget_block ul li .count',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '#sidebar .widget_inline  ul li, #sidebar .widget .post-date, #sidebar .widget .rss-date, #sidebar .widget cite, #sidebar .widget .count, #sidebar .widget_inline  caption, #sidebar .widget_calendar #wp-calendar td ',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#sidebar .widget_block ul li .count',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'element'   => '#sidebar .widget_inline  ul li, #sidebar .widget .post-date, #sidebar .widget .rss-date, #sidebar .widget cite, #sidebar .widget .count, #sidebar .widget_inline  caption, #sidebar .widget_calendar #wp-calendar td ',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'sbar_tc_hl',
        'label'         => __( 'Schriftfarbe (Überschrift)', 'kirki' ),
        'section'       => 'sidebar_general',
        'default'       => '#969191',
        'priority'      => 25,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#sidebar .widget .h1',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#sidebar .widget .h1',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    /**
     * Sidebar - Block
     */
    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'sbar_b_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'sidebar_block',
        'default'     => array(
            'link'    => '#645f5f',
            'hover'     => '#ffffff',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 26,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#sidebar .widget_block ul li a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_block ul li a:hover, #sidebar .widget_block ul li a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_block ul > li > a:hover + .count, #sidebar .widget_block ul > li > a:focus + .count',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#sidebar .widget_block ul li a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_block ul li a:hover, #sidebar .widget_block ul li a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_block ul > li > a:hover + .count, #sidebar .widget_block ul > li > a:focus + .count',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'sbar_b_lc_bg',
        'label'         => __( 'Hintergrundfarbe (Hover)', 'kirki' ),
        'section'       => 'sidebar_block',
        'default'       => '#c80a28',
        'priority'      => 27,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#sidebar .widget_block ul li a:hover, #sidebar .widget_block ul li a:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#sidebar .widget_block ul li a:hover, #sidebar .widget_block ul li a:focus',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
    );

    /**
     * Sidebar - Inline
     */
    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'sbar_i_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'sidebar_inline',
        'default'     => array(
            'link'    => '#645f5f',
            'hover'     => '#d31c13',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 28,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#sidebar .widget_inline ul li a, #sidebar .widget_tag_cloud a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_inline ul li a:hover, #sidebar .widget_inline ul li a:focus, #sidebar .widget_tag_cloud a:hover, #sidebar .widget_tag_cloud a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#sidebar .widget_inline ul li a, #sidebar .widget_tag_cloud a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#sidebar .widget_inline ul li a:hover, #sidebar .widget_inline ul li a:focus, #sidebar .widget_tag_cloud a:hover, #sidebar .widget_tag_cloud a:focus',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    /**
     * Breadcrumbs
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'bread_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'breadcrumbs',
        'default'       => '#ffffff',
        'priority'      => 29,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'background-color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'bread_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'breadcrumbs',
        'default'       => '#969191',
        'priority'      => 30,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'color',
            ),
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'bread_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'breadcrumbs',
        'default'     => array(
            'link'    => '#969191',
            'hover'     => '#d31c13',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 31,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#breadcrumbs a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#breadcrumbs a:hover, #breadcrumbs a:focus, #breadcrumbs a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#breadcrumbs a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => '#breadcrumbs a:hover, #breadcrumbs a:focus, #breadcrumbs a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'bread_bc',
        'label'         => __( 'Rahmenfarbe', 'kirki' ),
        'section'       => 'breadcrumbs',
        'default'       => '#efefef',
        'priority'      => 32,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '#main + #breadcrumbs',
                'function'  => 'css',
                'property'  => 'border-top',
                'value_pattern' => '1px solid $'
            ),
        ),
        'output'        => array(
            array(
                'element'   => '#breadcrumbs',
                'function'  => 'css',
                'property'  => 'border-bottom',
                'value_pattern' => '1px solid $'
            ),
            array(
                'element'   => '#main + #breadcrumbs',
                'function'  => 'css',
                'property'  => 'border-top',
                'value_pattern' => '1px solid $'
            ),
        ),
    );

    /**
     * Footer - Oben
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'foot_t_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'footer_top',
        'default'       => '#291413',
        'priority'      => 33,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#footer-top',
                'function'  => 'css',
                'property'  => 'background-color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '#footer-top',
                'function'  => 'css',
                'property'  => 'background-color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'foot_t_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'footer_top',
        'default'       => '#c1bfbf',
        'priority'      => 34,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#footer-top',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '#footer-top',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'foot_t_tc_hl',
        'label'         => __( 'Schriftfarbe (Überschrift)', 'kirki' ),
        'section'       => 'footer_top',
        'default'       => '#ffffff',
        'priority'      => 35,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#footer-top .h1',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '#footer-top .h1',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'foot_t_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'footer_top',
        'default'     => array(
            'link'    => '#c1bfbf',
            'hover'     => '#ffffff',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 36,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#footer-top a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => 'footer-top a:hover, #footer-top a:focus, #footer-top a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#footer-top a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => 'footer-top a:hover, #footer-top a:focus, #footer-top a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    /**
     * Footer - Unten
     */
    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'foot_b_bg',
        'label'         => __( 'Hintergrundfarbe', 'kirki' ),
        'section'       => 'footer_bottom',
        'default'       => '#20100f',
        'priority'      => 37,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#footer-bottom',
                'function'  => 'css',
                'property'  => 'background-color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '#footer-bottom',
                'function'  => 'css',
                'property'  => 'background-color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'color',
        'settings'      => 'foot_b_tc',
        'label'         => __( 'Schriftfarbe', 'kirki' ),
        'section'       => 'footer_bottom',
        'default'       => '#645f5f',
        'priority'      => 38,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'element'   => '#footer-bottom',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'element'   => '#footer-bottom',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    $fields[] = array(
        'type'          => 'multicolor',
        'settings'      => 'foot_b_lc',
        'label'         => __( 'Linkfarbe', 'kirki' ),
        'section'       => 'footer_bottom',
        'default'     => array(
            'link'    => '#645f5f',
            'hover'     => '#ffffff',
        ),
        'choices'     => array(
            'link'    => esc_attr__( 'Normal', 'kirki' ),
            'hover'   => esc_attr__( 'Hover', 'kirki' ),
        ),
        'priority'      => 39,
        'transport'     => 'postMessage',
        'js_vars'       => array(
            array(
                'choice'    => 'link',
                'element'   => '#footer-bottom a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => 'footer-bottom a:hover, #footer-bottom a:focus, #footer-bottom a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
        'output'        => array(
            array(
                'choice'    => 'link',
                'element'   => '#footer-bottom a',
                'function'  => 'css',
                'property'  => 'color',
            ),
            array(
                'choice'    => 'hover',
                'element'   => 'footer-bottom a:hover, #footer-bottom a:focus, #footer-bottom a:active',
                'function'  => 'css',
                'property'  => 'color',
            )
        ),
    );

    return $fields;
}
?>
