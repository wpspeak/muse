<?php

//* Simple Social Icon Defaults
add_filter( 'simple_social_default_styles', 'photog_social_default_styles' );
function photog_social_default_styles( $defaults ) {

	$args = array(
		'alignment'              => 'aligncenter',
		'background_color'       => '#f3d900',
		'background_color_hover' => '#333333',
		'border_radius'          => 0,
		'icon_color'             => '#ffffff',
		'icon_color_hover'       => '#f3d900',
		'size'                   => 36,
		);
		
	$args = wp_parse_args( $args, $defaults );
	
	return $args;
	
}