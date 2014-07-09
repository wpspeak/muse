<?php

//* Force full width template
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

//* Remove the post content (requires HTML5 theme support)
remove_action( 'genesis_entry_content', 'genesis_do_post_content' );

//* Remove the entry meta in the entry footer (requires HTML5 theme support)
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );

//* Remove the entry meta in the entry header (requires HTML5 theme support)
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );

//* Add featured image 
remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
add_action( 'genesis_entry_header', 'photog_featured_image', 3 );
function photog_featured_image() {

	if ( $image = genesis_get_image( 'format=url&size=photog_grid' ) ) {
		printf( '<div class="hover-icon"><a href="%s" rel="bookmark"><img class="post-photo entry-image aligncenter" src="%s" alt="%s" /></a></div>', get_permalink(), $image, the_title_attribute( 'echo=0' ) );
	}
 
}

/**
 * Grid Loop Pagination
 * Returns false if not grid loop.
 * Returns an array describing pagination if is grid loop
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/a-better-and-easier-grid-loop/
 *
 * @param object $query
 * @return bool is grid loop (true) or not (false)
 */
function photog_grid_loop_pagination( $query = false ) {
 
	// If no query is specified, grab the main query
	global $wp_query;
	if( !isset( $query ) || empty( $query ) || !is_object( $query ) )
		$query = $wp_query;
		
	// Specify pagination
	return array(
		'features_on_front' => 2,
	);
}
 
/**
 * Grid Loop Query Arguments
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/a-better-and-easier-grid-loop/
 *
 * @param object $query
 * @return null
 */
function photog_grid_loop_query_args( $query ) {
	$grid_args = photog_grid_loop_pagination( $query );
	if( $query->is_main_query() && !is_admin() && $grid_args ) {
 
		// First Page
		$page = $query->query_vars['paged'];
		if( ! $page ) {
			$query->set( 'posts_per_page', ( $grid_args['features_on_front'] + $grid_args['teasers_on_front'] ) );
			
		// Other Pages
		} else {
			$query->set( 'posts_per_page', ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) );
			$query->set( 'offset', ( $grid_args['features_on_front'] + $grid_args['teasers_on_front'] ) + ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) * ( $page - 2 ) );
			// Offset is posts on first page + posts on internal pages * ( current page - 2 )
		}
 
	}
}
add_action( 'pre_get_posts', 'photog_grid_loop_query_args' );
 
/**
 * Grid Loop Post Classes
 *
 * @author Bill Erickson
 * @link http://www.billerickson.net/a-better-and-easier-grid-loop/
 *
 * @param array $classes
 * @return array $classes
 */
function photog_grid_loop_post_classes( $classes ) {
	global $wp_query;
	$grid_args = photog_grid_loop_pagination();
	if( ! $grid_args )
		return $classes;
		
	// First Page Classes
	if( ! $wp_query->query_vars['paged'] ) {
	
		// Features
		if( $wp_query->current_post < $grid_args['features_on_front'] ) {
			$classes[] = 'feature one-half';
			if( 0 == ( $wp_query->current_post ) )
				$classes[] = 'first';
		
		// Teasers
		} else {
			$classes[] = 'one-third';
			if( 0 == ( $wp_query->current_post - $grid_args['features_on_front'] ) || 0 == ( $wp_query->current_post - $grid_args['features_on_front'] ) % 3 )
				$classes[] = 'first';
		}
		
	// Inner Pages
	} else {
 
		// Features
		if( $wp_query->current_post < $grid_args['features_inside'] ) {
			$classes[] = 'feature';
		
		// Teasers
		} else {
			$classes[] = 'one-third';
			if( 0 == ( $wp_query->current_post - $grid_args['features_inside'] ) || 0 == ( $wp_query->current_post - $grid_args['features_inside'] ) % 3 )
				$classes[] = 'first';
		}
	
	}
	
	return $classes;
}
add_filter( 'post_class', 'photog_grid_loop_post_classes' );
 
/**
 * Fix Posts Nav
 *
 * The posts navigation uses the current posts-per-page to 
 * calculate how many pages there are. If your homepage
 * displays a different number than inner pages, there
 * will be more pages listed on the homepage. This fixes it.
 *
 */
function photog_fix_posts_nav() {
	
	if( get_query_var( 'paged' ) )
		return;
		
	global $wp_query;
	$grid_args = photog_grid_loop_pagination();
	if( ! $grid_args )
		return;
 
	$max = ceil ( ( $wp_query->found_posts - $grid_args['features_on_front'] - $grid_args['teasers_on_front'] ) / ( $grid_args['features_inside'] + $grid_args['teasers_inside'] ) ) + 1;
	$wp_query->max_num_pages = $max;
	
}
//add_filter( 'genesis_after_endwhile', 'photog_fix_posts_nav', 5 );

genesis();