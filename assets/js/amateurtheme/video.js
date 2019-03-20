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