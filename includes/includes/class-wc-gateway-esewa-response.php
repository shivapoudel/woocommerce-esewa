<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles Responses
 */
abstract class WC_Gateway_eSewa_Response {

	/** @var bool Sandbox mode */
	protected $sandbox = false;

	/**
	 * Get the order from the eSewa Order ID
	 * @param  string $custom
	 * @return bool|WC_Order object
	 */
	protected function get_esewa_order( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) {
			WC_Gateway_eSewa::log( 'Error: Order ID and key were not found.' );
			return false;
		}

		return $order;
	}

	/**
	 * Complete order, add transaction ID and note
	 * @param WC_Order $order
	 * @param string   $txn_id
	 * @param string   $note
	 */
	protected function payment_complete( $order, $txn_id = '', $note = '' ) {
		$order->add_order_note( $note );
		$order->payment_complete( $txn_id );
	}

	/**
	 * Hold order and add note
	 * @param WC_Order $order
	 * @param string   $reason
	 */
	protected function payment_on_hold( $order, $reason = '' ) {
		$order->update_status( 'on-hold', $reason );
	}
}
