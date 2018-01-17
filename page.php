<?php get_header(); ?>

<div id="main">
    <div class="container">
        <div id="content">
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                the_content();
            endwhile; endif;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
