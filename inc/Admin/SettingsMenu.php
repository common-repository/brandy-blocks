<?php

namespace BrandyBlocks\Admin;

use BrandyBlocks\Traits\SingletonTrait;

class SettingsMenu {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );

		foreach ( array_keys( $this->get_settings_tabs() ) as $slug ) {
			add_action(
				'brandy_blocks_settings_' . $slug,
				function() use ( $slug ) {
					$path = dirname( __FILE__ ) . '/Views/html-block-settings-' . $slug . '.php';
					if ( file_exists( $path ) ) {
						include $path;
					}
				}
			);
		}

	}

	public function add_menu() {
		$title = 'Brandy Blocks';
		$slug  = 'brandy-blocks-settings';
		add_options_page(
			$title,
			$title,
			'manage_options',
			$slug,
			array( $this, 'render_page' ),
		);
	}

	public function render_page() {

		$current_tab   = ! empty( $_REQUEST['tab'] ) ? sanitize_title( $_REQUEST['tab'] ) : 'general';
		$settings_tabs = $this->get_settings_tabs();

		include dirname( __FILE__ ) . '/Views/html-block-settings.php';
	}

	public function get_settings_tabs() {
		return array(
			'general'    => __( 'General', 'brandy-blocks' ),
			'google_map' => __( 'Google Map', 'brandy-blocks' ),
		);
	}

}

SettingsMenu::get_instance();
