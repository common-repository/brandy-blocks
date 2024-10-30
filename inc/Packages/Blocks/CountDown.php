<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;


class CountDown extends AbstractBlock {

	use SingletonTrait;

	public $name = 'CountDown';
	protected function __construct() {
		parent::__construct();
	}
	protected function init_hooks() {
		wp_register_script( 'brandy-blocks/countdown', BRANDY_BLOCKS_PLUGIN_URL . '/inc/Packages/Blocks/CountDown.js', array( 'jquery' ), BRANDY_BLOCKS_VERSION, true );
	}
	
}
