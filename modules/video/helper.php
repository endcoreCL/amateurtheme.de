<?php
/*
 * Hilfsfunktionen
 *
 * @author		Christian Lang
 * @version		1.0
 * @category	video
 */

add_image_size( 'video_grid', 345, 194, true );
add_image_size( 'term_grid', 253, 253, true );

if ( ! function_exists( 'at_video_admin_columns' ) ) {
	/**
	 * Manage admin columns for videos
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	add_filter( 'manage_edit-video_columns', 'at_video_admin_columns' );
	function at_video_admin_columns( $columns )
	{
		$columns = array(
			'cb'                      => '<input type="checkbox" />',
			'title'                   => __( 'Titel', 'xcore' ),
			'taxonomy-video_actor'    => __( 'Darsteller', 'amateurtheme' ),
			'taxonomy-video_category' => __( 'Kategorie', 'amateurtheme' ),
			'taxonomy-video_tags'     => __( 'Schlagwörter', 'amateurtheme' ),
			'video_source'            => __( 'Quelle', 'amateurtheme' ),
			'date'                    => __( 'Hinzugefügt am', 'amateurtheme' )
		);

		return $columns;
	}
}

if ( ! function_exists( 'at_video_admin_columns_content' ) ) {
	/**
	 * Output details in admin columns for videos
	 *
	 * @param $column
	 * @param $post_id
	 */
	add_action( 'manage_video_posts_custom_column', 'at_video_admin_columns_content', 10, 2 );
	function at_video_admin_columns_content( $column, $post_id )
	{
		global $wpdb;

		switch ( $column ) {
			case 'video_source':
				$video_source = get_field( 'video_source', $post_id );
				if ( $video_source ) {
					echo at_video_sanitize_source( $video_source );
				} else {
					echo '-';
				}
				break;

			default :
				break;
		}
	}
}

if ( ! function_exists( 'at_video_sanitize_source' ) ) {
	/**
	 * Sanitize the source of a video
	 *
	 * @param $source
	 *
	 * @return string
	 */
	function at_video_sanitize_source( $source )
	{
		switch ( $source ) {
			case 'big7':
				return 'Big7';
				break;

			case 'mdh':
				return 'MyDirtyHobby';
				break;

			case 'pornme':
				return 'PornMe';
				break;

			case 'ac':
				return 'AmateurCommunity';
				break;

			case 'own':
				return 'Eigene Quelle';
				break;
		}

		return 'unknown';
	}
}

if ( ! function_exists( 'at_video_taxonomy_args' ) ) {
	/**
	 * edit taxonomy archive query for video_actor / video_category / video_tags
	 */
	add_filter( 'pre_get_posts', 'at_video_taxonomy_args' );
	function at_video_taxonomy_args( $query )
	{
		if ( is_admin() ) {
			return $query;
		}

		/**
		 * Video tags query
		 */
		if ( $query->is_tax( 'video_tags' ) && $query->is_main_query() ) {
			$posts_per_page = ( get_field( 'video_tags_posts_per_page', 'options' ) ? get_field( 'video_tags_posts_per_page', 'options' ) : 12 );
			$query->set( 'posts_per_page', $posts_per_page );
			$query->set( 'meta_key', 'video_date' );
			$query->set( 'orderby', 'meta_value' );
		}

		/**
		 * Video category query
		 */
		if ( $query->is_tax( 'video_category' ) && $query->is_main_query() ) {
			$posts_per_page = ( get_field( 'video_category_posts_per_page', 'options' ) ? get_field( 'video_category_posts_per_page', 'options' ) : 12 );
			$query->set( 'posts_per_page', $posts_per_page );
			$query->set( 'meta_key', 'video_date' );
			$query->set( 'orderby', 'meta_value' );
		}

		/**
		 * Video actor query
		 */
		if ( $query->is_tax( 'video_actor' ) && $query->is_main_query() ) {
			$posts_per_page = ( get_field( 'video_actor_posts_per_page', 'options' ) ? get_field( 'video_actor_posts_per_page', 'options' ) : 12 );
			$query->set( 'posts_per_page', $posts_per_page );
			$query->set( 'meta_key', 'video_date' );
			$query->set( 'orderby', 'meta_value' );
		}

		/**
		 * Video archive query
		 */
		if ( $query->is_post_type_archive( 'video' ) && $query->is_main_query() ) {
			$posts_per_page = ( get_field( 'video_archive_posts_per_page', 'options' ) ? get_field( 'video_archive_posts_per_page', 'options' ) : 12 );
			$query->set( 'posts_per_page', $posts_per_page );
			$query->set( 'meta_key', 'video_date' );
			$query->set( 'orderby', 'meta_value' );
		}

		/**
		 * Video search query
		 */
		if ( $query->is_search() && $query->get( 'post_type' ) == 'video' && $query->is_main_query() ) {
			$posts_per_page = ( get_field( 'video_search_posts_per_page', 'options' ) ? get_field( 'video_search_posts_per_page', 'options' ) : 12 );
			$query->set( 'posts_per_page', $posts_per_page );
			$query->set( 'meta_key', 'video_date' );
			$query->set( 'orderby', 'meta_value' );
		}

		return $query;
	}
}

