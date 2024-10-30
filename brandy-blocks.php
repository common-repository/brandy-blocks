<?php
/**
 * Plugin Name: Brandy Blocks - Extra blocks for Brandy theme
 * Plugin URI: https://yaycommerce.com/
 * Description: Brandy blocks.
 * Version: 1.0.5
 * Author: YayCommerce
 * Author URI: https://yaycommerce.com/
 * License: GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: brandy-blocks
 * Requires PHP: 5.7
 * Domain Path: /languages
 *
 * @package BrandyBlocks
 */

namespace BrandyBlocks;

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'BRANDY_BLOCKS_FILE' ) ) {
	define( 'BRANDY_BLOCKS_FILE', __FILE__ );
}
if ( ! defined( 'BRANDY_BLOCKS_ABSPATH' ) ) {
	define( 'BRANDY_BLOCKS_ABSPATH', __DIR__ . '/' );
}
if ( ! defined( 'BRANDY_BLOCKS_PLUGIN_PATH' ) ) {
	define( 'BRANDY_BLOCKS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BRANDY_BLOCKS_PLUGIN_URL' ) ) {
	define( 'BRANDY_BLOCKS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'BRANDY_BLOCKS_PLUGIN_BASENAME' ) ) {
	define( 'BRANDY_BLOCKS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'BRANDY_BLOCKS_IS_DEVELOPMENT' ) ) {
	define( 'BRANDY_BLOCKS_IS_DEVELOPMENT', false );
}
if ( ! defined( 'BRANDY_BLOCKS_VERSION' ) ) {
	define( 'BRANDY_BLOCKS_VERSION', '1.0.5' );
}
if ( ! defined( 'BRANDY_BLOCKS_SCRIPT_VERSION' ) ) {
	define( 'BRANDY_BLOCKS_SCRIPT_VERSION', BRANDY_BLOCKS_IS_DEVELOPMENT ? time() : BRANDY_BLOCKS_VERSION );
}

require_once BRANDY_BLOCKS_PLUGIN_PATH . 'vendor/autoload.php';

Initialize::get_instance();

add_action(
	'plugins_loaded',
	function() {
		$current_theme = wp_get_theme();

		if ( 'brandy' !== $current_theme->template ) {
			return;
		}

		/**
		 * Trick to add widget editor to customizer
		 */
		add_filter(
			'customize_loaded_components',
			function( $components ) {
				if ( ! isset( $components['widgets'] ) ) {
					$components[] = 'widgets';
				}
				return $components;
			}
		);
	},
	1
);
