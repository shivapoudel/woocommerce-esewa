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
		if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '2.3', '>=' ) ) {
			$this->includes();
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
	 * Get assets url.
	 *
	 * @return string
	 */
	public static function get_assets_url() {
		return plugins_url( 'assets/', __FILE__ );
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
	private function includes() {}

	/**
	 * Install method.
	 */
	public static function install() {}

	/**
	 * WooCommerce fallback notice.
	 *
	 * @return string
	 */
	public function woocommerce_missing_notice() {
		echo '<div class="error"><p>' . sprintf( __( 'WooCommerce eSewa depends on the last version of %s to work!', 'woocommerce-esewa' ), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __( 'WooCommerce', 'woocommerce-esewa' ) . '</a>' ) . '</p></div>';
	}
}

// Plugin install.
register_activation_hook( __FILE__, array( 'WC_eSewa', 'install' ) );

add_action( 'plugins_loaded', array( 'WC_eSewa', 'get_instance' ) );

endif;
