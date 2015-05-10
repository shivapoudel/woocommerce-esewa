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
			$posted = wp_unslash( $_REQUEST );

			do_action( 'valid-esewa-standard-ipn-request', $posted );
			exit;
		}

		wp_die( 'eSewa IPN Request Failure', 'eSewa IPN', array( 'response' => 200 ) );
	}

	/**
	 * There was a valid response
	 * @param array $posted data after wp_unslash
	 */
	public function valid_response( $posted ) {

	}

	/**
	 * Check eSewa IPN validity
	 */
	public function validate_ipn() {
		WC_Gateway_eSewa::log( 'Checking IPN response is valid' );

		// Get received values from post data
		$validate_ipn = wp_unslash( $_REQUEST );

		// Send back post vars to eSewa
		$params = array(
			'body'        => $validate_ipn,
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
}
