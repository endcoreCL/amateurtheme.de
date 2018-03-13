<?php
get_header();

// Vars
$video = new AT_Video(get_the_ID());
?>

<div id="main">
    <div class="container">
        <div id="content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="video-summary">
                    <div class="row">
                        <div class="col-10">
                            <div class="video-thumb-container">
                                <?php
                                $external_url = $video->external_url();
                                if($external_url) {
                                    ?>
                                    <a href="<?php echo $external_url; ?>" title="<?php $video->title(); ?>" target="_blank" rel="nofollow">
                                        <?php echo $video->thumbnail('full', array('class' => 'img-fluid', 'style' => 'width: 100%; height: auto;')); ?>
                                    </a>
                                    <?php
                                } else {
                                    echo $video->thumbnail();
                                }
                                ?>
                            </div>

                            <div class="video-info">
                                <h1><?php echo $video->title(); ?></h1>

                                <ul class="list-inline list-meta">
                                    <li class="list-inline-item video-views">
                                        <i class="fa fa-eye"  aria-hidden="true"></i> <?php echo $video->views(); ?>
                                    </li>
                                    <li class="list-inline-item video-rating">
                                        <i class="fa fa-star" aria-hidden="true"></i> <?php echo $video->rating(); ?>
                                    </li>
                                </ul>

                                <hr>

                                <?php echo get_the_term_list(get_the_ID(), 'video_category', '<span class="badge badge-dark">', '</span> <span class="badge badge-dark">', '</span>'); ?>
                            </div>
                        </div>

                        <div class="col-2">
                            <?php get_sidebar(); ?>
                        </div>
                    </div>
                </div>

                <?php get_template_part('parts/video/code', 'related'); ?>

                <?php get_template_part('parts/video/code', 'ad-bottom'); ?>
            <?php endwhile; endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
