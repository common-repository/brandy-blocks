<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;

class JumpToSection extends AbstractBlock {

	use SingletonTrait;

	public $name = 'JumpToSection';

	protected $attributes = array();

	protected function init_hooks() {
		wp_register_script( 'brandy-blocks/jump-to-section', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Packages/Blocks/JumpToSection.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
	}

}
