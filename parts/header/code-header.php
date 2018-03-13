<?php
$layout = new xCORE_Layout();
$navbar_pos = $layout->navbar_pos();
$banner = get_field('design_header_banner', 'options');

if($navbar_pos == 'top') {
	?>
	<header id="header">
		<div class="container">
			<div class="row">
				<div class="col-sm-3 align-self-center">
					<a class="header-brand" href="<?php echo home_url(); ?>"
					   title="<?php bloginfo( 'name' ); ?>"><?php echo $layout->logo(); ?></a>
				</div>
				<?php if ( $banner ) { ?>
					<div class="col-sm-6 align-self-center">
						<div class="header-search">
							<?php get_search_form(); ?>
						</div>
					</div>
					<div class="col-sm-3 align-self-center">
						<div class="header-text">
							<?php echo $banner; ?>
						</div>
					</div>
				<?php } else { ?>
					<div class="col-sm-9 align-self-center">
						<div class="header-search">
							<?php get_search_form(); ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</header>
	<?php
}
?>

<nav class="<?php echo $layout->navbar_wrapper_classes(); ?>" id="navigation">
	<div class="container">
		<?php
		if($navbar_pos == 'inline') {
			?>
			<a class="navbar-brand" href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php echo $layout->logo(); ?></a>
			<?php
		}
		?>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<?php
		wp_nav_menu(
			array(
				'menu' => 'main',
				'theme_location' => 'navigation_main',
				'container'       => 'div',
				'container_id'    => 'bs4navbar',
				'container_class' => 'collapse navbar-collapse',
				'menu_id'         => false,
				'menu_class'      => $layout->navbar_classes(),
				'depth'           => 4,
				'fallback_cb'     => 'xcore_nav_walker::fallback',
				'walker'          => new xcore_nav_walker()
			)
		);
		?>
	</div>
</nav>