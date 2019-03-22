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