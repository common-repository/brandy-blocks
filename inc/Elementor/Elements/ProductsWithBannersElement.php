<?php

namespace BrandyBlocks\Elementor\Elements;

use BrandyBlocks\Core\ProductController;
class ProductsWithBannersElement extends \Elementor\Widget_Base {
	protected $settings = array();

	public function get_name() {
		return 'products-with-banners-element';
	}

	public function get_title() {
		return esc_html__( 'Products With Banners', 'brandy-blocks' );
	}

	public function get_icon() {
		return 'eicon-products';
	}

	public function get_categories() {
		return array( 'brandy-blocks' );
	}

	public function get_keywords() {
		return array( __( 'Products With banners', 'brandy-blocks' ), __( 'Products With Banners element', 'brandy-blocks' ) );
	}
	protected function banner_controls_tab( $key = 'primary_banner' ) {
		$label_tab = 'primary_banner' === $key ? __( 'Primary Banner', 'brandy-blocks' ) : __( 'Secondary Banner', 'brandy-blocks' );

		$this->start_controls_tab(
			$key . '_tab',
			array(
				'label' => $label_tab,
			)
		);

		$this->add_control(
			$key . '_image',
			array(
				'label'   => esc_html__( 'Choose Image', 'brandy-blocks' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array(
					'url' => 'https://thien.ninjateam.org/wp-content/uploads/2024/01/fashion-banner-img.png',
				),
			)
		);

		$this->add_control(
			$key . '_title',
			array(
				'label'       => esc_html__( 'Title', 'brandy-blocks' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Our top stars', 'brandy-blocks' ),
				'placeholder' => esc_html__( 'Enter banner title here', 'brandy-blocks' ),
			)
		);

		$this->add_control(
			$key . '_subtitle',
			array(
				'label'       => esc_html__( 'Subtitle', 'brandy-blocks' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'rows'        => 10,
				'default'     => esc_html__( 'Discover and shop our most popular plants, pots and accessories', 'brandy-blocks' ),
				'placeholder' => esc_html__( 'Enter banner subtitle here', 'brandy-blocks' ),
			)
		);

		$this->add_control(
			$key . '_button_text',
			array(
				'label'       => esc_html__( 'Banner Button text', 'brandy-blocks' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'Shop Now', 'brandy-blocks' ),
				'placeholder' => esc_html__( 'Enter banner button text here', 'brandy-blocks' ),
			)
		);
		$this->end_controls_tab();
	}
	protected function banner_controls_settings() {
		$this->start_controls_section(
			'banner_section',
			array(
				'label' => __( 'Banner Settings', 'brandy-blocks' ),
			)
		);
		$this->start_controls_tabs(
			'banner_tabs'
		);
		self::banner_controls_tab( 'primary_banner' );
		self::banner_controls_tab( 'secondary_banner' );
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function content_controls_settings() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Content Settings', 'brandy-blocks' ),
			)
		);
		$data = array(
			'showImage'     => __( 'Show Image', 'brandy-blocks' ),
			'showCategory'  => __( 'Show Category', 'brandy-blocks' ),
			'showTitle'     => __( 'Show Title', 'brandy-blocks' ),
			'showRating'    => __( 'Show Rating', 'brandy-blocks' ),
			'showPrice'     => __( 'Show Price', 'brandy-blocks' ),
			'showAddToCart' => __( 'Add To Cart button', 'brandy-blocks' ),
		);
		foreach ( $data as $param => $value ) {
			$this->add_control(
				$param,
				array(
					'label'        => esc_html( $value ),
					'type'         => \Elementor\Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Show', 'brandy-blocks' ),
					'label_off'    => esc_html__( 'Hide', 'brandy-blocks' ),
					'return_value' => 'yes',
					'default'      => 'yes',
				)
			);
		}

		$this->end_controls_section();
	}

	protected function categories_controls_settings() {
		$this->start_controls_section(
			'categories_section',
			array(
				'label' => __( 'Product Categories Settings', 'brandy-blocks' ),
			)
		);
		$this->add_control(
			'heading_options',
			array(
				'label'     => esc_html__( 'Filter by Product Category', 'brandy-blocks' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$categories  = get_terms( 'product_cat', array( 'hide_empty' => true ) );
		$cat_options = array();

		foreach ( $categories as $category ) {
			$cat_options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'selectedCategories',
			array(
				'label'       => esc_html__( 'Select Categories', 'brandy-blocks' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $cat_options,

			)
		);

		$this->end_controls_section();
	}

	protected function _register_controls() {
		self::banner_controls_settings();
		self::content_controls_settings();
		self::categories_controls_settings();
	}

	protected function render_banner( $key = 'primary' ) {
		$imageURL = isset( $this->settings[ $key . '_banner_image' ]['url'] ) && ! empty( $this->settings[ $key . '_banner_image' ]['url'] ) ? $this->settings[ $key . '_banner_image' ]['url'] : '';
		$title    = ! empty( $this->settings[ $key . '_banner_title' ] ) ? $this->settings[ $key . '_banner_title' ] : '';
		$subTitle = ! empty( $this->settings[ $key . '_banner_subtitle' ] ) ? $this->settings[ $key . '_banner_subtitle' ] : '';
		$button   = ! empty( $this->settings[ $key . '_banner_button_text' ] ) ? $this->settings[ $key . '_banner_button_text' ] : '';

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
			esc_html( $button )
		);
	}

	public function products_by_categories() {
		$category_ids = isset( $this->settings['selectedCategories'] ) ? $this->settings['selectedCategories'] : array();

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

	protected function render() {
		$this->settings = $this->get_settings_for_display();
		printf(
			'<div class="brandy-block-products-with-banners %s">%s%s%s</div>',
			is_brandy_exists() ? 'brandy-core-styles' : '',
			self::render_banner( 'primary' ),
			self::render_list( $this->products_by_categories() ),
			self::render_banner( 'secondary' ),
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

	public function render_product( $product ) {

		$product_controller = new ProductController( $product );
		if ( function_exists( 'brandy_loop_product_item' ) ) {
			ob_start();
			\brandy_loop_product_item(
				$product,
				array(
					'show_title'      => $this->parse_bool( $this->settings['showTitle'] ),
					'show_image'      => $this->parse_bool( $this->settings['showImage'] ),
					'show_button'     => $this->parse_bool( $this->settings['showAddToCart'] ),
					'show_category'   => $this->parse_bool( $this->settings['showCategory'] ),
					'show_rating'     => $this->parse_bool( $this->settings['showRating'] ),
					'show_price'      => $this->parse_bool( $this->settings['showPrice'] ),
					'show_sale_flash' => $this->parse_bool( $this->settings['showImage'] ),
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
				$this->parse_bool( $this->settings['showImage'] ) ? $product_controller->get_template_sale_flash() : '',
				$this->parse_bool( $this->settings['showImage'] ) ? $product_controller->get_template_image() : '',
				$this->parse_bool( $this->settings['showCategory'] ) ? $product_controller->get_template_category() : '',
				$this->parse_bool( $this->settings['showTitle'] ) ? $product_controller->get_template_title() : '',
				$this->parse_bool( $this->settings['showRating'] ) ? $product_controller->get_template_rating() : '',
				$this->parse_bool( $this->settings['showPrice'] ) ? $product_controller->get_template_pricing() : '',
				$this->parse_bool( $this->settings['showAddToCart'] ) ? $product_controller->get_template_button() : ''
			);
		}

		return $content;
	}
}
