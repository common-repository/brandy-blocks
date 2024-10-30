<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $attributes ) || ! isset( $block ) ) {
	return;
}

use BrandyBlocks\Packages\Blocks\Form;

do_action( 'brandy_blocks_before_form_content', $attributes, $block );

Form::render_inner_blocks( $block );

wp_nonce_field( 'brandy-custom-form', 'brandy-custom-form-nonce' );

do_action( 'brandy_blocks_after_form_content', $attributes, $block );

