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
	protected function get_esewa_args( $order ) {
		WC_Gateway_eSewa::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		return apply_filters( 'woocommerce_esewa_args', array_merge(
			array(
				'tAmt'  => wc_format_decimal( $order->get_total(), 2 ),
				'amt'   => wc_format_decimal( $this->get_order_subtotal( $order ), 2 ),
				'txAmt' => wc_format_decimal( $order->get_total_tax(), 2 ),
				'pdc'   => wc_format_decimal( $order->get_total_shipping(), 2 ),
				'psc'   => wc_format_decimal( $this->get_service_charge( $order ), 2 ),
				'pid'   => $order->id,
				'su'    => esc_url( add_query_arg( 'utm_nooverride', '1', $this->gateway->get_return_url( $order ) ) ),
				'fu'    => esc_url( $order->get_cancel_order_url() )
			),
			$this->get_merchant_args()
		), $order );
	}

	/**
	 * Helper method to get the order subtotal
	 *
	 * @since 2.1
	 * @param WC_Order $order
	 * @return float
	 */
	private function get_order_subtotal( $order ) {

		$subtotal = 0;

		// subtotal
		foreach ( $order->get_items() as $item ) {

			$subtotal += ( isset( $item['line_subtotal'] ) ) ? $item['line_subtotal'] : 0;
		}

		return $subtotal;
	}

	/**
	 * Helper method to get the service charge
	 *
	 * @since 2.1
	 * @param WC_Order $order
	 * @return float
	 */
	private function get_service_charge( $order ) {
		$charge = 0;

		// Service Charge
		if ( sizeof( $order->get_fees() ) > 0 ) {
			foreach ( $order->get_fees() as $item ) {

				$charge += ( isset( $item['line_total'] ) ) ? $item['line_total'] : 0;
			}
		}

		return $charge;
	}

	/**
	 * Get merchant args for eSewa request
	 * @return array
	 */
	protected function get_merchant_args() {
		$merchant_args = array();

		if ( 'yes' == $this->gateway->get_option( 'testmode' ) ) {
			$merchant_args['scd'] = 'testmerchant';
		} else {
			$merchant_args['scd'] = $this->gateway->get_option( 'merchant' );
		}

		return $merchant_args;
	}
}
