<?php
define ( 'AT_PRG_PATTERN_SLUG' , ( get_field( 'prg_slug', 'options' ) ? get_field( 'prg_slug', 'options' ) : 'out' ) );

if ( ! function_exists( 'at_prg_pattern_load_form' ) ) {
	/**
	 * Load the prg pattern form in footer
	 */
	add_action( 'wp_footer', 'at_prg_pattern_load_form' );
	function at_prg_pattern_load_form() {
		?>
        <form id="redirform" action="<?php echo home_url( AT_PRG_PATTERN_SLUG ); ?>/" method="post">
            <input type="hidden" name="redirdata" id="redirdata">
        </form>
		<?php
	}
}

if ( ! function_exists( 'at_prg_pattern_shortcode' ) ) {
	/**
	 * A simple prg pattern shortcode
	 *
	 * @param $atts
	 * @param null $content
	 *
	 * @return string
	 */
	add_shortcode( 'prg_pattern_link', 'at_prg_pattern_shortcode' );
	function at_prg_pattern_shortcode($atts, $content = null) {
		extract(
            shortcode_atts(
                array(
                    'url' => '',
                    'target' => '_self',
                    'class' => ''
                ),
                $atts
            )
        );

		return '<span class="' . ( $class ? $class . ' ' : '' ) . 'redir-link" data-submit="' . base64_encode( $url ) . '" data-target="' . $target . '">' . do_shortcode( $content )  . '</span>';
	}
}

if ( ! function_exists( 'at_prg_pattern_rewrite' ) ) {
	/**
	 * Create rewrite riules
	 */
	add_action( 'init', 'at_prg_pattern_rewrite' );
	function at_prg_pattern_rewrite() {
		add_rewrite_rule( '^' . AT_PRG_PATTERN_SLUG . '/?', 'index.php?' . AT_PRG_PATTERN_SLUG . '=$matches[0]', 'top' );
	}
}

if ( ! function_exists( 'at_prg_pattern_cloak_query_vars' ) ) {
	/**
	 * Create query ars
	 *
	 * @param $query_vars
	 *
	 * @return array
	 */
	add_action( 'query_vars', 'at_prg_pattern_cloak_query_vars' );
	function at_prg_pattern_cloak_query_vars( $query_vars ) {
		$query_vars[] = AT_PRG_PATTERN_SLUG;

		return $query_vars;
	}
}

if ( ! function_exists( 'at_prg_pattern_out_parse_request' ) ) {
	/**
	 * Parse request
	 *
	 * @param $wp
	 */
	add_action( 'parse_request', 'at_prg_pattern_out_parse_request' );
	function at_prg_pattern_out_parse_request( $wp ) {
		if ( array_key_exists( AT_PRG_PATTERN_SLUG, $wp->query_vars ) ) {
			at_prg_pattern_out();
			exit();
		}
	}
}

if ( ! function_exists( 'at_prg_pattern_out' ) ) {
	/**
	 * Output for the "outgoing" page
	 */
	function at_prg_pattern_out() {
		$location = home_url();

		if( !empty( $_POST['redirdata'] ) ) {
			$url = base64_decode($_POST['redirdata']);

			if($url) {
				$location = $url;
			} else {
				$location = $_SERVER['HTTP_REFERER'];
			}
		}

		header( "Location: " . $location, true, 302 );

		exit();
	}
}