<?php

if ( ! function_exists( 'is_brandy_exists' ) ) {
	function is_brandy_exists() {
		return defined( 'BRANDY_VERSION' );
	}
}

if ( ! function_exists( 'is_wc_installed' ) ) {

	/**
	 * Check whether WC is installed
	 *
	 * @return boolean
	 *
	 * @since 1.0
	 */
	function is_wc_installed() {
		return function_exists( 'WC' );
	}
}

