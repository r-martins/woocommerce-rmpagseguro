<?php
/**
 * Plain email instructions.
 *
 * @author  Claudio_Sanches
 * @package WooCommerce_PagSeguro/Templates
 * @version 2.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

_e( 'Payment', 'woo-pagseguro-rm' );

echo "\n\n";

if ( 2 == $type ) {

	_e( 'Please use the link below to view your Banking Ticket, you can print and pay in your internet banking or in a lottery retailer:', 'woo-pagseguro-rm' );

	echo "\n";

	echo esc_url( $link );

	echo "\n";

	_e( 'After we receive the ticket payment confirmation, your order will be processed.', 'woo-pagseguro-rm' );

} elseif ( 3 == $type ) {

	_e( 'Please use the link below to make the payment in your bankline:', 'woo-pagseguro-rm' );

	echo "\n";

	echo esc_url( $link );

	echo "\n";

	_e( 'After we receive the confirmation from the bank, your order will be processed.', 'woo-pagseguro-rm' );

} else {

	echo sprintf( __( 'You just made the payment in %s using the %s.', 'woo-pagseguro-rm' ), $installments . 'x', $method );

	echo "\n";

	_e( 'As soon as the credit card operator confirm the payment, your order will be processed.', 'woo-pagseguro-rm' );

}

echo "\n\n****************************************************\n\n";
