<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates requests to send to eSewa.
 */
class WC_Gateway_eSewa_Request {

	/**
	 * Pointer to gateway making the request.
	 * @var WC_Gateway_eSewa
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from eSewa.
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Constructor.
	 * @param WC_Gateway_eSewa $gateway
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = WC()->api_request_url( 'WC_Gateway_eSewa' );
	}

	/**
	 * Get the eSewa request URL for an order.
	 * @param  WC_Order $order
	 * @param  bool     $sandbox
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false ) {
		$esewa_args = http_build_query( array_filter( $this->get_esewa_args( $order ) ), '', '&' );

		WC_Gateway_eSewa::log( 'eSewa Request Args for order ' . $order->get_order_number() . ': ' . print_r( $esewa_args, true ) );

		if ( $sandbox ) {
			return 'https://dev.esewa.com.np/epay/main?' . $esewa_args;
		} else {
			return 'https://esewa.com.np/epay/main?' . $esewa_args;
		}
	}

	/**
	 * Limit length of an arg.
	 *
	 * @param  string  $string
	 * @param  integer $limit
	 * @return string
	 */
	protected function limit_length( $string, $limit = 127 ) {
		if ( strlen( $string ) > $limit ) {
			$string = substr( $string, 0, $limit - 3 ) . '...';
		}
		return $string;
	}

	/**
	 * Get eSewa Args for passing to eSewa.
	 * @param  WC_Order $order
	 * @return array
	 */
	protected function get_esewa_args( $order ) {
		WC_Gateway_eSewa::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		return apply_filters( 'woocommerce_esewa_args', array(
			'amt'   => wc_format_decimal( $order->get_subtotal() - $order->get_total_discount(), 2 ),
			'txAmt' => wc_format_decimal( $order->get_total_tax(), 2 ),
			'pdc'   => wc_format_decimal( $order->get_total_shipping(), 2 ),
			'psc'   => wc_format_decimal( $this->get_service_charge( $order ), 2 ),
			'tAmt'  => wc_format_decimal( $order->get_total(), 2 ),
			'scd'   => $this->limit_length( $this->gateway->get_option( 'service_code' ), 32 ),
			'pid'   => $this->limit_length( $this->gateway->get_option( 'invoice_prefix' ) . $order->get_order_number(), 127 ),
			'su'    => esc_url_raw( add_query_arg( array( 'payment_status' => 'success', 'key' => $order->order_key ), $this->limit_length( $this->notify_url, 255 ) ) ),
			'fu'    => esc_url_raw( add_query_arg( array( 'payment_status' => 'failure', 'key' => $order->order_key ), $this->limit_length( $this->notify_url, 255 ) ) ),
		), $order );
	}

	/**
	 * Get the service charge to send to eSewa.
	 * @param  WC_Order $order
	 * @return float
	 */
	private function get_service_charge( $order ) {
		$fee_total = 0;
		if ( sizeof( $order->get_fees() ) > 0 ) {
			foreach ( $order->get_fees() as $item ) {
				$fee_total += ( isset( $item['line_total'] ) ) ? $item['line_total'] : 0;
			}
		}

		return $fee_total;
	}
}
