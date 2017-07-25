jQuery(document).ready(function (e) {
    /**
     * Tabs
     */
    jQuery('.at-import-tabs-nav a').on('click', function (e) {
        jQuery('.at-import-tabs-nav a').removeClass('nav-tab-active');
        jQuery('.at-import-tabs-content .at-import-tab').removeClass('active');

        var a = jQuery(this).attr('id').replace('-tab', '');
        jQuery('.at-import-tabs-content div#' + a).addClass("active");
        jQuery(this).addClass("nav-tab-active");
    });

    var a = window.location.hash.replace("#top#", "");
    ("" == a || "#_=_" == a) && (a = jQuery(".at-import-tabs-content .at-import-tab").attr("id")), jQuery('.at-import-tabs-nav a').removeClass('nav-tab-active'), jQuery('.at-import-tabs-content .at-import-tab').removeClass('active'), jQuery("#" + a).addClass("active"), jQuery("#" + a + "-tab").addClass("nav-tab-active");

    /**
     * Get amateur select
     */
    jQuery('.at-amateur-select').select2();
    jQuery('.at-amateur-select').on('select2:select', function (evt) {
        var uid = jQuery(this).val();
        var username = jQuery(this).find('option:selected').text();

        jQuery('#at-cronjobs #uid').attr('value', uid);
        jQuery('#at-cronjobs #username').attr('value', username);
    });

    /**
     * Add new amateur
     */
    jQuery('#at-cronjobs').submit(function(e) {
        jQuery.get( ajaxurl + '?&action=at_import_cronjob_add', jQuery(this).serialize()).done(function( data ) {
            var response = JSON.parse(data);

            if(response.status != 'ok') {
                jQuery('#at-cronjobs').append('<p>Fehler: Der Amateur konnte nicht angelegt werden.</p>');
                return false;
            } else {
                location.reload();
            }
        });

        return false;
    });

    jQuery('.cron-update').bind('click', function(e) {
        var id = jQuery(this).attr('data-id');
        var field = jQuery(this).attr('data-field');
        var value = jQuery(this).attr('data-value');

        jQuery(this).after('<span class="spinner" style="visibility:initial"></span>');

        jQuery.get( ajaxurl + '?&action=at_import_cronjob_edit', {id : id, field : field , value : value}).done(function( data ) {
            var response = JSON.parse(data);

            if(response.status == 'ok') {
                location.reload();
            }
        });

        e.preventDefault();
    });

    jQuery('.cron-delete').bind('click', function(e) {
        var id = jQuery(this).attr('data-id');
        jQuery(this).after('<span class="spinner" style="visibility:initial"></span>');

        jQuery.get( ajaxurl + '?&action=at_import_cronjob_delete', {id : id}).done(function( data ) {
            var response = JSON.parse(data);

            if(response.status == 'ok') {
                location.reload();
            }
        });

        e.preventDefault();
    });
})