<?php

defined( 'ABSPATH' ) || exit;

if ( ! \is_wc_installed() ) {
	echo esc_html__( 'Please install WooCommerce', 'brandy-blocks' );
	return;
}

$wishlist_list = empty( $args['wishlist_list'] ) ? array() : $args['wishlist_list'];
$products      = array_map( 'wc_get_product', $wishlist_list );
$in_drawer     = true;

?>

<div class="brandy-wishlist-shortcode">
	<div class="brandy-wishlist-drawer__content">
	<?php if ( empty( $products ) ) : ?> 
		<div class="brandy-wishlist-empty-state"><?php esc_html_e( 'No product on your wishlist.', 'brandy-blocks' ); ?></div>
	<?php else : ?>
		<div class="brandy-wishlist-drawer__list">
			<?php
			foreach ( $products as $product ) :
				$product_permalink = $product->get_permalink();
				$item_thumbnail    = apply_filters( 'brandy_wishlist_item_thumbnail', $product->get_image() );
				?>
				<div class="brandy-wishlist-drawer__item">
					<div class="item-thumbnail"><?php printf( '<a href="%s" aria-label="%s">%s</a>', esc_url( $product_permalink ), $product->get_name(), $item_thumbnail ); // PHPCS: XSS ok. ?></div>
					<div class="item-detail">
						<div class="item-name brandy-link-underline-to-a-child"><?php printf( '<a href="%1$s" aria-label="%2$s">%2$s</a>', esc_url( $product_permalink ), $product->get_name() ); // PHPCS: XSS ok. ?></div>
						<div class="item-price">
							<span><?php echo wp_kses_post( \wc_price( $product->get_price() ) ); ?></span>
							<span class="item-remove"><div class="brandy-wishlist-item-remove" data-product-id=<?php echo esc_attr( $product->get_id() ); ?>><svg xmlns="http://www.w3.org/2000/svg" width="16" height="18" fill="none" viewBox="0 0 16 18"><g stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"><path d="M13.721 6.92c0 6.942 1 10.08-5.73 10.08s-5.71-3.138-5.71-10.08M15 4.26H1M10.97 4.26S11.43 1 8 1C4.575 1 5.033 4.26 5.033 4.26"></path></g></svg></div></span>
						</div>
						<div class="item-actions">
								<?php
								if ( is_brandy_exists() ) {
									echo \brandy_get_wc_add_to_cart_button( //PHPCS:ignore.
										$product,
										array(
											'quantity' => 1,
											'is_ajax'  => true,
										)
									);
								}
								?>
							</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<input type="hidden" name="brandy-remove-wishlist-nonce" value="<?php echo esc_attr( wp_create_nonce( 'brandy_remove_wishlist_item' ) ); ?>"/>
		<div class="brandy-wishlist-shortcode-loading">
			<span class='brandy-loader brandy-wishlist-loading-icon'></span>
		</div>
		<?php endif; ?>
	</div>
</div>
