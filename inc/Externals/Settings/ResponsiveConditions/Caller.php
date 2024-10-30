<?php

namespace BrandyBlocks\Externals\Settings\ResponsiveConditions;

use BrandyBlocks\Traits\SingletonTrait;

class Caller {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_settings_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'block_type_metadata_settings', array( $this, 'declare_attribute' ), 10 );
	}

	public function declare_attribute( $settings ) {
		if ( ! empty( $settings['attributes'] ) ) {
			$settings['attributes']['hideOnDesktop'] = array(
				'type'      => 'boolean',
				'default'   => false,
				'attribute' => 'data-hide-on-desktop',
			);
			$settings['attributes']['hideOnTablet']  = array(
				'type'      => 'boolean',
				'default'   => false,
				'attribute' => 'data-hide-on-tablet',
			);
			$settings['attributes']['hideOnMobile']  = array(
				'type'      => 'boolean',
				'default'   => false,
				'attribute' => 'data-hide-on-mobile',
			);
		}
		return $settings;
	}

	public function enqueue_settings_scripts() {
		wp_enqueue_script( 'brandy-blocks/responsive-conditions-controls', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Build/Gutenberg/ResponsiveConditions/index.js', array( 'wp-edit-post' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
		wp_enqueue_style( 'brandy-blocks/responsive-conditions-controls', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Build/Gutenberg/ResponsiveConditions/style-index.js.css', array(), time() );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'brandy-blocks/responsive-conditions-style', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/ResponsiveConditions/style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
	}

}

Caller::get_instance();
