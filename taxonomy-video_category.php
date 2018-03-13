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
                    // related tags
                    $args = array(
                        'hide_empty' => true,
                        'number' => 10,
                        'name__like' => single_term_title(false, '')
                    );

                    $tags = get_terms('video_tags', $args);
                    if($tags) {
                        echo '<ul class="list-inline list-related-tags">';
                            foreach($tags as $tag) {
                                echo '<li class="list-inline-item term term-' . $tag->term_id . '"><a href="' . get_term_link($tag) . '" title="' . $tag->name . '""><span class="badge badge-primary">' . $tag->name . '</span></a></li>';
                            }
                        echo '</ul>';
                    }

                    $description = term_description();
                    if ($description && !is_paged()) {
                        echo '<div class="video-category-description">' . $description . '</div>';
                    }
                    ?>

					<div id="video-list" class="video-category">
                        <?php
                        if (have_posts()) :
                            echo '<div class="card-columns">';
                            while (have_posts()) : the_post();
                                get_template_part('parts/video/loop', 'grid');
                            endwhile;
                            echo '</div>';
	                        echo '<div class="divider"></div>';
                            echo at_pagination(3);
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>