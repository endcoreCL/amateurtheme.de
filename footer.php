<?php
if ( function_exists('yoast_breadcrumb') && ('bottom' == get_field('design_breadcrumbs_pos', 'option') ) ) {
	get_template_part( 'parts/stuff/code', 'breadcrumbs' );
}

if( get_field( 'design_footer', 'options' ) ) {
	$footer_bg = ( get_field( 'design_footer_bg', 'options' ) ? get_field( 'design_footer_bg', 'options' ) : 'dark' );

	$attributes = array(
		'id' => array( 'footer' ),
		'class' => array()
	);

	$attributes['class'][] = at_design_bg_classes( 'footer', $footer_bg );
	?>
    <footer <?php echo at_attribute_array_html( $attributes ); ?>>
		<?php
		$design_footer_widgets = get_field( 'design_footer_widgets', 'option' );
		if ( $design_footer_widgets ) {
			$design_footer_widget_areas = get_field( 'design_footer_widget_areas', 'option' );

			if ( $design_footer_widget_areas > 0 ) {
				?>
                <div class="footer-top">
                    <div class="container">
                        <div class="row">
							<?php
							for ( $i = 1; $i <= $design_footer_widget_areas; $i++ ) {
								?>
                                <div class="col footer-widget-<?php echo $i; ?>">
									<?php dynamic_sidebar( 'footer_' . $i ); ?>
                                </div>
								<?php
							}
							?>
                        </div>
                    </div>
                </div>
				<?php
			}
		}

		$footer_columns = get_field( 'design_footer_columns', 'options') ;
		if ( $footer_columns ) {
			?>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
						<?php
						$c=1;
						foreach ($footer_columns as $k => $v) {
							$class = ($v['class'] ? $v['class'] : 'col-sm');
							?>
                            <div class="<?php echo $class; ?>">
                                <div class="footer_bottom_inner text-<?php echo $v['align']; ?>">
									<?php
									$type = $v['type'];

									if ($type == 'text') {
										// year replacement
										$v['text'] = str_replace( '%%year%%', date('Y'), $v['text'] );
										echo '<p>' . $v['text'] . '</p>';
									} else if ( $type == 'menu' ) {
										$menu_id = $v['menu'];
										wp_nav_menu(
											array(
												'menu'          => $menu_id,
												'menu_id'       => false,
												'menu_class'    => 'list-inline',
												'container'     => false,
												'depth'         => 2,
												'fallback_cb'   => 'xcore_nav_walker_topbar::fallback',
												'walker'        => new xcore_nav_walker_topbar()
											)
										);
									}
									?>
                                </div>
                            </div>
							<?php
							$c++;
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
    </footer>
	<?php
}
?>
</div>
</body>

<?php wp_footer(); ?>

<?php
/* Support old Browsers (eg IE6-9) */
if( get_field( 'src_ie_support', 'options' ) ) {
	?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<?php
}
?>
</html>