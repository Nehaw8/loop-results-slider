<?php
/**
 * Template-based setup for the Results Slider module.
 * Include from functions.php:
 *   require_once get_template_directory() . '/inc/functions-modules.php';
 *
 * @package Loop
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue module assets only on pages that contain a Results Slider module.
 */
add_action( 'wp_enqueue_scripts', function () {
	if ( ! is_singular() || ! function_exists( 'get_field' ) ) {
		return;
	}

	$modules = get_field( 'page_modules', get_queried_object_id() );
	$layouts = is_array( $modules ) ? wp_list_pluck( $modules, 'acf_fc_layout' ) : array();

	if ( ! in_array( 'results_slider', $layouts, true ) ) {
		return;
	}

	$dir = get_template_directory();
	$uri = get_template_directory_uri();

	wp_enqueue_style(
		'loop-results-slider',
		$uri . '/assets/css/results-slider.css',
		array(),
		filemtime( $dir . '/assets/css/results-slider.css' )
	);

	wp_enqueue_script(
		'loop-results-slider',
		$uri . '/assets/js/results-slider.js',
		array(),
		filemtime( $dir . '/assets/js/results-slider.js' ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
} );

/**
 * Keep ACF field groups in the theme as Local JSON (version-controlled).
 */
add_filter( 'acf/settings/save_json', function () {
	return get_template_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = get_template_directory() . '/acf-json';
	return $paths;
} );
