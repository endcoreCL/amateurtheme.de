<?php
get_header();
$sidebar  = $xcore_layout->get_sidebar( 'blog_category' );
$classes  = $xcore_layout->get_sidebar_classes( 'blog_category' );
$headline = get_field( 'blog_category_headline', 'options' );
?>

<div id="main">
    <div class="container">
        <h1>
            <?php
            if ( $headline ) {
                printf( $headline, single_cat_title( '', false ) );
            } else {
                single_cat_title();
            }
            ?>
        </h1>

        <?php
        if ( category_description() && ! is_paged() ) {
            echo '<p>' . category_description() . '</p><hr>';
        }
        ?>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php $xcore_layout->get_posts( 'category' ); ?>
                </div>
            </div>

            <?php
            if ( $sidebar ) {
                ?>
                <div class="<?php echo $classes['sidebar']; ?>">
                    <div id="sidebar">
                        <?php get_sidebar(); ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
