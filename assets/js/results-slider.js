/**
 * Results Slider – lightweight, dependency-free.
 * Works with any number of [data-results-slider] blocks on a page.
 */
( function () {
	'use strict';

	function initSlider( root ) {
		if ( root.dataset.sliderReady ) {
			return;
		}
		root.dataset.sliderReady = 'true';

		var slides = root.querySelectorAll( '.results-slider__slide' );
		var prev   = root.querySelector( '[data-slider-prev]' );
		var next   = root.querySelector( '[data-slider-next]' );
		var index  = 0;

		if ( slides.length < 2 ) {
			return;
		}

		function goTo( i ) {
			index = ( i + slides.length ) % slides.length; // loop both ways
			slides.forEach( function ( slide, n ) {
				var active = n === index;
				slide.hidden = ! active;
				slide.classList.toggle( 'is-active', active );
			} );
		}

		prev.addEventListener( 'click', function () { goTo( index - 1 ); } );
		next.addEventListener( 'click', function () { goTo( index + 1 ); } );

		// Keyboard arrows when focus is inside the slider.
		root.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'ArrowLeft' )  { goTo( index - 1 ); }
			if ( e.key === 'ArrowRight' ) { goTo( index + 1 ); }
		} );

		// Basic swipe support.
		var startX = null;
		root.addEventListener( 'touchstart', function ( e ) { startX = e.touches[0].clientX; }, { passive: true } );
		root.addEventListener( 'touchend', function ( e ) {
			if ( startX === null ) { return; }
			var dx = e.changedTouches[0].clientX - startX;
			if ( Math.abs( dx ) > 50 ) { goTo( index + ( dx < 0 ? 1 : -1 ) ); }
			startX = null;
		} );
	}

	function initAll( scope ) {
		( scope || document ).querySelectorAll( '[data-results-slider]' ).forEach( initSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () { initAll(); } );
	} else {
		initAll();
	}

	// Re-init when the block re-renders in the editor preview.
	if ( window.acf && window.acf.addAction ) {
		window.acf.addAction( 'render_block_preview/type=loop/results-slider', function ( $el ) {
			initAll( $el[0] );
		} );
	}
} )();
