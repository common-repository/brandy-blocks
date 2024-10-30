<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;
use BrandyBlocks\Services\GoogleMap as GoogleMapService;

class GoogleMap extends AbstractBlock {

	use SingletonTrait;

	public $name = 'GoogleMap';

	protected $attributes = array();

	protected function init_hooks() {
		wp_register_script( 'brandy-blocks/google-map', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Packages/Blocks/GoogleMap.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
		wp_localize_script(
			'brandy-blocks/google-map',
			'bbGoogleMap',
			array(
				'apiKey' => GoogleMapService::get_api_key(),
			)
		);
	}

}
