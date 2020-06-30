<?php
get_header();

$post_type = ( $_GET['post_type'] ? $_GET['post_type'] : 'post' );
$sidebar   = $xcore_layout->get_sidebar( $post_type . '_search' );
$classes   = $xcore_layout->get_sidebar_classes( $post_type . '_search' );
$layout    = get_field( ( $post_type == 'post' ? 'blog' : $post_type ) . '_search_posts_layout', 'options' );
$headline  = get_field( ( $post_type == 'post' ? 'blog' : $post_type ) . '_search_headline', 'options' );
?>

<div id="main">
    <div class="container">
        <h1>
            <?php
            if ( $headline ) {
                printf( $headline, get_search_query() );
            } else {
                printf( __( 'Deine Suche nach <span class="highlight">%s</span>', 'xcore' ), get_search_query() );
            }
            ?>
        </h1>

        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <?php
                    if ( $post_type == 'video_actor' || $post_type == 'video_category' ) {
                        $terms = get_terms( $post_type, array( 'hide_empty' => true, 'name__like' => get_search_query() ) );

                        if ( $terms ) {
                            foreach ( $terms as $term ) {
                                include( locate_template( 'parts/video/loop-term-grid.php' ) );
                            }
                        }
                    } else {
                        if ( $layout ) {
                            if ( have_posts() ) :
                                if ( $post_type == 'video' ) {
                                    echo '<div id="video-list">';
                                }

                                if ( strpos( $layout, 'card' ) !== false ) {
                                    echo '<div class="' . $layout . '">';

                                    $layout = 'card'; // overwrite for post layout
                                }

                                while ( have_posts() ) :
                                    the_post();

                                    get_template_part( 'parts/' . $post_type . '/loop', $layout );
                                endwhile;

                                if ( strpos( $layout, 'card' ) !== false ) {
                                    echo '</div>';
                                }

                                if ( $post_type == 'video' ) {
                                    echo '</div>';
                                }

                                echo at_pagination();
                            else :
                                echo '<p>' . __( 'Deine Suche ergab keine Treffer.', 'amateurtheme' ) . '</p>';
                            endif;
                        } else {
                            echo '<p>' . __( 'Ungültiger Beitragstyp.', 'amateurtheme' ) . '</p>';
                        }
                    }
                    ?>
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
