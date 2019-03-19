<?php
$social_networks = get_field( 'social_networks', 'options' );

if ( $social_networks ) {
    ?>
    <div class="<?php echo get_post_type(); ?>-share">
        <ul class="list-share list-inline d-flex">
            <?php
            foreach ( $social_networks as $network ) {
                $url = '';
                $text = '';

                if ( $network == 'facebook' ) {
                    $url = 'https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink() );
                    $text = __( 'sharen', 'amateurtheme' );
                } else if ( $network == 'twitter' ) {
                    $url = 'https://twitter.com/share?url=' . urlencode( get_permalink() );
                    $text = __( 'tweeten', 'amateurtheme' );
                }
                ?>
                <li class="list-inline-item flex-fill social-<?php echo $network; ?>">
                    <a href="<?php echo $url; ?>" onClick="social_share(this, '<?php echo $network; ?>'); return false;">
                        <i class="fab fa-<?php echo $network; ?>"></i> <?php echo $text; ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
}