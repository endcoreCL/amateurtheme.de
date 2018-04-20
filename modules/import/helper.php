<?php
add_filter('cron_schedules','at_import_cron_schedules');
function at_import_cron_schedules($schedules){
    if(!isset($schedules["5min"])){
        $schedules["5min"] = array(
            'interval' => 5*60,
            'display' => __('Once every 5 minutes'));
    }
    if(!isset($schedules["15min"])){
        $schedules["15min"] = array(
            'interval' => 15*60,
            'display' => __('Once every 15 minutes'));
    }
    if(!isset($schedules["30min"])){
        $schedules["30min"] = array(
            'interval' => 30*60,
            'display' => __('Once every 30 minutes'));
    }
	if(!isset($schedules["weekly"])){
		$schedules["weekly"] = array(
			'interval' => ((60*60) * 24) *7,
			'display' => __('Once every week'));
	}
    return $schedules;
}


/**
 * Function: at_import_menu
 * Load menu items
 */
add_action('admin_menu', 'at_import_menu');
function at_import_menu() {
    add_menu_page('at_import', __('Import', 'amateurtheme'), 'administrator', 'at_import', "at_import", "dashicons-smiley");
    add_submenu_page( 'at_import', __('Big7', 'amateurtheme'), __('Big7', 'amateurtheme'), 'administrator', 'at_import_big7_panel', 'at_import_big7_panel');
    add_submenu_page( 'at_import', __('MyDirtyHobby', 'amateurtheme'), __('MyDirtyHobby', 'amateurtheme'), 'administrator', 'at_import_mydirtyhobby_panel', 'at_import_mydirtyhobby_panel');
	add_submenu_page( 'at_import', __('PornMe', 'amateurtheme'), __('PornMe', 'amateurtheme'), 'administrator', 'at_import_pornme_panel', 'at_import_pornme_panel');
	add_submenu_page( 'at_import', __('AmateurCommunity', 'amateurtheme'), __('AmateurCommunity', 'amateurtheme'), 'administrator', 'at_import_ac_panel', 'at_import_ac_panel');
}

/**
 * Function: at_import_menu_scripts
 * Load import scripts
 */
add_action('admin_enqueue_scripts', 'at_import_menu_scripts');
function at_import_menu_scripts($page) {
    if(strpos($page, 'at_import_') === false) return;

    // CSS
    wp_enqueue_style( 'at-import-panel', get_template_directory_uri() . '/modules/import/_assets/css/panel.css', false, '1.0');
    wp_enqueue_style( 'at-select2', get_template_directory_uri() . '/modules/import/_assets/css/select2.min.css', false, '1.0');

    // JS
    wp_enqueue_script( 'at-import-panel', get_template_directory_uri() . '/modules/import/_assets/js/panel.js');
    wp_enqueue_script( 'at-select2', get_template_directory_uri() . '/modules/import/_assets/js/select2.full.min.js');
}

/**
 * Function: at_import_cronjob_add
 * Add new cronjob
 */
add_action("wp_ajax_at_import_cronjob_add", "at_import_cronjob_add");
function at_import_cronjob_add() {
    $message = array(
        'status' => 'error'
    );

    $network = (isset($_GET['network']) ? $_GET['network'] : '');
    $type = (isset($_GET['type']) ? $_GET['type'] : '');

    if($network == '' || $type == '') {
        echo wp_json_encode($message);
        exit;
    }

    if($type == 'user') {
        $uid = (isset($_GET['uid']) ? $_GET['uid'] : '');
        $username = (isset($_GET['username']) ? $_GET['username'] : '');

        $args = array(
            'object_id' => $uid,
            'name' => $username,
            'alias' => $username,
            'network' => $network,
            'type' => $type
        );
    } else if($type == 'category') {
        $catid = (isset($_GET['catid']) ? $_GET['catid'] : '');
        $catname = (isset($_GET['catname']) ? $_GET['catname'] : '');

        $args = array(
            'object_id' => $catid,
            'name' => $catname,
            'alias' => $catname,
            'network' => $network,
            'type' => $type
        );
    }

    $cron = new AT_Import_Cron();
    $cron->add($args);

    $message['status'] = 'ok';

    echo wp_json_encode($message);

    do_action('at_import_cronjob_add');

    exit;
}

/**
 * Function: at_import_cronjob_edit
 * Edit cronjob
 */
