<hr class="hr-transparent">

<div class="post-author">
    <div class="card">
        <div class="row no-gutters">
            <div class="col-md-3">
                <?php echo get_avatar( get_the_author_meta('user_email'), '150', '', get_the_author_meta( 'display_name' ), array ( 'class' => 'card-img img-fluid' ) ); ?>
            </div>
            <div class="col-md-9">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="<?php echo get_author_posts_url(get_the_author_meta( 'ID' )); ?>">
                            <?php the_author_meta('display_name'); ?>
                        </a>
                    </h5>
                    <p class="card-text"><?php the_author_meta( 'description'); ?></p>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
                        <?php printf( __( 'Weitere Artikel von %s', 'amateurtheme' ), get_the_author_meta( 'display_name' ) ); ?>
                    </a>
                </li>

                <?php
                if ( get_the_author_meta( 'url' ) ) {
                    ?>
                    <li class="list-inline-item">
                        <a href="<?php echo get_the_author_meta( 'url' ); ?>" title="<?php printf( __('%s Webseite', 'amateurtheme'), get_the_author_meta( 'display_name') ); ?>" target="_blank" rel="nofollow">
                            <i class="fa fa-globe"></i>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <?php
                if ( get_the_author_meta( 'facebook' ) ) {
                    ?>
                    <li class="list-inline-item">
                        <a href="<?php echo get_the_author_meta( 'facebook' ); ?>" title="<?php printf( __('%s auf Facebook', 'amateurtheme'), get_the_author_meta( 'display_name') ); ?>" target="_blank" rel="nofollow">
                            <i class="fab fa-facebook"></i>
                        </a>
                    </li>
                    <?php
                }
                ?>

                <?php
                if ( get_the_author_meta( 'twitter' ) ) {
                    ?>
                    <li class="list-inline-item">
                        <a href="https://twitter.com/<?php echo get_the_author_meta( 'twitter' ); ?>" title="<?php printf( __('%s auf Twitter', 'amateurtheme'), get_the_author_meta( 'display_name') ); ?>" target="_blank" rel="nofollow">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</div>