<?php

namespace BrandyBlocks\Externals\Settings\DisplayAnimation;

use BrandyBlocks\Traits\SingletonTrait;

class Caller {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_settings_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_filter( 'block_type_metadata_settings', array( $this, 'declare_attribute' ), 10 );
		add_filter( 'render_block', array( $this, 'add_animation_data_attribute' ), 10, 2 );
	}

	public function declare_attribute( $settings ) {
		if ( ! empty( $settings['attributes'] ) ) {
			$settings['attributes']['displayAnimation'] = array(
				'type'    => 'object',
				'default' => array(
					'type'          => 'none',
					'duration'      => 3,
					'animateOnView' => false,
				),
			);
		}
		return $settings;
	}

	public function enqueue_settings_scripts() {
		wp_enqueue_script( 'brandy-blocks/display-animation-controls', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Build/Gutenberg/DisplayAnimation/index.js', array( 'wp-edit-post' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
	}

	public function enqueue_frontend_scripts() {
		wp_enqueue_style( 'brandy-blocks/display-animation-style', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/DisplayAnimation/style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
		wp_enqueue_script( 'brandy-blocks/display-animation-script', BRANDY_BLOCKS_PLUGIN_URL . 'inc/Externals/Settings/DisplayAnimation/script.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
	}

	public function add_animation_data_attribute( $content, $block ) {

		$attributes = (array) $block['attrs'];

		$escaped_data_attributes = array();
		$escaped_style           = '';

		foreach ( $attributes as $key => $value ) {
			if ( 'displayAnimation' !== $key ) {
				continue;
			}

			if ( empty( $value['type'] ) ) {
				continue;
			}

			if ( 'none' === $value['type'] ) {
				continue;
			}
			$escaped_data_attributes[] = 'data-animate-effect="' . esc_attr( $value['type'] ) . '"';
			$escaped_data_attributes[] = 'data-animate-on-view="' . esc_attr( $value['animateOnView'] ? 'true' : 'false' ) . '"';
			$escaped_style             = 'animation-duration:' . esc_attr( $value['duration'] ?? 3 ) . 's';
		}

		if ( ! empty( $escaped_style ) ) {
			$tags = new \WP_HTML_Tag_Processor( $content );
			if ( $tags->next_tag() ) {
				$existing_style = $tags->get_attribute( 'style' );
				$updated_style  = '';

				if ( empty( $existing_style ) ) {
					$existing_style = '';
				} else {
					if ( ! str_ends_with( $existing_style, ';' ) ) {
						$existing_style .= ';';
					}
				}
				$updated_style  = $existing_style;
				$updated_style .= $escaped_style . ';';
				$tags->set_attribute( 'style', $updated_style );
				$content = $tags->get_updated_html();
			}
		}

		return preg_replace( '/^<div /', '<div ' . implode( ' ', $escaped_data_attributes ) . ' ', trim( $content ) );
	}

}

Caller::get_instance();
