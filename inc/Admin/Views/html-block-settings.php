<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_exists        = isset( $settings_tabs[ $current_tab ] ) || has_action( 'brandy_blocks_settings_' . $current_tab );
$current_tab_label = isset( $settings_tabs[ $current_tab ] ) ? $settings_tabs[ $current_tab ] : '';

if ( ! $tab_exists ) {
	wp_safe_redirect( admin_url( 'admin.php?page=brandy-blocks-settings' ) );
	exit;
}
?>
<div class="wrap">
	<h1><?php esc_html_e( 'Brandy Blocks settings', 'brandy-blocks' ); ?></h1>
	<form method="<?php echo esc_attr( apply_filters( 'brandy_blocks_settings_form_method_tab_' . $current_tab, 'post' ) ); ?>" id="mainform" action="" enctype="multipart/form-data">
		<nav class="nav-tab-wrapper bb-nav-tab-wrapper">
		<?php
		foreach ( $settings_tabs as $slug => $label ) {
			echo '<a href="' . esc_html( admin_url( 'admin.php?page=brandy-blocks-settings&tab=' . esc_attr( $slug ) ) ) . '" class="nav-tab ' . ( $current_tab === $slug ? 'nav-tab-active' : '' ) . '">' . esc_html( $label ) . '</a>';
		}
		?>
		</nav>
		<div class="bb-settings-wrapper">
		<?php
		do_action( 'brandy_blocks_settings_' . $current_tab )
		?>
		</div>
		<p class="submit">
			<?php if ( empty( $GLOBALS['hide_save_button'] ) ) : ?>
				<button name="save" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save changes', 'brandy-blocks' ); ?>"><?php esc_html_e( 'Save changes', 'brandy-blocks' ); ?></button>
			<?php endif; ?>
			<?php wp_nonce_field( 'brandy-blocks-settings' ); ?>
		</p>
	</form>
</div>
