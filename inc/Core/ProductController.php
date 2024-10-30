<?php

namespace BrandyBlocks\Core;

class ProductController {

	protected $product = null;

	public function __construct( $product ) {
		$this->product = $product;
	}

	public function get_template_image() {
		$attr = array(
			'alt' => '',
		);

		if ( $this->product->get_image_id() ) {
			$image_alt = get_post_meta( $this->product->get_image_id(), '_wp_attachment_image_alt', true );
			$attr      = array(
				'alt' => ( $image_alt ? $image_alt : $this->product->get_name() ),
			);
		}

		return '<a class="brandy-block-product__image" href="' . $this->product->get_permalink() . '">' . $this->product->get_image( 'woocommerce_thumbnail', $attr ) . '</a>';
	}

	public function get_template_title() {
		return sprintf(
			'<h4 class="brandy-block-product__title %1$s"><a href="%2$s">%3$s</a></h4>',
			apply_filters( 'brandy_block_product_title_classes', '' ),
			$this->product->get_permalink(),
			esc_html( $this->product->get_title() )
		);
	}

	public function get_template_category() {

		$html = '';

		$terms = get_the_terms( $this->product->get_id(), 'product_cat' );

		$current_term = $terms[0];

		if ( ! empty( $current_term ) ) :
			$term_name = $current_term->name;
			$term_link = get_term_link( $current_term );
			$html      = sprintf(
				'<a class="brandy-block-product__category %1$s" href="%2$s">%3$s</a>',
				esc_attr( apply_filters( 'brandy_block_product_category_classes', '' ) ),
				esc_url( $term_link ),
				esc_html( $term_name )
			);
		endif;

		return $html;
	}

	public function get_template_sale_flash() {
		if ( ! $this->product->is_on_sale() ) {
			return '';
		}

		return sprintf( '<span class="brandy-block-product__sale-flash onsale">%s</span>', esc_html__( 'Sale!', 'brandy-blocks' ) );
	}

	public function get_template_pricing() {
		return sprintf(
			'<div class="brandy-block-product__price price">%s</div>',
			wp_kses_post( $this->product->get_price_html() )
		);
	}

