<?php
/**
 * Loading ac amateur cronjob functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_ac_amateur_cronjob_initiate' ) ) {
    /**
     * at_import_ac_amateur_cronjob_initiate function
     *
     */
    add_action('at_import_cronjob_edit', 'at_import_ac_amateur_cronjob_initiate', 10, 3);
    function at_import_ac_amateur_cronjob_initiate($id, $field, $value) {
        if ($field == 'import') {
            global $wpdb;

            $cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

            if ($cron) {
                if ($cron->network == 'ac') {
                    if ($value == '1') {
                        if (!wp_next_scheduled('at_import_ac_import_videos_cronjob', array($id))) {
                            wp_schedule_event(time(), '30min', 'at_import_ac_import_videos_cronjob', array($id));
                        }
                    } else {
                        wp_clear_scheduled_hook('at_import_ac_import_videos_cronjob', array($id));
                    }
                }
            }
        }
    }
}

if ( ! function_exists( 'at_import_ac_amateur_cronjob_delete' ) ) {
	/**
	 * at_import_ac_amateur_cronjob_delete function
	 *
	 */
	add_action('at_import_cronjob_delete', 'at_import_ac_amateur_cronjob_delete', 10, 1);
	function at_import_ac_amateur_cronjob_delete($id) {
		global $wpdb;

		$cron = $wpdb->get_row('SELECT * FROM ' . AT_CRON_TABLE . ' WHERE id = ' . $id);

		if ($cron) {
			if ($cron->network == 'ac') {
				wp_clear_scheduled_hook('at_import_ac_import_videos_cronjob', array($id));
			}
		}
	}
}