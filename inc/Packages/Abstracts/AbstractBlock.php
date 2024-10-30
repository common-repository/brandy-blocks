<?php

namespace BrandyBlocks\Packages\Abstracts;

abstract class AbstractBlock {

	public $name = '';

	protected function __construct() {
		register_block_type(
			BRANDY_BLOCKS_PLUGIN_PATH . '/inc/Packages/build/blocks/' . $this->name,
			$this->get_block_attributes()
		);
		add_filter(
			'__experimental_woocommerce_blocks_add_data_attributes_to_block',
			function( $allowed_blocks ) {
				$allowed_blocks[] = 'brandy/form';
				return $allowed_blocks;
			}
		);
		$this->init_hooks();
	}

	protected function get_block_attributes() {
		return array();
	}

	protected function init_hooks() {

	}
}
