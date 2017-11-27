jQuery( function( $ ) {
	'use strict';

	/**
	 * Object to handle eSewa admin functions.
	 */
	var wc_esewa_admin = {
		isTestMode: function() {
			return $( '#woocommerce_esewa_testmode' ).is( ':checked' );
		},

		/**
		 * Initialize.
		 */
		init: function() {
			$( document.body ).on( 'change', '#woocommerce_esewa_testmode', function() {
				var test_service_code = $( '#woocommerce_esewa_sandbox_service_code' ).parents( 'tr' ).eq( 0 ),
					live_service_code = $( '#woocommerce_esewa_service_code' ).parents( 'tr' ).eq( 0 );

				if ( $( this ).is( ':checked' ) ) {
					test_service_code.show();
					live_service_code.hide();
				} else {
					test_service_code.hide();
					live_service_code.show();
				}
			} );

			$( '#woocommerce_esewa_testmode' ).change();
		}
	};

	wc_esewa_admin.init();
});
