<?php
add_action( 'wp_footer', 'at_popup_loader' );
function at_popup_loader() {
	$active = get_field( 'popup_active', 'options' );

	if ( ! $active ) {
		return;
	}

	$headline = get_field( 'popup_headline', 'options' );
	$text = get_field( 'popup_text', 'options' );
	$devices = get_field( 'popup_devices', 'options' );

	if ( $text && $devices ) {
		$show_after_time = get_field( 'popup_show_after_time', 'options' );
		$show_after_scroll = get_field( 'popup_show_after_scroll', 'options' );
		$cookie = ( get_field( 'popup_cookie', 'options' ) ? true : false );
		$cookie_lifetime = ( $cookie ? get_field( 'popup_cookie_lifetime', 'options' ) : false );
		?>
		<div class="at-popup" data-show-time="<?php echo $show_after_time; ?>" data-show-scroll="<?php echo $show_after_scroll; ?>" data-cookie="<?php echo $cookie; ?>" data-cookie-lifetime="<?php echo $cookie_lifetime; ?>">
			<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
				<div class="toast-header">
					<?php
					if ( $headline ) {
						?>
						<strong class="mr-auto"><?php echo $headline; ?></strong>
						<?php
					}
					?>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="toast-body">
					<?php echo $text; ?>
				</div>
			</div>
		</div>
		<?php
	}
}