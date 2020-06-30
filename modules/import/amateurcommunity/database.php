<?php
/**
 * Loading ac database helper functions
 *
 * @author        Christian Lang
 * @version        1.0
 * @category    helper
 */

if ( ! function_exists( 'at_import_ac_database_notice' ) ) {
    /**
     * at_import_ac_database_notice
     *
     */
    add_action( 'admin_notices', 'at_import_ac_database_notice' );
    function at_import_ac_database_notice ()
    {
        global $wpdb;

        $database = new AT_Import_AC_DB();

        if (
            $wpdb->get_var( "show tables like '" . $database->table_amateurs . "'" ) != $database->table_amateurs ||
            $wpdb->get_var( "show tables like '" . $database->table_media . "'" ) != $database->table_media
        ) {
            ?>
            <div class="error">
                <p>
                    <?php _e( 'Eine oder mehrere Datenbank-Tabellen für den Import fehlen. Bitte aktualisiere deine Datenbank.', 'amateurtheme' ); ?>
                    <a class="button" id="at-import-ac-install-tables"><?php _e( 'Datenbank aktualisieren', 'amateurtheme' ); ?></a>
                </p>
            </div>

            <script type="text/javascript">
                jQuery('#at-import-ac-install-tables').bind('click', function (e) {
                    var target = jQuery(this).closest('.error');
                    jQuery(this).append(' <span class="spinner" style="visibility:initial"></span>');

                    jQuery.get(ajaxurl + '?&action=at_import_ac_install_tables', {}).done(function (data) {
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

if ( ! function_exists( 'at_import_ac_install_tables' ) ) {
    /**
     * at_import_ac_install_tables
     *
     */
    add_action( "wp_ajax_at_import_ac_install_tables", "at_import_ac_install_tables" );
    function at_import_ac_install_tables ()
    {
        at_import_ac_database_tables();

        $response = array();

        $response['status'] = 'ok';

        echo json_encode( $response );

        exit();
    }
}

if ( ! function_exists( 'at_import_ac_database_tables' ) ) {
    /**
     * at_import_ac_database_tables
     *
     */
    add_action( 'upgrader_process_complete', 'at_import_ac_database_tables', 10, 1 );
    add_action( 'after_switch_theme', 'at_import_ac_database_tables' );
    function at_import_ac_database_tables ()
    {
        global $wpdb;

        $database = new AT_Import_AC_DB();

        /**
         *  Amateure
         */
        if ( $wpdb->get_var( "show tables like '" . $database->table_amateurs . "'" ) != $database->table_amateurs ) {
            $sql = "CREATE TABLE " . $database->table_amateurs . " (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  uid int(11) DEFAULT 0,
                  nickname text,
                  url text,
                  age int(2) DEFAULT 0,
                  body text,
                  bodyhair text,
                  brasize text,
                  country text,
                  ethnic text,
                  eyecolor text,
                  gender text,
                  haircolor text,
                  hairlength text,
                  marital text,
                  piercings text,
                  pubichair text,
                  tattoos text,
                  zipArea text,
                  glasses int(1) DEFAULT 0,
                  livedating int(1) DEFAULT 0,
                  livecam int(1) DEFAULT 0,
                  smoking int(1) DEFAULT 0,
                  weblog int(1) DEFAULT 0,
                  children int(1) DEFAULT 0,
                  height int(3) DEFAULT 0,
                  weight int(3) DEFAULT 0,
                  experiences text,
                  lookingfor text,
                  preferences text,   
                  languages text,
                  tags text,
                  description text,
                  description_sc text,
                  aboutMe text,
                  aboutMe_sc text,
                  othersAboutMe text,
                  othersAboutMe_sc text,
                  fantasies text,
                  fantasies_sc text,
                  image_small text,
                  image_medium text,
                  image_large text,
                  image_sc_small text,
                  image_sc_medium text,
                  image_sc_large text,
                PRIMARY KEY (id),
                UNIQUE KEY (uid)
            );";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }

        /**
         *  Media
         */
        if ( $wpdb->get_var( "show tables like '" . $database->table_media . "'" ) != $database->table_media ) {
            $sql = "CREATE TABLE " . $database->table_media . " (
                id int(11) NOT NULL AUTO_INCREMENT,
                uid int(11),
                nickname varchar(255),
                media_id varchar(255),
                title text,
                title_sc text,
                description text,
                description_sc text,
                date varchar(25),
                rating varchar(50),
                type varchar(25),
                votes varchar(25),
                categories text,
                preview text,
                preview_sc text,
                url text,
                imported int(1),
                PRIMARY KEY (id),
                UNIQUE KEY (media_id)
            );";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );
        }
    }
}