<?php
/**
 * Plugin Name: WooCommerce eSewa
 * Plugin URI: https://github.com/shivapoudel/woocommerce-esewa
 * Description: WooCommerce eSewa is a Nepali payment gateway for WooCommerce.
 * Version: 2.0.0
 * Author: Shiva Poudel
 * Author URI: https://shivapoudel.com
 * Text Domain: woocommerce-esewa
 * Domain Path: /languages
 *
 * WC requires at least: 3.6.0
 * WC tested up to: 4.3.0
 *
 * @package WooCommerce_eSewa
 */

defined( 'ABSPATH' ) || exit;

// Define WC_ESEWA_PLUGIN_FILE.
if ( ! defined( 'WC_ESEWA_PLUGIN_FILE' ) ) {
	define( 'WC_ESEWA_PLUGIN_FILE', __FILE__ );
}

// Include the main WooCommerce eSewa class.
if ( ! class_exists( 'WooCommerce_eSewa' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-woocommerce-esewa.php';
}

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'WooCommerce_eSewa', 'get_instance' ) );
