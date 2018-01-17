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
        <div id="content">
            <h1><?php single_term_title(); ?></h1>

            <?php
            $description = term_description();
            if ($description && !is_paged()) {
                echo '<div class="category-description">' . $description . '</div>';
            }
            ?>

            <div class="category-videos">
                <?php
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
