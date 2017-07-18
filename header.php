<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title><?php wp_title(); ?></title>
		<?php wp_head(); ?>
	</head>
	
	<body <?php body_class(); ?>>
		<!--[if lt IE 8]>
			<p class="browserupgrade"><i class="glyphicon glyphicon-warning-sign"></i> 
				Sie verwenden einen <strong>veralteten</strong> Internet-Browser. Bitte laden Sie sich eine aktuelle Version von <a href="http://browsehappy.com/" target="_blank" rel="nofollow">browsehappy.com</a> um die Seite fehlerfrei zu verwenden.
			</p>
		<![endif]-->
		
		<a href="#content" class="sr-only sr-only-focusable">Skip to main content</a>
		<div id="<?php echo at_get_wrapper_id(); ?>">
			<?php 
			if(at_get_topbar())
				get_template_part('parts/topbar/col', '6-6'); 
			?>
			
			<header id="header" class="<?php echo at_get_section_layout_class('header', true); ?>" role="banner">
				<?php
				get_template_part('parts/header/col', at_header_structure());
				?>
			</header>
			
			<?php 
			if(at_teaser_hide() != "1" && !is_page_template('templates/page-builder.php')) {
				global $indicator, $arrows, $interval, $fade, $images;
				$indicator = get_field('teaser_indicator');
				$arrows = get_field('teaser_arrows');
				$fade = get_field('teaser_fade');
				$interval = get_field('teaser_interval');
				$images = get_field('teaser_image');
				
				get_template_part('parts/teaser/code', 'teaser'); 
			}
			?>
			
			<?php if ( function_exists('yoast_breadcrumb') && ('after_nav' == get_field('design_breadcrumbs_pos', 'option'))) {
				?>
				<section id="breadcrumbs" class="<?php echo at_get_section_layout_class('breadcrumbs'); ?>">
					<div class="container">
						<?php
						yoast_breadcrumb('<p>','</p>');
						?>
					</div>
				</section>
				<?php
			}
?>