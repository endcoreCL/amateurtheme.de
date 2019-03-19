<?php
get_header();

/**
 * Vars
 */
$queried_object = get_queried_object();
$term_id = $queried_object->term_id;
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
                    <h1><?php single_term_title(); ?></h1>

                    <?php
                    $description = term_description();
                    if ($description && !is_paged()) {
                        echo '<div class="video-tag-description">' . $description . '</div>';
                    }
                    ?>

                    <div id="video-list" class="video-tag">
                        <?php
                        if (have_posts()) :
                            echo '<div class="card-deck">';
                            while (have_posts()) : the_post();
                                get_template_part('parts/video/loop', 'card');
                            endwhile;
                            echo '</div>';
	                        echo '<div class="divider"></div>';
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