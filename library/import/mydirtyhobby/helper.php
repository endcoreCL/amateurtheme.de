<?php
/**
 * Function at_import_mdh_scripts
 * Load import scripts
 */
add_action('admin_enqueue_scripts', 'at_import_mdh_scripts');
function at_import_mdh_scripts($page) {
    if(strpos($page, 'at_import_mydirtyhobby') === false) return;

    wp_enqueue_script( 'at-mydirtyhobby', get_template_directory_uri() . '/library/import/assets/js/mydirtyhobby.js');
}

add_action('wp_ajax_at_import_mdh_crawl_amateurs', 'at_import_mdh_crawl_amateurs');
function at_import_mdh_crawl_amateurs() {
    $offset = (isset($_GET['offset']) ? $_GET['offset'] : '0');
    $import = new AT_Import_MDH_Crawler();
    $response = $import->crawlAmateurs($offset);
    echo json_encode($response);
    exit;
}

add_action('wp_ajax_at_import_mdh_get_videos', 'at_import_mdh_get_videos');
function at_import_mdh_get_videos() {
    $u_id = (isset($_GET['u_id']) ? $_GET['u_id'] : false);

    if(!$u_id) {
        echo json_encode(array('status', 'error'));
        exit;
    }

    $import = new AT_Import_MDH_Crawler();
    $response = $import->getAmateurVideos($u_id);

    if($response) {
        foreach($response as $item) {
            $item->imported = "false";

            $unique = at_import_mdh_check_if_video_exists($item->id);

            if(!$unique) {
                $item->imported = "true";
            }
        }
    }

    echo json_encode($response);
    exit;
}

add_action("wp_ajax_at_mdh_import_video", "at_mdh_import_video");
function at_mdh_import_video() {
    global $wpdb;

    if ( !function_exists( 'ini_get' ) || !ini_get( 'safe_mode' ) ) {
        set_time_limit( 0 );
    }

    $database = new AT_Import_MDH_DB();

    // set import var to prevent multiple imports at the same time
    update_option('mdh_import_running', '1');

    $results = '';
    $id = $_POST['id'];
    $image = $_POST['image'];
    $title = $_POST['title'];
    $duration = str_replace('min', '', trim($_POST['duration']));
    $rating = round(($_POST['rating']/2)*2)/2;
    $time = $_POST['time'];
    $description = $_POST['description'];

    if(!$date = date_create_from_format('d.m.Y', $time)) {
        $date = date_create_from_format('d.m.y', date('d.m.y'));
    }
    $date = $date->format('Ymd');

    $video_category = $_POST['video_category'];
    $video_actor = $_POST['video_actor'];

    $admin = $wpdb->get_results("SELECT * from $wpdb->users LIMIT 0,1");
    $post_author = $admin[0]->ID;

    /*
     * CHECK IF VIDEO IS ALREADY IMPORTED
     */
    $unique = at_import_mdh_check_if_video_exists($id);

    if($unique) {
        $args = array(
            'post_title' => $title,
            'post_status' => (get_option('at_mdh_post_status') ? get_option('at_mdh_post_status') : 'publish'),
            'post_author' => $post_author,
            'post_type' => 'video',
        );

        if(get_option('at_mdh_video_description') == '1') {
            $args['post_content'] = $description;
        }

        $video_id = wp_insert_post($args);

        if($video_id) {
            add_post_meta($video_id, 'video_format', 'link');
            add_post_meta($video_id, 'video_link', 'http://in.mydirtyhobby.com/track/' . get_option('at_mdh_naffcode') . '/?ac=user&ac2=previewvideo&uv_id=' . $id);
            add_post_meta($video_id, 'video_dauer', $duration);
            add_post_meta($video_id, 'video_bewertung', $rating);
            add_post_meta($video_id, 'video_aufrufe', '0');
            add_post_meta($video_id, 'video_src', 'mdh');
            add_post_meta($video_id, 'video_unique_id', $id);

            if($image) {
                $att_id = at_attach_external_image($image, $video_id, true, $video_id.'-vorschau', array('post_title' => $title));
            }

            if($video_category != "-1") wp_set_object_terms($video_id, $video_category, 'video_category');
            if($video_actor != "-1") wp_set_object_terms($video_id, $video_actor, 'video_actor');

            $results = $id;

            $wpdb->query(
                "UPDATE $database->table_videos SET imported = '1' WHERE video_id = $id"
            );

        } else {
            $results = 'error';
        }
    } else {
        $wpdb->query(
            "UPDATE $database->table_videos SET imported = '1' WHERE video_id = $id"
        );
    }

    echo $results;

    // set import var to prevent multiple imports at the same time
    update_option('mdh_import_running', '0');

    die();
}

add_action('wp_ajax_at_import_mdh_get_category_videos', 'at_import_mdh_get_category_videos');
function at_import_mdh_get_category_videos() {
    $c_id = (isset($_GET['c_id']) ? $_GET['c_id'] : false);

    if(!$c_id) {
        echo json_encode(array('status', 'error'));
        exit;
    }

    $import = new AT_Import_MDH_Crawler();
    $response = $import->getCategoryVideos($c_id);

    if($response) {
        foreach($response as $item) {
            $item->imported = "false";

            $unique = at_import_mdh_check_if_video_exists($item->id);

            if(!$unique) {
                $item->imported = "true";
            }
        }
    }

    echo json_encode($response);
    exit;
}

function at_import_mdh_get_video_count($source_id, $imported = false) {
    global $wpdb;

    $database = new AT_Import_MDH_DB();

    if($imported) {
        $result = $wpdb->get_row('SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '" AND imported = 1');
    } else {
        $result = $wpdb->get_row('SELECT COUNT(source_id) as count FROM ' . $database->table_videos . ' WHERE source_id = "' . $source_id . '"');
    }

    if($result) {
        return $result->count;
    }

    return '0';
}

/**
 * Function: Check if Videos exists
 */
function at_import_mdh_check_if_video_exists($video_id) {
    global $wpdb;

    $database = new AT_Import_MDH_DB();

    $unique_user_video = $wpdb->get_var(
        $wpdb->prepare("
			SELECT count(id)
			FROM $database->table_videos
			WHERE video_id = '%s'
			AND imported = '1'",
            $video_id
        )
    );

    if($unique_user_video != '0') {
        return false;
    }

    $unique_post = $wpdb->get_var(
        $wpdb->prepare("
			SELECT count(id)
			FROM $wpdb->posts wpo, $wpdb->postmeta wpm
			WHERE wpo.ID = wpm.post_id
			AND wpm.meta_key = 'video_unique_id'
			AND wpm.meta_value = '%s'",
            $video_id
        )
    );

    if($unique_post != '0') {
        return false;
    }

    return true;
}