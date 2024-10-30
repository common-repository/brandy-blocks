<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;
use Error;

class RelativePosts extends AbstractBlock {

	use SingletonTrait;

	public $name = 'RelativePosts';

	protected $attributes = array();

}
