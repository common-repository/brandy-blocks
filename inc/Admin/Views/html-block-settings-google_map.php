<?php

use BrandyBlocks\Services\GoogleMap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_POST['save'] ) && wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ?? '' ), 'brandy-blocks-settings' ) ) {
	$input_api_key = sanitize_text_field( $_POST['api_key'] ?? '' );
	GoogleMap::update_settings(
		array(
			'api_key' => $input_api_key,
		)
	);
}

$options = GoogleMap::get_settings();
$api_key = $options['api_key'] ?? '';
?>

<p><?php esc_html_e( 'Fine-tune these settings to optimize your Google Maps usage and ensure a seamless navigation experience.', 'brandy-blocks' ); ?></p>
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><?php echo esc_html__( 'API Key', 'brandy-blocks' ); ?></th>
			<td>
				<input name="api_key" type="password" id="api-key" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text">
				<p class="description" id="api-key-description">
					<?php
					// Translators: link.
					printf( esc_html__( 'Add your API key to make the Google Map block work. You can find your API key %1$shere%2$s', 'brandy-blocks' ), '<a href="https://console.cloud.google.com/project/_/google/maps-apis/overview?_gl=1*y42m9u*_ga*MjA2NjYxMDA1NC4xNzI0ODM4MTM2*_ga_NRWSTWS78N*MTcyNDkwMzEyOC40LjEuMTcyNDkwMzEyOC4wLjAuMA..">', '</a>' );
					?>
				</p>
			</td>
		</tr>
	</tbody>
</table>
