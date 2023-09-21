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

			if ('redirect' === method || $( '#woocommerce_pagseguro_tc_redirect' ).is( ':checked' )) {
				$( '#woocommerce_pagseguro_redirect_max_age' ).closest( 'tr' ).show();
				$( '#woocommerce_pagseguro_redirect_max_uses' ).closest( 'tr' ).show();
			} else {
				$( '#woocommerce_pagseguro_redirect_max_age' ).closest( 'tr' ).hide();
				$( '#woocommerce_pagseguro_redirect_max_uses' ).closest( 'tr' ).hide();
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
		 * Switch user data for sandbox and production.
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

		// region related to check/unchecking woocommerce_pagseguro_tc_redirect and showing others title
		function pagseguroSwitchOtherTitle(){
			//Others title
			if ( $('#woocommerce_pagseguro_tc_redirect').is(':checked') ) {
				jQuery('#woocommerce_pagseguro_tc_redirect_title').closest('tr') .show();
			}else{
				jQuery('#woocommerce_pagseguro_tc_redirect_title').closest('tr') .hide();
			}
		}

		pagseguroSwitchOtherTitle();
		$( 'body' ).on( 'change', '#woocommerce_pagseguro_tc_redirect', function () {
			pagseguroSwitchOtherTitle();
		});
		// endregion

		pagSeguroSwitchTCOptions( $( '#woocommerce_pagseguro_method' ).val() );

		$( 'body' ).on( 'change', '#woocommerce_pagseguro_method', function () {
			pagSeguroSwitchTCOptions( $( this ).val() );
		}).change();

		$( 'body' ).on( 'change', '#woocommerce_pagseguro_tc_redirect', function () {
			pagSeguroSwitchTCOptions( $( '#woocommerce_pagseguro_method' ).val() );
		}).change();


		pagSeguroSwitchOptions( $( '#woocommerce_pagseguro_tc_ticket' ).is( ':checked' ) );
		$( 'body' ).on( 'change', '#woocommerce_pagseguro_tc_ticket', function () {
			pagSeguroSwitchOptions( $( this ).is( ':checked' ) );
		});

		pagSeguroSwitchUserData( $( '#woocommerce_pagseguro_sandbox' ).is( ':checked' ) );
		$( 'body' ).on( 'change', '#woocommerce_pagseguro_sandbox', function () {
			pagSeguroSwitchUserData( $( this ).is( ':checked' ) );
		});

		/*region valor minimo da parcela sem juros*/
		function hideOrShowMinTotalOptions() {
			return function () {
				$('#woocommerce_pagseguro_cc_installments_options_min_total').closest('tr').hide();

				if ($(this).val() === 'min_total')
					$('#woocommerce_pagseguro_cc_installments_options_min_total').closest('tr').show();
			};
		}

		$('#woocommerce_pagseguro_cc_installment_options').change(hideOrShowMinTotalOptions());
		hideOrShowMinTotalOptions().call($('#woocommerce_pagseguro_cc_installment_options'));
		/*endregion*/

		//region redirect methods hide or show
		function hideOrShowRedirectOptions() {
			return function () {
				$('#woocommerce_pagseguro_redirect_methods').closest('tr').hide();
				if (($('#woocommerce_pagseguro_tc_redirect').is(':visible') && $('#woocommerce_pagseguro_tc_redirect').is(':checked'))
					|| $('#woocommerce_pagseguro_method').val() == 'redirect')
					$('#woocommerce_pagseguro_redirect_methods').closest('tr').show();
			};
		}

		$('#woocommerce_pagseguro_method').change(hideOrShowRedirectOptions());
		$('#woocommerce_pagseguro_tc_redirect').change(hideOrShowRedirectOptions());
		hideOrShowRedirectOptions().call();
		//endregion



		//max installments no interest
		function hideOrShowFixedOptions() {
			return function () {
				$('#woocommerce_pagseguro_cc_installment_options_fixed').closest('tr').hide();

				if ($(this).val() === 'fixed')
					$('#woocommerce_pagseguro_cc_installment_options_fixed').closest('tr').show();
			};
		}

		$('#woocommerce_pagseguro_cc_installment_options').change(hideOrShowFixedOptions());
		hideOrShowFixedOptions().call($('#woocommerce_pagseguro_cc_installment_options'));
		//endregion
	});

}( jQuery ));
