<?php

add_action( 'wp_head', function () {
	$favicon      = get_field( 'design_general_favicon', 'options' );
	$faviconTouch = get_field( 'favicon_general_touchicon', 'options' );

	if ( $favicon ) {
		?>
		<link rel="icon" type="image/x-icon" href="<?= $favicon['url'] ?>">
		<?php
	}

	if ( $faviconTouch ) {
		?>
		<link rel="icon" type="image/png" sizes="<?= $faviconTouch['width'] ?>x<?= $faviconTouch['height'] ?>" href="<?= $faviconTouch['url'] ?>">
		<link rel="apple-touch-icon" sizes="<?= $faviconTouch['width'] ?>x<?= $faviconTouch['height'] ?>" href="<?= $faviconTouch['url'] ?>">
		<?php
	}
} );