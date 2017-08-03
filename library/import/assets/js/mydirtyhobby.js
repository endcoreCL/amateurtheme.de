jQuery(document).ready(function (e) {
    // checkConnection
    checkConnection();

    jQuery('[data-action="crawl-amateurs"]').on('click', function() {
        jQuery(this).after('<span class="spinner at-crawl-amateurs-spinner" style="visibility:initial;float:none;margin-top:-3px"></span>');

        var status_container = jQuery('.at-crawl-amateurs .status');
        var offset = jQuery(status_container).find('.offset');
        var total = jQuery(status_container).find('.total');

        jQuery(offset).text('0');
        jQuery(status_container).show();

        at_crawl_amateurs('0');
    });

    jQuery('form#at-get-videos').on('submit', function(e) {
        var loader = jQuery(this).find('.spinner');

        if (loader.hasClass('is-active')) {
            return;
        }

        loader.addClass('is-active');

        jQuery.get(ajaxurl + '?&action=at_import_mdh_get_videos', jQuery(this).serialize()).done(function (data) {
            var target = jQuery('#videos-wrapper table tbody');

            if (data != "[]") {
                var items = JSON.parse(data);

                jQuery(target).html('');

                jQuery.each(items, function (i, item) {
                    var html = at_get_videos_html(item);
                    jQuery(target).append(html);
                });

                jQuery('.tablenav .video-count span').html(jQuery('#videos-wrapper tbody tr').length);
            } else {
                jQuery(target).html('<tr><th scope="row" class="check-column"><input type="checkbox" id="cb-select-0" name="video[]" value="0" disabled></th><td colspan="5">Es wurden keine (neuen) Videos gefunden.</td></tr>')
            }
        }).always(function () {
            loader.removeClass('is-active');
        });

        e.preventDefault();
    });

    /**
     * Video import
     */
    jQuery('#videos-wrapper .start-import').bind('click', function (e) {
        var current_button = this;
        var max_videos = jQuery('#videos-wrapper tbody tr:not(.success)').find('input[type="checkbox"]:checked').length;
        var ajax_loader = jQuery('.ajax-loader');
        var i = 1;

        if (max_videos != "0") {
            jQuery(ajax_loader).addClass('active').find('p').html('Importiere Video <span class="current">1</span> von ' + max_videos);

            jQuery('#videos-wrapper tbody tr').find('input[type="checkbox"]:checked').each(function () {
                var current = jQuery(this).closest('tr');

                var id = jQuery(this).val();
                var video_category = jQuery(current_button).parent().find('#video_category option:selected').val();
                var video_actor = jQuery(current_button).parent().find('#video_actor option:selected').val();

                var video = {
                    id: id,
                    video_category: video_category,
                    video_actor: video_actor
                };

                var xhr = jQuery.post(ajaxurl + '?action=at_mdh_import_video', video).done(function (data) {
                    if (data != "error") {
                        jQuery('#videos-wrapper table tr#video-' + data).addClass('success');
                        jQuery('#videos-wrapper table tr#video-' + data).find('input[type=checkbox]').attr('checked', false).attr('disabled', true);
                    } else {
                        jQuery('#videos-wrapper table tr#video-' + data).addClass('error');
                        jQuery(ajax_loader).removeClass('active');
                    }
                }).success(function () {
                    var procentual = (100 / max_videos) * i;
                    var procentual_fixed = procentual.toFixed(2);
                    jQuery(ajax_loader).find('.current').html(i);
                    jQuery(ajax_loader).find('.progress-bar').css('width', procentual + '%').html(procentual_fixed + '%');

                    if (i >= max_videos) {
                        jQuery(ajax_loader).removeClass('active');
                    }

                    i++;
                }).error(function() {
                    jQuery(ajax_loader).removeClass('active');
                });
            });
        }

        e.preventDefault();
    });

});

/*
 * CHECK CONNECTION
 */
var checkConnection = function () {
    /*jQuery.get(ajaxurl + '?&action=vi_mdh_topitems_get_videos', jQuery(this).serialize()).done(function (data) {
        var result = JSON.parse(data);
        var resultContainer = jQuery('#checkConnection');

        if (result.status == 'too many connections') {
            resultContainer.append('<div class="error"><p>Die Verbindung zu MyDirtyHobby wurde aufgrund zu vieler Verbindungen gesperrt. Bitte wende dich an den Support von MyDirtyHobby.</p></div>');
        }
    })*/
};


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
    if (item.imported == 'true') {
        var html = '<tr id="video-' + item.id + '" class="video video-' + item.id + ' success imported">';
        html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + item.id + '" name="video[]" value="' + item.id + '" disabled></th>';
    } else {
        var html = '<tr id="video-' + item.id + '" class="video video-' + item.id + '">';
        html += '<th scope="row" class="check-column"><input type="checkbox" id="cb-select-' + item.id + '" name="video[]" value="' + item.id + '"></th>';
    }

    html += '<td class="image"><img src="' + item.image + '" alt="" style="max-width:60px;"/></td>';
    html += '<td class="title">' + item.title + '</td>';
    html += '<td class="duration">' + item.runtime + '</td>';
    html += '<td class="rating">' + item.rating + '</td>';
    html += '<td class="description" style="display:none;">' + item.description + '</td>';
    html += '<td class="time">' + item.releasetime + '</td>';
    html += '</tr>';

    return html;
}