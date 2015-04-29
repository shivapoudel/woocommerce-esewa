<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for eSewa Gateway
 */
return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'woocommerce-esewa' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable eSewa Payment', 'woocommerce-esewa' ),
		'default' => 'yes'
	),
	'title' => array(
		'title'       => __( 'Title', 'woocommerce-esewa' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-esewa' ),
		'default'     => __( 'eSewa', 'woocommerce-esewa' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-esewa' ),
		'default'     => __( 'Pay via eSewa; you can pay with eSewa account securly.', 'woocommerce-esewa' )
	),
	'merchant_id' => array(
		'title'       => __( 'Merchant ID', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter eSewa Merchant ID; this is needed in order to take payment.', 'woocommerce-esewa' ),
		'default'     => ''
	),
);
