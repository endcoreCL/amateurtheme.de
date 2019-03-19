<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'single' );
$classes = $xcore_layout->get_sidebar_classes( 'single' );

$author = get_field( 'blog_single_author', 'options' );
$related = get_field( 'blog_single_related', 'options' );
$nav = get_field( 'blog_single_nav', 'options');
?>

<div id="main">
    <div class="container">
        <h1><?php the_title(); ?></h1>

        <?php get_template_part( 'parts/post/code', 'meta' ); ?>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php
                    if (have_posts()) :
                        while (have_posts()) :
                            the_post();

                            the_content();

                            if ( $author ) {
                                get_template_part( 'parts/post/code', 'author' );
                            }

                            if ( $related ) {
                                get_template_part( 'parts/post/code', 'related' );
                            }

                            if ( $nav ) {
                                get_template_part( 'parts/post/code', 'nav' );
                            }
                        endwhile;
                    endif;
                    ?>
                </div>
            </div>

            <?php
            if ( $sidebar ) {
                ?>
                <div class="<?php echo $classes['sidebar']; ?>">
                    <?php get_sidebar(); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