if ( ! function_exists( 'at_video_set_post_view' ) ) {
	/**
	 * function to set video views
	 */
	add_action( 'wp_ajax_video_views', 'at_video_set_post_view' );
	add_action( 'wp_ajax_nopriv_video_views', 'at_video_set_post_view' );
	function at_video_set_post_view()
	{
		$post_id = $_POST['post_id'];

		$views = get_field( 'video_views', $post_id );

		if ( $views ) {
			$views += 1;
		} else {
			$views = 1;
		}

		update_field( 'video_views', $views, $post_id );

		exit;
	}
}

if ( ! function_exists( 'at_video_category_related_tags' ) ) {
	/**
	 * A simple function to get related tags for a category
	 *
	 * @param $category
	 * @param $id
	 *
	 * @return array
	 */
	function at_video_category_related_tags( $category, $id )
	{
		if ( false === ( $tags = get_transient( 'cat_' . $id . '_tags' ) ) ) {
			// related tags
			$titles = explode( ' ', $category );
			$tags   = array();

			if ( $titles ) {
				foreach ( $titles as $title ) {
					$args = array(
						'hide_empty' => true,
						'number'     => 10,
						'name__like' => $title
					);

					$tags_tmp = get_terms( 'video_tags', $args );

					if ( $tags_tmp ) {
						$tags = array_merge( $tags, $tags_tmp );
					}
				}

				if ( $tags ) {
					$tags = array_slice( $tags, 0, 10 );
					$exp  = apply_filters( 'at_video_category_related_tags_transient_expiration', 12 * HOUR_IN_SECONDS );
					set_transient( 'cat_' . $id . '_tags', $tags, $exp );
				}
			}
		}

		return $tags;
	}
}

if ( ! function_exists( 'at_video_related_videos' ) ) {
	/**
	 * A function to get the related videos
	 *
	 * @param int $post_id
	 * @param int $page
	 *
	 * @return bool|WP_Query
	 */
	function at_video_related_videos( $post_id = 0, $page = 1 )
	{
		$options        = get_field( 'video_single_related_options', 'options' );
		$video_actor    = wp_get_post_terms( $post_id, 'video_actor', array( 'fields' => 'ids' ) );
		$video_category = wp_get_post_terms( $post_id, 'video_category', array( 'fields' => 'ids' ) );

		$args = array(
			'post_type'      => 'video',
			'posts_per_page' => ( $options['posts_per_page'] ? $options['posts_per_page'] : 12 ),
			'tax_query'      => array(),
			'orderby'        => 'rand',
			'post__not_in'   => array( $post_id )
		);

		if ( $video_actor ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'video_actor',
				'field'    => 'term_id',
				'terms'    => $video_actor,
			);
		}

		if ( $video_category ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'video_category',
				'field'    => 'term_id',
				'terms'    => $video_category,
			);
		}

		if ( $page ) {
			$args['paged'] = $page;
		}

		$related = new WP_Query( $args );

		if ( $related->have_posts() ) {
			return $related;
		}

		return false;
	}
}

