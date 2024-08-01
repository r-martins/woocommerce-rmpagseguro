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
	<div class="woo-connect">
		<p><a href="https://pagseguro.ricardomartins.net.br/woocommerce.html?utm_source=wooadmin&utm_media=banner&utm_campaign=bannerlegado" target="_blank"><img src="<?php echo plugins_url( 'assets/images/upgrade-to-connect.gif',  WC_PAGSEGURO_PLUGIN_FILE );?>" width="540" height="100" border="0" title="Saiba mais sobre a nova geração de nosso plugin PagBank."/></a></p>
	</div>
<?php endif;
