<?php
/**
 * eSewa Payment Gateway
 *
 * Provides a eSewa Payment Gateway.
 *
 * @class       WC_Gateway_eSewa
 * @extends     WC_Payment_Gateway
 * @category    Class
 * @author      AxisThemes
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Gateway_eSewa Class
 */
class WC_Gateway_eSewa extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {
		$this->id                 = 'esewa';
		$this->icon               = apply_filters( 'woocommerce_esewa_icon', '' );
		$this->method_title       = __( 'eSewa', 'woocommerce-esewa' );
		$this->method_description = __( 'eSewa works by sending customers to eSewa where they can enter their payment information.', 'woocommerce-esewa' );
		$this->has_fields         = false;
	}
}
