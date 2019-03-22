<?php
$breadcrumbs_bg = ( get_field( 'design_breadcrumbs_bg', 'options' ) ? get_field( 'design_breadcrumbs_bg', 'options' ) : 'white' );

$attributes = array(
	'id' => array( 'breadcrumbs' ),
	'class' => array()
);

$attributes['class'][] = at_design_bg_classes( 'breadcrumbs', $breadcrumbs_bg );
?>
<section <?php echo at_attribute_array_html( $attributes ); ?>>
	<div class="container">
		<?php
		$breadcrumbs = yoast_breadcrumb( '<nav><ol class="breadcrumb"><li class="breadcrumb-item">','</li></ol></nav>', false );
		echo str_replace( '»', '</li><li class="breadcrumb-item">', $breadcrumbs );
		?>
	</div>
</section>