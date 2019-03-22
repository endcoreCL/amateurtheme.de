<?php
$xcore_layout   = new xCORE_Layout();
$logo_pos       = $xcore_layout->logo_pos();
$banner         = get_field( 'design_header_banner', 'options' );
$search         = get_field( 'design_header_search', 'options' );
$search_pos     = get_field( 'design_header_search_pos', 'options' );

if( $logo_pos == 'top' || $banner || $search ) {
    $header_bg = get_field( 'design_header_bg', 'options' );

    $attributes = array(
        'id'    => array( 'header' ),
        'class' => array()
    );

	$attributes['class'][] = at_design_bg_classes( 'header', $header_bg );
    ?>
    <header <?php echo at_attribute_array_html( $attributes ); ?>>
        <div class="container">
            <div class="row">
                <?php
                if($logo_pos == 'top') {
                    ?>
                    <div class="col-sm-4 align-self-center">
                        <a class="header-brand" href="<?php echo home_url(); ?>" title="<?php bloginfo( 'name' ); ?>">
                            <?php echo $xcore_layout->logo(); ?>
                        </a>
                    </div>
                    <?php
                    if ( $banner ) {
                        if( $search && $search_pos == 'top' ) {
                            ?>
                            <div class="col-sm-4 align-self-center d-none d-md-block">
                                <div class="header-search">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                            <div class="col-sm-8 col-md-4 align-self-center d-none d-sm-block">
                                <div class="header-text">
                                    <?php echo $banner; ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="col-sm-8 align-self-center d-none d-sm-block">
                                <div class="header-text">
                                    <?php echo $banner; ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else if($search && $search_pos == 'top') { ?>
                        <div class="col-sm-4 offset-sm-6 d-none d-md-block">
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
                            <div class="col-sm-6 align-self-center d-none d-md-block">
                                <div class="header-search">
                                    <?php get_search_form(); ?>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 align-self-center d-none d-sm-block">
                                <div class="header-text">
                                    <?php echo $banner; ?>
                                </div>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="col-sm-12 align-self-center d-none d-sm-block">
                                <div class="header-text">
                                    <?php echo $banner; ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else if($search && $search_pos == 'top') { ?>
                        <div class="col-sm-6 offset-sm-6 d-none d-md-block">
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
<?php
}
?>

<nav class="<?php echo $xcore_layout->navbar_wrapper_classes(); ?>" id="navigation">
	<div class="container">
		<?php
		if($logo_pos == 'inline') {
			?>
			<a class="navbar-brand" href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>"><?php echo $xcore_layout->logo(); ?></a>
			<?php
		}
		?>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

        <?php
        wp_nav_menu(
            array(
                'menu'              => 'main',
                'theme_location'    => 'navigation_main',
                'container'         => 'div',
                'container_id'      => 'navbarNav',
                'container_class'   => 'collapse navbar-collapse',
                'menu_id'           => false,
                'menu_class'        => $xcore_layout->navbar_classes(),
                'depth'             => 5,
                'fallback_cb'       => 'xcore_nav_walker::fallback',
                'walker'            => new xcore_nav_walker()
            )
        );

        if($search_pos == 'inline') {
            get_search_form();
        }
        ?>
	</div>
</nav>