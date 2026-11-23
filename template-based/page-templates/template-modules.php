<?php
/**
 * Template Name: Modular Page
 *
 * Renders the page as a stack of modules from the "page_modules"
 * Flexible Content field. Each layout maps to a file in
 * template-parts/modules/ (results_slider -> results-slider.php).
 *
 * @package Loop
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="primary" class="site-main site-main--modules">
	<?php
	if ( have_rows( 'page_modules' ) ) :
		while ( have_rows( 'page_modules' ) ) :
			the_row();

			$layout = str_replace( '_', '-', get_row_layout() );
			get_template_part( 'template-parts/modules/' . $layout );
		endwhile;
	endif;
	?>
</main>

<?php
get_footer();
