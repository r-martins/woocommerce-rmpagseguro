<?php
/**
 * Admin View: Notice - Currency not supported.
 *
 * @package WooCommerce_PagSeguro/Admin/Notices
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="error inline">
	<p><strong><?php _e( 'PagSeguro Disabled', 'woo-pagseguro-rm' ); ?></strong>: <?php printf( __( 'Currency <code>%s</code> is not supported. Works only with Brazilian Real.', 'woo-pagseguro-rm' ), get_woocommerce_currency() ); ?>
	</p>
</div>
