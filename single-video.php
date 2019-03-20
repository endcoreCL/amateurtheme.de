<?php
get_header();

// Vars
$video = new AT_Video( get_the_ID() );
$categories = get_field( 'video_single_category', 'options' );
$ad_top = get_field( 'video_single_ad_top', 'options' );
$prg = get_field( 'prg_activate', 'options' );

// classes
$col = array( 'content' => 'col-sm-12', 'sidebar' => 'col-sm-12' );

if ( $ad_top ) {
    $col['content'] = 'col-sm-10';
    $col['sidebar'] = 'col-sm-2';
}
?>

<div id="main" data-post-id="<?php the_ID(); ?>">
    <div class="container">
        <div id="content">
            <?php
            if (have_posts()) : while (have_posts()) : the_post();
                ?>
                <div class="video-summary">
                    <div class="row">
                        <div class="<?php echo $col['content']; ?>">
                            <div class="video-thumb-container">
                                <?php
                                // preview videos
                                $preview_webm = $video->preview_video( 'webm' );
                                $preview_mp4 = $video->preview_video( 'mp4' );

                                if( $preview_webm || $preview_mp4 ) {
                                    $poster = $video->thumbnail_url();
                                    ?>
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <video poster="<?php echo $poster; ?>" id="player" playsinline controls data-plyr-config='{"loadSprite" : "<?php echo get_template_directory_uri(); ?>/assets/img/plyr.svg"}'>
                                            <?php if( $preview_mp4 ) { ?><source src="<?php echo $preview_mp4; ?>" type="video/mp4"><?php } ?>
                                            <?php if( $preview_webm ) { ?><source src="<?php echo $preview_webm; ?>" type="video/webm"><?php } ?>
                                        </video>
                                    </div>
                                    <?php
                                } else {
	                                // thumbnail
	                                $external_url = $video->external_url();
	                                if ( $external_url ) {
	                                    if ( $prg ) {
	                                        ?>
                                            <span class="redir-link" data-submit="<?php echo base64_encode( $external_url ); ?>" title="<?php $video->title(); ?>" data-target="_blank">
	                                        <?php
                                        } else {
	                                        ?>
                                            <a href="<?php echo $external_url; ?>" title="<?php $video->title(); ?>" target="_blank" rel="nofollow">
	                                        <?php
                                        }

                                        echo $video->thumbnail(
                                            'full',
                                            array(
                                                'class' => 'img-fluid',
                                                'style' => 'width: 100%; height: auto;'
                                            )
                                        );

	                                    if ( $prg ) {
	                                        ?>
                                            </span>
                                            <?php
	                                    } else {
	                                        ?>
                                            </a>
	                                        <?php
                                        }
	                                } else {
                                        echo $video->thumbnail(
                                            'full',
                                            array(
                                                'class' => 'img-fluid',
                                                'style' => 'width: 100%; height: auto;'
                                            )
                                        );
	                                }
                                }
                                ?>
                            </div>

                            <div class="video-info">
                                <h1><?php echo $video->title(); ?></h1>

                                <?php
                                get_template_part( 'parts/video/code', 'meta' );

                                the_content();

                                if ( $categories ) {
	                                echo get_the_term_list( get_the_ID(), 'video_category', '<span class="badge badge-dark">', '</span> <span class="badge badge-dark">', '</span>' );
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                        if ( $ad_top ) {
	                        ?>
                            <div class="<?php echo $col['sidebar']; ?>">
		                        <?php echo $ad_top; ?>
                            </div>
	                        <?php
                        }
                        ?>
                    </div>
                </div>

                <?php
                get_template_part( 'parts/video/code', 'related' );

                get_template_part( 'parts/video/code', 'ad-bottom' );
             endwhile; endif;
             ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
