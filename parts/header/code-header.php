<?php
$layout = new xCORE_Layout();
$logo_pos = $layout->logo_pos();
$banner = get_field('design_header_banner', 'options');
$search = get_field('design_header_search', 'options');
$search_pos = get_field('design_header_search_pos', 'options');
?>
<header id="header">
    <div class="container">
        <div class="row">
            <?php
            if($logo_pos == 'top') {
                ?>
                <div class="col-sm-3 align-self-center">
                    <a class="header-brand" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
                        <?php echo $layout->logo(); ?>
                    </a>
                </div>
                <?php
                if ( $banner ) {
                    if( $search && $search_pos == 'top' ) {
                        ?>
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
                        <?php
                    } else {
                        ?>
                        <div class="col-sm-9 align-self-center">
                            <div class="header-text">
                                <?php echo $banner; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else if($search && $search_pos == 'top') { ?>
                    <div class="col-sm-6 offset-sm-3">
                        <div class="header-search">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                <?php
                }
            } else {
                if ( $banner ) {
                    if( $search && $search_pos == 'top' ) {
                        ?>
                        <div class="col-sm-6 align-self-center">
                            <div class="header-search">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        <div class="col-sm-6 align-self-center">
                            <div class="header-text">
                                <?php echo $banner; ?>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-sm-12 align-self-center">
                            <div class="header-text">
                                <?php echo $banner; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else if($search && $search_pos == 'top') { ?>
                    <div class="col-sm-6 offset-sm-6">
                        <div class="header-search">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</header>

<nav class="<?php echo $layout->navbar_wrapper_classes(); ?>" id="navigation">
	<div class="container">
		<?php
		if($logo_pos == 'inline') {
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

		if($search_pos == 'inline') {
			get_search_form();
        }
		?>
	</div>
</nav>