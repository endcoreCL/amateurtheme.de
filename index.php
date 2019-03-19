<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'blog_general' );
$classes = $xcore_layout->get_sidebar_classes( 'blog_general' );
$page_for_posts = get_option( 'page_for_posts' );
?>

<div id="main">
    <div class="container">
        <?php
        if ( $page_for_posts ) {
            echo '<h1>' . get_the_title( $page_for_posts ) . '</h1>';

            $content = apply_filters( 'the_content', get_post_field( 'post_content', $page_for_posts ) );

            if ( $content && ! is_paged() ) {
                echo $content . '<hr>';
            }
        }
        ?>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php $xcore_layout->get_posts( 'general' ); ?>
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
