<?php
get_header();

/**
 * Vars
 */
$queried_object = get_queried_object();
$term_id = $queried_object->term_id;
$actor = new AT_Video_Actor($term_id);
?>

<div id="main">
    <div class="container">
        <div id="content">
            <?php if(!is_paged()) { ?>
                <div class="actor-header">
                    <div class="row">
                        <div class="col-sm-4">
                            <?php
                            $actor_image = $actor->image();
                            if ($actor_image) {
                                echo '<img src="' . $actor_image['url'] . '" alt="' . $actor_image['alt'] . '" class="img-fluid" />';
                            }
                            ?>
                        </div>

                        <div class="col-sm-8">
                            <h1><?php single_term_title(); ?></h1>

                            <?php
                            $description = term_description();
                            if ($description) {
                                echo '<div class="actor-description"><h3>' . __('Über mich', 'amateurtheme') . '</h3>' . $description . '</div>';
                            }

                            $profile_url = $actor->url();
                            if ($profile_url) {
                                echo '<p><a href="' . $profile_url . '" target="_blank" rel="nofollow" class="btn btn-at">' . __('zum Profil', 'amateurtheme') . '</a></p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="actor-details">
                    <?php
                    $fields = array(
                        __('Geschlecht', 'amateurtheme') => 'gender',
                        __('Körpergröße', 'amateurtheme') => 'size',
                        __('Haarfarbe', 'amateurtheme') => 'haircolor',
                        __('Haarlänge', 'amateurtheme') => 'hairlength',
                        __('Figur', 'amateurtheme') => 'bodytype',
                        __('Erscheinungsbild', 'amateurtheme') => 'bodystyle',
                        __('Augenfarbe', 'amateurtheme') => 'eyecolor',
                        __('PLZ', 'amateurtheme') => 'zipcode',
                        __('Stadt', 'amateurtheme') => 'city',
                        __('Land', 'amateurtheme') => 'country',
                        __('Alter', 'amateurtheme') => 'age',
                        __('Sternzeichen', 'amateurtheme') => 'star_sign',
                        __('Orientierung', 'amateurtheme') => 'sex_orientation',
                        __('Gewicht', 'amateurtheme') => 'weight',
                        __('Körpchengröße', 'amateurtheme') => 'breast_size',
                        __('Intimrasur', 'amateurtheme') => 'shave',
                        __('Beruf', 'amateurtheme') => 'job',
                        __('Ich bin', 'amateurtheme') => 'relationship_status',
                        __('Ich suche', 'amateurtheme') => 'search',
                        __('für', 'amateurtheme') => 'search_for',
                        __('Raucher', 'amateurtheme') => 'smoke',
                        __('Alkohol', 'amateurtheme') => 'alcohol'
                    );
                    ?>
                    <table class="table table-details">
                        <tr>
                            <?php
                            $i=0;
                            foreach($fields as $k => $v) {
                                if($i%2==0 && $i!=0) echo '</tr><tr>';

                                echo '<td class="row-label">' . $k . '</td><td class="row-value">' . $actor->field($v) . '</td>';

                                $i++;
                            }
                            ?>
                        </tr>
                    </table>
                </div>
                <?php
            } else {
                // paged
                ?>
                <h1><?php single_term_title(); ?> Videos</h1>
                <?php
            }
            ?>

            <div class="actor-videos">
                <?php
                if(!is_paged()) {
                    ?>
                    <h2><?php _e('Videos', 'amateurtheme'); ?></h2>
                    <?php
                }

                if (have_posts()) :
                    echo '<div class="row">';
                        while (have_posts()) : the_post();
                            get_template_part('parts/video/loop', 'grid');
                        endwhile;
                    echo '</div>';
                    echo at_pagination(3);
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
