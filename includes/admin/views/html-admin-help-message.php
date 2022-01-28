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
		<p><?php echo esc_html( sprintf( __( 'By using our module you are helping many NGO\'s and saving on PagSeguro taxes. Help us to make it even better and leave a %s review!', 'woo-pagseguro-rm' ), '&#9733;&#9733;&#9733;&#9733;&#9733;' ) ); ?></p>
		<p>
            <a href="https://wordpress.org/support/plugin/woo-pagseguro-rm/reviews/?filter=5#postform" target="_blank" class="button button-primary"><?php esc_html_e( 'Leave a review', 'woo-pagseguro-rm' ); ?></a>
            <a href="https://pagseguro.ricardomartins.net.br/compare.html" target="_blank" class="button button-secondary"><?php esc_html_e( 'How much I am saving?', 'woo-pagseguro-rm' ); ?></a>
            <a href="https://pagsegurotransparente.zendesk.com/hc/pt-br/articles/208158763" target="_blank" class="button button-secondary"><?php esc_html_e( 'Who am I helping?', 'woo-pagseguro-rm' ); ?></a>
            <a href="https://pagsegurotransparente.zendesk.com/hc/pt-br/" target="_blank" class="button button-secondary"><?php esc_html_e( 'I need help', 'woo-pagseguro-rm' ); ?></a>
        </p>
	</div>
<?php endif;
