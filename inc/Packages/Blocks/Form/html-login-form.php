<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $attributes ) || ! isset( $block ) ) {
	return;
}

use BrandyBlocks\Packages\Blocks\Form;

do_action( 'brandy_blocks_before_form_content', $attributes, $block );

if ( ! empty( $_GET['wc_login_failed'] ) ) {
	Form::render_message( $attributes, 'failed' );
}

Form::render_inner_blocks( $block );

wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' );

do_action( 'brandy_blocks_after_form_content', $attributes, $block );

