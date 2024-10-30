<?php

namespace BrandyBlocks\Externals\Settings\ButtonSettings;

use BrandyBlocks\Traits\SingletonTrait;

class Caller {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_settings_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'enqueue_block_assets', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'block_type_metadata_settings', array( $this, 'declare_attribute' ), 10, 2 );
		add_filter( 'render_block_woocommerce/product-button', array( $this, 'change_woocommerce_button_styles' ), 10, 2 );
	}

	public function declare_attribute( $settings, $name ) {
		if ( ! isset( $settings['name'] ) ) {
			return $settings;
		}
		if ( ! in_array( $settings['name'], array( 'core/button', 'woocommerce/product-button' ) ) ) {
			return $settings;
		}

		if ( ! empty( $settings['attributes'] ) ) {
			$settings['attributes']['hoverBackgroundColor'] = array(
				'type'    => 'string',
				'default' => '',
			);
			$settings['attributes']['hoverTextColor']       = array(
				'type'    => 'string',
				'default' => '',
			);
			$settings['attributes']['hoverBorder']          = array(
				'type'    => 'object',
				'default' => array(),
			);
		}
		return $settings;
	}

	public function enqueue_settings_scripts() {
		wp_enqueue_script( 'brandy-blocks/button-settings-controls', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Build/Gutenberg/ButtonSettings/index.js', array( 'wp-edit-post' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'brandy-blocks/button-settings-style', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/ButtonSettings/style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
	}

	public function change_woocommerce_button_styles( $html, $block ) {
		$tag = new \WP_HTML_Tag_Processor( $html );

		if ( $tag->next_tag() ) {
			if ( $tag->get_attribute( 'data-hover-background-color' ) ) {
				$hover_background_color = $tag->get_attribute( 'data-hover-background-color' );
			}
			if ( $tag->get_attribute( 'data-hover-text-color' ) ) {
				$hover_text_color = $tag->get_attribute( 'data-hover-text-color' );
			}
			if ( $tag->get_attribute( 'data-hover-border' ) ) {
				$hover_border = json_decode( $tag->get_attribute( 'data-hover-border' ), true );
			}

			$escape_styles = array();
			if ( isset( $hover_background_color ) ) {
				$escape_styles[] = '--button-hover-background-color:' . $hover_background_color;
			}
			if ( isset( $hover_text_color ) ) {
				$escape_styles[] = '--button-hover-color:' . $hover_text_color;
			}
			if ( isset( $hover_border ) ) {
				foreach ( array( 'top', 'bottom', 'left', 'right' ) as $aspect ) {
					if ( isset( $hover_border[ $aspect ]['color'] ) ) {
						$escape_styles[] = '--button-hover-border-c:' . $hover_border[ $aspect ]['color'];
					}
					if ( isset( $hover_border[ $aspect ]['width'] ) ) {
						$escape_styles[] = '--button-hover-border-w:' . $hover_border[ $aspect ]['width'];
					}
					if ( isset( $hover_border[ $aspect ]['style'] ) ) {
						$escape_styles[] = '--button-hover-border-s:' . $hover_border[ $aspect ]['style'];
					}
				}
				if ( isset( $hover_border['color'] ) ) {
					$escape_styles[] = '--button-hover-border-c:' . $hover_border['color'];
				}
				if ( isset( $hover_border['width'] ) ) {
					$escape_styles[] = '--button-hover-border-w:' . $hover_border['width'];
				}
				if ( isset( $hover_border['style'] ) ) {
					$escape_styles[] = '--button-hover-border-s:' . $hover_border['style'];
				}
			}
			$existing_style = $tag->get_attribute( 'style' );
			$updated_style  = '';

			if ( empty( $existing_style ) ) {
				$existing_style = '';
			} else {
				if ( ! str_ends_with( $existing_style, ';' ) ) {
					$existing_style .= ';';
				}
			}
			$updated_style  = $existing_style;
			$updated_style .= implode( ';', $escape_styles );
			$tag->set_attribute( 'style', $updated_style );
			$html = $tag->get_updated_html();
		}
		return $html;
	}

}

Caller::get_instance();
