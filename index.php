<?php get_header(); ?>

<div id="main">
    <div class="container">
        <div id="content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article <?php post_class(); ?>>
                    <h2><?php the_title(); ?></h2>
                    <?php the_excerpt(); ?>
                </article>
            <?php endwhile;  endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
