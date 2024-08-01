<?php
/**
 * WooCommerce PagSeguro Gateway class
 *
 * @package WooCommerce_PagSeguro/Classes/Gateway
 * @version 2.13.0
 * Arquivo modificado por Ricardo Martins em 4 de Setembro de 2019 (GPLv2)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce PagSeguro gateway.
 */
class WC_PagSeguro_Gateway extends WC_Payment_Gateway {
	public $email;
	public $public_key;
	public $sandbox_email;
	public $sandbox_public_key;
	public $method;
	public $tc_credit;
	public $tc_transfer;
	public $tc_ticket;
	public $tc_ticket_message;
	public $tc_redirect;
	public $tc_redirect_title;
	public $invoice_prefix;
	public $hides_when_is_outside_brazil;
	public $sandbox;
	public $debug;
	public $stcmirror;
	public $send_only_total;
	public $log;
	public $api;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'pagseguro';
		$this->icon               = apply_filters( 'woocommerce_pagseguro_icon', plugins_url( 'assets/images/pagseguro.svg', plugin_dir_path( __FILE__ ) ) );
		$this->method_title       = __( 'PagSeguro', 'woo-pagseguro-rm' );
		$this->method_description = __( 'Accept payments by credit card, bank debit or banking ticket using the PagSeguro.', 'woo-pagseguro-rm' );
		$this->order_button_text  = __( 'Proceed to payment', 'woo-pagseguro-rm' );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->title                        = $this->get_option( 'title' );
		$this->description                  = $this->get_option( 'description' );
		$this->email                        = $this->get_option( 'email' );
		$this->public_key                   = $this->get_option( 'public_key' ); //added by Ricardo Martins
		$this->sandbox_email                = $this->get_option( 'sandbox_email' );
		$this->sandbox_public_key           = $this->get_option( 'sandbox_public_key' ); //added by Ricardo Martins
		$this->method                       = $this->get_option( 'method', 'direct' );
		$this->tc_credit                    = $this->get_option( 'tc_credit', 'yes' );
		$this->tc_transfer                  = $this->get_option( 'tc_transfer', 'yes' );
		$this->tc_ticket                    = $this->get_option( 'tc_ticket', 'yes' );
		$this->tc_ticket_message            = $this->get_option( 'tc_ticket_message', 'yes' );
		$this->tc_redirect                  = $this->get_option( 'tc_redirect', 'yes' );
		$this->tc_redirect_title            = $this->get_option( 'tc_redirect_title', 'Others' );
		$this->send_only_total              = $this->get_option( 'send_only_total', 'no' );
		$this->invoice_prefix   			= $this->get_option( 'invoice_prefix', 'WC-' );
		$this->hides_when_is_outside_brazil = $this->get_option( 'hides_when_is_outside_brazil', 'no' );
		$this->sandbox                      = $this->get_option( 'sandbox', 'no' );
		$this->debug                        = $this->get_option( 'debug' );
		$this->stcmirror                    = $this->get_option( 'stcmirror' );

		// Active logs.
		if ( 'yes' === $this->debug ) {
			if ( function_exists( 'wc_get_logger' ) ) {
				$this->log = wc_get_logger();
			} else {
				$this->log = new WC_Logger();
			}
		}

		// Set the API.
		$this->api = new WC_PagSeguro_API( $this );

		// Main actions.
		add_action( 'woocommerce_api_wc_pagseguro_gateway', array( $this, 'ipn_handler' ) );
		add_action( 'woocommerce_api_wc_pagseguro_info', array( $this, 'wc_test' ) );
		add_action( 'valid_pagseguro_ipn_request', array( $this, 'update_order_status' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );

		// Transparent checkout actions.
		if ( 'transparent' === $this->method ) {
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
			add_action( 'woocommerce_email_after_order_table', array( $this, 'email_instructions' ), 10, 3 );
			add_action( 'wp_enqueue_scripts', array( $this, 'checkout_scripts' ) );
		}
	}

