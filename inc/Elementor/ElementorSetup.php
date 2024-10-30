<?php

namespace BrandyBlocks\Elementor;

use BrandyBlocks\Elementor\Elements\TestElement;
use BrandyBlocks\Elementor\Elements\FeatureProductElement;
use BrandyBlocks\Elementor\Elements\ProductsWithBannersElement;

use BrandyBlocks\Elementor\Elements\RelativeBlogsElement;
use BrandyBlocks\Traits\SingletonTrait;

class ElementorSetup {
	use SingletonTrait;

	protected function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_brandy_widget_categories' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_elements' ) );

	}

	public function add_brandy_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'brandy-blocks',
			array(
				'title' => __( 'Brandy', 'text-domain' ),
				'icon'  => 'fa fa-brands fa-bandcam',
			)
		);
	}

	public function register_elements( $widgets_manager ) {
		$widgets_manager->register( new ProductsWithBannersElement() );
	}
}
