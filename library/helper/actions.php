<?php
/**
 * WP Actions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

add_action( 'at_init', 'xcore_global_vars' );
function xcore_global_vars ()
{
    global $xcore_layout;
    $xcore_layout = new xCORE_Layout();
}

/**
 * Remove unsued meta
 *
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Remove unused CSS
 */
add_action( 'widgets_init', 'xcore_remove_recent_comment_style' );
function xcore_remove_recent_comment_style ()
{
    global $wp_widget_factory;
    remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

/**
 * Define AJAX_URL in frontend
 */
add_action( 'wp_head', 'xcore_ajaxurl' );
function xcore_ajaxurl ()
{
    ?>
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
    </script>
    <?php
}

/**
 * Define Variables in backend
 */
add_action( 'admin_head', 'xcore_backend_js_vars' );
function xcore_backend_js_vars ()
{
    ?>
    <script type="text/javascript">
        var template_directory_uri = '<?php echo get_template_directory_uri(); ?>';
    </script>
    <?php
}

/**
 * Custom CSS
 */
add_action( 'wp_head', 'xcore_custom_css' );
function xcore_custom_css ()
{
    $custom_css = get_field( 'customizing_css', 'options' );

    if ( $custom_css ) {
        ?>
        <style>
            <?php echo $custom_css; ?>
        </style>
        <?php
    }
}

/**
 * Custom Code (Header)
 */
add_action( 'wp_head', 'xcore_custom_code_header' );
function xcore_custom_code_header ()
{
    $code = get_field( 'customizing_code_header', 'options' );

    if ( $code ) {
        echo $code;
    }
}

/**
 * Custom Code (Footer)
 */
add_action( 'wp_footer', 'xcore_custom_code_footer' );
function xcore_custom_code_footer ()
{
    $code = get_field( 'customizing_code_footer', 'options' );

    if ( $code ) {
        echo $code;
    }
}

/**
 * Google Analytics Tracking
 */
add_action( 'wp_head', 'xcore_ga_tracking' );
function xcore_ga_tracking ()
{
    $ga_id = get_field( 'tracking_ga', 'options' );

    if ( $ga_id ) {
        ?>
        <script type="text/javascript">
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', '<?php echo $ga_id; ?>', 'auto');
            ga('set', 'anonymizeIp', true);
            ga('send', 'pageview');
        </script>
        <?php
    }
}

/**
 * Google Tag Manager Tracking
 */
add_action( 'wp_head', 'xcore_gtm_tracking' );
function xcore_gtm_tracking ()
{
    $gtm_id = get_field( 'tracking_gtm', 'options' );

    if ( $gtm_id ) {
        ?>
        <!-- Google Tag Manager -->
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '<?php echo $gtm_id; ?>');</script>
        <!-- End Google Tag Manager -->
        <?php
    }
}

add_action( 'xcore_after_body', 'xcore_gtm_tracking_additional' );
function xcore_gtm_tracking_additional ()
{
    $gtm_id = get_field( 'tracking_gtm', 'options' );

    if ( $gtm_id ) {
        ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php
    }
}

/**
 * Facebook Pixel Tracking
 */
add_action( 'wp_head', 'xcore_fb_pixel_tracking' );
function xcore_fb_pixel_tracking ()
{
    $fb = get_field( 'tracking_fb', 'options' );

    if ( isset( $fb['id'] ) && $fb['id'] != '' ) {
        ?>
        <!-- Facebook Pixel Code -->
        <script>
            !function (f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function () {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/<?php echo $fb['language']; ?>/fbevents.js');
            fbq('init', '<?php echo $fb['id']; ?>');
            fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" src="https://www.facebook.com/tr?id=829996753848170&ev=PageView&noscript=1"/></noscript>
        <!-- End Facebook Pixel Code -->
        <?php
    }
}

if ( ! function_exists( 'at_fix_no_editor_on_posts_page' ) ) {

    /**
     * Add the wp-editor back into WordPress after it was removed in 4.2.2.
     *
     * @param Object $post
     *
     * @return void
     */
    function at_fix_no_editor_on_posts_page ( $post )
    {
        if ( isset( $post ) && $post->ID != get_option( 'page_for_posts' ) ) {
            return;
        }

        remove_action( 'edit_form_after_title', '_wp_posts_page_notice' );
        add_post_type_support( 'page', 'editor' );
    }

    add_action( 'edit_form_after_title', 'at_fix_no_editor_on_posts_page', 0 );
}


add_action( 'at_after_navbar', 'at_navigation_javascript_fix' );
function at_navigation_javascript_fix ()
{
    ?>
    <script type="text/javascript">
        /**
         * Navbar classes (javascript only)
         */
        function at_get_document_width() {
            return Math.max(
                document.body.scrollWidth,
                document.documentElement.scrollWidth,
                document.body.offsetWidth,
                document.documentElement.offsetWidth,
                document.documentElement.clientWidth
            );
        }

        function at_has_class(element, className) {
            return (' ' + element.className + ' ').indexOf(' ' + className + ' ') > -1;
        }

        function at_navbar_classes() {
            var el = document.getElementById('navigation');
            var width = at_get_document_width();

            // expand-sm
            if (at_has_class(el, 'navbar-expand-sm')) {
                if (width <= 575.98) {
                    el.classList.add('navbar-mobile');
                    el.classList.remove('navbar-desktop');
                } else {
                    el.classList.add('navbar-desktop');
                    el.classList.remove('navbar-mobile');
                }
            }

            // expand-md
            if (at_has_class(el, 'navbar-expand-md')) {
                if (width <= 767.98) {
                    el.classList.add('navbar-mobile');
                    el.classList.remove('navbar-desktop');
                } else {
                    el.classList.add('navbar-desktop');
                    el.classList.remove('navbar-mobile');
                }
            }

            // expand-lg
            if (at_has_class(el, 'navbar-expand-lg')) {
                if (width <= 991.98) {
                    el.classList.add('navbar-mobile');
                    el.classList.remove('navbar-desktop');
                } else {
                    el.classList.add('navbar-desktop');
                    el.classList.remove('navbar-mobile');
                }
            }

            // expand-xl
            if (at_has_class(el, 'navbar-expand-xl')) {
                if (width <= 1199.98) {
                    el.classList.add('navbar-mobile');
                    el.classList.remove('navbar-desktop');
                } else {
                    el.classList.add('navbar-desktop');
                    el.classList.remove('navbar-mobile');
                }
            }
        }

        at_navbar_classes();

        window.addEventListener('resize', at_navbar_classes);
    </script>
    <?php
}