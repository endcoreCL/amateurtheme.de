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
	             * Feld: Slideshow
	             */
	            if( get_row_layout() == 'page_builder_slideshow' ) :
		            $options = get_sub_field( 'options' );
		            $items = get_sub_field( 'items' );

		            $attributes = array(
			            'id' => array( get_sub_field( 'id' ) ),
			            'class' => array( 'section', 'slideshow', 'section-' . $i ),
			            'style' => array(),
		            );

		            if( get_sub_field( 'class' ) ) {
			            $attributes['class'][] = get_sub_field( 'class' );
		            }

		            if( get_sub_field('id' ) ) {
			            $attributes['class'][] = 'id-' . get_sub_field( 'id' );
		            }

		            $attributes_owl = array(
                        'class' => array ( 'owl-carousel', 'owl-theme', 'owl-slideshow' ),
                        'data-autoplay' => array ( ( $options['autoplay'] ? true : false ) ),
                        'data-timeout' => array ( ( $options['timeout'] ? $options['timeout'] : 4000 ) ),
                        'data-nav' => array ( ( $options['nav'] ? true : false ) ),
                        'data-dots' => array ( ( $options['dots'] ? true : false ) ),
                    );

		            if( $items ) {
			            $count = count( $items );
			            ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div <?php echo at_attribute_array_html( $attributes_owl ); ?>>
                                <?php
                                foreach ( $items as $item ) {
                                    $images = $item['images'];
                                    $caption = $item['caption'];
                                    ?>
                                    <div class="item">
                                        <picture>
                                            <?php
                                            if ( $images['xs'] ) {
	                                            ?>
                                                <source media="(max-width: 575px)" srcset="<?php echo $images['xs']['url']; ?>">
	                                            <?php
                                            }
                                            if ( $images['sm'] ) {
	                                            ?>
                                                <source media="(min-width: 576px) AND (max-width: 767px)" srcset="<?php echo $images['sm']['url']; ?>">
	                                            <?php
                                            }
                                            if ( $images['md'] ) {
	                                            ?>
                                                <source media="(min-width: 768px) AND (max-width: 991px)" srcset="<?php echo $images['md']['url']; ?>">
	                                            <?php
                                            }
                                            if ( $images['lg'] ) {
	                                            ?>
                                                <source media="(min-width: 992px) AND (max-width: 1199px)" srcset="<?php echo $images['lg']['url']; ?>">
	                                            <?php
                                            }
                                            if ( $images['xl'] ) {
                                                ?>
                                                <source srcset="<?php echo $images['xl']['url']; ?>">
                                                <img src="<?php echo $images['xl']['url']; ?>" alt="<?php echo $images['xl']['alt']; ?>" />
                                                <?php
                                            }
                                            ?>
                                        </picture>

                                        <?php
                                        if ( $caption ) {
                                            ?>
                                            <div class="caption">
                                                <div class="inner">
                                                    <?php echo $caption; ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
			            <?php
		            }
	            endif;

                /**
                 * Feld: Textarea
                 */
                if( get_row_layout() == 'page_builder_textarea' ) :
                    $items = get_sub_field( 'editor' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'textarea', 'textarea-row-' . get_sub_field( 'rows' ), 'section-' . $i ),
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
                 * Feld: Accordions
                 */
                if( get_row_layout() == 'page_builder_accordions' ) :
                    $headline = get_sub_field( 'headline' );
                    $group = get_sub_field( 'group' );
	                $items = get_sub_field( 'items' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'accordions', 'section-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field('id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    if( $items ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if ( $headline ) {
                                    echo '<h2>' .$headline . '</h2>';
                                }

                                if ( $items ) {
	                                $output = '[accordions id="accordions' . $i . '"]';
                                        foreach ( $items as $k => $item ) {
                                            $title = $item['title'];
                                            $open = $item['open'];
                                            $text = $item['text'];

                                            $output .= '[accordion id="accordion_' . $k . '"' . ( $group ? ' parent="accordions' . $i . '" ' : ' ' ) . 'headline="' . $title . '" expanded="' . ( $open ? 'true' : 'false' ) . '"]';
                                                $output .= $text;
                                            $output .= '[/accordion]';
                                        }
	                                $output .= '[/accordions]';

                                    echo do_shortcode( $output );
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: Tabs
                 */
                if( get_row_layout() == 'page_builder_tabs' ) :
                    $headline = get_sub_field( 'headline' );
                    $type = get_sub_field( 'type' );
	                $items = get_sub_field( 'items' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'tabs', 'section-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field('id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    if( $items ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                if ( $headline ) {
                                    echo '<h2>' .$headline . '</h2>';
                                }

                                if ( $items ) {
	                                $output = '[tabs id="tabs' . $i . '" type="' . $type . '"]';
                                        foreach ( $items as $k => $item ) {
                                            $title = $item['title'];
                                            $text = $item['text'];

                                            $output .= '[tab id="tabs' . $i . '_' . $k . '" headline="' . $title . '" expanded="' . ( $k == 0 ? 'true' : 'false' ) . '"]';
                                                $output .= $text;
                                            $output .= '[/tab]';
                                        }
	                                $output .= '[/tabs]';

                                    echo do_shortcode( $output );
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: CTA
                 */
                if( get_row_layout() == 'page_builder_cta' ) :
                    $text = get_sub_field( 'text' );
                    $color = get_sub_field( 'color' );
	                $button = get_sub_field( 'button' );
	                $style = get_sub_field( 'style' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'cta', 'section-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field('id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    if ( $style ) {
                        $attributes['class'][] = 'cta-' . $style;
                    }

                    if( $items ) {
                        ?>
                        <div <?php echo at_attribute_array_html( $attributes ); ?>>
                            <div class="container">
                                <?php
                                $button_html = '<a href="' . $button['url'] . '" target="' . $button['target'] . '" class="btn btn-' . $button['style'] . ' btn-lg">' . $button['text'] . '</a>';
                                $text_html = '<p class="h1"' . ( $color ? ' style="color: ' . $color . '"' : '' ) . '>' . $text. '</p>';

                                if ( $text ) {
                                    if  ( $style == 'small' ) {
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <?php echo $text_html; ?>
                                            </div>

                                            <div class="col-sm-3">
                                                <?php echo $button_html; ?>
                                            </div>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="text-center">
	                                        <?php echo $text_html; ?>
	                                        <?php echo $button_html; ?>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                endif;

                /**
                 * Feld: Blog
                 */
                if( get_row_layout() == 'page_builder_blog' ) :
                    $text = get_sub_field( 'text' );
                    $type = get_sub_field( 'type' );

                    $attributes = array(
                        'id' => array( get_sub_field( 'id' ) ),
                        'class' => array( 'section', 'blog', 'item-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
                    }

                    if( get_sub_field( 'id' ) ) {
                        $attributes['class'][] = 'id-' . get_sub_field( 'id' );
                    }

                    if( get_sub_field( 'bgcolor' ) ) {
                        $attributes['style'][] = 'background-color: ' . get_sub_field( 'bgcolor' ) . ';';
                    }

                    $args = array(
                        'posts_per_page' => 6,
                        'tax_query' => array()
                    );

                    if( $type == 'latest' ) {
                        $count = get_sub_field( 'latest_count' );
                        $category = get_sub_field( 'latest_category' );
                        $tags = get_sub_field( 'latest_tags' );

                        if( $count ) {
                            $args['posts_per_page'] = $count;
                        }

                        if( $category ) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'category',
                                'terms' => $category,
                                'field' => 'term_id'
                            );
                        }

                        if( $tags ) {
                            $args['tax_query'][] = array(
                                'taxonomy' => 'post_tags',
                                'terms' => $tags,
                                'field' => 'term_id'
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
                                <div class="card-deck">
                                    <?php
                                    while( $videos->have_posts() ) {
                                        $videos->the_post();

                                        get_template_part( 'parts/post/loop', 'card' );
                                    }

                                    wp_reset_query();
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
                        'class' => array( 'section', 'videos', 'section-' . $i ),
                        'style' => array(),
                    );

                    if( get_sub_field( 'class' ) ) {
                        $attributes['class'][] = get_sub_field( 'class' );
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
                        'class' => array( 'section', 'video-category', 'section-' . $i ),
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
                        'class' => array( 'section', 'video-actor', 'section-' . $i ),
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
                        'class' => array( 'section', 'video-tag', 'section-' . $i ),
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
                        'taxonomy' => 'video_tags',
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

	            /**
	             * Feld: Trennlinie
	             */
	            if(get_row_layout() == 'page_builder_hr'):
		            $layout = get_sub_field('layout');

		            $element_attributes = array(
			            'style' => array(),
		            );

		            $attributes = array(
			            'class' => array('section', 'hr', 'secion-' . $i),
			            'style' => array(),
		            );

		            if(get_sub_field('id')) {
			            $attributes['id'][] = get_sub_field('id');
		            }

		            if(get_sub_field('class')) {
			            $attributes['class'][] = get_sub_field('class');
		            }

		            if(get_sub_field('size')) {
			            $element_attributes['class'][] = get_sub_field('size');
		            }

		            if(get_sub_field('color')) {
			            $element_attributes['style'][] = 'border-color: ' . get_sub_field('color') . ';';
		            }

		            ?>
                    <div <?php echo at_attribute_array_html($attributes); ?>>
		                <?php if($layout == 'boxed') { echo '<div class="container">'; } ?>
		                <hr <?php echo at_attribute_array_html($element_attributes); ?>>
		                <?php if($layout == 'boxed') { echo '</div>'; } ?>
		            </div>
                    <?php
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