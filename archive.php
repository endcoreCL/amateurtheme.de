<?php
get_header();
$sidebar = $xcore_layout->get_sidebar( 'blog_date' );
$classes = $xcore_layout->get_sidebar_classes( 'blog_date' );
?>

<div id="main">
    <div class="container">
        <h1>
            <?php
            if ( is_day() ) :
                printf( __( 'Tagesarchive: %s', 'amateurtheme' ), get_the_date() );
            elseif ( is_month() ) :
                printf( __( 'Monatsarchive: %s', 'amateurtheme' ), get_the_date( _x( 'F Y', 'monatliches datum format', 'amateurtheme' ) ) );
            elseif ( is_year() ) :
                printf( __( 'Jahresarchive: %s', 'amateurtheme' ), get_the_date( _x( 'Y', 'jaehrliches datum format', 'amateurtheme' ) ) );
            else :
                _e( 'Archive', 'amateurtheme' );
            endif;
            ?>
        </h1>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php $xcore_layout->get_posts( 'date' ); ?>
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
