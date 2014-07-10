<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Muse Theme' );
define( 'CHILD_THEME_URL', 'http://wpspeak.com/themes/muse' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Enqueue Custom Scripts
add_action( 'wp_enqueue_scripts', 'muse_custom_scripts' );
function muse_custom_scripts() {
	
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'muse-google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400,700|Raleway|Open+Sans:300,400', array(), CHILD_THEME_VERSION );
	wp_enqueue_script( 'muse-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true ); 
	
}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Create color style options
add_theme_support( 'genesis-style-selector', array(
	'muse-red'   => __( 'Red', 'muse' ),
	'muse-blue'    => __( 'Blue', 'muse' ),
	'muse-green'  => __( 'Green', 'muse' ),
) );

//* Add image sizes
add_image_size( 'muse_grid', 550, 366, true );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Remove the site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/**
 * Template Chooser
 * Use CPT archive templates for taxonomies
 * @author Bill Erickson
 * @link http://www.billerickson.net/code/use-same-template-for-taxonomy-and-cpt-archive/
 *
 * @param string, default template path
 * @return string, modified template path
 *
 */
function muse_template_chooser( $template ) {
 
	if( is_archive() )
		$template = get_query_template( 'front-page' );
	return $template;
}
add_filter( 'template_include', 'muse_template_chooser' );

//* Change the footer text
add_filter('genesis_footer_creds_text', 'muse_footer_creds_filter');
function muse_footer_creds_filter( $creds ) {
	$creds = '[footer_copyright] &middot; ' . get_bloginfo('name') . ' &middot; [footer_childtheme_link before="Designed by "]';
	return $creds;
}

//* Remove comment after notes 
add_filter( 'comment_form_defaults', 'muse_custom_comment_form' );
function muse_custom_comment_form($fields) {
	$fields['comment_notes_after'] = '';  
    return $fields;
}

//* Unregister secondary navigation menu
add_theme_support( 'genesis-menus', array( 'primary' => __( 'Primary Navigation Menu', 'genesis' ) ) );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );
 
//* Unregister layouts
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

