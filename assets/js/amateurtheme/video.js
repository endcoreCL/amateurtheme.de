/*
jQuery(document).ready(function () {
    const player = new Plyr('#player');
});*/

/**
 * Count a visit
 */
jQuery(document).ready(function () {
    var post_id = jQuery('#main').attr('data-post-id');

    if ( post_id ) {
        jQuery.ajax({
            url: ajaxurl,
            data: {action: 'video_views', post_id: post_id},
            type: 'POST',
            success: function (data) {
            },
            error: function () {

            }
        });
    }
});

/**
 * Paginated related posts
 */
jQuery(document).ready(function () {
    jQuery(document).on('click', '.video-related[data-pagination="true"] a.page-link', function(e) {
        var post_id = jQuery('#main').attr('data-post-id');
        var page = jQuery(this).attr('data-page');

        if ( post_id ) {
            jQuery('.video-related[data-pagination="true"] .page-link').attr( 'disabled', true );
            jQuery('.video-related[data-pagination="true"]').addClass('loading');

            jQuery.ajax({
                url: ajaxurl,
                data: {action: 'video_related', post_id: post_id, page : page},
                type: 'POST',
                success: function (data) {
                    jQuery('.video-related[data-pagination="true"] .inner').html(data);

                    jQuery('.video-related[data-pagination="true"] .page-link').attr( 'disabled', false );
                    jQuery('.video-related[data-pagination="true"]').removeClass('loading');
                },
                error: function () {
                    jQuery('.video-related[data-pagination="true"] .page-link').attr( 'disabled', false );
                    jQuery('.video-related[data-pagination="true"]').removeClass('loading');
                }
            });
        }

        e.preventDefault();
        return false;
    });
});