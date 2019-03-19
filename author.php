<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'author' );
$classes = $xcore_layout->get_sidebar_classes( 'author' );
?>

<div id="main">
    <div class="container">
        <h1><?php printf( __('Alle Beiträge von <span>%s</span>', 'amateurtheme'),  get_the_author() ); ?></h1>

        <?php
        if(get_the_author_meta('description') && ! is_paged()) {
            ?>
            <p class="author-bio">
                <?php the_author_meta( 'description' ); ?>
            </p>
            <?php
        }
        ?>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php $xcore_layout->get_posts( 'author' ); ?>
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
