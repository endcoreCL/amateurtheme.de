<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'blog_tag' );
$classes = $xcore_layout->get_sidebar_classes( 'blog_tag' );
?>

<div id="main">
    <div class="container">
        <h1><?php single_tag_title(); ?></h1>

        <?php
        if ( tag_description() && ! is_paged() ) {
            echo '<p>' . tag_description() . '</p><hr>';
        }
        ?>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php $xcore_layout->get_posts( 'tag' ); ?>
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
