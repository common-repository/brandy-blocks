<?php

namespace BrandyBlocks\Services;

class GoogleMap {
	public const OPTION_NAME = 'brandy_blocks_google_map_settings';

	public static function get_settings() {
		return get_option(
			self::OPTION_NAME,
			array(
				'api_key' => '',
			)
		);
	}

	public static function update_settings( $data ) {
		$old_option = self::get_settings();
		$new_option = wp_parse_args( $data, $old_option );
		return update_option(
			self::OPTION_NAME,
			$new_option
		);
	}

	public static function get_api_key() {
		$settings = self::get_settings();
		return $settings['api_key'] ?? '';
	}

}
