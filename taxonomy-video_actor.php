<?php
get_header();

/**
 * Vars
 */
$queried_object = get_queried_object();
$term_id = $queried_object->term_id;
$actor = new AT_Video_Actor($term_id);

$actor_image = $actor->image();
$actor_description = term_description();
$actor_profile_url = $actor->url();

$video_actor_description  = get_field( 'video_actor_description', 'options' );
$video_actor_details = get_field( 'video_actor_details', 'options' );
?>

<div id="main">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <div id="sidebar">
                    <?php get_sidebar(); ?>
                </div>
            </div>

            <div class="col-10">
                <div id="content">
                    <?php if( !is_paged() ) { ?>
                        <div class="video-actor-header clearfix">
							<h1><?php single_term_title(); ?></h1>
                            <?php
                            if ( $video_actor_description ) {
	                            if ( $actor_image ) {
		                            echo '<img src="' . $actor_image['url'] . '" alt="' . $actor_image['alt'] . '" class="alignright img-fluid">';
	                            }

	                            if ( $actor_description ) {
		                            echo '<div class="video-actor-description">' . $actor_description . '</div>';
	                            }

	                            if ( $actor_profile_url ) {
		                            echo '<p><a href="' . $actor_profile_url . '" target="_blank" rel="nofollow" class="btn btn-primary">' . __( 'zum Profil', 'amateurtheme' ) . '</a></p>';
	                            }

	                            echo '<hr class="hr-transparent">';
                            }
							?>
                        </div>

                        <?php
                        if ( $video_actor_details ) {
                            ?>
                            <div class="video-actor-details">
                                <?php
                                echo '<h2>' . __( 'Details', 'amateurtheme' ) . '</h2>';
                                $fields = apply_filters( 'at_video_actor_details_fields',
                                    array(
                                        __( 'Geschlecht', 'amateurtheme' ) => 'gender',
                                        __( 'Körpergröße', 'amateurtheme' ) => 'size',
                                        __( 'Haarfarbe', 'amateurtheme' ) => 'haircolor',
                                        __( 'Haarlänge', 'amateurtheme' ) => 'hairlength',
                                        __( 'Figur', 'amateurtheme' ) => 'bodytype',
                                        __( 'Erscheinungsbild', 'amateurtheme' ) => 'bodystyle',
                                        __( 'Augenfarbe', 'amateurtheme' ) => 'eyecolor',
                                        __( 'PLZ', 'amateurtheme' ) => 'zipcode',
                                        __( 'Stadt', 'amateurtheme' ) => 'city',
                                        __( 'Land', 'amateurtheme' ) => 'country',
                                        __( 'Alter', 'amateurtheme' ) => 'age',
                                        __( 'Sternzeichen', 'amateurtheme' ) => 'star_sign',
                                        __( 'Orientierung', 'amateurtheme' ) => 'sex_orientation',
                                        __( 'Gewicht', 'amat eurtheme' ) => 'weight',
                                        __( 'Körpchengröße', 'amateurtheme' ) => 'breast_size',
                                        __( 'Intimrasur', 'amateurtheme' ) => 'shave',
                                        __( 'Beruf', 'amateurtheme' ) => 'job',
                                        __( 'Ich bin', 'amateurtheme' ) => 'relationship_status',
                                        __( 'Ich suche', 'amateurtheme' ) => 'search',
                                        __( 'für', 'amateurtheme' ) => 'search_for',
                                        __( 'Raucher', 'amateurtheme' ) => 'smoke',
                                        __( 'Alkohol', 'amateurtheme' ) => 'alcohol'
                                    )
                                );
                                ?>
                                <table class="table table-bordered table-details">
                                    <colgroup>
                                        <col style="width: 20%">
                                        <col style="width: 30%">
                                        <col style="width: 20%">
                                        <col style="width: 30%">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <?php
                                            $i=0;
                                            foreach( $fields as $k => $v ) {
                                                if( $i%2==0 && $i!=0 ) echo '</tr><tr>';

                                                echo '<td class="td-label">' . $k . '</td><td class="td-value">' . $actor->field( $v ) . '</td>';

                                                $i++;
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                        }
                        ?>

                        <hr class="hr-transparent">

                        <?php
                    } else {
                        // paged
                        ?>
                        <h1><?php printf( __( '%s Videos', 'amateurtheme' ), single_term_title( '', false ) ); ?></h1>
                        <?php
                    }
                    ?>

					<div id="video-list" class="video-actor">
                        <?php
                        if( ! is_paged() ) {
                            ?>
                            <h2><?php _e( 'Videos', 'amateurtheme' ); ?></h2>
                            <?php
                        }

                        if ( have_posts() ) :
                            ?>
                            <div class="card-deck">
                                <?php
                                while ( have_posts() ) :
                                    the_post();

                                    get_template_part( 'parts/video/loop', 'card' );
                                endwhile;
                                ?>
                            </div>
                            <hr class="hr-transparent">
                            <?php
                            echo at_pagination();
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
