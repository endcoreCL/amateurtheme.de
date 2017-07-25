<?php

/**
 * Function: at_import_database_notice
 */
add_action( 'admin_notices', 'at_import_database_notice' );
function at_import_database_notice() {
    global $wpdb;

    if(
        $wpdb->get_var("show tables like '" . AT_CRON_TABLE . "'") != AT_CRON_TABLE
    ) {
        ?>
        <div class="error">
            <p>
                Eine oder mehrere Datenbank-Tabellen für den Import fehlen. Bitte aktualisiere deine Datenbank.
                <a class="button" id="at-import-install-tables">Datenbank aktualisieren</a>
            </p>
        </div>

        <script type="text/javascript">
            jQuery('#at-import-install-tables').bind('click', function (e) {
                var target = jQuery(this).closest('.error');
                jQuery(this).append(' <span class="spinner" style="visibility:initial"></span>');

                jQuery.get(ajaxurl + '?&action=at_import_install_tables', {}).done(function (data) {
                    var response = JSON.parse(data);

                    if (response.status == 'ok') {
                        jQuery(target).find('.spinner').remove();
                        jQuery(target).fadeOut();
                    }
                });

                e.preventDefault();
            });
        </script>
        <?php
    }
}

/**
 * Function: AJAX at_import_install_tables
 */
add_action("wp_ajax_at_import_install_tables", "at_import_install_tables");
function at_import_install_tables() {
    at_import_database_tables();

    $response = array();

    $response['status'] = 'ok';

    echo json_encode($response);

    exit();
}

/**
 * Function: at_import_database_tables
 */
add_action('upgrader_process_complete', 'at_import_database_tables', 10, 1);
add_action('after_switch_theme', 'at_import_database_tables');
function at_import_database_tables() {
    global $wpdb;

    /**
     *  Cronjobs
     */
    if ($wpdb->get_var("show tables like '" . AT_CRON_TABLE . "'") != AT_CRON_TABLE) {
        $sql = "CREATE TABLE " . AT_CRON_TABLE . " (
            id int(11) NOT NULL AUTO_INCREMENT,
            object_id int(11),
            network varchar(255),
            name varchar(255),
            alias varchar(255),
            type varchar(255),
            options longtext,
            processing int(1),
            scrape int(1),
            import int(1),
            last_pos bigint(20),
            created bigint(20),
            updated bigint(20),
            skipped bigint(20),
            deleted bigint(20),
            last_activity datetime NOT NULL,
            PRIMARY KEY (id)
		);";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}