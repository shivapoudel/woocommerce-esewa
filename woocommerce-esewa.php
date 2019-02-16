<?php
/**
 * Plugin Name: WooCommerce eSewa
 * Plugin URI: https://gitlab.com/shivapoudel/woocommerce-esewa
 * GitLab Plugin URI: https://gitlab.com/shivapoudel/woocommerce-esewa
 * Description: WooCommerce eSewa is a Nepali payment gateway for WooCommerce.
 * Version: 1.8.0
 * Author: Shiva Poudel
 * Author URI: https://shivapoudel.com
 * License: GPLv3 or later
 * Text Domain: woocommerce-esewa
 * Domain Path: /languages/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.5.0
 *
 * @package WooCommerce_eSewa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
