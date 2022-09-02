<?php

/**
 * Created by PhpStorm.
 * User: christianlang
 * Date: 04.08.17
 * Time: 15:22
 *
 * Fallback WMID: 1217
 * Fallback Security key: c6aafc209e9593997dd949a85e15d49a
 */
class AT_Import_Big7_Crawler
{
	public function __construct()
	{
		// folder
		$upload_dir   = wp_upload_dir();
		$this->folder = $upload_dir['basedir'] . '/big7';

		$this->wmb          = get_option( 'at_big7_wmb' );
		$this->security_key = get_option( 'at_big7_security_key' );
		$this->videos       = 'https://cash.big7.com/xml_export.php?wmb=' . $this->wmb . '&security_key=' . $this->security_key . '&file=all_amateurs_videos&format=json&dl';
		$this->amateure     = 'https://cash.big7.com/xml_export.php?wmb=' . $this->wmb . '&security_key=' . $this->security_key . '&file=all_amateurs&format=json&dl';
		$this->categories   = 'https://cash.big7.com/xml_export.php?wmb=' . $this->wmb . '&security_key=' . $this->security_key . '&file=video_categories&format=json';

		$this->json_result = array();
	}

	function json_callback( $item )
	{
		$this->json_result[] = $item;
	}

	function get( $params = array() )
	{
		if ( $params['type'] == 'videos' ) {
			$url = $this->videos;
		}

		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, 1 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

		$response = curl_exec( $curl );

		if ( curl_errno( $curl ) ) {
			return 'Curl error: ' . curl_error( $curl );
		}

		curl_close( $curl );

		return $response;
	}

	function save( $data, $filename = 'videos.json' )
	{
		try {
			$fp = fopen( $this->folder . '/' . $filename, 'w' );
			fwrite( $fp, $data );
			fclose( $fp );

			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}

	function read( $filename = 'videos.json', $filter = array() )
	{
		//$cache = get_transient( 'at_import_big7_json_cache' );

		//if(!file_exists($this->folder . '/' . $filename) || $cache === false) {
		/**
		 * Try to get the file from Big7 directly
		 */
		if ( $filename == 'videos.json' ) {
			$data = file_get_contents( $this->videos );
		} else {
			$data = file_get_contents( $this->amateure );
		}

		if ( $data ) {
			$this->save( $data, $filename );
			//set_transient( 'at_import_big7_json_cache', 'cached' );
		}
		//}

		set_time_limit( 0 );

		$parser = new \JsonCollectionParser\Parser();
		$parser->parse( $this->folder . '/' . $filename, [ $this,
			'json_callback' ], true );

		$json_data = $this->json_result;

		if ( $json_data ) {
			if ( $filter ) {
				$filtered_data = array();

				foreach ( $filter as $key => $value ) {
					foreach ( $json_data as $k => $v ) {
						if ( $v[ $key ] == $value ) {
							$filtered_data[] = $v;
						}
					}
				}

				return $filtered_data;
			}

			return $json_data;
		}

		return false;
	}


	function getAmateur( $uid )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$data = $wpdb->get_results(
			"
    		SELECT * FROM {$database->table_amateurs}
    	  	WHERE uid = {$uid}
    		",
			OBJECT
		);

		if ( $data ) {
			return $data[0];
		}

		return false;
	}

	function getAmateurName( $uid )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$data = $wpdb->get_var(
			"
    		SELECT username FROM {$database->table_amateurs}
    	  	WHERE uid = {$uid}
    		"
		);

		if ( $data ) {
			return $data;
		}

		return false;
	}

	function getVideos( $uid )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$data = $wpdb->get_results(
			"
    		SELECT * FROM {$database->table_videos}
    	  	WHERE uid = {$uid}
    		",
			OBJECT
		);

		if ( $data ) {
			// sanitize categories
			$Crawler    = new AT_Import_Big7_Crawler();
			$categories = $Crawler->getCategories();
			$fsk18      = get_option( 'at_big7_fsk18' );

			if ( $categories && $fsk18 ) {
				foreach ( $data as $k => $v ) {
					$itemCategories = explode( ',', $v->categories );

					if ( $itemCategories ) {
						foreach ( $itemCategories as $key => $value ) {
							foreach ( $categories as $term ) {
								if ( $value == $term['name_softcore'] ) {
									$itemCategories[ $key ] = $term['name'];
								}
							}
						}

						$data[ $k ]->categories = implode( ',', $itemCategories );
					}
				}
			}

			return $data;
		}

		return false;
	}

	function getVideosByCategory( $category, $offset = 0, $limit = 100 )
	{
		global $wpdb;

		$database = new AT_Import_Big7_DB();

		$data = $wpdb->get_results(
			"
            SELECT * FROM {$database->table_videos}
            WHERE categories LIKE '%{$category}%'
            LIMIT {$offset}, {$limit}
            ",
			OBJECT
		);

		if ( $data ) {
			// sanitize categories
			$Crawler    = new AT_Import_Big7_Crawler();
			$categories = $Crawler->getCategories();
			$fsk18      = get_option( 'at_big7_fsk18' );

			if ( $categories && $fsk18 ) {
				foreach ( $data as $k => $v ) {
					$itemCategories = explode( ',', $v->categories );

					if ( $itemCategories ) {
						foreach ( $itemCategories as $key => $value ) {
							foreach ( $categories as $term ) {
								if ( $value == $term['name_softcore'] ) {
									$itemCategories[ $key ] = $term['name'];
								}
							}
						}

						$data[ $k ]->categories = implode( ',', $itemCategories );
					}
				}
			}

			return $data;
		}

		return false;
	}

	function jsonAmateurs()
	{
		$data = $this->read( 'amateure.json' );

		if ( $data ) {
			return $data;
		}

		return false;
	}

	function jsonVideos()
	{
		$data = $this->read( 'videos.json' );

		if ( $data ) {
			return $data;
		}

		return false;
	}

	function getCategories()
	{
		$categories = get_transient( 'at_import_big7_categories' );

		if ( ! $categories ) {
			$jsonData = file_get_contents( $this->categories );

			if ( $jsonData ) {
				$categories = json_decode( $jsonData, true );
				set_transient( 'at_import_big7_categories', $categories, 72 * HOUR_IN_SECONDS );
			}
		}

		return $categories;
	}
}