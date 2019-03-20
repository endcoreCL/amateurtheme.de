<?php
get_header();

/**
 * Vars
 */
$sidebar = $xcore_layout->get_sidebar( 'video_category' );
$classes = $xcore_layout->get_sidebar_classes( 'video_category' );
$headline = get_field( 'video_category_headline', 'options' );
$queried_object = get_queried_object();
$term_id = $queried_object->term_id;
?>

<div id="main">
    <div class="container">
        <div class="row">
            <div class="<?php echo $classes['content']; ?>">
                <div id="content">
                    <h1>
                        <?php
                        if ( $headline ) {
                            printf( $headline, single_term_title( '', false ) ) ;
                        } else {
	                        single_term_title();
                        }
                        ?>
                    </h1>

                    <?php
                    $tags = at_video_category_related_tags ( $queried_object->name, $term_id );
                    if( $tags ) {
                        ?>
                        <ul class="list-inline list-related-tags">
                            <?php
                            foreach( $tags as $tag ) {
                                ?>
                                <li class="list-inline-item term term-<?php echo $tag->term_id; ?>">
                                    <a href="<?php echo get_term_link( $tag ); ?>" title="<?php echo $tag->name; ?>">
                                        <span class="badge badge-primary">
                                            <?php echo $tag->name; ?>
                                        </span>
                                    </a>
                                </li>
                                <?php
                            }
                        echo '</ul>';
                    }

                    $description = term_description();
                    if ( $description && ! is_paged() ) {
                        echo '<div class="video-category-description">' . $description . '</div><hr class="hr-transparent">';
                    }
                    ?>

					<div id="video-list" class="video-category">
                        <?php
                        if ( have_posts() ) :
                            ?>
                            <div class="card-deck">
                                <?php
                                while ( have_posts() ) :
                                    the_post();

                                    get_template_part( 'parts/video/loop', 'card' );
                                endwhile;
                                ?>
                            </div>
                            <hr class="hr-transparent">
                            <?php
                            echo at_pagination();
                        endif;
                        ?>
                    </div>
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