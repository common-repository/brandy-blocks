<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;
use BrandyBlocks\Wishlist;

class AddToWishlist extends AbstractBlock {

	use SingletonTrait;

	public $name = 'AddToWishlist';

	const META_NAME = 'brandy_wishlist';

	protected function get_block_attributes() {
		return array(
			'render_callback' => array( $this, 'render' ),
		);
	}

	public function init_hooks() {
	}

	public function render( $attributes, $content, $block ) {

		if ( ! is_wc_installed() ) {
			return esc_html_e( 'Please install WooCommerce', 'brandy-blocks' );
		}
		$current_product = null;

		if ( ! empty( $block->context['singleProduct'] ) && ! empty( $block->context['postId'] ) ) {
			$current_product = wc_get_product( $block->context['postId'] );
		} else {
			global $product;
			$current_product = $product;
		}

		if ( ! $current_product || ! ( $current_product instanceof \WC_Product ) ) {
			return '';
		}
		$product_id     = $current_product->get_id();
		$wishlist_items = Wishlist::get_wishlist_items();

		$is_added = in_array( $product_id, $wishlist_items );

		$tags = new \WP_HTML_Tag_Processor( $content );
		if ( $tags->next_tag() ) {
			if ( $is_added ) {
				$existing_class  = $tags->get_attribute( 'class' );
				$existing_class .= ' added';
				$tags->set_attribute( 'class', $existing_class );
			}
			$tags->set_attribute( 'data-product-id', $product_id );
			$content = $tags->get_updated_html();
		}

		return $content;
	}
}
