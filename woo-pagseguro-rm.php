<?php
/**
 * Plugin Name:          Módulo PagSeguro - Ricardo Martins (com descontos)
 * Plugin URI:           https://r-martins.github.io/PagSeguro-Magento-Transparente/woocommerce.html
 * Description:          Adiciona PagSeguro como meio de pagamento (com desconto nas taxas oficiais).
 * Author:               Ricardo Martins (a partir do plugin de Claudio Sanches)
 * Author URI:           http://r-martins.github.io/PagSeguro-Magento-Transparente/woocommerce.html
 * Version:              3.16.7
 * License:              GPLv3 or later
 * Text Domain:          woo-pagseguro-rm
 * Domain Path:          /languages
 * WC requires at least: 3.0.0
 * WC tested up to:      9.1
 * Tested up to:         6.6
 *
 * WooCommerce PagSeguro is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * WooCommerce PagSeguro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WooCommerce PagSeguro. If not, see
 * <https://www.gnu.org/licenses/gpl-3.0.txt>.
 *
 * @package WooCommerce_PagSeguro
 * Forked from Original Plugin by Claudio Sanches (https://github.com/claudiosanches/woocommerce-pagseguro)
 * Arquivo modificado por Ricardo Martins em 4 de Setembro de 2019 (GPLv2)
 */

defined( 'ABSPATH' ) || exit;

// Plugin constants.
define( 'WC_PAGSEGURO_VERSION', '3.16.7' );
define( 'WC_PAGSEGURO_PLUGIN_FILE', __FILE__ );

if ( ! class_exists( 'WC_PagSeguro' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-wc-pagseguro.php';
	add_action( 'plugins_loaded', array( 'WC_PagSeguro', 'init' ) );
}
