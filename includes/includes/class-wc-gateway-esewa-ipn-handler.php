<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

include_once( 'class-wc-gateway-esewa-response.php' );

/**
 * Handles IPN Responses from eSewa
 */
class WC_Gateway_eSewa_IPN_Handler extends WC_Gateway_eSewa_Response {

	/** @var string Merchant/Service code to validate */
	protected $service_code;

	/**
	 * Constructor
	 */
	public function __construct( $sandbox = false, $service_code = '' ) {
		add_action( 'woocommerce_api_wc_gateway_esewa', array( $this, 'check_response' ) );
		add_action( 'valid-esewa-standard-ipn-request', array( $this, 'valid_response' ) );

		$this->service_code = $service_code;
		$this->sandbox      = $sandbox;
	}

	/**
	 * Check for eSewa IPN Response
	 */
	public function check_response() {
		if ( ! empty( $_REQUEST ) && $this->validate_ipn() ) {
			$requested = wp_unslash( $_REQUEST );

			do_action( 'valid-esewa-standard-ipn-request', $requested );
			exit;
		}

		wp_die( 'eSewa IPN Request Failure', 'eSewa IPN', array( 'response' => 200 ) );
	}

	/**
	 * There was a valid response
	 * @param array $requested data after wp_unslash
	 */
	public function valid_response( $requested ) {

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
		$ipn = array(
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
		$response = wp_remote_post( $this->sandbox ? 'https://dev.esewa.com.np/epay/transrec' : 'https://esewa.com.np/epay/transrec', $ipn );

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
}
