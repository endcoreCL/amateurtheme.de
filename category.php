<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'category' );
$classes = $xcore_layout->get_sidebar_classes( 'category' );
?>

<div id="main">
    <div class="container">
        <h1><?php single_cat_title(); ?></h1>

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
                    <?php get_sidebar(); ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
