<?php

define( 'AT_PRG_PATTERN_SLUG', get_field( 'prg_slug', 'options' ) ?: 'out' );

/**
 * Load the prg pattern form in footer
 */
add_action( 'wp_footer', function () {
    ?>
    <form id="redirform" action="<?php echo home_url( AT_PRG_PATTERN_SLUG ); ?>/" method="post">
        <input type="hidden" name="redirdata" id="redirdata">
    </form>
    <?php
} );

/**
 * A simple prg pattern shortcode
 *
 * @param $atts
 * @param null $content
 *
 * @return string
 */
add_shortcode( 'prg_pattern_link', function ( $atts, $content = null ) {
    extract(
        shortcode_atts(
            array(
                'url'    => '',
                'target' => '_self',
                'class'  => ''
            ),
            $atts
        )
    );

    return '<span class="' . ( $class ? $class . ' ' : '' ) . 'redir-link" data-submit="' . base64_encode( $url ) . '" data-target="' . $target . '">' . do_shortcode( $content ) . '</span>';
} );

add_action( 'init', function () {
    add_rewrite_rule( '^' . AT_PRG_PATTERN_SLUG . '/?', 'index.php?' . AT_PRG_PATTERN_SLUG . '=$matches[0]', 'top' );
} );

/**
 * Create query ars
 *
 * @param $query_vars
 *
 * @return array
 */
add_action( 'query_vars', function ( $query_vars ) {
    $query_vars[] = AT_PRG_PATTERN_SLUG;

    return $query_vars;
} );

/**
 * Parse request
 *
 * @param $wp
 */
add_action( 'parse_request', function ( $wp ) {
    if ( array_key_exists( AT_PRG_PATTERN_SLUG, $wp->query_vars ) ) {
        at_prg_pattern_out();
        exit();
    }
} );

/**
 * Output for the "outgoing" page
 */
function at_prg_pattern_out ()
{
    $location = home_url();

    if ( ! empty( $_POST['redirdata'] ) ) {
        $url = base64_decode( $_POST['redirdata'] );

        if ( $url ) {
            $location = $url;
        } else {
            $location = $_SERVER['HTTP_REFERER'];
        }
    }

    ?>
    <script>
        window.location.replace("<?= $location ?>");
    </script>
    <?php

    exit();
}