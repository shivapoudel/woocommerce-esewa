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
	'servicecode' => array(
		'title'       => __( 'Service Code', 'woocommerce-esewa' ),
		'type'        => 'text',
		'desc_tip'    => true,
		'description' => __( 'Please enter your eSewa Service Code; this is needed in order to take payment.', 'woocommerce-esewa' ),
		'default'     => '',
		'placeholder' => 'Eg: AxisThemes'
	),
	'mode' => array(
		'title'       => __( 'Payment Mode', 'woocommerce-esewa' ),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __( 'Choose whether you wish to use standard or hosted payment mode.', 'woocommerce-esewa' ),
		'default'     => 'standard',
		'desc_tip'    => true,
		'options'     => array(
			'standard' => __( 'Standard', 'woocommerce-esewa' ),
			'hosted'   => __( 'Hosted Payments', 'woocommerce-esewa' )
		)
	),
	'testmode' => array(
		'title'       => __( 'Sandbox Mode', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable Sandbox Mode', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => __( 'Enable sandbox mode to test payments.', 'woocommerce-esewa' ),
	),
	'debug' => array(
		'title'       => __( 'Debug Log', 'woocommerce-esewa' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'woocommerce-esewa' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log eSewa events, inside <code>%s</code>', 'woocommerce-esewa' ), wc_get_log_file_path( 'esewa' ) )
	)
);
