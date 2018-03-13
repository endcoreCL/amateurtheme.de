<?php
/**
 * Loading big7 import helper functions
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

if ( ! function_exists( 'at_import_big7_scripts' ) ) {
    /**
     * at_import_big7_scripts function
     *
     */
    add_action('admin_enqueue_scripts', 'at_import_big7_scripts');
    function at_import_big7_scripts($page) {
        if (strpos($page, 'at_import_big7') === false) return;

        wp_enqueue_script('at-big7', get_template_directory_uri() . '/modules/import/_assets/js/big7.js');
    }
}

if ( ! function_exists( 'at_import_big7_prepare_video_fields' ) ) {
    /**
     * at_import_big7_prepare_video_fields
     *
     * @param $video_id
     * @return array|bool
     */
    function at_import_big7_prepare_video_fields($data) {
        if ($data) {
            // format duration
            $raw_duration = preg_replace('/\D/', '', $data->duration);
            $duration = gmdate("H:i:s", $raw_duration);

            // format date
            $raw_date = $data->date;
            $date = date('Ymd', strtotime($raw_date));

            // format rating
            $raw_rating = $data->rating / 10;
            $rating = $rating = round(($raw_rating / 2) * 2) / 2;

            $fields = array(
                'duration' => $duration,
                'views' => 0,
                'date' => $date,
                'rating' => $rating,
                'rating_count' => $data->rating_count,
                'url' => $data->link,
                'source' => 'big7',
                'language' => 'de',
                'unique_id' => $data->video_id
            );

            return $fields;
        }

        return false;
    }
}

if ( ! function_exists( 'at_import_big7_get_video_count' ) ) {
	/**
	 * at_import_big7_get_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_big7_get_video_count($source_id) {
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$videos = $wpdb->get_var('SELECT COUNT(id) as count FROM ' . $database->table_videos . ' WHERE uid = ' . $source_id);

		if($videos) {
			return $videos;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_imported_video_count' ) ) {
    /**
     * at_import_big7_get_imported_video_count
     *
     * @param $source_id
     * @param bool $imported
     * @return string
     */
    function at_import_big7_get_imported_video_count($source_id) {
        global $wpdb;

        $term = $wpdb->get_var('SELECT term_id FROM ' . $wpdb->termmeta . ' WHERE meta_value = "' . $source_id . '"');

        if($term) {
            $item = get_term_by('id', $term, 'video_actor');
            if($item) {
                return $item->count;
            }
        }

        return '0';
    }
}

if ( ! function_exists( 'at_import_big7_get_category_video_count' ) ) {
	/**
	 * at_import_big7_get_video_category_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_big7_get_category_video_count($category) {
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$videos = $wpdb->get_var('SELECT COUNT(id) as count FROM ' . $database->table_videos . ' WHERE categories LIKE "%' . $category . '%"');

		if($videos) {
			return $videos;
		}

		return '0';
	}
}

if ( ! function_exists( 'at_import_big7_get_category_imported_video_count' ) ) {
	/**
	 * at_import_big7_get_category_imported_video_count
	 *
	 * @param $source_id
	 * @param bool $imported
	 * @return string
	 */
	function at_import_big7_get_category_imported_video_count($category) {
		global $wpdb;

		$term = get_term_by('name', $category, 'video_actor');

		if($term) {
			return $term->count;
		}

		return '0';
	}
}

function at_import_big7_get_categories() {
	global $wpdb;

	$database = new AT_Import_Big7_DB();

	$data = $wpdb->get_var(
		"
		SELECT
		  GROUP_CONCAT( DISTINCT SUBSTRING_INDEX(SUBSTRING_INDEX(categories, ',', n.digit+1), ',', -1)) categories
		FROM
		  " . $database->table_videos . "
		  INNER JOIN
		  (SELECT 0 digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3  UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6) n
		  ON LENGTH(REPLACE(categories, ',' , '')) <= LENGTH(categories)-n.digit
		"
	);

	if($data) {
		$items = explode(',', $data);
		$results = array();

		if($items) {
			foreach($items as $item) {
				$unique = substr(base_convert(md5($item), 16, 10) , -10);
				$results[$unique] = $item;
			}
		}

		return $results;
	}

	return false;
}

function at_import_json_process($item) {
    print_r($item);
}
