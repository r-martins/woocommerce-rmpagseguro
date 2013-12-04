<?php
/**
 * WC PagSeguro Helpers Class.
 *
 * PagSeguro payment helpers.
 *
 * @since 2.2.0
 */
class WC_PagSeguro_Helpers {

	/**
	 * Payment type name.
	 *
	 * @param  int    $value Type number.
	 *
	 * @return string        Type name.
	 */
	public function payment_type( $value ) {
		$types = array(
			1 => __( 'Credit Card', 'wcpagseguro' ),
			2 => __( 'Billet', 'wcpagseguro' ),
			3 => __( 'Online Debit', 'wcpagseguro' ),
			4 => __( 'PagSeguro credit', 'wcpagseguro' ),
			5 => __( 'Oi Paggo', 'wcpagseguro' ),
			7 => __( 'Account deposit', 'wcpagseguro' )
		);

		if ( isset( $types[ $value ] ) ) {
			return $types[ $value ];
		} else {
			return __( 'Unknown', 'wcpagseguro' );
		}
	}

	/**
	 * Payment method name.
	 *
	 * @param  int    $value Method number.
	 *
	 * @return string        Method name.
	 */
	public function payment_method( $value ) {
		$credit_card = __( 'Credit Card', 'wcpagseguro' );
		$billet = __( 'Billet', 'wcpagseguro' );
		$online_debit = __( 'Online Debit', 'wcpagseguro' );

		$methods = array(
			101 => $credit_card . ' ' . 'Visa',
			102 => $credit_card . ' ' . 'MasterCard',
			103 => $credit_card . ' ' . 'American Express',
			104 => $credit_card . ' ' . 'Diners',
			105 => $credit_card . ' ' . 'Hipercard',
			106 => $credit_card . ' ' . 'Aura',
			107 => $credit_card . ' ' . 'Elo',
			108 => $credit_card . ' ' . 'PLENOCard',
			109 => $credit_card . ' ' . 'PersonalCard',
			110 => $credit_card . ' ' . 'JCB',
			111 => $credit_card . ' ' . 'Discover',
			112 => $credit_card . ' ' . 'BrasilCard',
			113 => $credit_card . ' ' . 'FORTBRASIL',
			114 => $credit_card . ' ' . 'CARDBAN',
			115 => $credit_card . ' ' . 'VALECARD',
			116 => $credit_card . ' ' . 'Cabal',
			117 => $credit_card . ' ' . 'Mais!',
			118 => $credit_card . ' ' . 'Avista',
			119 => $credit_card . ' ' . 'GRANDCARD',
			201 => $billet . ' ' . 'Bradesco',
			202 => $billet . ' ' . 'Santander',
			301 => $online_debit . ' ' . 'Bradesco',
			302 => $online_debit . ' ' . 'Itaú',
			303 => $online_debit . ' ' . 'Unibanco',
			304 => $online_debit . ' ' . 'Banco do Brasil',
			305 => $online_debit . ' ' . 'Real',
			306 => $online_debit . ' ' . 'Banrisul',
			307 => $online_debit . ' ' . 'HSBC',
			401 => __( 'PagSeguro credit', 'wcpagseguro' ),
			501 => __( 'Oi Paggo', 'wcpagseguro' ),
			701 => __( 'Account deposit', 'wcpagseguro' )
		);

		if ( isset( $methods[ $value ] ) ) {
			return $methods[ $value ];
		} else {
			return __( 'Unknown', 'wcpagseguro' );
		}
	}

	/**
	 * Error messages.
	 *
	 * @param  int    $code Error code.
	 *
	 * @return string       Error message.
	 */
	public function error_message( $code ) {
		switch ( $code ) {
			case 11013:
			case 11014:
				return __( 'Please enter a valid phone number with DDD. Example: (11) 5555-5555.', 'wcpagseguro' );
				break;
			case 11017:
				return __( 'Please enter a valid zip code number.', 'wcpagseguro' );
				break;
			case 11164:
				return __( 'Please enter a valid CPF number.', 'wcpagseguro' );
				break;

			default:
				return __( 'An error has occurred while processing your payment, please review your data and try again. Or contact us for assistance.', 'wcpagseguro' );
				break;
		}
	}
}