add_action("wp_ajax_at_import_cronjob_edit", "at_import_cronjob_edit");
function at_import_cronjob_edit() {
    $message = array(
        'status' => 'error'
    );

    $object_id = (isset($_GET['id']) ? $_GET['id'] : '');
    $field = (isset($_GET['field']) ? $_GET['field'] : '');
    $value = (isset($_GET['value']) ? $_GET['value'] : '');

    if($object_id == '' || $field == '') {
        echo wp_json_encode($message);
        exit;
    }

    $fields = array($field => $value);
    $where = array('id' => $object_id);

    $cron = new AT_Import_Cron();
    $cron->update($fields, $where);

    $message['status'] = 'ok';

    echo wp_json_encode($message);

    do_action('at_import_cronjob_edit', $object_id, $field, $value);

    exit;
}

/**
 * Function: at_import_cronjob_delete
 * Delete cronjob
 */
add_action("wp_ajax_at_import_cronjob_delete", "at_import_cronjob_delete");
function at_import_cronjob_delete() {
    $message = array(
        'status' => 'error'
    );

    $id = (isset($_GET['id']) ? $_GET['id'] : '');

    if($id == '') {
        echo wp_json_encode($message);
        exit;
    }

	do_action('at_import_cronjob_delete', $id);

    $cron = new AT_Import_Cron();
    $cron->delete($id);

    $message['status'] = 'ok';

    echo wp_json_encode($message);

    exit;
}

/**
 * Remove thumbnails before delete video
 */
add_action('before_delete_post', 'at_import_remove_media_when_deleted');
function at_import_remove_media_when_deleted($post_id) {
	global $post_type;
	if ( $post_type != 'video' ) return;

	if( has_post_thumbnail($post_id)) {
		$attachment_id = get_post_thumbnail_id($post_id);

		if ( empty ( get_posts( array( 'post_type' => 'any', 'post_status' => 'any', 'fields' => 'ids', 'no_found_rows' => true, 'posts_per_page' => -1, 'meta_key' => '_thumbnail_id', 'meta_value' => $attachment_id, 'post__not_in' => array( $post_id ) ) ) ) ) {
			$attachment_urls = array( wp_get_attachment_url( $attachment_id ) );
			foreach ( get_intermediate_image_sizes() as $size ) {
				$intermediate = image_get_intermediate_size( $attachment_id, $size );
				if ( $intermediate ) {
					$attachment_urls[] = $intermediate['url'];
				}
			}

			$used = array();
			foreach ( $attachment_urls as $attachment_url ) {
				$used = array_merge( $used, get_posts( array( 'post_type' => 'any', 'post_status' => 'any', 'fields' => 'ids', 'no_found_rows' => true, 'posts_per_page' => -1, 's' => $attachment_url, 'post__not_in' => array( $post_id ) ) ) );
			}

			if ( empty( $used ) ) {
				wp_delete_attachment($attachment_id, true);
			}
		}
	}
}


if ( ! function_exists( 'at_write_api_log' ) ) {
    /**
     * at_write_api_log function.
     *
     * @param  string $type
     * @param  int $post_id
     * @param  string $msg
     * @return null
     */
    function at_write_api_log($type, $post_id, $msg) {
        if (!$type)
            return;

        if (!$post_id)
            return;

        $log = (is_array(get_option('at_' . $type . '_api_log')) ? get_option('at_' . $type . '_api_log') : array());
        $log[] = array('time' => time(), 'post_id' => $post_id, 'msg' => $msg);

        // limit log to 200 items
        $log = array_reverse($log);
        $log = array_slice($log, 0, 200);
        $log = array_reverse($log);

        update_option('at_' . $type . '_api_log', $log);
    }
}

if ( ! function_exists( 'at_delete_api_log' ) ) {
    /**
     * at_delete_api_log function.
     *
     */
    add_action('wp_ajax_at_api_clear_log', 'at_delete_api_log');
    function at_delete_api_log() {
        $type = (isset($_GET['type']) ? $_GET['type'] : '');

        update_option('at_' . $type . '_api_log', array());

        $status = array('status' => 'success');

        echo json_encode($status);

        exit();
    }
}

if ( ! function_exists( 'at_import_check_if_video_exists' ) ) {
    /**
     * at_import_check_if_video_exists
     *
     * @param $video_id
     * @return bool
     */
    function at_import_check_if_video_exists($video_id) {
        global $wpdb;

        $unique_post = $wpdb->get_var(
            $wpdb->prepare("
			SELECT count(meta_id)
			FROM $wpdb->postmeta wpm
			WHERE wpm.meta_value = '%s'",
                $video_id
            )
        );

        if ($unique_post > 0) {
            return false;
        }

        return true;
    }
}