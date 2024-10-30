<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Core\ProductController;
use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;


class ProductsWithBanners extends AbstractBlock {

	use SingletonTrait;

	public $name = 'ProductsWithBanners';

	public const DEFAULT_ATTRIBUTES = array(
		'banner_settings'           => array(
			'primary'   => array(
				'mediaID'    => null,
				'imageURL'   => 'https://thien.ninjateam.org/wp-content/uploads/2024/01/fashion-banner-img.png',
				'title'      => 'Our top stars',
				'subTitle'   => 'Discover and shop our most popular plants, pots and accessories',
				'buttonText' => 'Shop Now',
			),
			'secondary' => array(
				'mediaID'    => null,
				'imageURL'   => 'https://thien.ninjateam.org/wp-content/uploads/2024/01/fashion-banner-img.png',
				'title'      => 'Love Your Space',
				'buttonText' => 'Shop Now',
			),
		),
		'content_settings'          => array(
			'showImage'     => true,
			'showCategory'  => true,
			'showTitle'     => true,
			'showPrice'     => true,
			'showRating'    => true,
			'showAddToCart' => true,
		),
		'product_category_settings' => array(
			'selectedCategories' => array(),
			'searchTerm'         => '',
		),

	);

	protected $attributes = array();

	protected function __construct() {

		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		parent::__construct();

	}

	protected function init_hooks() {

		wp_register_script( 'brandy-blocks/products-with-banners', BRANDY_BLOCKS_PLUGIN_URL . '/inc/Packages/Blocks/ProductsWithBanners.js', array( 'jquery' ), BRANDY_BLOCKS_VERSION, true );

	}

	protected function get_block_attributes() {
		return array(
			'render_callback' => array( $this, 'render' ),
		);
	}

	public function render( $attributes = array(), $content = '', $block = null ) {

		if ( ! \is_wc_installed() ) {
			return __( 'Please install WooCommerce', 'brandy-blocks' );
		}

		$this->attributes = $this->parse_attributes_from_block_attributes( $attributes );
		$products         = $this->get_query_result( $this->attributes );
		$content          = sprintf(
			'<div class="brandy-block-products-with-banners" data-settings="%s">%s%s%s</div>',
			esc_attr( wp_json_encode( $this->attributes ) ),
			self::render_banner( 'primary' ),
			$this->render_list( $products ),
			self::render_banner( 'secondary' ),
		);
		return $content;
	}

	public function get_query_result( $attributes ) {
		$category_ids = isset( $attributes['product_category_settings']['selectedCategories'] ) && ! empty( $attributes['product_category_settings']['selectedCategories'] ) ? $attributes['product_category_settings']['selectedCategories'] : array();

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 5,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		if ( $category_ids ) {

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $category_ids,
					'operator' => 'IN',
				),
			);
		}

		$result = new \WP_Query( $args );
		$result = array_values( array_map( 'wc_get_product', $result->posts ) );
		return $result;
	}

	protected function render_banner( $key = 'primary' ) {
		$banner     = $this->attributes['banner_settings'][ $key ];
		$imageURL   = isset( $banner['imageURL'] ) && ! empty( $banner['imageURL'] ) ? $banner['imageURL'] : '';
		$title      = ! empty( $banner['title'] ) ? $banner['title'] : self::DEFAULT_ATTRIBUTES['banner_settings'][ $key ]['title'];
		$subTitle   = ! empty( $banner['subTitle'] ) ? $banner['subTitle'] : self::DEFAULT_ATTRIBUTES['banner_settings'][ $key ]['subTitle'];
		$buttonText = ! empty( $banner['buttonText'] ) ? $banner['buttonText'] : self::DEFAULT_ATTRIBUTES['banner_settings'][ $key ]['buttonText'];

		return sprintf(
			'<div class="product brandy-banner-wrapper %1$s" id="%2$s">
                <div class="brandy-products-with-banners-banner">
					<h2 class="brandy-products-with-banners-banner__title">%4$s</h2>
					<p class="brandy-products-with-banners-banner__description">%5$s</p>
					<img class="brandy-products-with-banners-banner__img" src="%3$s" alt="%4$s">
                    <a href="#" class="brandy-products-with-banners-banner__button">%6$s</a>
            </div>
        </div>',
			esc_attr( 'brandy-' . $key . '-banner-wrapper' ),
			esc_attr( 'brandy_' . $key . '_banner_' . md5( uniqid() ) ),
			esc_url( $imageURL ),
			esc_html( $title ),
			esc_html( $subTitle ),
			esc_html( $buttonText )
		);
	}

	public function render_list( $products ) {
		return implode( '', array_map( array( $this, 'render_product' ), $products ) );
	}

	private function parse_bool( $value ) {
		if ( 'true' === $value ) {
			return true;
		}

		if ( 'false' === $value ) {
			return false;
		}

		return $value;
	}

	protected function parse_attributes_from_block_attributes( $block_attributes ) {
		return array(
			'banner_settings'           => isset( $block_attributes['banner_settings'] ) ? $block_attributes['banner_settings'] : self::DEFAULT_ATTRIBUTES['banner_settings'],
			'content_settings'          => isset( $block_attributes['content_settings'] ) ? $block_attributes['content_settings'] : self::DEFAULT_ATTRIBUTES['content_settings'],
			'product_category_settings' => isset( $block_attributes['product_category_settings'] ) ? $block_attributes['product_category_settings'] : self::DEFAULT_ATTRIBUTES['product_category_settings'],
		);
	}

	public function render_product( $product ) {
		$content_settings   = isset( $this->attributes['content_settings'] ) ? $this->attributes['content_settings'] : self::DEFAULT_ATTRIBUTES['content_settings'];
		$product_controller = new ProductController( $product );

		if ( function_exists( 'brandy_loop_product_item' ) ) {
			ob_start();
			\brandy_loop_product_item(
				$product,
				array(
					'show_title'      => $this->parse_bool( $content_settings['showTitle'] ),
					'show_image'      => $this->parse_bool( $content_settings['showImage'] ),
					'show_button'     => $this->parse_bool( $content_settings['showAddToCart'] ),
					'show_category'   => $this->parse_bool( $content_settings['showCategory'] ),
					'show_rating'     => $this->parse_bool( $content_settings['showRating'] ),
					'show_price'      => $this->parse_bool( $content_settings['showPrice'] ),
					'show_sale_flash' => $this->parse_bool( $content_settings['showImage'] ),
				)
			);
			$content = ob_get_contents();
			ob_end_clean();
		} else {
			$content = sprintf(
				'<div class="product">
                    <div class="brandy-block-product__thumbnail">%s%s</div>
                    <div class="brandy-block-product__content">%s%s%s%s%s</div>
                </div>',
				$this->parse_bool( $content_settings['showImage'] ) ? $product_controller->get_template_sale_flash() : '',
				$this->parse_bool( $content_settings['showImage'] ) ? $product_controller->get_template_image() : '',
				$this->parse_bool( $content_settings['showCategory'] ) ? $product_controller->get_template_category() : '',
				$this->parse_bool( $content_settings['showTitle'] ) ? $product_controller->get_template_title() : '',
				$this->parse_bool( $content_settings['showRating'] ) ? $product_controller->get_template_rating() : '',
				$this->parse_bool( $content_settings['showPrice'] ) ? $product_controller->get_template_pricing() : '',
				$this->parse_bool( $content_settings['showAddToCart'] ) ? $product_controller->get_template_button() : ''
			);
		}

		return apply_filters( 'brandy_blocks_loop_product_content', $content, $product, $product_controller, $content_settings );
	}
}
