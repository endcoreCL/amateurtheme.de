<?php
/*
 * Registrieren der Sidebars
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	widgets
 */
 
register_sidebar(array(
    'name' => __('Allgemeine Sidebar', 'amateurtheme'),
    'id' => 'standard',
    'description' => __('Allgemeine Sidebar.', 'amateurtheme'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<p class="h1">',
    'after_title' => '</p>',
));

register_sidebar(array(
	'name' => __('Städte Sidebar', 'amateurtheme'),
	'id' => 'location',
	'description' => __('Sidebar für alle Städteseiten.', 'amateurtheme'),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<p class="h1">',
	'after_title' => '</p>',
));

register_sidebar(array(
	'name' => __('Blog Sidebar', 'amateurtheme'),
	'id' => 'post',
	'description' => __('Sidebar für alle Blogseiten (Kategorien, Tags, Autoren, Beiträge, usw.).', 'amateurtheme'),
	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	'after_widget' => '</aside>',
	'before_title' => '<p class="h1">',
	'after_title' => '</p>',
));

if(get_field('design_footer_widgets', 'option') == '1') {
	for($i=1; $i<5; $i++) {
		register_sidebar(array(
			'name' => __('Footer ', 'amateurtheme') . '('.$i.')',
			'id' => 'footer_'.$i,
			'description' => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' => '</aside>',
			'before_title' => '<p class="h1">',
			'after_title' => '</p>',
		));
	}
}
?>