<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles Responses.
 */
abstract class WC_Gateway_eSewa_Response {

	/** @var bool Sandbox mode */
	protected $sandbox = false;

	/**
	 * Get the order from the eSewa Order ID and Key variable.
	 *
	 * @param  string $order_id
	 * @param  string $order_key
	 * @return bool|WC_Order object
	 */
	protected function get_esewa_order( $order_id, $order_key ) {
		if ( is_string( $order_key ) ) {

			if ( ! $order = wc_get_order( $order_id ) ) {
				// We have an invalid $order_id, probably because invoice_prefix has changed.
				$order_id = wc_get_order_id_by_order_key( $order_key );
				$order    = wc_get_order( $order_id );
			}

			if ( ! $order || $order->get_order_key() !== $order_key ) {
				WC_Gateway_eSewa::log( 'Order Keys do not match.', 'error' );
				return false;
			}

		// Nothing was found.
		} else {
			WC_Gateway_eSewa::log( 'Order ID and key were not found.', 'error' );
			return false;
		}

		return $order;
	}

	/**
	 * Complete order, add transaction ID and note.
	 *
	 * @param WC_Order $order the order object.
	 * @param string   $txn_id
	 * @param string   $note
	 */
	protected function payment_complete( $order, $txn_id = '', $note = '' ) {
		$order->add_order_note( $note );
		$order->payment_complete( $txn_id );
	}

	/**
	 * Hold order and add note.
	 *
	 * @param WC_Order $order the order object.
	 * @param string   $reason
	 */
	protected function payment_on_hold( $order, $reason = '' ) {
		$order->update_status( 'on-hold', $reason );
		$order->reduce_order_stock();
		WC()->cart->empty_cart();
	}
}
