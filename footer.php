		<?php if ( function_exists('yoast_breadcrumb') && ('above_footer' == get_field('design_breadcrumbs_pos', 'option'))) {
			?>
			<section id="breadcrumbs" class="<?php echo at_get_section_layout_class('breadcrumbs'); ?>">
				<div class="container">
					<?php
					yoast_breadcrumb('<p>','</p>');
					?>
				</div>
			</section>
			<?php
		} ?>
	
		<footer id="footer" class="<?php echo at_get_section_layout_class('footer'); ?>">
			<?php 
			if(get_field('design_footer_widgets', 'option') == '1')
				get_template_part('parts/footer/code', 'top'); 
			
			get_template_part('parts/footer/code', 'bottom'); 
			?>
		</footer>

		<?php get_template_part('parts/profiles/code', 'modal'); ?>
	
		<?php wp_footer(); ?>
		
				
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		</div>
	</body>
</html>