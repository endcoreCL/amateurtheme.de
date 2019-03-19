/**
 * Bootstrap Functions
 */
jQuery(document).ready(function () {
    jQuery('[data-toggle="popover"]').popover();
    jQuery('[data-toggle="tooltip"]').tooltip();
});

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