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
    return $schedules;
}


/**
 * Function: at_import_menu
 * Load menu items
 */
add_action('admin_menu', 'at_import_menu');
function at_import_menu() {
    add_menu_page('at_import', __('Import', 'amateurtheme'), 'administrator', 'at_import', "at_import", "dashicons-smiley");
    add_submenu_page( 'at_import', __('MyDirtyHobby', 'amateurtheme'), __('MyDirtyHobby', 'amateurtheme'), 'administrator', 'at_import_mydirtyhobby_panel', 'at_import_mydirtyhobby_panel');
}

/**
 * Function: at_import_menu_scripts
 * Load import scripts
 */
add_action('admin_enqueue_scripts', 'at_import_menu_scripts');
function at_import_menu_scripts($page) {
    if(strpos($page, 'at_import_') === false) return;

    // CSS
    wp_enqueue_style( 'at-import-panel', get_template_directory_uri() . '/library/import/assets/css/panel.css', false, '1.0');
    wp_enqueue_style( 'at-select2', get_template_directory_uri() . '/library/import/assets/css/select2.min.css', false, '1.0');

    // JS
    wp_enqueue_script( 'at-import-panel', get_template_directory_uri() . '/library/import/assets/js/panel.js');
    wp_enqueue_script( 'at-select2', get_template_directory_uri() . '/library/import/assets/js/select2.full.min.js');
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

    $cron = new AT_Import_Cron();
    $cron->delete($id);

    $message['status'] = 'ok';

    echo wp_json_encode($message);

    do_action('at_import_cronjob_delete', $id);

    exit;
}