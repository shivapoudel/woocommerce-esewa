<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates requests to send to PayPal
 */
class WC_Gateway_eSewa_Request {

	/**
	 * Pointer to gateway making the request
	 * @var WC_Gateway_eSewa
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from eSewa
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Constructor
	 * @param WC_Gateway_eSewa $gateway
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'WC_Gateway_eSewa' );
	}

	/**
	 * Get the eSewa request URL for an order
	 * @param  WC_Order $order
	 * @param  boolean  $sandbox
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$esewa_args = http_build_query( $this->get_esewa_args( $order ), '', '&' );

		if ( $sandbox ) {
			return 'https://dev.esewa.com.np/epay/main?' . $esewa_args;
		} else {
			return 'http://esewa.com/epay/main?' . $esewa_args;
		}
	}

	/**
	 * Get eSewa Args for passing to PP
	 *
	 * @param  WC_Order $order
	 * @return array
	 */
	protected function get_paypal_args( $order ) {
		WC_Gateway_eSewa::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );
	}
}
