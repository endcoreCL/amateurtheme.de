<?php
/*
 * Contact Form 7 Hilfsfunktionen
 * 
 * @author		Christian Lang
 * @version		1.0
 * @category	helper
 */

/**
 * wpcf7_load_css filter
 *
 * @desc removed contact form 7 css
 */
add_filter( 'wpcf7_load_css', '__return_false' );