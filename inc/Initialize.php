<?php

namespace BrandyBlocks;

use BrandyBlocks\Elementor\ElementorSetup;
use BrandyBlocks\Externals\ExternalsLoader;
use BrandyBlocks\Packages\PackagesLoader;
use BrandyBlocks\Shortcodes\ShortcodesLoader;

class Initialize {
	use \BrandyBlocks\Traits\SingletonTrait;

	protected function __construct() {
		require_once BRANDY_BLOCKS_PLUGIN_PATH . 'inc/Functions.php';
		require_once BRANDY_BLOCKS_PLUGIN_PATH . 'inc/Admin/SettingsMenu.php';

		ShortcodesLoader::get_instance();
		PackagesLoader::get_instance();
		ElementorSetup::get_instance();
		ExternalsLoader::get_instance();
		Wishlist::get_instance();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts() {
		if ( $this->can_enqueue_swiper() ) {
			if ( ! wp_script_is( 'brandy-swiper-script' ) ) {
				wp_enqueue_script( 'brandy-swiper-script', BRANDY_BLOCKS_PLUGIN_URL . '/assets/lib/swiper/swiper.min.js', array(), BRANDY_BLOCKS_SCRIPT_VERSION, true );
			}
			if ( ! wp_style_is( 'brandy-swiper-style' ) && wp_script_is( 'brandy-swiper-script' ) ) {
				wp_enqueue_style( 'brandy-swiper-style', BRANDY_BLOCKS_PLUGIN_URL . '/assets/lib/swiper/swiper.min.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
			}
		}
	}

	private function can_enqueue_swiper() {
		$deps = array(
			'brandy-blocks/testimonials',
		);
		foreach ( $deps as $dep ) {
			if ( wp_script_is( $dep ) ) {
				return true;
			}
		}
		return false;
	}
}
