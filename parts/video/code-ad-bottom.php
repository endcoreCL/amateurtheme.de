<?php
$ad = get_field( 'video_single_ad', 'options' );
if ( $ad ) {
    ?>
    <hr class="hr-transparent">
    
    <div class="video-banner">
        <?php echo $ad; ?>
    </div>
    <?php
}