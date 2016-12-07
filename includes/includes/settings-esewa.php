<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for eSewa Gateway.
 */
return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'woocommerce-esewa' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable eSewa Payment', 'woocommerce-esewa' ),
		'default' => 'yes',
	),
	'title' => array(
		'title'       => __( 'Title', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-esewa' ),
		'default'     => __( 'eSewa', 'woocommerce-esewa' ),
	),
	'description' => array(
		'title'       => __( 'Description', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce-esewa' ),
		'default'     => __( 'Pay via eSewa; you can pay with eSewa account securely.', 'woocommerce-esewa' ),
	),
	'service_code' => array(
		'title'       => __( 'Service code', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your eSewa Service Code; this is needed in order to take payment.', 'woocommerce-esewa' ),
		'default'     => '',
		'placeholder' => 'Eg: Woo',
	),
	'invoice_prefix' => array(
		'title'       => __( 'Invoice prefix', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your eSewa account for multiple stores ensure this prefix is unique as eSewa will not allow orders with the same invoice number.', 'woocommerce-esewa' ),
		'default'     => 'WC-',
	),
	'testmode' => array(
		'title'       => __( 'Sandbox mode', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Sandbox Mode', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Enable eSewa sandbox to test payments. Sign up for a developer account %1$shere%2$s.', 'woocommerce-esewa' ), '<a href="https://dev.esewa.com.np/" target="_blank">', '</a>' ),
	),
	'debug' => array(
		'title'       => __( 'Debug log', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log eSewa events, such as IPN requests, inside <code>%s</code>', 'woocommerce-esewa' ), wc_get_log_file_path( 'esewa' ) ),
	),
);
