<?php
/**
 * Admin help message.
 *
 * @package WooCommerce_PagSeguro/Admin/Settings
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( apply_filters( 'woocommerce_pagseguro_help_message', true ) ) : ?>
	<div class="updated inline woocommerce-message">
		<p><?php echo esc_html( sprintf( __( 'Help us keep the %s plugin free making a donation or rate %s on WordPress.org. Thank you in advance!', 'woo-pagseguro-rm' ), __( 'WooCommerce PagSeguro', 'woo-pagseguro-rm' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ) ); ?></p>
		<p><a href="http://claudiosmweb.com/doacoes/" target="_blank" class="button button-primary"><?php esc_html_e( 'Make a donation', 'woo-pagseguro-rm' ); ?></a> <a href="https://wordpress.org/support/view/plugin-reviews/woocommerce-pagseguro?filter=5#postform" target="_blank" class="button button-secondary"><?php esc_html_e( 'Make a review', 'woo-pagseguro-rm' ); ?></a></p>
	</div>
<?php endif;
