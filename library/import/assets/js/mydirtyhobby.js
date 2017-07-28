jQuery(document).ready(function (e) {
    jQuery('[data-action="crawl-amateurs"]').on('click', function() {
        jQuery(this).after('<span class="spinner at-crawl-amateurs-spinner" style="visibility:initial;float:none;margin-top:-3px"></span>');

        var status_container = jQuery('.at-crawl-amateurs .status');
        var offset = jQuery(status_container).find('.offset');
        var total = jQuery(status_container).find('.total');

        jQuery(offset).text('0');
        jQuery(status_container).show();

        at_crawl_amateurs('0');
    });

    jQuery('form.at-get-videos').on('submit', function(e) {
        var loader = jQuery(this).find('.spinner');

        if (loader.hasClass('is-active')) {
            return;
        }

        loader.addClass('is-active');

        jQuery.get(ajaxurl + '?&action=at_import_mdh_get_videos', jQuery(this).serialize()).done(function (data) {
            var target = jQuery('#videos table tbody');

            if (data != "[]") {
                var items = JSON.parse(data);

                jQuery(target).html('');

                jQuery.each(items, function (i, item) {
                    var html = at_get_videos_html(item);
                    jQuery(target).append(html);
                });

                jQuery('.tablenav .video-count span').html(jQuery('#videos tbody tr').length);
            } else {
                jQuery(target).html('<tr><th scope="row" class="check-column"><input type="checkbox" id="cb-select-0" name="video[]" value="0" disabled></th><td colspan="5">Es wurden keine (neuen) Videos gefunden.</td></tr>')
            }
        }).always(function () {
            loader.removeClass('is-active');
        });

        e.preventDefault();
    });
});

/**
 * Amateur crawl
 * @param max
 * @param step_size
 * @param steps
 */
var at_crawl_amateurs = function(next_offset) {
    var status_container = jQuery('.at-crawl-amateurs .status');
    var offset = jQuery(status_container).find('.offset');
    var total = jQuery(status_container).find('.total');
    var time_remaining = jQuery(status_container).find('.time-remaining');
    var start_time = new Date().getTime();

    jQuery.ajaxQueue({
        url: ajaxurl,
        dataType: 'json',
        type: 'GET',
        data: "action=at_import_mdh_crawl_amateurs&offset=" + next_offset,
        success: function(data){
            if(data.status == 'ok') {
                var next_offset = data.next_offset;
                var total_items = data.total;
                var request_time = new Date().getTime() - start_time;

                console.log(request_time);

                jQuery(offset).text(next_offset);
                jQuery(total).text(total_items);

                // calculate time remaining
                var calculated_time = (((total_items / 100) - (next_offset / 100)) * (request_time / 1000))  / 60;
                jQuery(time_remaining).text(Math.round(calculated_time));

                at_crawl_amateurs(next_offset);
            } else {
                jQuery('.at-crawl-amateurs-spinner').remove();
                jQuery(status_container).hide();
            }
        },
        error: function() {
            jQuery('.at-crawl-amateurs-spinner').remove();
            jQuery(status_container).hide();
        }
    });
}

/**
 * Video generate html
 * @param max
 * @param step_size
 * @param steps
 */
var at_get_videos_html = function(item) {
    var html = '<tr id="video-' + item.id + '" class="video video-' + item.id + '">';
    html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + item.id + '" name="video[]" value="' + item.id + '"></th>';
    html += '<td class="image"><img src="' + item.image + '" alt="" style="max-width:60px;"/></td>';
    html += '<td class="title">' + item.title + '</td>';
    html += '<td class="duration">' + item.duration + '</td>';
    html += '<td class="rating">' + item.rating + '</td>';
    html += '<td class="description" style="display:none;">' + item.description + '</td>';
    html += '<td class="time">' + item.time + '</td>';
    html += '</tr>';

    return html;
}