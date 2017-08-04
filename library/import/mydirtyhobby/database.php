<?php
/**
 * Loading mydirtyhobby mydirtyhobby database helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_mdh_database_notice' ) ) {
    /**
     * at_import_mdh_database_notice
     *
     */
    add_action( 'admin_notices', 'at_import_mdh_database_notice' );
    function at_import_mdh_database_notice() {
        global $wpdb;

        $database = new AT_Import_MDH_DB();

        if(
            $wpdb->get_var("show tables like '" . $database->table_amateurs . "'") != $database->table_amateurs ||
            $wpdb->get_var("show tables like '" . $database->table_videos . "'") != $database->table_videos
        ) {
            ?>
            <div class="error">
                <p>
                    <?php _e('Eine oder mehrere Datenbank-Tabellen für den Import fehlen. Bitte aktualisiere deine Datenbank.', 'amateurtheme'); ?>
                    <a class="button" id="at-import-mdh-install-tables"><?php _e('Datenbank aktualisieren', 'amateurtheme'); ?></a>
                </p>
            </div>

            <script type="text/javascript">
                jQuery('#at-import-mdh-install-tables').bind('click', function (e) {
                    var target = jQuery(this).closest('.error');
                    jQuery(this).append(' <span class="spinner" style="visibility:initial"></span>');

                    jQuery.get(ajaxurl + '?&action=at_import_mdh_install_tables', {}).done(function (data) {
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
}

if ( ! function_exists( 'at_import_mdh_install_tables' ) ) {
    /**
     * at_import_mdh_install_tables
     *
     */
    add_action("wp_ajax_at_import_mdh_install_tables", "at_import_mdh_install_tables");
    function at_import_mdh_install_tables() {
        at_import_mdh_database_tables();

        $response = array();

        $response['status'] = 'ok';

        echo json_encode($response);

        exit();
    }
}

if ( ! function_exists( 'at_import_mdh_database_tables' ) ) {
    /**
     * at_import_mdh_database_tables
     *
     */
    add_action('upgrader_process_complete', 'at_import_mdh_database_tables', 10, 1);
    add_action('after_switch_theme', 'at_import_mdh_database_tables');
    function at_import_mdh_database_tables() {
        global $wpdb;

        $database = new AT_Import_MDH_DB();

        /**
         *  Amateure
         */
        if ($wpdb->get_var("show tables like '" . $database->table_amateurs . "'") != $database->table_amateurs) {
            $sql = "CREATE TABLE " . $database->table_amateurs . " (
                id int(11) NOT NULL AUTO_INCREMENT,
                uid int(11),
                username text,
                PRIMARY KEY (id)
            );";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }

        /**
         *  Videos
         */
        if ($wpdb->get_var("show tables like '" . $database->table_videos . "'") != $database->table_videos) {
            $sql = "CREATE TABLE " . $database->table_videos . " (
                id int(11) NOT NULL AUTO_INCREMENT,
                source_id varchar(25),
                object_id varchar(25),
                object_name text,
                video_id int(25),
                preview text,
                title varchar(255),
                duration varchar(50),
                rating varchar(50),
                rating_count varchar(50),
                date datetime,
                description text,
                link text,
                tags text,
                categories text,
                language varchar(10),
                imported int(1),
                PRIMARY KEY (id),
                UNIQUE KEY (video_id)
            );";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}