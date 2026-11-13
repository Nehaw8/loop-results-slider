<?php
/**
 * Add this to the theme's functions.php (or include it from there).
 *
 * Expected theme structure:
 *   /acf-json/group_loop_results_slider.json
 *   /blocks/results-slider/block.json
 *   /blocks/results-slider/render.php
 *   /assets/css/results-slider.css
 *   /assets/js/results-slider.js
 *
 * Requires ACF Pro 6.0+.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register front-end + editor assets used by the block (handles referenced in block.json).
 */
add_action( 'init', function () {
	$dir = get_stylesheet_directory();
	$uri = get_stylesheet_directory_uri();

	wp_register_style(
		'loop-results-slider',
		$uri . '/assets/css/results-slider.css',
		array(),
		filemtime( $dir . '/assets/css/results-slider.css' )
	);

	wp_register_script(
		'loop-results-slider',
		$uri . '/assets/js/results-slider.js',
		array(),
		filemtime( $dir . '/assets/js/results-slider.js' ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	// Register the ACF block from block.json (must run after the asset handles exist).
	register_block_type( $dir . '/blocks/results-slider' );
}, 5 );

/**
 * Custom block category so Loop modules are grouped in the inserter.
 */
add_filter( 'block_categories_all', function ( $categories ) {
	array_unshift( $categories, array(
		'slug'  => 'loop-modules',
		'title' => __( 'Loop Modules', 'loop' ),
	) );
	return $categories;
} );

/**
 * Save / load ACF field groups as JSON in the theme (version-controlled fields).
 */
add_filter( 'acf/settings/save_json', function () {
	return get_stylesheet_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
} );

/**
 * Font used in the design (swap for the brand font if Loop supplies one).
 */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'loop-font-inter',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;700;800&display=swap',
		array(),
		null
	);
} );
