<?php
/**
 * Handles responses from eSewa IPN.
 *
 * @package WooCommerce_eSewa\Classes\Payment
 * @version 1.8.0
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/class-wc-gateway-esewa-response.php';

/**
 * WC_Gateway_eSewa_IPN_Handler class.
 */
class WC_Gateway_eSewa_IPN_Handler extends WC_Gateway_eSewa_Response {

	/**
	 * Merchant/Service code for IPN support.
	 *
	 * @var string
	 */
	protected $service_code;

	/**
	 * Constructor.
	 *
	 * @param WC_Gateway_eSewa $gateway      Gateway class.
	 * @param bool             $sandbox      Use sandbox or not.
	 * @param string           $service_code Merchant/Service code.
	 */
	public function __construct( $gateway, $sandbox = false, $service_code = '' ) {
		add_action( 'woocommerce_api_wc_gateway_esewa', array( $this, 'check_response' ) );
		add_action( 'valid-esewa-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->service_code = $service_code;
		$this->sandbox      = $sandbox;
		$this->gateway      = $gateway;
	}

	/**
	 * Check for eSewa IPN Response.
	 */
	public function check_response() {
		if ( ! empty( $_REQUEST ) && $this->validate_ipn() ) { // WPCS: CSRF ok.
			$requested = wp_unslash( $_REQUEST ); // WPCS: CSRF ok, input var ok.

			// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
			do_action( 'valid-esewa-standard-ipn-request', $requested );
			exit;
		}

		wp_die( 'eSewa Request Failure', 'eSewa IPN', array( 'response' => 500 ) );
	}

	/**
	 * There was a valid response.
	 *
	 * @param array $requested Request data after wp_unslash.
	 */
	public function valid_response( $requested ) {
		$order = isset( $requested['oid'], $requested['key'] ) ? $this->get_esewa_order( $requested['oid'], $requested['key'] ) : false;

		if ( $order ) {

			// Lowercase returned variables.
			$requested['payment_status'] = strtolower( $requested['payment_status'] );

			// Validate transaction status.
			if ( isset( $requested['refId'] ) && 'success' === $requested['payment_status'] ) {
				$requested['payment_status'] = 'completed';
				$requested['pending_reason'] = __( 'eSewa IPN response failed.', 'woocommerce-esewa' );
			} else {
				$requested['payment_status'] = 'failed';
			}

			WC_Gateway_eSewa::log( 'Found order #' . $order->get_id() );
			WC_Gateway_eSewa::log( 'Payment status: ' . $requested['payment_status'] );

			if ( method_exists( $this, 'payment_status_' . $requested['payment_status'] ) ) {
				call_user_func( array( $this, 'payment_status_' . $requested['payment_status'] ), $order, $requested );
				wp_safe_redirect( esc_url_raw( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ) );
				exit;
			}
		}
	}

	/**
	 * Check eSewa IPN validity.
	 */
	public function validate_ipn() {
		WC_Gateway_eSewa::log( 'Checking IPN response is valid' );

		$amount      = isset( $_REQUEST['amt'] ) ? wc_clean( wp_unslash( $_REQUEST['amt'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$order_id    = isset( $_REQUEST['oid'] ) ? wc_clean( wp_unslash( $_REQUEST['oid'] ) ) : ''; // WPCS: input var ok, CSRF ok.
		$transaction = isset( $_REQUEST['refId'] ) ? wc_clean( wp_unslash( $_REQUEST['refId'] ) ) : ''; // WPCS: input var ok, CSRF ok.

		// Fix esewa amount validation.
		if ( isset( $_REQUEST['key'] ) ) { // WPCS: CSRF ok.
			$order = $this->get_esewa_order( $order_id, wc_clean( wp_unslash( $_REQUEST['key'] ) ) ); // WPCS: input var ok, CSRF ok.

			if ( number_format( $order->get_total(), 2, '.', '' ) !== number_format( $amount, 2, '.', '' ) ) {
				WC_Gateway_eSewa::log( 'Amount alert: eSewa amount do not match (sent "' . $order->get_total() . '" | returned "' . $amount . '").', 'alert' );
				$amount = $order->get_total();
			}
		}

		// Send back post vars to esewa.
		$params = array(
			'body'        => array(
				'amt' => $amount,
				'pid' => $order_id,
				'rid' => $transaction,
				'scd' => $this->service_code,
			),
			'timeout'     => 60,
			'httpversion' => '1.1',
			'compress'    => false,
			'decompress'  => false,
			'user-agent'  => 'WooCommerce/' . WC()->version,
			'sslverify'   => apply_filters( 'https_local_ssl_verify', false ),
		);

		// Post back to get a response.
		$response = wp_safe_remote_post( $this->sandbox ? 'https://uat.esewa.com.np/epay/transrec' : 'https://esewa.com.np/epay/transrec', $params );

		WC_Gateway_eSewa::log( 'IPN Response: ' . wc_print_r( $response, true ) );

		// Check to see if the request was valid.
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( strtoupper( $response['body'] ), 'SUCCESS' ) ) {
			WC_Gateway_eSewa::log( 'Received valid response from eSewa IPN' );
			return true;
		}

		WC_Gateway_eSewa::log( 'Received invalid response from eSewa IPN' );

		if ( is_wp_error( $response ) ) {
			WC_Gateway_eSewa::log( 'Error response: ' . $response->get_error_message() );
		}

		return false;
	}

	/**
	 * Handle a completed payment.
	 *
	 * @param WC_Order $order     Order object.
	 * @param array    $requested Request data.
	 */
	protected function payment_status_completed( $order, $requested ) {
		if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
			WC_Gateway_eSewa::log( 'Aborting, Order #' . $order->get_id() . ' is already complete.' );
			exit;
		}

		if ( 'completed' === $requested['payment_status'] ) {
			if ( $order->has_status( 'cancelled' ) ) {
				$this->payment_status_paid_cancelled_order( $order, $requested );
			}

			$this->payment_complete( $order, ( ! empty( $requested['refId'] ) ? wc_clean( $requested['refId'] ) : '' ), __( 'IPN payment completed', 'woocommerce-esewa' ) );

			// Log eSewa Reference Code.
			if ( ! empty( $requested['refId'] ) ) {
				update_post_meta( $order->get_id(), 'eSewa Reference Code', wc_clean( $requested['refId'] ) );
			}
		} else {
			/* translators: %s: pending reason */
			$this->payment_on_hold( $order, sprintf( __( 'Payment pending: %s', 'woocommerce-esewa' ), $requested['pending_reason'] ) );
		}
	}

	/**
	 * Handle a failed payment.
	 *
	 * @param WC_Order $order     Order object.
	 * @param array    $requested Request data.
	 */
	protected function payment_status_failed( $order, $requested ) {
		/* translators: %s: payment status */
		$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce-esewa' ), wc_clean( $requested['payment_status'] ) ) );
	}

	/**
	 * When a user cancelled order is marked paid.
	 *
	 * @param WC_Order $order     Order object.
	 * @param array    $requested Request data.
	 */
	protected function payment_status_paid_cancelled_order( $order, $requested ) {
		if ( version_compare( WC_VERSION, '3.3.0', '>' ) ) {
			$this->send_ipn_email_notification(
				/* translators: %s: order link. */
				sprintf( __( 'Payment for cancelled order %s received', 'woocommerce-esewa' ), '<a class="link" href="' . esc_url( $order->get_edit_order_url() ) . '">' . $order->get_order_number() . '</a>' ),
				/* translators: %s: order ID. */
				sprintf( __( 'Order #%s has been marked paid by eSewa IPN, but was previously cancelled. Admin handling required.', 'woocommerce-esewa' ), $order->get_order_number() )
			);
		}
	}

	/**
	 * Send a notification to the user handling orders.
	 *
	 * @param string $subject Email subject.
	 * @param string $message Email message.
	 */
	protected function send_ipn_email_notification( $subject, $message ) {
		$new_order_settings = get_option( 'woocommerce_new_order_settings', array() );
		$mailer             = WC()->mailer();
		$message            = $mailer->wrap_message( $subject, $message );

		$woocommerce_esewa_settings = get_option( 'woocommerce_esewa_settings' );
		if ( ! empty( $woocommerce_esewa_settings['ipn_notification'] ) && 'no' === $woocommerce_esewa_settings['ipn_notification'] ) {
			return;
		}

		$mailer->send( ! empty( $new_order_settings['recipient'] ) ? $new_order_settings['recipient'] : get_option( 'admin_email' ), strip_tags( $subject ), $message );
	}
}
