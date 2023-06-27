(function ( $ ) {
	'use strict';

	$( function () {

		/**
		 * Switch transparent checkout options display basead in payment type.
		 *
		 * @param {String} method
		 */
		function pagSeguroSwitchTCOptions( method ) {
			var fields  = $( '#woocommerce_pagseguro_tc_credit' ).closest( '.form-table' ),
				heading = fields.prev( 'h3' );

			if ('redirect' === method) {
				$( '#woocommerce_pagseguro_redirect_max_age' ).closest( 'tr' ).show();
			} else {
				$( '#woocommerce_pagseguro_redirect_max_age' ).closest( 'tr' ).hide();
			}

			if ( 'transparent' === method ) {
				fields.show();
				heading.show();
			} else {
				fields.hide();
				heading.hide();
			}
		}

		/**
		 * Switch banking ticket message display.
		 *
		 * @param {String} checked
		 */
		function pagSeguroSwitchOptions( checked ) {
			var fields = $( '#woocommerce_pagseguro_tc_ticket_message' ).closest( 'tr' );

			if ( checked ) {
				fields.show();
			} else {
				fields.hide();
			}
		}

		/**
		 * Awitch user data for sandbox and production.
		 *
		 * @param {String} checked
		 */
		function pagSeguroSwitchUserData( checked ) {
			var email = $( '#woocommerce_pagseguro_email' ).closest( 'tr' ),
				pubKey = $( '#woocommerce_pagseguro_public_key' ).closest( 'tr' ),
				sandboxEmail = $( '#woocommerce_pagseguro_sandbox_email' ).closest( 'tr' ),
				sandboxPubKey = $( '#woocommerce_pagseguro_sandbox_public_key' ).closest( 'tr' );

			if ( checked ) {
				email.hide();
				pubKey.hide();
				sandboxEmail.show();
				sandboxPubKey.show();
			} else {
				email.show();
				pubKey.show();
				sandboxEmail.hide();
				sandboxPubKey.hide();
			}
		}

		pagSeguroSwitchTCOptions( $( '#woocommerce_pagseguro_method' ).val() );

		$( 'body' ).on( 'change', '#woocommerce_pagseguro_method', function () {
			pagSeguroSwitchTCOptions( $( this ).val() );
		}).change();

		pagSeguroSwitchOptions( $( '#woocommerce_pagseguro_tc_ticket' ).is( ':checked' ) );
		$( 'body' ).on( 'change', '#woocommerce_pagseguro_tc_ticket', function () {
			pagSeguroSwitchOptions( $( this ).is( ':checked' ) );
		});

		pagSeguroSwitchUserData( $( '#woocommerce_pagseguro_sandbox' ).is( ':checked' ) );
		$( 'body' ).on( 'change', '#woocommerce_pagseguro_sandbox', function () {
			pagSeguroSwitchUserData( $( this ).is( ':checked' ) );
		});
	});

}( jQuery ));
