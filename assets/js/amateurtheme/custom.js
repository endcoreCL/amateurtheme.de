/**
 * Bootstrap Functions
 */
jQuery(document).ready(function () {
    jQuery('[data-toggle="popover"]').popover();
    jQuery('[data-toggle="tooltip"]').tooltip();
});

/**
 * Navigation Dropdown
 */
jQuery( document ).ready( function () {
    jQuery( '[data-toggle="dropdown"]' ).on( 'click' , function () {
        jQuery( this ).find( 'i' ).toggleClass( 'fa-angle-down fa-angle-up' );
    } );

    jQuery( '.navbar ul.dropdown-menu [data-toggle="dropdown"]' ).on( 'click', function( event ) {
        event.preventDefault();
        event.stopPropagation();
        jQuery( this ).parent().siblings().removeClass( 'show' );
        jQuery( this ).parent().toggleClass( 'show' );
        jQuery( this ).parent().find( '.dropdown-menu:first' ).toggleClass( 'show' );
    } );
} );

/**
 * Navigation collapse
 */
jQuery( document ).ready( function (e) {
    jQuery('#navbarNav').on('show.bs.collapse', function (e) {
        var button = jQuery('[data-target="#navbarNav"]');

        var value = button.html();
        var toggle_text = button.attr( 'data-toggle-text' );

        if (toggle_text) {
            button.html( toggle_text ).attr( 'data-toggle-text', value.trim() );
        }
    } );

    jQuery('#navbarNav').on('hide.bs.collapse', function (e) {
        var button = jQuery('[data-target="#navbarNav"]');

        var value = button.html();
        var toggle_text = button.attr( 'data-toggle-text' );

        if (toggle_text) {
            button.html( toggle_text ).attr( 'data-toggle-text', value.trim() );
        }
    } );

    jQuery('#navbarNav').on('hidden.bs.collapse', function () {

    } );
} );

/**
 * Social share
 */
function social_share(elem, m) {
    if (m == 'twitter') {
        var desc = '';
        var el, els = document.getElementsByTagName("meta");
        var i = els.length;
        while (i--) {
            el = els[i];
            if (el.getAttribute("property") == "og:title") {
                desc = el.content;
                break;
            }
        }
        var creator = "";
        if (document.getElementsByName("twitter:creator").length) {
            creator = document.getElementsByName("twitter:creator")[0].content;
        }
        creator = creator.replace('@', '');
        elem.href += "&text=" + desc + "&via=" + creator + "&related=" + creator;
    }

    elem = window.open(elem.href, "Teile diese Seite", "width=600,height=500,resizable=yes");
    elem.moveTo(screen.width / 2 - 300, screen.height / 2 - 450);
    elem.focus();
}

/**
 * PRG Pattern
 */
jQuery(document).ready(function($) {
    $('.redir-link[data-submit]').click(function (e) {
        e.preventDefault();
        var $this = $(this), $redirectForm = $('#redirform'), $redirectValue = $('#redirdata'), $target = $this.data('target'), value = $this.data('submit');

        $redirectValue.val(value);

        if($target) {
            $redirectForm.attr('target', $target);
        }

        $redirectForm.submit();
    });
});

/**
 * Alert Shortcode
 */
jQuery( document ).ready( function () {
    if ( jQuery('.xcore-alert a').length > 0 ) {
        jQuery('.xcore-alert a:not(.btn)').addClass('alert-link');
    }
});

/**
 * Auto responsive embed youtube videos
 */
jQuery( document ).ready( function() {
    if ( jQuery('iframe[src*="youtube"]').length > 0 ) {
        jQuery('iframe[src*="youtube"]').wrap('<div class="embed-responsive embed-responsive-16by9">');
    }
});

/**
 * Owl carousels
 */
jQuery( document ).ready( function () {
    /**
     * PB: Slideshow
     */
    if( jQuery( '.owl-slideshow' ).length ) {
        jQuery( '.owl-slideshow').each(function() {
            var autoplay = jQuery(this).attr('data-autoplay');
            var timeout = jQuery(this).attr('data-timeout');
            var nav = jQuery(this).attr('data-nav');
            var dots = jQuery(this).attr('data-dots');
            var count = jQuery(this).find('.item').length;
            var drag = ( count > 1 ? true : false );

            jQuery( this ).owlCarousel({
                loop: false,
                autoplay: autoplay,
                autoplayTimeout:  timeout,
                nav: nav,
                dots: dots,
                items: 1,
                touchDrag: drag,
                mouseDrag: drag,
                pullDrag: drag,
                autoHeight: true
            });
        });

    }
});

/**
 * Popup
 */
jQuery( document ).ready( function () {
    if ( jQuery('.at-popup').length > 0 ) {
        var cookieExpire = jQuery('.at-popup').attr('data-cookie-lifetime');
        var show_time = jQuery('.at-popup').attr('data-show-time');
        var show_scroll = jQuery('.at-popup').attr('data-show-scroll');

        var popup = ouibounce(false, {
            timer: 0,
            cookieExpire: cookieExpire,
            callback: function() {
                jQuery('.at-popup:not(.dismiss)').addClass('active');
            }
        });

        if ( show_time || show_scroll ) {
            if (show_time) {
                setTimeout(function () {
                    popup.fire();
                }, show_time);
            }

            if (show_scroll) {
                jQuery(document).scroll(function (e) {
                    var scrollAmount = jQuery(window).scrollTop();
                    var documentHeight = jQuery(document).height();
                    var scrollPercent = (scrollAmount / documentHeight) * 100;
                    if (scrollPercent > show_scroll) {
                        popup.fire();
                    }
                });
            }
        } else {
            popup.fire();
        }

        jQuery('.at-popup [data-dismiss=toast]').on('click', function() {
            jQuery('.at-popup').removeClass('active').addClass('dismiss');
        });
    }
});