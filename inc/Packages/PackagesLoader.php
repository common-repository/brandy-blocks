<?php

namespace BrandyBlocks\Packages;

use BrandyBlocks\Traits\SingletonTrait;

class PackagesLoader {

	use SingletonTrait;

	protected function __construct() {

		add_action( 'init', array( $this, 'register_blocks' ) );

		add_action(
			'init',
			function() {
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ) );

			}
		);

		add_filter( 'block_categories_all', array( $this, 'register_block_categories' ) );

		add_filter( 'block_type_metadata', array( $this, 'alter_core_block_registration' ) );

		add_filter(
			'__experimental_woocommerce_blocks_add_data_attributes_to_namespace',
			function( $allowed_namespaces ) {
				$allowed_namespaces[] = 'brandy';
				return $allowed_namespaces;
			}
		);

	}

	public function register_blocks() {
		$dir = new \DirectoryIterator( BRANDY_BLOCKS_PLUGIN_PATH . 'inc/Packages/build/blocks' );
		foreach ( $dir as $fileinfo ) {
			if ( $fileinfo->isDot() ) {
				continue;
			}
			$folder_name = $fileinfo->getFilename();

			if ( ! file_exists( BRANDY_BLOCKS_PLUGIN_PATH . 'inc/Packages/build/blocks/' . $folder_name ) ) {
				continue;
			}

			$class_name = 'BrandyBlocks\\Packages\\Blocks\\' . $folder_name;

			if ( ! class_exists( $class_name ) || ! is_callable( array( $class_name, 'get_instance' ) ) ) {
				continue;
			}

			$class_name::get_instance();

		}
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'brandy_blocks_script', BRANDY_BLOCKS_PLUGIN_URL . '/inc/Packages/Assets/js/script.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
		wp_enqueue_style( 'brandy_block_editor_styles', BRANDY_BLOCKS_PLUGIN_URL . '/inc/Packages/Assets/css/editor-style.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
		wp_localize_script(
			'brandy_blocks_script',
			'brandyBlocks',
			array(
				'url'    => array(
					'site'   => get_site_url(),
					'images' => BRANDY_BLOCKS_PLUGIN_URL . 'assets/images',
				),
				'ajax'   => array(
					'path' => admin_url( 'admin-ajax.php' ),
				),
				'blocks' => array(),
			)
		);

	}

	public function register_block_categories( $categories ) {
		return array(
			array(
				'slug'  => 'brandy-blocks',
				'title' => 'Brandy',
				'icon'  => null,
			),
			...$categories,
		);
	}

	public function alter_core_block_registration( $metadata ) {
		if ( 'core/query-pagination' === $metadata['name'] ) {
			$metadata['ancestor'][] = 'brandy/relative-posts';
		}
		return $metadata;
	}
}
