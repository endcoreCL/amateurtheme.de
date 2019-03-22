<?php
/**
 * Template Name: Page Builder
 */
get_header(); ?>

<div id="main">
    <div id="page-builder">
        <?php
        if( have_rows('page_builder') ):
            ob_start();

            $i = 0;
            while ( have_rows('page_builder') ) : the_row();

                /**
                 * Feld: Textarea
                 */
                if( get_row_layout() == 'page_builder_textarea' ) :
                    $items = get_sub_field( 'editor' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'textarea', 'textarea-row-' . get_sub_field( 'rows' ), 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'padding' ) == '1' ) {
                        $attributes['class'][] = 'no-padding';
                    }

                    if( get_sub_field('id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    if( $items ) {
                        $count = count( $items );
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <div class="row">
                                    <?php
                                    foreach( $items as $item ) {
                                        echo at_pb_render_editor( $item, $count );
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: Videos
                 */
                if( get_row_layout() == 'page_builder_videos' ) :
                    $text = get_sub_field( 'text' );
                    $type = get_sub_field( 'type' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'videos', 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'padding' ) == '1' ) {
                        $attributes['class'][] = 'no-padding';
                    }

                    if( get_sub_field( 'id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    $args = array(
                        'post_type' => 'video',
                        'posts_per_page' => 9,
                        'tax_query' => array()
                    );

                    if( $type == 'latest' ) {
                        $count = get_sub_field( 'latest_count' );
                        $actor = get_sub_field( 'latest_actor' );
                        $category = get_sub_field( 'latest_category' );
                        $tags = get_sub_field( 'latest_tags' );
                        $source = get_sub_field( 'latest_source' );

                        if( $count ) {
                            $args['posts_per_page'] = $count;
                        }

                        if( $actor ) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'video_actor',
                                'terms' => $actor,
                                'field' => 'term_id'
                            );
                        }

                        if( $category ) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'video_category',
                                'terms' => $category,
                                'field' => 'term_id'
                            );
                        }

                        if( $tags ) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'video_tags',
                                'terms' => $tags,
                                'field' => 'term_id'
                            );
                        }

                        if ( $source ) {
                            $args['meta_query'][] = array(
                                'key' => 'video_source',
                                'value' => $source,
                                'compare' => '='
                            );
                        }
                    } else if( $type == 'post__in' ) {
                        $ids = get_sub_field( 'post__in_video' );

                        if( $ids ) {
                            $args['post__in'] = $ids;
                            $args['posts_per_page'] = count( $ids );
                        }
                    }

                    $videos = new WP_Query( $args );

                    if( $videos->have_posts() ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if( $text ) {
                                    echo $text;
                                }
                                ?>
                                <div id="video-list">
                                    <div class="card-deck">
                                        <?php
                                        while( $videos->have_posts() ) {
                                            $videos->the_post();

                                            get_template_part( 'parts/video/loop', 'card' );
                                        }

                                        wp_reset_query();
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: Videos - Kategorien
                 */
                if( get_row_layout() == 'page_builder_video_category' ) :
                    $text = get_sub_field( 'text' );
                    $items = get_sub_field( 'items' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'video-category', 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'padding' ) == '1' ) {
                        $attributes['class'][] = 'no-padding';
                    }

                    if( get_sub_field( 'id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    $args = array(
                        'taxonomy'  => 'video_category',
                        'orderby'   => 'name'
                    );

                    if ( $items ) {
                        $args['include'] = $items;
                    }

                    $terms = get_terms( $args );

                    if ( $terms ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if ( $text ) {
	                                echo $text;
                                }
                                ?>
                                <div class="card-deck">
                                    <?php
                                    foreach ( $terms as $term ) {
                                        include( locate_template( 'parts/video/term-category.php' ) );
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: Videos - Darsteller
                 */
                if( get_row_layout() == 'page_builder_video_actor' ) :
                    $text = get_sub_field( 'text' );
                    $items = get_sub_field( 'items' );
                    $pagination = get_sub_field( 'pagination' );
                    $per_page = get_sub_field( 'per_page' );

                    // verify paged
                    $paged = false;
                    if ( isset ( $_GET['_page'] ) ) {
                        if ( intval( $_GET['_page'] ) > 1 ) {
                            $paged = true;
                        }
                    }

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'video-actor', 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'padding' ) == '1' ) {
                        $attributes['class'][] = 'no-padding';
                    }

                    if( get_sub_field( 'id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    $args = array(
                        'taxonomy'  => 'video_actor',
                        'orderby'   => 'name'
                    );

                    if ( $items ) {
                        $args['include'] = $items;
                    }

                    if ( $pagination && $per_page ) {
                        $args['number'] = $per_page;
                    }

                    if ( isset ( $_GET['_page'] ) && $_GET['_page'] > 1 ) {
                        $args['offset'] =  $per_page  * ( intval ( $_GET['_page'] ) - 1 );
                    }

                    $terms = get_terms( $args );

                    if ( $terms ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if ( $text ) {
	                                echo $text;
                                }
                                ?>
                                <div class="card-deck">
                                    <?php
                                    foreach ( $terms as $term ) {
                                        include( locate_template( 'parts/video/term-actor.php' ) );
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php

                        if ( $per_page ) {
                            if ( $items && count ( $items ) > $per_page || ! $items ) {
                                // get maximum pages
                                $args_o = array(
                                    'taxonomy'  => 'video_actor',
                                    'orderby'   => 'name'
                                );

                                if ( $items ) {
                                    $args_o['include'] = $items;
                                }

                                $terms_count = get_terms ( $args_o );

                                if ( $terms_count ) {
                                    $max = count ( $terms_count );

                                    if ( $max ) {
                                        $max_pages = ceil ( $max / $per_page );
                                        echo at_pagination( $max_pages, 3, true );
                                    }
                                }
                            }
                        }
                    }
                endif;

                /**
                 * Feld: Videos - Schlagwörter
                 */
                if( get_row_layout() == 'page_builder_video_tag' ) :
                    $text = get_sub_field( 'text' );
                    $items = get_sub_field( 'items' );

                    // verify letter
                    $filtered = false;
                    if ( isset ( $_GET['_filter'] ) && $_GET['_filter'] != '' ) {
                        $filtered = true;
                    }

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'video-tag', 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'padding' ) == '1' ) {
                        $attributes['class'][] = 'no-padding';
                    }

                    if( get_sub_field( 'id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    /**
                     * @TODO: Set taxonomy to: video_tags
                     */
                    $args = array(
                        'taxonomy' => 'video_actor',
                        'orderby' => 'name'
                    );

                    $terms = get_terms ( $args );

                    if ( $terms ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if ( $text ) {
	                                echo $text;
                                }

                                $term_list = at_terms_generate_az ( $terms );

                                // show all terms with a-z
                                if ( $term_list ) {
                                    if ( ! $filtered ) {
                                        // output of a-z nav
                                        ?>
                                        <nav aria-label="Letter navigation" role="navigation">
                                            <ul class="pagination justify-content-center ft-wpbs">
                                                <?php
                                                $alphas = range('A', 'Z');
                                                ?>

                                                <li class="page-item active">
                                                    <a href="<?php echo get_permalink(); ?>" class="page-link">
                                                        <?php _e( 'Beliebt', 'amateurtheme' ); ?>
                                                    </a>
                                                </li>

                                                <li class="page-item <?php echo ( ! isset ( $term_list['#'] ) ? 'disabled' : ''); ?>">
                                                    <a href="#num" class="page-link">#</a>
                                                </li>

                                                <?php
                                                foreach ( $alphas as $alpha ) {
                                                    $letter = strtolower( $alpha );
                                                    ?>
                                                    <li class="page-item <?php echo ( ! isset ( $term_list[$alpha] ) ? 'disabled' : ''); ?>">
                                                        <a href="#<?php echo $letter; ?>" class="page-link"><?php echo $alpha; ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </nav>
                                        <?php

                                        // output of terms
                                        foreach ( $term_list as $key => $value ) {
                                            ?>
                                            <p class="h2" id="<?php echo ( $key == '#' ? 'num' : strtolower( $key ) ); ?>"><?php echo $key; ?></p>

                                            <ul class="list-unstyled row">
                                                <?php
                                                $i=0;
                                                foreach ( $value as $term ) {
                                                    if ( $i > 10 ) {
                                                        ?>
                                                        <li class="col-3">
                                                            <a class="more-link" href="<?php echo get_permalink(); ?>?_filter=<?php echo strtolower( $key ); ?>">
                                                                <?php _e( 'Alle anzeigen', 'amateurtheme' ); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                        break;
                                                    }
                                                    ?>
                                                    <li class="col-3">
                                                        <a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
                                                            <?php echo $term->name; ?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                    $i++;
                                                }
                                                ?>
                                            </ul>
                                            <hr>
                                            <?php
                                        }
                                    } else {
                                        // show only filtered terms
                                        $letter =  strtoupper( htmlspecialchars( $_GET['_filter'] ) );

                                        // catch #
                                        if ( $letter == 'NUM' ) $letter = '#';

                                        //output of a-z nav
                                        ?>
                                        <nav aria-label="Letter navigation" role="navigation">
                                            <ul class="pagination justify-content-center ft-wpbs">
                                                <?php
                                                $alphas = range('A', 'Z');
                                                ?>

                                                <li class="page-item">
                                                    <a href="<?php the_permalink(); ?>" class="page-link">
                                                        <?php _e( 'Beliebt', 'amateurtheme' ); ?>
                                                    </a>
                                                </li>

                                                <li class="page-item <?php echo ( ! isset ( $term_list['#'] ) ? 'disabled' : ''); ?>">
                                                    <a href="<?php echo get_permalink(); ?>?_filter=num" class="page-link">#</a>
                                                </li>

                                                <?php
                                                foreach ( $alphas as $alpha ) {
                                                    ?>
                                                    <li class="<?php echo ( $alpha == $letter ? 'active' : ''); ?> page-item <?php echo ( ! isset ( $term_list[$alpha] ) ? 'disabled' : ''); ?>">
                                                        <a href="<?php echo get_permalink(); ?>?_filter=<?php echo strtolower ( $alpha ); ?>" class="page-link"><?php echo $alpha; ?></a>
                                                    </li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                        </nav>
                                        <?php

                                        // output of terms
                                        ?>
                                        <h2><?php printf( __('Schlagwörter > %s', 'amateurtheme' ), $letter ); ?></h2>
                                        <ul class="list-unstyled row">
                                            <?php
                                            foreach ( $term_list[$letter] as $term ) {
                                                ?>
                                                <li class="col-3">
                                                    <a href="<?php echo get_term_link( $term ); ?>" title="<?php echo $term->name; ?>">
                                                        <?php echo $term->name; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                            ?>
                                        </ul>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                $i++;
            endwhile;

            $output = ob_get_contents();
            ob_end_clean();

            remove_filter( 'the_content', 'wpautop' );
            echo apply_filters(' the_content', $output );
            add_filter( 'the_content', 'wpautop' );
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>