<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( 'class-wc-gateway-esewa-response.php' );

/**
 * Handles PDT Responses from eSewa
 */
class WC_Gateway_eSewa_PDT_Handler extends WC_Gateway_eSewa_Response {

	/** @var string service_code for PDT support */
	protected $service_code;

	/**
	 * Constructor
	 */
	public function __construct( $sandbox = false, $service_code = '' ) {
		add_action( 'woocommerce_thankyou_esewa', array( $this, 'check_response' ) );

		$this->service_code = $service_code;
		$this->sandbox      = $sandbox;
	}

	/**
	 * Validate a PDT Transaction to ensure its authentic
	 * @param  string $transaction
	 * @return bool
	 */
	protected function validate_transaction( $transaction ) {
		$pdt = array(
			'body'        => array(
				'scd'  => $this->service_code
			),
			'timeout'     => 60,
			'httpversion' => '1.1',
			'user-agent'  => 'WooCommerce/' . WC_VERSION
		);

		// Post back to get a response
		$response = wp_remote_post( $this->sandbox ? 'https://dev.esewa.com.np/epay/transrec' : 'https://esewa.com.np/epay/transrec', $pdt );

		if ( is_wp_error( $response ) || ! strpos( $response['body'], "SUCCESS" ) === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Check Response for PDT
	 */
	public function check_response() {
		if ( empty( $_REQUEST['oid'] ) || empty( $_REQUEST['amt'] ) || empty( $_REQUEST['refId'] ) ) {
			return;
		}

		$order_id    = wc_clean( stripslashes( $_REQUEST['oid'] ) );
		$amount      = wc_clean( stripslashes( $_REQUEST['amt'] ) );
		$transaction = wc_clean( stripslashes( $_REQUEST['refId'] ) );
	}
}
