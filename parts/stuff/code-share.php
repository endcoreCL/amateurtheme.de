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
                $classes = array( 'list-inline-item',  'flex-fill', 'network-' . $network );

                if ( $network == 'facebook' ) {
                    $url = 'https://www.facebook.com/sharer.php?u=' . urlencode( get_permalink() );
                    $text = __( 'sharen', 'amateurtheme' );
                } else if ( $network == 'twitter' ) {
                    $url = 'https://twitter.com/share?url=' . urlencode( get_permalink() );
                    $text = __( 'tweeten', 'amateurtheme' );
                } else if ( $network == 'whatsapp' ) {
                    $url = 'whatsapp://send?text=' . urlencode(get_the_title()) . ' - ' . urlencode( get_permalink );
                    $text =  __( 'WhatsApp', 'amateurtheme' );
                    $classes[] = 'd-block d-sm-none';
                }
                ?>
                <li class="<?php echo implode( ' ', $classes ); ?>">
                    <a class="network-link" href="<?php echo $url; ?>" onClick="social_share(this, '<?php echo $network; ?>'); return false;">
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