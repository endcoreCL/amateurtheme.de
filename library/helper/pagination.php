<?php
/**
 * Bootstrap Pagination
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_pagination' ) ) {
    /**
     * A simple pagination based on bootstrap 4 styles
     *
     * @param string $pages
     * @param int $range
     */
    function at_pagination ( $pages = 0, $range = 3, $custom_param = false )
    {
        $showitems = ( $range * 2 ) + 1;

        global $paged;

        if ( empty( $paged ) ) {
            $paged = 1;
        }

        if ( ! $pages ) {
            global $wp_query;
            $pages = $wp_query->max_num_pages;

            if ( ! $pages ) {
                $pages = 1;
            }
        }

        if ( $custom_param ) {
            if ( $custom_param > 0 && is_numeric( $custom_param ) ) {
                $paged = $custom_param;
            } else {
                $paged = ( isset ( $_GET['_page'] ) ? intval( $_GET['_page'] ) : 1 );
            }
        }

        if ( 1 != $pages ) {
            echo '<nav aria-label="Page navigation" role="navigation">';
            echo '<span class="sr-only">Page navigation</span>';
            echo '<ul class="pagination justify-content-center ft-wpbs">';
            echo '<li class="page-item disabled hidden-md-down d-none d-lg-inline-block"><span class="page-link">' . sprintf( __( 'Seite %s von %s', 'amateurtheme' ), $paged, $pages ) . '</span></li>';

            if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
                echo '<li class="page-item"><a class="page-link" data-page="1" href="' . ( $custom_param ? at_get_pagenum_link( 1 ) : get_pagenum_link( 1 ) ) . '" aria-label="' . __( 'Erste Seite', 'amateurtheme' ) . '"> &laquo; <span class="hidden-sm-down d-none d-md-inline-block"> ' . __( 'Anfang', 'amateurtheme' ) . '</span></a></li>';
            }

            if ( $paged > 1 && $showitems < $pages ) {
                echo '<li class="page-item"><a class="page-link" data-page="' . ( $paged - 1 ) . '" href="' . ( $custom_param ? at_get_pagenum_link( $paged - 1 ) : get_pagenum_link( $paged - 1 ) ) . '" aria-label="' . __( 'Vorherige Seite', 'amateurtheme' ) . '"> &lsaquo; <span class="hidden-sm-down d-none d-md-inline-block"> ' . __( 'Vorherige', 'amateurtheme' ) . '</span></a></li>';
            }

            for ( $i = 1; $i <= $pages; $i++ ) {
                if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
                    echo ( $paged == $i ) ? '<li class="page-item active"><span class="page-link"><span class="sr-only">' . __( 'Aktuelle Seite', 'amateurtheme' ) . ' </span>' . $i . '</span></li>' : '<li class="page-item"><a class="page-link" data-page="' . $i . '" href="' . ( $custom_param ? at_get_pagenum_link( $i ) : get_pagenum_link( $i ) ) . '"><span class="sr-only">' . __( 'Seite', 'amateurtheme' ) . ' </span>' . $i . '</a></li>';
                }
            }

            if ( $paged < $pages && $showitems < $pages ) {
                echo '<li class="page-item"><a class="page-link" data-page="' . ( $paged + 1 ) . '" href="' . ( $custom_param ? at_get_pagenum_link( $paged + 1 ) : get_pagenum_link( $paged + 1 ) ) . '" aria-label="' . __( 'N??chste Seite', 'amateurtheme' ) . '"><span class="hidden-sm-down d-none d-md-inline-block">' . __( 'N??chste', 'amateurtheme' ) . ' </span> &rsaquo; </a></li>';
            }

            if ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) {
                echo '<li class="page-item"><a class="page-link" data-page="' . $pages . '" href="' . ( $custom_param ? at_get_pagenum_link( $pages ) : get_pagenum_link( $pages ) ) . '" aria-label="' . __( 'Letzte Seite', 'amateurtheme' ) . '"><span class="hidden-sm-down d-none d-md-inline-block">' . __( 'Ende', 'amateurtheme' ) . ' </span> &raquo; </a></li>';
            }
            echo '</ul>';
            echo '</nav>';
        }
    }
}

function at_get_pagenum_link ( $pagenum = 1 )
{
    if ( $pagenum == 1 ) {
        return get_permalink();
    }

    return '?_page=' . $pagenum;
}