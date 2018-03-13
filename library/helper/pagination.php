<?php
/**
 * Bootstrap Pagination
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */


function at_pagination($pages = '', $range = 3) {
    $showitems = ($range * 2) + 1;
    global $paged;

    if(empty($paged)) {
        $paged = 1;
    }

    if($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;

        if(!$pages) {
            $pages = 1;
        }
    }

    if(1 != $pages) {
        echo '<nav aria-label="Page navigation" role="navigation">';
            echo '<span class="sr-only">Page navigation</span>';
            echo '<ul class="pagination justify-content-center ft-wpbs">';
                echo '<li class="page-item disabled hidden-md-down d-none d-lg-block"><span class="page-link">' . sprintf(__('Seite %s von %s', 'amateurtheme'), $paged, $pages) . '</span></li>';

                if($paged > 2 && $paged > $range+1 && $showitems < $pages)
                    echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="' . __('Erste Seite', 'amateurtheme') . '">&laquo;<span class="hidden-sm-down d-none d-md-block"> ' . __('Anfang', 'amateurtheme') . '</span></a></li>';

                if($paged > 1 && $showitems < $pages)
                    echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="' . __('Vorherige Seite', 'amateurtheme') . '">&lsaquo;<span class="hidden-sm-down d-none d-md-block"> ' . __('Vorherige', 'amateurtheme') . '</span></a></li>';

                for ($i=1; $i <= $pages; $i++)
                {
                    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
                        echo ($paged == $i)? '<li class="page-item active"><span class="page-link"><span class="sr-only">' . __('Aktuelle Seite', 'amateurtheme') . ' </span>'.$i.'</span></li>' : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '"><span class="sr-only">' . __('Seite', 'amateurtheme') . ' </span>' . $i . '</a></li>';
                }

                if ($paged < $pages && $showitems < $pages)
                    echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '" aria-label="' . __('Nächste Seite', 'amateurtheme') . '"><span class="hidden-sm-down d-none d-md-block">' . __('Nächste', 'amateurtheme') . ' </span>&rsaquo;</a></li>';

                if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
                    echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($pages).'" aria-label="' . __('Letzte Seite', 'amateurtheme') . '"><span class="hidden-sm-down d-none d-md-block">' . __('Ende', 'amateurtheme') . ' </span>&raquo;</a></li>';
            echo '</ul>';
        echo '</nav>';
    }
}