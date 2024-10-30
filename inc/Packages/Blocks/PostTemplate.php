<?php

namespace BrandyBlocks\Packages\Blocks;

use BrandyBlocks\Packages\Abstracts\AbstractBlock;
use BrandyBlocks\Traits\SingletonTrait;

class PostTemplate extends AbstractBlock {

	use SingletonTrait;

	public $name = 'PostTemplate';

	protected $attributes = array();

	protected function get_block_attributes() {
		return array(
			'render_callback'   => array( $this, 'render' ),
			'skip_inner_blocks' => true,
		);
	}

	public function block_core_post_template_uses_featured_image( $inner_blocks ) {
		foreach ( $inner_blocks as $block ) {
			if ( 'core/post-featured-image' === $block->name ) {
				return true;
			}
			if (
				'core/cover' === $block->name &&
				! empty( $block->attributes['useFeaturedImage'] )
			) {
				return true;
			}
			if ( $block->inner_blocks && $this->block_core_post_template_uses_featured_image( $block->inner_blocks ) ) {
				return true;
			}
		}

		return false;
	}

	public function render( $attributes, $content, $block ) {
		$page_key = isset( $block->context['queryId'] ) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
		$page     = empty( $_GET[ $page_key ] ) ? 1 : (int) $_GET[ $page_key ];

		$query_args = \build_query_vars_from_query_block( $block, $page );

		$query = new \WP_Query( $query_args );

		if ( ! $query->have_posts() ) {
			return __( 'No results found', 'brandy-blocks' );
		}

		if ( \block_core_post_template_uses_featured_image( $block->inner_blocks ) ) {
			update_post_thumbnail_cache( $query );
		}

		$classnames = '';
		if ( isset( $attributes['style']['elements']['link']['color']['text'] ) ) {
			$classnames .= ' has-link-color';
		}

		// Ensure backwards compatibility by flagging the number of columns via classname when using grid layout.

		if ( isset( $attributes['layout']['type'] ) && 'default' !== $attributes['layout']['type'] ) {

			if ( ! empty( $attributes['layout']['columnCount'] ) ) {
				$classnames .= ' ' . sanitize_title( 'columns-' . $attributes['layout']['columnCount'] );
			}
		}

		$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => trim( $classnames ) ) );

		$content = '';

		while ( $query->have_posts() ) {
			$query->the_post();

			// Get an instance of the current Post Template block.
			$block_instance = $block->parsed_block;

			// Set the block name to one that does not correspond to an existing registered block.
			// This ensures that for the inner instances of the Post Template block, we do not render any block supports.
			$block_instance['blockName'] = 'core/null';

			$post_id              = get_the_ID();
			$post_type            = get_post_type();
			$filter_block_context = static function ( $context ) use ( $post_id, $post_type ) {
				$context['postType'] = $post_type;
				$context['postId']   = $post_id;
				return $context;
			};

			// Use an early priority to so that other 'render_block_context' filters have access to the values.
			add_filter( 'render_block_context', $filter_block_context, 1 );
			// Render the inner blocks of the Post Template block with `dynamic` set to `false` to prevent calling
			// `render_callback` and ensure that no wrapper markup is included.
			$block_content = ( new \WP_Block( $block_instance ) )->render( array( 'dynamic' => false ) );
			remove_filter( 'render_block_context', $filter_block_context, 1 );

			// Wrap the render inner blocks in a `li` element with the appropriate post classes.
			$post_classes = implode( ' ', get_post_class( 'wp-block-post' ) );

			$inner_block_directives = '';

			$content .= '<li' . $inner_block_directives . ' class="' . esc_attr( $post_classes ) . '">' . $block_content . '</li>';
		}

		/*
		* Use this function to restore the context of the template tags
		* from a secondary query loop back to the main query loop.
		* Since we use two custom loops, it's safest to always restore.
		*/
		wp_reset_postdata();

		return sprintf(
			'<ul %1$s>%2$s</ul>',
			$wrapper_attributes,
			$content
		);
	}

}
