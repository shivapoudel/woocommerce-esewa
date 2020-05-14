<?php
/**
 * Payment gateway - eSewa
 *
 * Provides an eSewa Payment Gateway.
 *
 * @package WooCommerce_eSewa\Classes\Payment
 * @version 1.8.0
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Gateway_eSewa Class.
 */
class WC_Gateway_eSewa extends WC_Payment_Gateway {

	/**
	 * Whether or not logging is enabled.
	 *
	 * @var boolean
	 */
	public static $log_enabled = false;

	/**
	 * A log object returned by wc_get_logger().
	 *
	 * @var boolean
	 */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'esewa';
		$this->icon               = apply_filters( 'woocommerce_esewa_icon', plugins_url( 'assets/images/esewa.png', WC_ESEWA_PLUGIN_FILE ) );
		$this->has_fields         = false;
		$this->order_button_text  = __( 'Proceed to eSewa', 'woocommerce-esewa' );
		$this->method_title       = __( 'eSewa', 'woocommerce-esewa' );
		$this->method_description = __( 'Take payments via eSewa - sends customers to eSewa to enter their payment information.', 'woocommerce-esewa' );

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->testmode     = 'yes' === $this->get_option( 'testmode', 'no' );
		$this->debug        = 'yes' === $this->get_option( 'debug', 'no' );
		$this->service_code = $this->testmode ? $this->get_option( 'sandbox_service_code' ) : $this->get_option( 'service_code' );

		// Transactional details URL.
		$this->view_transaction_url = $this->testmode ? 'https://uat.esewa.com.np/#/transaction/%s' : 'https://esewa.com.np/#/transaction/%s';

		// Enable logging for events.
		self::$log_enabled = $this->debug;

		if ( $this->testmode ) {
			$this->description .= ' ' . __( 'SANDBOX ENABLED. You can use testing accounts only.', 'woocommerce-esewa' );
			$this->description  = trim( $this->description );
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = 'no';
		} elseif ( $this->service_code ) {
			include_once dirname( __FILE__ ) . '/gateways/class-wc-gateway-esewa-ipn-handler.php';
			new WC_Gateway_eSewa_IPN_Handler( $this, $this->testmode, $this->service_code );
		}
	}

	/**
	 * Return whether or not this gateway still requires setup to function.
	 *
	 * When this gateway is toggled on via AJAX, if this returns true a
	 * redirect will occur to the settings page instead.
	 *
	 * @since 2.0.0
	 * @return bool
	 */
	public function needs_setup() {
		return empty( $this->service_code );
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Log message.
	 * @param string $level Optional, defaults to info, valid levels:
	 *                      emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, array( 'source' => 'esewa' ) );
		}
	}

	/**
	 * Processes and saves options.
	 * If there is an error thrown, will continue to save and validate fields, but will leave the erroring field out.
	 *
	 * @return bool was anything saved?
	 */
	public function process_admin_options() {
		$saved = parent::process_admin_options();

		// Maybe clear logs.
		if ( 'yes' !== $this->get_option( 'debug', 'no' ) ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->clear( 'esewa' );
		}

		return $saved;
	}

	/**
	 * Check if this gateway is enabled and available in the user's country.
	 *
	 * @return bool
	 */
	public function is_valid_for_use() {
		return in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_esewa_supported_currencies', array( 'NPR' ) ), true );
	}

	/**
	 * Admin Panel Options.
	 * - Options for bits like 'title' and availability on a country-by-country basis.
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
		if ( $this->is_valid_for_use() ) {
			parent::admin_options();
		} else {
			?>
			<div class="inline error">
				<p>
					<strong><?php esc_html_e( 'Gateway Disabled', 'woocommerce-esewa' ); ?></strong>: <?php esc_html_e( 'eSewa does not support your store currency.', 'woocommerce-esewa' ); ?>
				</p>
			</div>
			<?php
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = include 'gateways/settings-esewa.php';
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int $order_id Order ID.
	 * @return array
	 */
	public function process_payment( $order_id ) {
		include_once dirname( __FILE__ ) . '/gateways/class-wc-gateway-esewa-request.php';

		$order         = wc_get_order( $order_id );
		$esewa_request = new WC_Gateway_eSewa_Request( $this );

		return array(
			'result'   => 'success',
			'redirect' => $esewa_request->get_request_url( $order, $this->testmode ),
		);
	}

	/**
	 * Load admin scripts.
	 *
	 * @since 1.8.0
	 */
	public function admin_scripts() {
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( 'woocommerce_page_wc-settings' !== $screen_id ) {
			return;
		}

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'woocommerce_esewa_admin', plugins_url( '/assets/js/esewa-admin' . $suffix . '.js', WC_ESEWA_PLUGIN_FILE ), array(), WC_VERSION, true );
	}
}
