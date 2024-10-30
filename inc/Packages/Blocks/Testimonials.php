<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;

class Testimonials extends AbstractBlock {

	use SingletonTrait;

	public $name = 'Testimonials';

	protected function init_hooks() {
		wp_register_script( 'brandy-blocks/testimonials', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Packages/Blocks/Testimonials.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );

		$next_icon = apply_filters( 'brandy_blocks_testimonials_icon', function_exists( 'brandy_swiper_navigation_icon' ) ? \brandy_swiper_navigation_icon( 'next' ) : '', 'next' );
		$back_icon = apply_filters( 'brandy_blocks_testimonials_icon', function_exists( 'brandy_swiper_navigation_icon' ) ? \brandy_swiper_navigation_icon() : '', 'back' );

		$localize_data = array();
		if ( $next_icon ) {
			$localize_data['nextIcon'] = $next_icon;
		}
		if ( $back_icon ) {
			$localize_data['backIcon'] = $back_icon;
		}

		wp_localize_script(
			'brandy-blocks/testimonials',
			'brandyBlocksTestimonials',
			$localize_data
		);
	}

}
