<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;

class Form extends AbstractBlock {

	use SingletonTrait;

	public $name = 'Form';

	protected function get_block_attributes() {
		return array(
			'render_callback' => array( $this, 'render' ),
		);
	}

	public function init_hooks() {
		add_action(
			'woocommerce_login_failed',
			function() {
				$_GET['wc_login_failed'] = true;
			}
		);

		add_action( 'brandy_blocks_before_form_content', array( $this, 'before_form_content' ) );
		add_action( 'brandy_blocks_after_form_content', array( $this, 'after_form_content' ) );
	}

	public function render( $attributes, $content, $block ) {

		$tags = new \WP_HTML_Tag_Processor( $content );

		if ( $tags->next_tag() ) {
			$style = $tags->get_attribute( 'style' );
			$class = $tags->get_attribute( 'class' );
		}

		$html = '';
		ob_start(); ?>
		<form data-block-name="brandy/form" method="post" style="<?php echo esc_attr( $style ); ?>" class="<?php echo esc_attr( $class ); ?>">
			<?php $this->render_form( $attributes, $block ); ?>
		</form>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public function render_form( $attributes, $block ) {
		$mapped_views = array(
			'login'          => __DIR__ . '/Form/html-login-form.php',
			'reset_password' => __DIR__ . '/Form/html-reset-password-form.php',
			'custom'         => __DIR__ . '/Form/html-custom-form.php',
		);
		$path         = $mapped_views[ $attributes['action'] ?? '' ];
		if ( file_exists( $path ) ) {
			do_action( 'brandy_blocks_before_form', $attributes, $block );

			include $path;

			do_action( 'brandy_blocks_after_form', $attributes, $block );
		}

	}

	public static function render_inner_blocks( $block ) {
		foreach ( $block->inner_blocks as $inner_block ) {
			echo $inner_block->render(); //XSS: ignore
		}
	}

	public function before_form_content( $attributes ) {
	}

	public function after_form_content( $attributes ) {

		if ( ! empty( $attributes['successUrl'] ) ) {
			echo '<input type="hidden" name="redirect" value="' . esc_url( $attributes['successUrl'] ) . '" />';
		}

		if ( ! empty( $attributes['failedUrl'] ) ) {
			echo '<input type="hidden" name="fallbackRedirect" value="' . esc_url( $attributes['failedUrl'] ) . '" />';
		}

	}

	public static function render_message( $attributes, $type = 'success' ) {
		printf(
			'<div class="brandy-form-message brandy-form-%1$s-message">%2$s</div>',
			esc_attr( $type ),
			'success' === $type ? esc_html( $attributes['successMessage'] ) : esc_html( $attributes['failedMessage'] )
		);
	}
}
