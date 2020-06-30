<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 25.07.17
 * Time: 14:12
 */
class AT_Import_PornMe_DB
{
    public function __construct ()
    {
        // tables
        global $wpdb;
        $this->table_amateurs = $wpdb->prefix . 'import_pornme_amateurs';
        $this->table_videos   = $wpdb->prefix . 'import_pornme_videos';
        $this->table_tags     = $wpdb->prefix . 'import_pornme_tags';

        // ajax
        add_action( 'wp_ajax_at_import_pornme_db_amateur', array( &$this, 'generateAmateurDB' ) );

        // initiate cronjob func
        add_action( 'at_import_pornme_generate_amateur_db', array( &$this, 'generateAmateurDB' ) );
        add_action( 'at_import_pornme_generate_tag_db', array( &$this, 'generateTagDB' ) );

        // initiate cronjob
        if ( ! wp_next_scheduled( 'at_import_pornme_generate_amateur_db' ) ) {
            wp_schedule_event( time(), 'weekly', 'at_import_pornme_generate_amateur_db' );
        }
        if ( ! wp_next_scheduled( 'at_import_pornme_generate_tag_db' ) ) {
            wp_schedule_event( time(), 'weekly', 'at_import_pornme_generate_tag_db' );
        }
    }

    public function generateAmateurDB ()
    {
        global $wpdb;

        $crawler     = new AT_Import_PornMe_Crawler();
        $total_pages = $crawler->getNumPages( 'user' );

        at_error_log( 'Started cronjob (PornMe, generateAmateurDB)' );

        if ( $total_pages ) {
            $wpdb->hide_errors();

            $count = 0;
            for ( $i = 0; $i <= $total_pages; $i++ ) {
                $amateurs = $crawler->jsonAmateurs( $i );

                if ( isset( $amateurs['amateurs'] ) ) {
                    foreach ( $amateurs['amateurs'] as $amateur ) {
                        $uid      = intval( $amateur['UID'] );
                        $username = $amateur['username'];

                        $wpdb->query(
                            "
							INSERT INTO {$this->table_amateurs} (uid, username) VALUES ({$uid} ,'" . esc_sql( $username ) . "')
							ON DUPLICATE KEY UPDATE username = '" . esc_sql( $username ) . "'
							"
                        );

                        $count++;
                    }
                }
            }

            at_error_log( 'Stopped cronjob (PornMe, generateAmateurDB), imported ' . $count . ' amateurs.' );
        }
    }

    public function generateTagDB ()
    {
        global $wpdb;

        $crawler     = new AT_Import_PornMe_Crawler();
        $total_pages = $crawler->getNumPages( 'tag' );

        at_error_log( 'Started cronjob (PornMe, generateTagsDB)' );

        if ( $total_pages ) {
            $wpdb->hide_errors();

            $count = 0;
            for ( $i = 0; $i <= $total_pages; $i++ ) {
                $tags = $crawler->jsonTags( $i );

                if ( isset( $tags['categories'] ) ) {
                    foreach ( $tags['categories'] as $k => $tag ) {
                        $wpdb->query(
                            "
							INSERT IGNORE INTO {$this->table_tags} (tag) VALUES ('" . esc_sql( $tag ) . "')
							"
                        );

                        $count++;
                    }
                }
            }

            at_error_log( 'Stopped cronjob (PornMe, generateTagsDB), imported ' . $count . ' tags.' );
        }
    }
}

$AT_Import_PornMe_DB = new AT_Import_PornMe_DB();