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

?>
<input type="hidden" name="wc_reset_password" value="true">

<?php
wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' );

do_action( 'brandy_blocks_after_form_content', $attributes, $block );
