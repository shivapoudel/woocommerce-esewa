<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'class-wc-gateway-esewa-response.php' );

/**
 * Handles IPN Responses from eSewa
 */
class WC_Gateway_eSewa_IPN_Handler extends WC_Gateway_eSewa_Response {

	/** @var string Service code for IPN support */
	protected $service_code;

	/**
	 * Constructor
	 * @param WC_Gateway_eSewa $gateway
	 */
	public function __construct( $gateway, $sandbox = false, $service_code = '' ) {
		add_action( 'woocommerce_api_wc_gateway_esewa', array( $this, 'check_response' ) );
		add_action( 'valid-esewa-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->service_code = $service_code;
		$this->sandbox      = $sandbox;
		$this->gateway      = $gateway;
	}

	/**
	 * Check for eSewa IPN Response
	 */
	public function check_response() {
		if ( ! empty( $_REQUEST ) && $this->validate_ipn() ) {
			$posted = wp_unslash( $_REQUEST );

			do_action( 'valid-esewa-standard-ipn-request', $posted );
			exit;
		}

		wp_die( 'eSewa IPN Request Failure', 'eSewa IPN', array( 'response' => 200 ) );
	}

	/**
	 * There was a valid response
	 * @param array $posted Post data after wp_unslash
	 */
	public function valid_response( $posted ) {
		if ( ! empty( $posted['key'] ) && $order = $this->get_esewa_order( $posted['oid'], $posted['key'] ) ) {

			// Check to see if the transaction was valid
			if ( isset( $posted['amt'] ) && isset( $posted['refId'] ) ) {
				$posted['payment_status'] = 'completed';
			}

			WC_Gateway_eSewa::log( 'Found order #' . $order->id );
			WC_Gateway_eSewa::log( 'Payment status: ' . $posted['payment_status'] );

			if ( method_exists( __CLASS__, 'payment_status_' . $posted['payment_status'] ) ) {
				wp_redirect( esc_url( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ) );
				call_user_func( array( __CLASS__, 'payment_status_' . $posted['payment_status'] ), $order, $posted );
			}
		}
	}

	/**
	 * Check eSewa IPN validity
	 */
	public function validate_ipn() {
		WC_Gateway_eSewa::log( 'Checking IPN response is valid' );

		$amount      = wc_clean( stripslashes( $_REQUEST['amt'] ) );
		$order_id    = wc_clean( stripslashes( $_REQUEST['oid'] ) );
		$transaction = wc_clean( stripslashes( $_REQUEST['refId'] ) );

		// Send back post vars to eSewa
		$params = array(
			'body'        => array(
				'amt'  => $amount,
				'pid'  => $order_id,
				'rid'  => $transaction,
				'scd'  => $this->service_code
			),
			'timeout'     => 60,
			'sslverify'   => false,
			'httpversion' => '1.1',
			'compress'    => false,
			'decompress'  => false,
			'user-agent'  => 'WooCommerce/' . WC()->version
		);

		// Post back to get a response
		$response = wp_remote_post( $this->sandbox ? 'https://dev.esewa.com.np/epay/transrec' : 'https://esewa.com.np/epay/transrec', $params );

		WC_Gateway_eSewa::log( 'IPN Request: ' . print_r( $params, true ) );
		WC_Gateway_eSewa::log( 'IPN Response: ' . print_r( $response, true ) );

		// check to see if the request was valid
		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 && strstr( strtoupper( $response['body'] ), 'SUCCESS' ) ) {
			WC_Gateway_eSewa::log( 'Received valid response from eSewa' );
			return true;
		}

		WC_Gateway_eSewa::log( 'Received invalid response from eSewa' );

		if ( is_wp_error( $response ) ) {
			WC_Gateway_eSewa::log( 'Error response: ' . $response->get_error_message() );
		}

		return false;
	}

	/**
	 * Handle a completed payment
	 * @param WC_Order $order
	 */
	protected function payment_status_completed( $order, $posted ) {
		if ( $order->has_status( 'completed' ) ) {
			WC_Gateway_eSewa::log( 'Aborting, Order #' . $order->id . ' is already complete.' );
			exit;
		}

		if ( 'completed' === $posted['payment_status'] ) {
			$this->payment_complete( $order, ( ! empty( $posted['refId'] ) ? wc_clean( $posted['refId'] ) : '' ), __( 'IPN payment completed', 'woocommerce-esewa' ) );
		} else {
			$this->payment_on_hold( $order, sprintf( __( 'Payment pending: eSewa amounts do not match (amt %s).', 'woocommerce-error' ), $posted['amt'] ) );
		}
	}

	/**
	 * Handle a failed payment
	 * @param WC_Order $order
	 */
	protected function payment_status_failed( $order, $requested ) {
		$order->update_status( 'failed', sprintf( __( 'Payment %s via IPN.', 'woocommerce-esewa' ), wc_clean( $requested['payment_status'] ) ) );
	}
}