	public function get_template_button() {

		if ( function_exists( 'brandy_get_wc_add_to_cart_button' ) ) {
			return \brandy_get_wc_add_to_cart_button( $this->product, array( 'quantity' => 1 ) );
		}

		$attributes = array(
			'aria-label'       => $this->product->add_to_cart_description(),
			'data-quantity'    => '1',
			'data-product_id'  => $this->product->get_id(),
			'data-product_sku' => $this->product->get_sku(),
			'data-price'       => wc_get_price_to_display( $this->product ),
			'rel'              => 'nofollow',
			'class'            => 'brandy-block-product__add-to-cart add_to_cart_button brandy-btn',
		);

		if (
			$this->product->supports( 'ajax_add_to_cart' ) &&
			$this->product->is_purchasable() &&
			( $this->product->is_in_stock() || $this->product->backorders_allowed() )
		) {
			$attributes['class'] .= ' ajax_add_to_cart';
		}

		$add_to_cart_btn = sprintf(
			'<a href="%s" %s>%s</span></a>',
			esc_url( $this->product->add_to_cart_url() ),
			wc_implode_html_attributes( $attributes ),
			esc_html( $this->product->add_to_cart_text() )
		);

		$product_added_to_cart_btn = sprintf(
			'<a class="added-to-cart-btn view-cart" href="%s"><svg width="29" height="29" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.3 20.6875C13.3 24.7609 16.6141 28.075 20.6875 28.075C24.7609 28.075 28.075 24.7609 28.075 20.6875C28.075 16.6141 24.7609 13.3 20.6875 13.3C16.6141 13.3 13.3 16.6141 13.3 20.6875ZM14.575 20.6875C14.575 17.3168 17.3168 14.575 20.6875 14.575C24.0582 14.575 26.8 17.3168 26.8 20.6875C26.8 24.0582 24.0582 26.8 20.6875 26.8C17.3168 26.8 14.575 24.0582 14.575 20.6875Z" fill="#122940" stroke="#122940" stroke-width="0.15"/><path d="M23.1021 23.9984L23.1024 23.9986C23.2196 24.0916 23.361 24.1375 23.5 24.1375C23.6858 24.1375 23.8722 24.056 23.9974 23.8989L23.9974 23.8988C24.2166 23.6235 24.172 23.2233 23.898 23.0028L23.8978 23.0027L21.325 20.944V17.3125C21.325 16.9606 21.0394 16.675 20.6875 16.675C20.3356 16.675 20.05 16.9606 20.05 17.3125V21.25C20.05 21.444 20.1381 21.6262 20.2894 21.7482L20.2896 21.7484L23.1021 23.9984Z" fill="#122940" stroke="#122940" stroke-width="0.15"/><path d="M2.49175 23.575H11.6875C12.0394 23.575 12.325 23.2894 12.325 22.9375C12.325 22.5856 12.0394 22.3 11.6875 22.3H2.49175C2.33067 22.3 2.2 22.1693 2.2 22.0082V8.95H22.3V11.6875C22.3 12.0394 22.5856 12.325 22.9375 12.325C23.2894 12.325 23.575 12.0394 23.575 11.6875V8.3125C23.575 7.96058 23.2894 7.675 22.9375 7.675H1.5625C1.21058 7.675 0.925 7.96058 0.925 8.3125V22.0082C0.925 22.872 1.62795 23.575 2.49175 23.575Z" fill="#122940" stroke="#122940" stroke-width="0.15"/><path d="M18.9816 2.45256L18.9814 2.4522C18.8968 2.29656 18.7332 2.2 18.5557 2.2H5.9444C5.76696 2.2 5.60317 2.29652 5.51737 2.45252L5.51734 2.45256L2.11988 8.61975L18.9816 2.45256ZM18.9816 2.45256L22.379 8.61968M18.9816 2.45256L22.379 8.61968M1.25416 8.87098L1.25404 8.87091C0.946633 8.70127 0.834485 8.31251 1.00411 8.00514M1.25416 8.87098L1.00411 8.00514M1.25416 8.87098C1.5626 9.04049 1.9502 8.9284 2.11984 8.61981L1.25416 8.87098ZM1.00411 8.00514L4.4015 1.83809M1.00411 8.00514L4.4015 1.83809M23.496 8.00406L23.496 8.00413C23.6655 8.31246 23.5535 8.69989 23.2452 8.86963M23.496 8.00406L23.2448 8.86985C23.2449 8.86978 23.245 8.8697 23.2452 8.86963M23.496 8.00406L20.0985 1.8369C19.7894 1.27394 19.1972 0.925 18.5557 0.925H5.9444C5.30291 0.925 4.71067 1.27392 4.40156 1.83798M23.496 8.00406L4.40156 1.83798M23.2452 8.86963C23.1479 8.92455 23.0419 8.95 22.9375 8.95C22.7134 8.95 22.4952 8.83156 22.3791 8.61981M23.2452 8.86963L22.3791 8.61981M22.3791 8.61981L22.379 8.61968M22.3791 8.61981L22.379 8.61968M4.4015 1.83809C4.40152 1.83805 4.40154 1.83801 4.40156 1.83798M4.4015 1.83809L4.40156 1.83798" fill="#122940" stroke="#122940" stroke-width="0.15"/><path d="M11.6125 8.3125C11.6125 8.66442 11.8981 8.95 12.25 8.95C12.6019 8.95 12.8875 8.66442 12.8875 8.3125V1.5625C12.8875 1.21058 12.6019 0.925 12.25 0.925C11.8981 0.925 11.6125 1.21058 11.6125 1.5625V8.3125Z" fill="#122940" stroke="#122940" stroke-width="0.15"/></svg></a>',
			esc_url( function_exists( 'wc_get_cart_url' ) ? \wc_get_cart_url() : '#' )
		);

		return $add_to_cart_btn . $product_added_to_cart_btn;

	}

	public function get_template_rating() {
		$rating_count = $this->product->get_rating_count();
		$average      = $this->product->get_average_rating();

		if ( $rating_count > 0 ) {
			return sprintf(
				'<div class="brandy-block-product__rating">%s</div>',
				wc_get_rating_html( $average, $rating_count ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
		}
		return '';
	}
}
