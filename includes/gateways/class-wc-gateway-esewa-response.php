<?php
/**
 * Abstract class for handling eSewa IPN responses.
 *
 * @package WooCommerce_eSewa\Abstracts
 * @version 1.8.0
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Gateway_eSewa_Response class.
 */
abstract class WC_Gateway_eSewa_Response {

	/**
	 * Sandbox mode.
	 *
	 * @var boolean
	 */
	protected $sandbox = false;

	/**
	 * Get the order from the eSewa Order ID and Key variable.
	 *
	 * @param  string $order_id  Order ID.
	 * @param  string $order_key Order key.
	 * @return bool|WC_Order object
	 */
	protected function get_esewa_order( $order_id, $order_key ) {
		if ( is_string( $order_key ) ) {
			$order = wc_get_order( $order_id );

			if ( ! $order ) {
				// We have an invalid $order_id, probably because invoice_prefix has changed.
				$order_id = wc_get_order_id_by_order_key( $order_key );
				$order    = wc_get_order( $order_id );
			}

			if ( ! $order || $order->get_order_key() !== $order_key ) {
				WC_Gateway_eSewa::log( 'Order Keys do not match.', 'error' );
				return false;
			}
		} else {
			// Nothing was found.
			WC_Gateway_eSewa::log( 'Order ID and key were not found.', 'error' );
			return false;
		}

		return $order;
	}

	/**
	 * Complete order, add transaction ID and note.
	 *
	 * @param WC_Order $order Order object.
	 * @param string   $txn_id Transaction ID.
	 * @param string   $note Payment note.
	 */
	protected function payment_complete( $order, $txn_id = '', $note = '' ) {
		$order->add_order_note( $note );
		$order->payment_complete( $txn_id );
		WC()->cart->empty_cart();
	}

	/**
	 * Hold order and add note.
	 *
	 * @param WC_Order $order Order object.
	 * @param string   $reason Reason why the payment is on hold.
	 */
	protected function payment_on_hold( $order, $reason = '' ) {
		$order->update_status( 'on-hold', $reason );
		$order->reduce_order_stock();
		WC()->cart->empty_cart();
	}
}
