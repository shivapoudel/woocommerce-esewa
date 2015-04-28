<?php
/**
 * Plugin Name: WooCommerce eSewa
 * Plugin URI: https://github.com/shivapoudel/woocommerce-esewa
 * Description: WooCommerce eSewa is a Nepali payment gateway for WooCommerce.
 * Author: AxisThemes
 * Author URI: http://axisthemes.com
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: woocommerce-esewa
 * Domain Path: /languages/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_eSewa' ) ) :

/**
 * WooCommerce eSewa main class.
 */
class WC_eSewa {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Checks with WooCommerce is installed.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			$this->includes();

			// Hooks
			add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'plugin_action_links' ) );
		} else {
			add_action( 'admin_notices', array( $this, 'woocommerce_missing_notice' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-esewa' );

		load_textdomain( 'woocommerce-esewa', trailingslashit( WP_LANG_DIR ) . 'woocommerce-esewa/woocommerce-esewa-' . $locale . '.mo' );
		load_plugin_textdomain( 'woocommerce-esewa', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 */
	private function includes() {
		include_once( 'includes/class-wc-esewa-gateway.php' );
	}

	/**
	 * Add the gateway to WooCommerce.
	 *
	 * @param  array $methods WooCommerce payment methods.
	 * @return array          Payment methods with eSewa.
	 */
	public function add_gateway( $methods ) {
		$methods[] = 'WC_Gateway_eSewa';
		return $methods;
	}

	/**
	 * Show action links on the plugin screen.
	 * @param  mixed $links Plugin Action links
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=wc_gateway_esewa' ) . '" title="' . esc_attr( __( 'View Settings', 'woocommerce-esewa' ) ) . '">' . __( 'Settings', 'woocommerce-esewa' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error notice is-dismissible"><p>' . sprintf( __( 'WooCommerce eSewa depends on the last version of %s to work!', 'woocommerce-esewa' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce', 'woocommerce-esewa' ) . '</a>' ) . '</p></div>';
	}

	/**
	 * Plugin Logger.
	 *
	 * @return WC_Logger
	 */
	public static function logger() {
		if ( class_exists( 'WC_Logger' ) ) {
			return new WC_Logger();
		} else {
			global $woocommerce;
			return $woocommerce->logger();
		}
	}
}

add_action( 'plugins_loaded', array( 'WC_eSewa', 'get_instance' ), 0 );

endif;