	/**
	 * Returns a bool that indicates if currency is amongst the supported ones.
	 *
	 * @return bool
	 */
	public function using_supported_currency() {
		return 'BRL' === get_woocommerce_currency();
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function is_sanbox_on() {
		return (bool) ('yes' === $this->sandbox);
	}

	/**
	 * Get email.
	 *
	 * @return string
	 */
	public function get_email() {
		return $this->is_sanbox_on() ? $this->sandbox_email : $this->email;
	}

	/**
	 * Get public key.
	 *
	 * @return string
	 */
	public function get_public_key() {
		return $this->is_sanbox_on() ? $this->sandbox_public_key : $this->public_key;
	}

	/**
	 * Returns a value indicating the the Gateway is available or not. It's called
	 * automatically by WooCommerce before allowing customers to use the gateway
	 * for payment.
	 *
	 * @return bool
	 */
	public function is_available() {
		// Test if is valid for use.
		$available = 'yes' === $this->get_option( 'enabled' ) && '' !== $this->get_email()  && $this->using_supported_currency();

		if ( 'transparent' === $this->method && ! class_exists( 'Extra_Checkout_Fields_For_Brazil' ) ) {
			$available = false;
		}

		return $available;
	}

	/**
	 * Has fields.
	 *
	 * @return bool
	 */
	public function has_fields() {
		return 'transparent' === $this->method;
	}

	/**
	 * Checkout scripts.
	 */
	public function checkout_scripts() {
		if ( is_checkout() && $this->is_available() ) {
			if ( ! get_query_var( 'order-received' ) ) {
				$session_id = $this->api->get_session_id();
				$suffix     = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				wp_enqueue_style( 'pagseguro-checkout', plugins_url( 'assets/css/frontend/transparent-checkout' . $suffix . '.css', plugin_dir_path( __FILE__ ) ), array(), WC_PAGSEGURO_VERSION );
				wp_enqueue_script( 'pagseguro-library', $this->api->get_direct_payment_url(), array(), WC_PAGSEGURO_VERSION, true );
				wp_enqueue_script( 'pagseguro-checkout', plugins_url( 'assets/js/frontend/transparent-checkout' . $suffix . '.js', plugin_dir_path( __FILE__ ) ), array( 'jquery', 'pagseguro-library', 'woocommerce-extra-checkout-fields-for-brazil-front' ), WC_PAGSEGURO_VERSION, true );

				wp_localize_script(
					'pagseguro-checkout',
					'wc_pagseguro_params',
					array(
						'session_id'         => $session_id,
						'interest_free'      => __( 'interest free', 'woo-pagseguro-rm' ),
						'invalid_card'       => __( 'Invalid credit card number.', 'woo-pagseguro-rm' ),
						'invalid_expiry'     => __( 'Invalid expiry date, please use the MM / YY date format.', 'woo-pagseguro-rm' ),
						'expired_date'       => __( 'Please check the expiry date and use a valid format as MM / YY.', 'woo-pagseguro-rm' ),
						'general_error'      => __( 'Unable to process the data from your credit card on the PagSeguro, please try again or contact us for assistance.', 'woo-pagseguro-rm' ),
						'empty_installments' => __( 'Select a number of installments.', 'woo-pagseguro-rm' ),
						'no_interest_installments_min_value' => $this->get_numeric_option( 'no_interest_installments_min_value' ),
						'cc_installment_options' => $this->get_option( 'cc_installment_options' ),
						'cc_installment_options_fixed' => $this->get_numeric_option('cc_installment_options_fixed'),
						'cc_installments_options_min_total' => $this->get_option('cc_installments_options_min_total')
					)
				);
			}
		}
	}

	/**
	 * Retrieves an option numeric value, parsing the configured value
	 *
	 * @return float|null
	 */
	public function get_numeric_option( $option_name )
	{
		$value = $this->get_option( $option_name );

		if ( $value == '' ) {
			return null;
		}

		// it means that the comma is the decimal separator
		if ( strpos( $value, ',' ) !== false ) {
			return (float) str_replace( ',', '.', str_replace( '.', '', $value ) );
		}

		return (float) $value;
	}

	/**
	 * Get log.
	 *
	 * @return string
	 */
	protected function get_log_view() {
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.2', '>=' ) ) {
			return '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . __( 'System Status &gt; Logs', 'woo-pagseguro-rm' ) . '</a>';
		}

		return '<code>woocommerce/logs/' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.txt</code>';
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled'              => array(
				'title'   => __( 'Enable/Disable', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable PagSeguro', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'title'                => array(
				'title'       => __( 'Title', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-pagseguro-rm' ),
				'desc_tip'    => true,
				'default'     => __( 'PagSeguro', 'woo-pagseguro-rm' ),
			),
			'description'          => array(
				'title'       => __( 'Description', 'woo-pagseguro-rm' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-pagseguro-rm' ),
				'default'     => __( 'Pay via PagSeguro (via Credit card, balance, bank billet or PIX)', 'woo-pagseguro-rm' ),
			),
			'integration'          => array(
				'title'       => __( 'Integration', 'woo-pagseguro-rm' ),
				'type'        => 'title',
				'description' => '',
			),
			'method'               => array(
				'title'       => __( 'Integration method', 'woo-pagseguro-rm' ),
				'type'        => 'select',
				'description' => __( 'Choose how the customer will interact with the PagSeguro. Redirect (Client goes to PagSeguro page) or Lightbox (Inside your store)', 'woo-pagseguro-rm' ),
				'desc_tip'    => true,
				'default'     => 'direct',
				'class'       => 'wc-enhanced-select',
				'options'     => array(
					'redirect'    => __( 'Redirect', 'woo-pagseguro-rm' ),
					'lightbox'    => __( 'Lightbox', 'woo-pagseguro-rm' ),
					'transparent' => __( 'Transparent Checkout (recommended)', 'woo-pagseguro-rm' ),
				),
			),
			'redirect_methods' => array(
				'title' 		=> __( 'Redirect Methods', 'woo-pagseguro-rm' ),
				'type' 			=> 'multiselect',
				'description' 	=> __( 'Select the payment methods that will be displayed when customer goes to PagSeguro', 'woo-pagseguro-rm' ),
				'default' 		=> array( 'CREDIT_CARD', 'BOLETO', 'PIX'),
				'options' 		=> array(
					'CREDIT_CARD' 	=> __( 'Credit Card', 'woo-pagseguro-rm' ),
					'BOLETO' 		=> __( 'Boleto', 'woo-pagseguro-rm' ),
					'PIX' 			=> __( 'Pix', 'woo-pagseguro-rm' ),
				),
			),
			'redirect_max_age' => array(
				'title'   => __( 'Redirect Expiry (minutes)', 'woo-pagseguro-rm' ),
				'type'    => 'number',
				'default' => 120,
				'placeholder' => 120,
				'custom_attributes' => array(
					'min' => 30,
					'max' => 999999999,
				),
				'desc_tip' => true,
				'description'   => __( 'Defines how long the user can use the checkout link to pay for the order.', 'woo-pagseguro-rm' ),
			),
			'redirect_max_uses' => array(
				'title'   => __( 'Redirect Max Uses', 'woo-pagseguro-rm' ),
				'type'    => 'number',
				'default' => 3,
				'placeholder' => '',
				'custom_attributes' => array(
					'min' => 1,
					'max' => 999,
				),
				'desc_tip' => false,
				'description'   => __( 'Limits the number of times the user can use the same link to pay for the order.', 'woo-pagseguro-rm' ),
			),
			//modified by Ricardo Martins
			'sandbox'              => array(
				'title'       => __( 'PagSeguro Sandbox', 'woo-pagseguro-rm' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable PagSeguro Sandbox', 'woo-pagseguro-rm' ),
				'desc_tip'    => false,
				'default'     => 'no',
				'description' => __( '⚠️ The Sandbox environment is extremelly unstable and it could be offline when you test it.', 'woo-pagseguro-rm' ),
			),
			'stcmirror'              => array(
				'title'       => __( 'Use CDN mirror for PagSeguro static files?', 'woo-pagseguro-rm' ),
				'label'		  => 'Habilitar',
				'type'        => 'checkbox',
				'desc_tip'    => false,
				'default'     => 'yes',
				'description' => __( 'Instead asking for PagSeguro files, we can obtain those files from a mirror server (faster and more reliable).', 'woo-pagseguro-rm' ),
			),
			'email'                => array(
				'title'       => __( 'PagSeguro Email', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				'description' => __( 'Please enter your PagSeguro email address. This is needed in order to take payment.', 'woo-pagseguro-rm' ),
				'desc_tip'    => true,
				'default'     => '',
			),
			//modified by Ricardo Martins
			'public_key'                => array(
				'title'       => __( 'PagSeguro App Key', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				/* translators: %s: link to PagSeguro settings */
				'description' => sprintf( __( 'To get your app key, authorize the app. %s to authorize it (it\'s free).', 'woo-pagseguro-rm' ), '<a href="https://r-martins.github.io/PagSeguro-Magento-Transparente/woocommerce/wizard.html" target="_blank" id="psauthorize">' . __( 'Click here', 'woo-pagseguro-rm' ) . '</a>' ),
				'default'     => '',
			),
            'authjslink' => array(
              'title' => '',
              'type' => 'pslink',
            ),
			'sandbox_email'        => array(
				'title'       => __( 'PagSeguro Sandbox Email', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				/* translators: %s: link to PagSeguro settings */
				'description' => sprintf( __( 'Please enter your PagSeguro sandbox email address. You can get your sandbox email %s.', 'woo-pagseguro-rm' ), '<a href="https://sandbox.pagseguro.uol.com.br/vendedor/configuracoes.html">' . __( 'here', 'woo-pagseguro-rm' ) . '</a>' ),
				'default'     => '',
			),
			'sandbox_public_key'   => array(
				'title'       => __( 'PagSeguro Sandbox App Key', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				/* translators: %s: link to PagSeguro settings */
				'description' => sprintf( __( 'To get your sandbox app key, authorize your sandbox app. %s to authorize it (it\'s free).', 'woo-pagseguro-rm' ), '<a href="https://pagseguro.ricardomartins.net.br/sandbox.html" target="_blank">' . __( 'Click here', 'woo-pagseguro-rm' ) . '</a>' ),
				'default'     => '',
			),
			'transparent_checkout' => array(
				'title'       => __( 'Transparent Checkout Options', 'woo-pagseguro-rm' ),
				'type'        => 'title',
				'description' => '',
			),
			'tc_credit'            => array(
				'title'   => __( 'Credit Card', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Credit Card for Transparente Checkout', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'cc_installment_options' => array(
				'title' => __( 'Opções de Parcelamento', 'woo-pagseguro-rm' ),
				'type'  => 'select',
				'desc'  => '',
				'default' => 'external',
				'options' => array(
					'external' => __( 'Obedecer configurações da conta PagBank (padrão)', 'woo-pagseguro-rm' ),
					'fixed'  => __( 'Até X parcelas sem juros', 'woo-pagseguro-rm' ),
					'min_total'  => __( 'Até X parcelas sem juros dependendo do valor da parcela', 'woo-pagseguro-rm' ),
				),
			),
			'cc_installment_options_fixed' => array(
				'title' => __( 'Número de Parcelas sem Juros', 'woo-pagseguro-rm' ),
				'type'  => 'number',
				'desc'  => '',
				'default' => 3,
				'custom_attributes' => array(
					'min' => 1,
					'max' => 18,
				)
			),
			'cc_installments_options_min_total' => array(
				'title' => __( 'Valor Mínimo da Parcela sem Juros', 'woo-pagseguro-rm' ),
				'type'  => 'number',
				'description'  => __('Valor inteiro sem decimais. Exemplo: 10 para R$ 10,00 <br/><small>Neste exemplo, um pedido de R$100 poderá ser parcelado em 10x sem juros.<br/>Taxa padrão de juros: 2,99% a.m (consulte valor atualizado).</small>', 'woo-pagseguro-rm' ),
				//        'desc_tip' => true,
				'default' => 50,
				'custom_attributes' => array(
					'min' => 5,
					'max' => 99999,
				)
			),
			'tc_transfer'          => array(
				'title'   => __( 'Bank Transfer', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Bank Transfer for Transparente Checkout', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'tc_ticket'            => array(
				'title'   => __( 'Banking Ticket', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Banking Ticket for Transparente Checkout', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'tc_ticket_message'    => array(
				'title'   => __( 'Banking Ticket Tax Message', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Display a message alerting the customer that will be charged R$ 1,00 for payment by Banking Ticket', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'tc_redirect'            => array(
				'title'   => __( 'Others', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable the Redirect to PagSeguro option together with the Transparent Checkout', 'woo-pagseguro-rm' ),
				'default' => 'yes',
			),
			'tc_redirect_title' => array(
				'title'       => __( 'Redirect Title', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				'description' => __( 'Name that will be displayed as Other payment options (tab title)', 'woo-pagseguro-rm' ),
				'default'     => __( 'Others', 'woo-pagseguro-rm' ),
			),
			'behavior'             => array(
				'title'       => __( 'Integration Behavior', 'woo-pagseguro-rm' ),
				'type'        => 'title',
				'description' => '',
			),
			'send_only_total'      => array(
				'title'   => __( 'Send only the order total', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'If this option is enabled will only send the order total, not the list of items.', 'woo-pagseguro-rm' ),
				'default' => 'no',
			),
			'invoice_prefix'       => array(
				'title'       => __( 'Invoice Prefix', 'woo-pagseguro-rm' ),
				'type'        => 'text',
				'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PagSeguro account for multiple stores ensure this prefix is unqiue as PagSeguro will not allow orders with the same invoice number.', 'woo-pagseguro-rm' ),
				'desc_tip'    => true,
				'default'     => 'WC-',
			),
			'hides_when_is_outside_brazil' => array(
				'title'   => __( 'Disable module if selected country is not Brazil', 'woo-pagseguro-rm' ),
				'type'    => 'checkbox',
				'label'   => __( 'Disable module if selected country is not Brazil (enable this option if you sell to other countries than Brazil).', 'woo-pagseguro-rm' ),
				'default' => 'no',
			),
			'testing'              => array(
				'title'       => __( 'Gateway Testing', 'woo-pagseguro-rm' ),
				'type'        => 'title',
				'description' => '',
			),
			'debug'                => array(
				'title'       => __( 'Debug Log', 'woo-pagseguro-rm' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'woo-pagseguro-rm' ),
				'default'     => 'no',
				/* translators: %s: log page link */
				'description' => sprintf( __( 'Log PagSeguro events, such as API requests, inside %s', 'woo-pagseguro-rm' ), $this->get_log_view() ),
			),
		);
	}

	/**
	 * Validate the Public Key
	 * @param $key
	 * @param $value
	 * @return string
	 */
	public function validate_public_key_field( $key, $value ) {
        if($this->sandbox == 'yes'){
            return $value;
        }

		if (strlen($value) > 35) {
			WC_Admin_Settings::add_error(__('Incorrect App Key. The App Key (or Public Key) is only 35 characters long and starts with the PUB prefix.', 'woo-pagseguro-rm'));
			return '';
		}

		$endpoint = 'https://ws.ricardomartins.net.br/pspro/v7/auth/' . $value;
		$response = wp_remote_get($endpoint);
		$responseBody = wp_remote_retrieve_body($response);
		$responseBody = unserialize($responseBody);
        $validPublicKey = isset($responseBody['auth']) ? $responseBody['auth'] : false;
		if (!$validPublicKey) {
			WC_Admin_Settings::add_error( __('Invalid App Key.', 'woo-pagseguro-rm') );
			return '';
		}

		return $value;
	}

	/**
	 * Validate the Sandbox Public Key
	 * @param $key
	 * @param $value
	 * @return string
	 */
	public function validate_sandbox_public_key_field( $key, $value ) {
        if ($this->sandbox == 'no') {
            return $value;
        }

		if (strlen($value) > 35) {
			WC_Admin_Settings::add_error(__('Incorrect App Key. The App Key (or Public Key) is only 35 characters long and starts with the PUB prefix.', 'woo-pagseguro-rm'));
			return '';
		}

		$endpoint = 'https://ws.ricardomartins.net.br/pspro/v7/auth/' . $value . '?isSandbox=1';
		$response = wp_remote_get($endpoint);
		$responseBody = wp_remote_retrieve_body($response);
		$responseBody = unserialize($responseBody);
        $validPublicKey = isset($responseBody['auth']) ? $responseBody['auth'] : false;
		if (!$validPublicKey) {
			WC_Admin_Settings::add_error( __('Invalid App Key.', 'woo-pagseguro-rm') );
			return '';
		}

		return $value;
	}

	/**
	 * Admin page.
	 */
	public function admin_options() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'pagseguro-admin', plugins_url( 'assets/js/admin/admin' . $suffix . '.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ), WC_PAGSEGURO_VERSION, true );

		include dirname( __FILE__ ) . '/admin/views/html-admin-page.php';
	}

	/**
	 * Send email notification.
	 *
	 * @param string $subject Email subject.
	 * @param string $title   Email title.
	 * @param string $message Email message.
	 */
	protected function send_email( $subject, $title, $message ) {
		$mailer = WC()->mailer();

		$mailer->send( get_option( 'admin_email' ), $subject, $mailer->wrap_message( $title, $message ) );
	}

	/**
	 * Payment fields.
	 */
	public function payment_fields() {
		wp_enqueue_script( 'wc-credit-card-form' );

		$description = $this->get_description();
		if ( $description ) {
			echo wpautop( wptexturize( $description ) ); // WPCS: XSS ok.
		}

		$cart_total = $this->get_order_total();

		if ( 'transparent' === $this->method ) {
			wc_get_template(
				'transparent-checkout-form.php', array(
					'cart_total'        => $cart_total,
					'tc_credit'         => $this->tc_credit,
					'tc_transfer'       => $this->tc_transfer,
					'tc_ticket'         => $this->tc_ticket,
					'tc_ticket_message' => $this->tc_ticket_message,
					'tc_redirect'       => $this->tc_redirect,
					'tc_redirect_title' => $this->tc_redirect_title,
					'flag'              => plugins_url( 'assets/images/brazilian-flag.png', plugin_dir_path( __FILE__ ) ),
				), 'woocommerce/pagseguro/', WC_PagSeguro::get_templates_path()
			);
		}
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		$order = wc_get_order( $order_id );

		// adds 'is sandbox transaction' info to the order
		if ($this->is_sanbox_on()) {
			// WooCommerce 3.0 or later.
			if ( method_exists( $order, 'update_meta_data' ) ) {
				$order->update_meta_data( 'is_sandbox' ,'yes' );
				$order->save();
			} else {
				update_post_meta( $order->id, 'is_sandbox', 'yes' ); // @codingStandardsIgnoreLine
			}
		}

		if ( 'lightbox' !== $this->method ) {
			if ( isset( $_POST['pagseguro_sender_hash'] ) && 'transparent' === $this->method && $_POST['pagseguro_payment_method'] !== 'redirect' ) { // WPCS: input var ok, CSRF ok.
				$postedData = array_merge( $_POST, array( 'no_interest_installments_min_value' => $this->get_numeric_option ( 'no_interest_installments_min_value' ) ) );
				$response = $this->api->do_payment_request( $order, $postedData ); // WPCS: input var ok, CSRF ok.

				if ( $response['data'] ) {
					$this->update_order_status( $response['data'] );
				}
			} else {
				$response = $this->api->do_checkout_request( $order, $_POST ); // WPCS: input var ok, CSRF ok.
			}

			if ( $response['url'] ) {
				// Remove cart.
				WC()->cart->empty_cart();

				if (isset($response['data'])) {
					$this->save_payment_meta_data($order, $response['data']);
				}
				return array(
					'result'   => 'success',
					'redirect' => $response['url'],
				);
			} else {
				foreach ( $response['error'] as $error ) {
					wc_add_notice( $error, 'error' );
				}

				return array(
					'result'   => 'fail',
					'redirect' => '',
				);
			}
		} else {
			$use_shipping = isset( $_POST['ship_to_different_address'] ) ? true : false; // WPCS: input var ok, CSRF ok.

			return array(
				'result'   => 'success',
				'redirect' => add_query_arg( array( 'use_shipping' => $use_shipping ), $order->get_checkout_payment_url( true ) ),
			);
		}
	}

	/**
	 * Output for the order received page.
	 *
	 * @param int $order_id Order ID.
	 */
	public function receipt_page( $order_id ) {
		$order        = wc_get_order( $order_id );
		$request_data = $_POST;  // WPCS: input var ok, CSRF ok.
		if ( isset( $_GET['use_shipping'] ) && true === (bool) $_GET['use_shipping'] ) {  // WPCS: input var ok, CSRF ok.
			$request_data['ship_to_different_address'] = true;
		}

		$response = $this->api->do_checkout_request( $order, $request_data );


		if ( $response['url'] ) {
			// Lightbox script.
			wc_enqueue_js(
				'
				$( "#browser-has-javascript" ).show();
				$( "#browser-no-has-javascript, #cancel-payment, #submit-payment" ).hide();
				var isOpenLightbox = PagSeguroLightbox({
						code: "' . esc_js( $response['token'] ) . '"
					}, {
						success: function ( transactionCode ) {
							window.location.href = "' . str_replace( '&amp;', '&', esc_js( $this->get_return_url( $order ) ) ) . '";
						},
						abort: function () {
							window.location.href = "' . str_replace( '&amp;', '&', esc_js( $order->get_cancel_order_url() ) ) . '";
						}
				});
				if ( ! isOpenLightbox ) {
					window.location.href = "' . esc_js( $response['url'] ) . '";
				}
			'
			);

			wc_get_template(
				'lightbox-checkout.php', array(
					'cancel_order_url'    => $order->get_cancel_order_url(),
					'payment_url'         => $response['url'],
					'lightbox_script_url' => $this->api->get_lightbox_url(),
				), 'woocommerce/pagseguro/', WC_PagSeguro::get_templates_path()
			);
		} else {
			include dirname( __FILE__ ) . '/views/html-receipt-page-error.php';
		}
	}

	/**
	 * IPN handler.
	 */
	public function ipn_handler() {
		@ob_clean(); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged

		$ipn = $this->api->process_ipn_request( $_POST ); // WPCS: input var ok, CSRF ok.

		if ( $ipn ) {
			header( 'HTTP/1.1 200 OK' );
			do_action( 'valid_pagseguro_ipn_request', $ipn );
			exit();
		} else {
			wp_die( esc_html__( 'PagSeguro Request Unauthorized', 'woo-pagseguro-rm' ), esc_html__( 'PagSeguro Request Unauthorized', 'woo-pagseguro-rm' ), array( 'response' => 401 ) );
		}
	}

	/**
	 * Save payment meta data.
	 *
	 * @param WC_Order $order Order instance.
	 * @param array    $posted Posted data.
	 */
	protected function save_payment_meta_data( $order, $posted ) {
		$meta_data    = array();
		$payment_data = array(
			'type'         => '',
			'method'       => '',
			'installments' => '',
			'link'         => '',
		);

		if ( isset( $posted->sender->email ) ) {
			$meta_data[ __( 'Payer email', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->sender->email );
		}
		if ( isset( $posted->sender->name ) ) {
			$meta_data[ __( 'Payer name', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->sender->name );
		}
		if ( isset( $posted->paymentMethod->type ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$payment_data['type'] = intval( $posted->paymentMethod->type ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

			$meta_data[ __( 'Payment type', 'woo-pagseguro-rm' ) ] = $this->api->get_payment_name_by_type( $payment_data['type'] );
		}
		if ( isset( $posted->paymentMethod->code ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$payment_data['method'] = $this->api->get_payment_method_name( intval( $posted->paymentMethod->code ) ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

			$meta_data[ __( 'Payment method', 'woo-pagseguro-rm' ) ] = $payment_data['method'];
		}
		if ( isset( $posted->installmentCount ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$payment_data['installments'] = sanitize_text_field( (string) $posted->installmentCount ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

			$meta_data[ __( 'Installments', 'woo-pagseguro-rm' ) ] = $payment_data['installments'];
		}
		if ( isset( $posted->paymentLink ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$payment_data['link'] = sanitize_text_field( (string) $posted->paymentLink ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar

			$meta_data[ __( 'Payment URL', 'woo-pagseguro-rm' ) ] = $payment_data['link'];
		}
		if ( isset( $posted->creditorFees->intermediationRateAmount ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$meta_data[ __( 'Intermediation Rate', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->creditorFees->intermediationRateAmount ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
		}
		if ( isset( $posted->creditorFees->intermediationFeeAmount ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
			$meta_data[ __( 'Intermediation Fee', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->creditorFees->intermediationFeeAmount ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
		}
        if ( isset( $posted->code ) ) { // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            $meta_data[ __( 'Transaction Code', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->code ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
        }
        if ( isset( $posted->gatewaySystem) ) {
            if ( isset ($posted->gatewaySystem->acquirerName ) ) {
                $meta_data[ __( 'Acquirer Name', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->gatewaySystem->acquirerName ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
            if ( isset ($posted->gatewaySystem->nsu ) ) {
                $meta_data[ __( 'NSU', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->gatewaySystem->nsu ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
            if ( isset ($posted->gatewaySystem->tid ) ) {
                $meta_data[ __( 'TID', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->gatewaySystem->tid ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
            if ( isset ($posted->gatewaySystem->authorizationCode ) ) {
                $meta_data[ __( 'Authorization Code', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->gatewaySystem->authorizationCode ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
            if ( isset ($posted->gatewaySystem->establishmentCode ) ) {
                $meta_data[ __( 'Estabilishment Code', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $posted->gatewaySystem->establishmentCode ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
            if ($paymentMethod = $this->getPaymentMethodFromCode($posted)) {
                $meta_data[ __( 'Payment Method', 'woo-pagseguro-rm' ) ] = sanitize_text_field( (string) $paymentMethod ); // phpcs:ignore WordPress.NamingConventions.ValidVariableName.NotSnakeCaseMemberVar
            }
        }

        $meta_data['_wc_pagseguro_payment_data'] = $payment_data;

		// WooCommerce 3.0 or later.
		if ( method_exists( $order, 'update_meta_data' ) ) {
			foreach ( $meta_data as $key => $value ) {
				$order->update_meta_data( $key, $value );
			}
			$order->save();
		} else {
			foreach ( $meta_data as $key => $value ) {
				update_post_meta( $order->id, $key, $value ); // @codingStandardsIgnoreLine
			}
		}
	}

	/**
	 * Update order status.
	 *
	 * @param array $posted PagSeguro post data.
	 */
	public function update_order_status( $posted ) {
		if ( isset( $posted->reference ) ) {
			$id    = (int) str_replace( $this->invoice_prefix, '', $posted->reference );
			$order = wc_get_order( $id );

			// Check if order exists.
			if ( ! $order ) {
				return;
			}

			$order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;

			// Checks whether the invoice number matches the order.
			// If true processes the payment.
			if ( $order_id === $id ) {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, 'PagSeguro payment status for order ' . $order->get_order_number() . ' is: ' . intval( $posted->status ) );
				}

				// Save meta data.
				$this->save_payment_meta_data( $order, $posted );

				switch ( intval( $posted->status ) ) {
					case 1:
						$order->update_status( 'on-hold', __( 'PagSeguro: The buyer initiated the transaction, but so far the PagSeguro not received any payment information.', 'woo-pagseguro-rm' ) );

						break;
					case 2:
						$order->update_status( 'on-hold', __( 'PagSeguro: Payment under review.', 'woo-pagseguro-rm' ) );

						// Reduce stock for billets.
						if ( function_exists( 'wc_reduce_stock_levels' ) ) {
							wc_reduce_stock_levels( $order_id );
						}

						break;
					case 3:
						// Sometimes PagSeguro should change an order from cancelled to paid, so we need to handle it.
						if ( method_exists( $order, 'get_status' ) && 'cancelled' === $order->get_status() ) {
							$order->update_status( 'processing', __( 'PagSeguro: Payment approved.', 'woo-pagseguro-rm' ) );
							wc_reduce_stock_levels( $order_id );
						} else {
							$order->add_order_note( __( 'PagSeguro: Payment approved.', 'woo-pagseguro-rm' ) );

							// Changing the order for processing and reduces the stock.
							$order->payment_complete( sanitize_text_field( (string) $posted->code ) );
						}

						break;
					case 4:
						// if the payment is pending and the transaction status goes directly to status 4, we must complete the payment
						if ( method_exists( $order, 'get_status' ) && in_array( $order->get_status(), array('on-hold', 'pending' ) ) ) {
							$order->payment_complete( sanitize_text_field( (string) $posted->code ) );
						}

						$order->add_order_note( __( 'PagSeguro: Payment completed and credited to your account.', 'woo-pagseguro-rm' ) );

						break;
					case 5:
						$order->update_status( 'on-hold', __( 'PagSeguro: Payment came into dispute.', 'woo-pagseguro-rm' ) );
						$this->send_email(
							/* translators: %s: order number */
							sprintf( __( 'Payment for order %s came into dispute', 'woo-pagseguro-rm' ), $order->get_order_number() ),
							__( 'Payment in dispute', 'woo-pagseguro-rm' ),
							/* translators: %s: order number */
							sprintf( __( 'Order %s has been marked as on-hold, because the payment came into dispute in PagSeguro.', 'woo-pagseguro-rm' ), $order->get_order_number() )
						);

						break;
					case 6:
						$order->update_status( 'refunded', __( 'PagSeguro: Payment refunded.', 'woo-pagseguro-rm' ) );
						$this->send_email(
							/* translators: %s: order number */
							sprintf( __( 'Payment for order %s refunded', 'woo-pagseguro-rm' ), $order->get_order_number() ),
							__( 'Payment refunded', 'woo-pagseguro-rm' ),
							/* translators: %s: order number */
							sprintf( __( 'Order %s has been marked as refunded by PagSeguro.', 'woo-pagseguro-rm' ), $order->get_order_number() )
						);

						if ( function_exists( 'wc_increase_stock_levels' ) ) {
							wc_increase_stock_levels( $order_id );
						}

						break;
					case 7:
						$order->update_status( 'cancelled', __( 'PagSeguro: Payment canceled.', 'woo-pagseguro-rm' ) );

						if ( function_exists( 'wc_increase_stock_levels' ) ) {
							wc_increase_stock_levels( $order_id );
						}

						break;

					default:
						break;
				}
			} else {
				if ( 'yes' === $this->debug ) {
					$this->log->add( $this->id, 'Error: Order Key does not match with PagSeguro reference.' );
				}
			}
		}
	}

	/**
	 * Thank You page message.
	 *
	 * @param int $order_id Order ID.
	 */
	public function thankyou_page( $order_id ) {
		$order = wc_get_order( $order_id );
		// WooCommerce 3.0 or later.
		if ( method_exists( $order, 'get_meta' ) ) {
			$data = $order->get_meta( '_wc_pagseguro_payment_data' );
		} else {
			$data = get_post_meta( $order->id, '_wc_pagseguro_payment_data', true ); // @codingStandardsIgnoreLine
		}

		if ( isset( $data['type'] ) ) {
			wc_get_template(
				'payment-instructions.php', array(
					'type'         => $data['type'],
					'link'         => $data['link'],
					'method'       => $data['method'],
					'installments' => $data['installments'],
				), 'woocommerce/pagseguro/', WC_PagSeguro::get_templates_path()
			);
		}
	}

	/**
	 * Add content to the WC emails.
	 *
	 * @param  WC_Order $order         Order object.
	 * @param  bool     $sent_to_admin Send to admin.
	 * @param  bool     $plain_text    Plain text or HTML.
	 * @return string
	 */
	public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		// WooCommerce 3.0 or later.
		if ( method_exists( $order, 'get_meta' ) ) {
			if ( $sent_to_admin || 'on-hold' !== $order->get_status() || $this->id !== $order->get_payment_method() ) {
				return;
			}

			$data = $order->get_meta( '_wc_pagseguro_payment_data' );
		} else {
			if ( $sent_to_admin || 'on-hold' !== $order->status || $this->id !== $order->payment_method ) {
				return;
			}

			$data = get_post_meta( $order->id, '_wc_pagseguro_payment_data', true );
		}

		if ( isset( $data['type'] ) ) {
			if ( $plain_text ) {
				wc_get_template(
					'emails/plain-instructions.php', array(
						'type'         => $data['type'],
						'link'         => $data['link'],
						'method'       => $data['method'],
						'installments' => $data['installments'],
					), 'woocommerce/pagseguro/', WC_PagSeguro::get_templates_path()
				);
			} else {
				wc_get_template(
					'emails/html-instructions.php', array(
						'type'         => $data['type'],
						'link'         => $data['link'],
						'method'       => $data['method'],
						'installments' => $data['installments'],
					), 'woocommerce/pagseguro/', WC_PagSeguro::get_templates_path()
				);
			}
		}
	}


	/**
	 * Diagnostic tool used to check basic configuration of the module in case you open a ticket request
	 */
	public function wc_test()
	{
		@ob_clean(); // phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged

		header('Content-Type: application/json');
//		echo plugin_dir_path(__FILE__);
//		echo plugin_dir_path(__FILE__) . '..' . DIRECTORY_SEPARATOR . 'woo-pagseguro-rm.php';
//		exit;
		$plugin_data = get_file_data(plugin_dir_path(__FILE__) . '..' . DIRECTORY_SEPARATOR . 'woo-pagseguro-rm.php', array('Version'=>'Version'));


		$exta_checkout_fields_active = array_search(
				"woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php",
				get_option('active_plugins')
			) !== false;

		$pub_length = strlen($this->get_option('public_key'));
		$public_key = $pub_length == 35 ? 'Good' : 'Wrong with ' . $pub_length . ' characters';

		$session_id = $this->api->get_session_id();

		$pubauth = wp_safe_remote_get('https://ws.ricardomartins.net.br/pspro/v7/auth/' . $this->get_option('public_key'));
		$info = array(
			'platform'=>'Wordpress',
			'module_version' => $plugin_data,
			'extra_checkout_fields' => $exta_checkout_fields_active,
			'public_key' => $public_key,
			'session_id' => $session_id,
			'valid_pub' => @$pubauth['body'],
			'configuration' => array(
				'enabled' => $this->get_option('enabled'),
				'method' => $this->get_option('method'),
				'sandbox' => $this->get_option('sandbox'),
				'tc_credit' => $this->get_option('tc_credit'),
				'tc_ticket' => $this->get_option('tc_ticket'),
				'tc_ticket_message' => $this->get_option('tc_ticket_message'),
				'send_only_total' => $this->get_option('send_only_total'),
				'invoice_prefix' => $this->get_option('invoice_prefix'),
				'debug' => $this->get_option('debug'),
			)
		);


		echo json_encode($info);
		exit;
	}

    /**
     * Returns the payment method name used in the transaction, or false if not set or now known
     * @param $posted
     *
     * @return false|string
     */
    public function getPaymentMethodFromCode($posted)
    {
        $methods = [
            101 => 'Cartão de crédito Visa',
            102 => 'Cartão de crédito MasterCard',
            103 => 'Cartão de crédito American Express',
            104 => 'Cartão de crédito Diners',
            105 => 'Cartão de crédito Hipercard',
            106 => 'Cartão de crédito Aura',
            107 => 'Cartão de crédito Elo',
            108 => 'Cartão de crédito PLENOCard',
            109 => 'Cartão de crédito PersonalCard',
            110 => 'Cartão de crédito JCB',
            111 => 'Cartão de crédito Discover',
            112 => 'Cartão de crédito BrasilCard',
            113 => 'Cartão de crédito FORTBRASIL',
            114 => 'Cartão de crédito CARDBAN',
            115 => 'Cartão de crédito VALECARD',
            116 => 'Cartão de crédito Cabal',
            117 => 'Cartão de crédito Mais!',
            118 => 'Cartão de crédito Avista',
            119 => 'Cartão de crédito GRANDCARD',
            120 => 'Cartão de crédito Sorocred',
            122 => 'Cartão de crédito Up Policard',
            123 => 'Cartão de crédito Banese Card',
            201 => 'Boleto Bradesco',
            202 => 'Boleto Santander',
            301 => 'Débito online Bradesco',
            302 => 'Débito online Itaú',
            303 => 'Débito online Unibanco',
            304 => 'Débito online Banco do Brasil',
            305 => 'Débito online Banco Real',
            306 => 'Débito online Banrisul',
            307 => 'Débito online HSBC',
            401 => 'Saldo PagSeguro',
            402 => 'PIX',
            501 => 'Oi Paggo',
            701 => 'Depósito em conta - Banco do Brasil',
        ];

        if ( isset($posted->paymentMethod->code) && isset($methods[(int)$posted->paymentMethod->code])) {
            return $methods[(int)$posted->paymentMethod->code];
        }

        return false;
    }
    /**
     * Changes PagSeguro authorize link with the ability to capture the generated Public Key and auto-configure it within the module.
     *
     * @access public
     * @param mixed $key
     * @param mixed $data
     * @return string
     */
    public function generate_pslink_html( $key, $data ) {
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery('#psauthorize').on('click', authorizePagseguro).attr('href', 'javascript:void(0)')
            var intervalPs;
            window.addEventListener("message", function(ev) {
                if (ev.data.message != undefined && ev.data.message.substring(0, 3) === "PUB") {
                    clearInterval(intervalPs);
                    jQuery('#woocommerce_pagseguro_public_key').val(ev.data.message);
                    ev.source.close();
                }
            });

            function authorizePagseguro() {
                var child = window.open("https://pagseguro.ricardomartins.net.br/autorizar.html?sameWindow=true&utm_source=wordpress-config&utm_campaign=authorizepopup&utm_medium=textlink", "_blank", "height=600,width=700,popup=yes");
                // for test purposes
                // var child = window.open("https://pagseguro.ricardomartins.net.br/pro/app/created.html?publicKey=PUBD123", "_blank", "height=600,width=700,popup=yes");

                intervalPs = setInterval(function() {
                    try {
                        child.postMessage({ message: "getPublicKey" }, "*");
                    }
                    catch(e) {
                        // we're here when the child window has been navigated away or closed
                        if (child.closed) {
                            clearInterval(intervalPs);
                            console.debug("pagseguro authorization window was closed");
                            return;
                        }
                    }
                }, 500);
            }
        </script>
        <?php
        return ob_get_clean();
    }
}
