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

    error_log(print_r($_POST, true));

    $database = new AT_Import_MDH_DB();
    $results = '';
    $video_id = $_POST['id'];
    $video_category = $_POST['video_category'];
    $video_actor = $_POST['video_actor'];

    $video = new AT_Import_Video($video_id);

    if($video) {
        $post_id = $video->insert();

        if($post_id) {
            $item = $wpdb->get_row('SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id);

            if($item) {
                // fields
                $fields = at_import_mdh_prepare_video_fields($video_id);
                if($fields) {
                    $video->set_fields($fields);
                }

                // thumbnail
                if($item->preview) {
                    $preview  = json_decode($item->preview);
                    $image = $preview->censored;

                    if(get_option('at_mdh_fsk18') == '1') {
                        $image = $preview->normal;
                    }

                    if($image) {
                        $video->set_thumbnail($image);
                    }
                }

                // category
                if($video_category != '-1' && $video_category != '') {
                    $video->set_term('video_category', $video_category);
                } else {
                    $categories = json_decode($item->categories);
                    if($categories) {
                        foreach ($categories as $cat) {
                            if ($cat->name) {
                                $video->set_term('video_category', $cat->name);
                            }
                        }
                    }
                }

                // actor
                if($video_actor != '-1' && $video_actor != '') {
                    $video->set_term('video_actor', $video_actor);
                } else {
                    $video->set_term('video_actor', $item->object_name);
                }

                // update video item as imported
                $wpdb->update(
                    $database->table_videos,
                    array(
                        'imported' => 1
                    ),
                    array(
                        'video_id' => $video_id
                    )
                );
            }

            $results = $video_id;
        }
    } else {
        // video already exist, update video as imported
        $wpdb->update(
            $database->table_videos,
            array(
                'imported' => 1
            ),
            array(
                'video_id' => $video_id
            )
        );

        $results = 'error';
    }

    echo $results;

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

function at_import_mdh_prepare_video_fields($video_id) {
    global $wpdb;
    $database = new AT_Import_MDH_DB();
    $video = $wpdb->get_row('SELECT * FROM ' . $database->table_videos . ' WHERE video_id = ' . $video_id);
    if($video) {
        // format duration
        $raw_duration = $video->duration;
        $duration = gmdate("H:i:s", str_replace('.', '', $raw_duration));

        // format date
        $raw_date = $video->date;
        $date = date('Ymd', strtotime($raw_date));

        // format rating
        $raw_rating = $video->rating;
        $rating = $rating = round(($raw_rating/2)*2)/2;

        $fields = array(
            'duration' => $duration,
            'views' => 0,
            'date' => $date,
            'rating' => $rating,
            'rating_count' => $video->rating_count,
            'url' => $video->link,
            'source' => 'mdh',
            'language' => $video->language,
            'unique_id' => $video_id
        );

        return $fields;
    }

    return false;
}