add_action( 'wp_ajax_video_related', 'at_video_related_videos_ajax' );
add_action( 'wp_ajax_nopriv_video_related', 'at_video_related_videos_ajax' );
function at_video_related_videos_ajax()
{
	$post_id = ( isset ( $_POST['post_id'] ) ? $_POST['post_id'] : false );
	$page    = ( isset ( $_POST['page'] ) ? $_POST['page'] : 1 );

	if ( ! $post_id ) {
		die( 'error' );
	}

	$related = at_video_related_videos( $post_id, $page );

	if ( $related->have_posts() ) {
		?>
		<div class="card-deck">
			<?php
			while ( $related->have_posts() ) {
				$related->the_post();

				get_template_part( 'parts/video/loop', 'card' );
			}
			?>
		</div>

		<hr class="hr-transparent">

		<?php
		$max_pages = ( $related->max_num_pages > 8 ? 8 : $related->max_num_pages );

		echo at_pagination( $max_pages, 8, $page );
	}

	exit;
}

/**
 * Overwrite Bounceboster URL
 */
add_filter( 'option_bouncebooster', function ( $option ) {
	$actualLink = "$_SERVER[REQUEST_URI]";
	$videoSlug  = get_field( 'video_single_slug', 'option' );

	if ( strpos( $actualLink, '/' . $videoSlug . '/' ) !== false ) {
		global $wpdb;

		$postName    = str_replace( array( '/' . $videoSlug . '/', '/' ), '', $actualLink );
		$currentPost = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$postName' AND post_type = 'video' LIMIT 0,1" );

		if ( $currentPost ) {
			$videoLink = get_post_meta( $currentPost, 'video_url', true );

			if ( $videoLink ) {
				$option['url_default'] = $videoLink;
			}
		}
	}

	return $option;
}, 999 );

/**
 * Sheduled event to redownload missing thumbnails
 */
if ( ! wp_next_scheduled( 'checkVideoThumbnails' ) ) {
	wp_schedule_event( time(), 'daily', 'checkVideoThumbnails' );
}

add_action( 'wp_ajax_checkVideoThumbnails', 'at_checkVideoThumbnails' );
add_action( 'wp_ajax_nopriv_checkVideoThumbnails', 'at_checkVideoThumbnails' );
add_action( 'checkVideoThumbnails', 'at_checkVideoThumbnails' );
function at_checkVideoThumbnails()
{
	$args = array(
		'post_type'      => 'video',
		'meta_query'     => array(
			'relation' => 'AND',
			array(
				'relation' => 'OR',
				array(
					'key'     => '_thumbnail_id',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => '_thumbnail_id',
					'value'   => '',
					'compare' => '='
				)
			),
			array(
				'relation' => 'OR',
				array(
					'key'     => 'video_source',
					'value'   => 'mdh',
					'compare' => '='
				),
				array(
					'key'     => 'video_source',
					'value'   => 'big7',
					'compare' => '='
				),
			)
		),
		'posts_per_page' => -1,
		'fields'         => 'ids'
	);

	$videos = new WP_Query( $args );

	if ( $videos->have_posts() ) {
		global $wpdb;

		while ( $videos->have_posts() ) {
			$videos->the_post();

			$video_source = get_field( 'video_source' );
			$video_id     = get_post_meta( get_the_ID(), 'video_unique_id', true );

			$imageUrl = false;

			switch ( $video_source ) {
				case 'mdh':
					$database = new AT_Import_MDH_DB();
					$videoObj = $wpdb->get_row( "SELECT * FROM $database->table_videos WHERE video_id = $video_id" );
					if ( $videoObj ) {
						$preview  = json_decode( $videoObj->preview );
						$imageUrl = $preview->censored;

						if ( get_option( 'at_mdh_fsk18' ) == '1' ) {
							$imageUrl = $preview->normal;
						}

						// fix object error
						if ( is_object( $imageUrl ) ) {
							$imageUrl = $imageUrl->url;
						}
					}
					break;

				case 'big7':
					$database = new AT_Import_Big7_DB();
					$videoObj = $wpdb->get_row( "SELECT * FROM $database->table_videos WHERE video_id = $video_id" );
					if ( $videoObj ) {
						$imageUrl = $videoObj->preview;
					}
					break;
			}

			if ( $imageUrl ) {
				at_attach_external_image( $imageUrl, get_the_ID(), true, get_the_title() . '-preview', array( 'post_title' => get_the_title() ) );
			}
		}

		wp_reset_query();
	}

	die();
}