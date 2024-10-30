<?php

namespace BrandyBlocks;

use BrandyBlocks\Traits\SingletonTrait;

class Wishlist {

	use SingletonTrait;

	const META_NAME = 'brandy_wishlist';

	protected function __construct() {
		$this->init_hooks();
	}

	public function init_hooks() {

		// if ( is_admin() ) {
		// 	return;
		// }

		add_action( 'wp_ajax_brandy_add_to_wishlist', array( $this, 'ajax_add_to_wishlist' ) );
		add_action( 'wp_ajax_brandy_remove_wishlist_item', array( $this, 'ajax_remove_wishlist_item' ) );
		add_action( 'wp_ajax_nopriv_brandy_add_to_wishlist', array( $this, 'ajax_add_to_wishlist' ) );
		add_action( 'wp_ajax_nopriv_brandy_remove_wishlist_item', array( $this, 'ajax_remove_wishlist_item' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_filter( 'brandy_wishlist_items_count', array( $this, 'count' ) );

		add_shortcode( 'brandy_wishlist_items', array( $this, 'wishlist_shortcode' ) );

		add_action(
			'brandy_wishlist_items',
			function() {
				echo self::wishlist_shortcode(); //XSS:ok.
			}
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_script( 'brandy-wishlist-script', BRANDY_BLOCKS_PLUGIN_URL . '/assets/js/wishlist.js', array( 'jquery' ), BRANDY_BLOCKS_SCRIPT_VERSION, true );
		wp_localize_script(
			'brandy-wishlist-script',
			'brandyWishlist',
			array(
				'ajax' => array(
					'path'   => admin_url( 'admin-ajax.php' ),
					'nonces' => array(
						'update_cart'          => wp_create_nonce( 'brandy_update_cart' ),
						'send_subscribe_mail'  => wp_create_nonce( 'send_subscribe_mail' ),
						'remove_wishlist_item' => wp_create_nonce( 'brandy_remove_wishlist_item' ),
						'add_wishlist_item'    => wp_create_nonce( 'brandy_add_to_wishlist' ),
					),
				),
			)
		);
		wp_enqueue_style( 'brandy-wishlist-style', BRANDY_BLOCKS_PLUGIN_URL . '/assets/css/wishlist.css', array(), BRANDY_BLOCKS_SCRIPT_VERSION );
	}

	public static function get_user_wishlist_items() {

		if ( ! is_wc_installed() ) {
			return array();
		}

		$list = self::get_wishlist_items();

		$new_list = array_filter(
			$list,
			function( $id ) {
				return ! empty( \wc_get_product( $id ) );
			}
		);

		if ( ! empty( array_diff( $list, $new_list ) ) ) {
			self::update_wishlist_items( $new_list );
		}

		return $new_list;
	}
	public function ajax_add_to_wishlist() {
		try {
			$nonce = '';
			if ( isset( $_GET['nonce'] ) ) {
				$nonce = sanitize_text_field( $_GET['nonce'] );
			} elseif ( isset( $_POST['nonce'] ) ) {
				$nonce = sanitize_text_field( $_POST['nonce'] );
			}
			if ( ! wp_verify_nonce( $nonce, 'brandy_add_to_wishlist' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Verify nonce failed', 'brandy' ),
					)
				);
			}

			if ( ! is_wc_installed() ) {
				throw new \Error( __( 'WooCommerce uninstalled', 'brandy' ) );
			}

			$product_id = isset( $_GET['product_id'] ) ? sanitize_text_field( $_GET['product_id'] ) : '';

			$product = \wc_get_product( $product_id );

			if ( empty( $product ) ) {
				throw __( 'Product not found', 'brandy' );
			}

			$current_wishlist = self::get_wishlist_items();

			if ( ! in_array( $product_id, $current_wishlist ) ) {
				$current_wishlist[] = $product_id;
			}

			self::update_wishlist_items( $current_wishlist );

			wp_send_json_success(
				array(
					'product_id' => $product_id,
					'message'    => __( 'Success', 'brandy' ), //PHPCS:ignore
					'fragments'  => array(
						'count'     => self::count(),
						'shortcode' => self::wishlist_shortcode(),
					),
				)
			);
		} catch ( \Error $err ) {
			wp_send_json_error(
				array(
					'message' => $err->getMessage(),
				)
			);
		}
	}

	public function ajax_remove_wishlist_item() {
		try {

			$nonce = '';
			if ( isset( $_GET['nonce'] ) ) {
				$nonce = sanitize_text_field( $_GET['nonce'] );
			} elseif ( isset( $_POST['nonce'] ) ) {
				$nonce = sanitize_text_field( $_POST['nonce'] );
			}
			if ( ! wp_verify_nonce( $nonce, 'brandy_remove_wishlist_item' ) ) {
				wp_send_json_error(
					array(
						'message' => __( 'Verify nonce failed', 'brandy' ),
					)
				);
			}

			if ( ! is_wc_installed() ) {
				throw new \Error( __( 'WooCommerce uninstalled', 'brandy' ) );
			}

			$product_id = isset( $_GET['product_id'] ) ? sanitize_text_field( $_GET['product_id'] ) : '';

			$current_wishlist = self::get_wishlist_items();

			$pos = array_search( $product_id, $current_wishlist );

			if ( false !== $pos ) {
				array_splice( $current_wishlist, $pos, 1 );
			}

			self::update_wishlist_items( $current_wishlist );

			wp_send_json_success(
				array(
					'product_id' => $product_id,
					'message'    => __( 'Removed', 'brandy' ),
					'fragments'  => array(
						'count'     => self::count(),
						'shortcode' => self::wishlist_shortcode(),
					),
				)
			);
		} catch ( \Error $err ) {
			wp_send_json_error(
				array(
					'message' => $err->getMessage(),
				)
			);
		}
	}

	public static function wishlist_shortcode() {
		$html = '';
		ob_start();
		$args = array(
			'wishlist_list' => self::get_user_wishlist_items(),

		);
		include BRANDY_BLOCKS_PLUGIN_PATH . '/templates/wishlist-shortcode.php';
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	public static function count() {
		return count( self::get_user_wishlist_items() );
	}

	public static function get_wishlist_items() {

		if ( ! headers_sent() && ! session_id() ) {
			session_start();
		}

		$session_items = $_SESSION['brandy_wishlist_items'] ?? array();
		$user_items    = array();

		if ( is_user_logged_in() ) {
			$user_items = get_user_meta( get_current_user_id(), self::META_NAME, true );
			$user_items = empty( $user_items ) ? array() : $user_items;
		}

		$items = array_unique( array_merge( $session_items, $user_items ) );

		return $items;
	}

	public static function update_wishlist_items( $new_items = null ) {

		if ( is_null( $new_items ) ) {
			return;
		}

		if ( ! headers_sent() && ! session_id() ) {
			session_start();
		}

		if ( is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), self::META_NAME, $new_items );
		}

		$_SESSION['brandy_wishlist_items'] = $new_items;

	}

}
