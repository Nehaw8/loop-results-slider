<?php
/**
 * Block: Results Slider (loop/results-slider)
 *
 * ACF fields (see acf-json/group_loop_results_slider.json):
 * - heading        (textarea)  Large decorative heading, one line per row.
 * - slides         (repeater)
 *     - image      (image, returns ID)
 *     - tags       (repeater) -> label (text)
 *     - summary    (textarea)
 *     - link       (link)      Optional "view case study" link (orange arrow).
 *     - stats      (repeater, max 3)
 *         - value  (text)  e.g. "603" or "£21k"
 *         - suffix (text)  e.g. "%" (rendered smaller)
 *         - label  (text)  e.g. "Increase in conversions"
 *
 * @var array    $block      Block settings and attributes.
 * @var string   $content    Inner HTML (unused).
 * @var bool     $is_preview True when rendering inside the editor.
 * @var int|string $post_id  Current post ID.
 *
 * @package Loop
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' );
$slides  = get_field( 'slides' );

// Editor placeholder when nothing has been added yet.
if ( empty( $slides ) ) {
	if ( ! empty( $is_preview ) ) {
		echo '<p style="padding:2rem;background:#f1f1f1;">' . esc_html__( 'Results Slider: add at least one slide.', 'loop' ) . '</p>';
	}
	return;
}

$block_id    = ! empty( $block['anchor'] ) ? $block['anchor'] : 'results-slider-' . $block['id'];
$slide_count = count( $slides );
$classes     = array( 'results-slider' );

if ( ! empty( $block['className'] ) ) {
	$classes[] = $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$classes[] = 'align' . $block['align'];
}
?>
<section
	id="<?php echo esc_attr( $block_id ); ?>"
	class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
	aria-roledescription="carousel"
	<?php if ( $heading ) : ?>aria-labelledby="<?php echo esc_attr( $block_id ); ?>-heading"<?php endif; ?>
	data-results-slider
>
	<div class="results-slider__inner">

		<div class="results-slider__intro">
			<?php if ( $heading ) : ?>
				<h2 id="<?php echo esc_attr( $block_id ); ?>-heading" class="results-slider__heading">
					<?php
					// Each line of the textarea becomes its own span (second line is offset in the design).
					foreach ( preg_split( '/\r\n|\r|\n/', trim( $heading ) ) as $line ) {
						printf( '<span>%s</span>', esc_html( $line ) );
					}
					?>
				</h2>
			<?php endif; ?>

			<?php if ( $slide_count > 1 ) : ?>
				<div class="results-slider__nav">
					<button type="button" class="results-slider__arrow results-slider__arrow--prev" data-slider-prev aria-controls="<?php echo esc_attr( $block_id ); ?>-track">
						<span class="screen-reader-text"><?php esc_html_e( 'Previous result', 'loop' ); ?></span>
						<svg aria-hidden="true" viewBox="0 0 32 20" width="32" height="20"><path d="M31 10H2M10 1.5 1.5 10 10 18.5" fill="none" stroke="currentColor" stroke-width="2.5"/></svg>
					</button>
					<button type="button" class="results-slider__arrow results-slider__arrow--next" data-slider-next aria-controls="<?php echo esc_attr( $block_id ); ?>-track">
						<span class="screen-reader-text"><?php esc_html_e( 'Next result', 'loop' ); ?></span>
						<svg aria-hidden="true" viewBox="0 0 32 20" width="32" height="20"><path d="M1 10h29M22 1.5l8.5 8.5-8.5 8.5" fill="none" stroke="currentColor" stroke-width="2.5"/></svg>
					</button>
				</div>
			<?php endif; ?>
		</div>

		<div class="results-slider__track" id="<?php echo esc_attr( $block_id ); ?>-track" aria-live="polite">
			<?php foreach ( $slides as $index => $slide ) : ?>
				<?php
				$image   = $slide['image'] ?? 0;
				$tags    = $slide['tags'] ?? array();
				$summary = $slide['summary'] ?? '';
				$link    = $slide['link'] ?? array();
				$stats   = $slide['stats'] ?? array();
				$active  = 0 === $index;
				?>
				<article
					class="results-slider__slide<?php echo $active ? ' is-active' : ''; ?>"
					role="group"
					aria-roledescription="slide"
					aria-label="<?php echo esc_attr( sprintf( /* translators: 1: slide number, 2: total */ __( '%1$d of %2$d', 'loop' ), $index + 1, $slide_count ) ); ?>"
					<?php echo $active ? '' : 'hidden'; ?>
				>
					<div class="results-slider__case">
						<?php if ( $image ) : ?>
							<figure class="results-slider__media">
								<?php
								echo wp_get_attachment_image(
									$image,
									'large',
									false,
									array(
										'class'   => 'results-slider__image',
										'loading' => $active ? 'eager' : 'lazy',
										'sizes'   => '(min-width: 1024px) 480px, 100vw',
									)
								);
								?>
							</figure>
						<?php endif; ?>

						<?php if ( $tags ) : ?>
							<ul class="results-slider__tags">
								<?php foreach ( $tags as $tag ) : ?>
									<?php if ( ! empty( $tag['label'] ) ) : ?>
										<li class="results-slider__tag"><?php echo esc_html( $tag['label'] ); ?></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<?php if ( $summary ) : ?>
							<p class="results-slider__summary"><?php echo esc_html( $summary ); ?></p>
						<?php endif; ?>
					</div>

					<div class="results-slider__aside">
						<?php if ( $stats ) : ?>
							<dl class="results-slider__stats">
								<?php foreach ( $stats as $stat ) : ?>
									<div class="results-slider__stat">
										<dt class="results-slider__stat-label"><?php echo esc_html( $stat['label'] ?? '' ); ?></dt>
										<dd class="results-slider__stat-value">
											<?php echo esc_html( $stat['value'] ?? '' ); ?><?php if ( ! empty( $stat['suffix'] ) ) : ?><span class="results-slider__stat-suffix"><?php echo esc_html( $stat['suffix'] ); ?></span><?php endif; ?>
										</dd>
									</div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>

						<?php if ( ! empty( $link['url'] ) ) : ?>
							<a class="results-slider__link"
								href="<?php echo esc_url( $link['url'] ); ?>"
								<?php echo ! empty( $link['target'] ) ? 'target="' . esc_attr( $link['target'] ) . '" rel="noopener"' : ''; ?>>
								<span class="screen-reader-text"><?php echo esc_html( $link['title'] ?: __( 'View case study', 'loop' ) ); ?></span>
								<svg aria-hidden="true" viewBox="0 0 20 32" width="20" height="32"><path d="M10 31V2M1.5 10 10 1.5 18.5 10" fill="none" stroke="currentColor" stroke-width="2.5"/></svg>
							</a>
						<?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
