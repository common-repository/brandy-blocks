<?php

namespace BrandyBlocks\Externals\Settings\StyleProductOriginalPrice;

use BrandyBlocks\Traits\SingletonTrait;

class Caller {

	use SingletonTrait;

	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_settings_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'render_block', array( $this, 'add_original_price_style_attribute' ), 10, 2 );
	}

	public function enqueue_settings_scripts() {
		wp_enqueue_script( 'brandy-blocks/woo-product-original-price-controls', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Build/Gutenberg/StyleProductOriginalPrice/index.js', array( 'wp-edit-post' ), BRANDY_BLOCKS_SCRIPT_VERSION );
		wp_localize_script(
			'brandy-blocks/woo-product-original-price-controls',
			'brandyGlobalSettings',
			wp_get_global_settings()
		);
	}

	public function enqueue_admin_scripts() {
		wp_enqueue_style( 'brandy-blocks/admin-original-price-settings-style', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/StyleProductOriginalPrice/style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'brandy-blocks/original-price-settings-style', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/StyleProductOriginalPrice/style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
	}

	public function add_original_price_style_attribute( $content, $block ) {
		$block_name = $block['blockName'];

		if ( 'woocommerce/product-price' !== $block_name || ! $content ) {
			return $content;
		}

		$attributes                = (array) $block['attrs'];
		$original_price_typography = isset( $attributes['originalPriceTypography'] ) ? $attributes['originalPriceTypography'] : array();

		if ( empty( $original_price_typography ) ) {
			return $content;
		}

		$font_size   = isset( $original_price_typography['fontSize'] ) ? $original_price_typography['fontSize'] : 'default';
		$font_color  = isset( $original_price_typography['color'] ) ? $original_price_typography['color'] : 'default';
		$font_weight = isset( $original_price_typography['appearance']['style']['fontWeight'] ) ? $original_price_typography['appearance']['style']['fontWeight'] : 'default';
		$font_style  = isset( $original_price_typography['appearance']['style']['fontStyle'] ) ? $original_price_typography['appearance']['style']['fontStyle'] : 'default';

		$style = '';

		if ( $font_size && 'default' !== $font_size ) {
			$style .= '--original-price-size: ' . $font_size . ';';
		}

		if ( $font_color && 'default' !== $font_color ) {
			$style .= '--original-price-color: ' . $font_color . ';';
		}

		if ( $font_weight && 'default' !== $font_weight ) {
			$style .= '--original-price-weight: ' . $font_weight . ';';
		}

		if ( $font_style && 'default' !== $font_style ) {
			$style .= '--original-price-style: ' . $font_style . ';';
		}

		if ( ! empty( $style ) ) {
			$tags = new \WP_HTML_Tag_Processor( $content );
			if ( $tags->next_tag( 'div' ) ) {
				$existing_style = $tags->get_attribute( 'style' ) ?? '';
				$tags->set_attribute( 'style', $existing_style . $style );
				$content = $tags->get_updated_html();
			}
		}

		return $content;
	}
}

Caller::get_instance();
