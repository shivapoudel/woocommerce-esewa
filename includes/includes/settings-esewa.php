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
	'merchant' => array(
		'title'       => __( 'Merchant ID', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your eSewa Merchant ID; this is needed in order to take payment.', 'woocommerce-esewa' ),
		'default'     => '',
		'placeholder' => 'Eg: 0000MID'
	),
	'paymentaction' => array(
		'title'       => __( 'Payment Action', 'woocommerce-esewa' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woocommerce-esewa' ),
		'default'     => 'sale',
		'desc_tip'    => true,
		'options'     => array(
			'sale'          => __( 'Capture', 'woocommerce-esewa' ),
			'authorization' => __( 'Authorize', 'woocommerce-esewa' )
		)
	),
	'testmode' => array(
		'title'       => __( 'Test Mode', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable eSewa test mode', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => __( 'Enable eSewa test mode to test payments.', 'woocommerce-esewa' ),
	),
	'debug' => array(
		'title'       => __( 'Debug Log', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log eSewa events, inside <code>%s</code>', 'woocommerce-esewa' ), wc_get_log_file_path( 'esewa' ) )
	)
);
