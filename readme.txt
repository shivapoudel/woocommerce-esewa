=== WooCommerce eSewa ===
Contributors: axisthemes, shivapoudel
Tags: woocommerce, esewa
Requires at least: 4.0
Tested up to: 4.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds eSewa as payment gateway in WooCommerce plugin.

== Description ==

= Add eSewa gateway to WooCommerce =

This plugin adds eSewa gateway to WooCommerce.

Please notice that [WooCommerce](http://wordpress.org/plugins/woocommerce/) must be installed and active.

= Introduction =

Add eSewa as a payment method in your WooCommerce store.

[eSewa](https://esewa.com.np/) is a Nepali Digital Payment Portal developed by fonepay. This means that if your store doesn't accept payment in NPR, you really do not need this plugin!!!

The plugin WooCommerce eSewa was developed without any incentive or eSewa Company. None of the developers of this plugin have ties to any of these two companies.

= Requirements =

* WordPress 4.0 or later.
* WooCommerce 2.3 or later.

= Installation =

Check out our installation guide and configuration of WooCommerce eSewa tab [Installation](http://wordpress.org/extend/plugins/woocommerce-esewa/installation/).

= Questions? =

You can answer you questions using:

* Our Session [FAQ](http://wordpress.org/extend/plugins/woocommerce-esewa/faq/).
* Creating a topic in the [WordPress support forum](http://wordpress.org/support/plugin/woocommerce-esewa) (English only).

= Contribute =

You can contribute to the source code in our [GitHub](https://github.com/axisthemes/woocommerce-esewa/) page.

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* This ready! You can now navigate to WooCommerce -> Settings -> Payment Gateways, choose eSewa and fill in your eSewa Merchant/Service Code.

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is needed to use this plugin? =

* WordPress 4.0 or later.
* WooCommerce 2.3 or later.
* Merchant/Service Code from eSewa.

= eSewa receives payments from which countries? =

At the moment the eSewa receives payments only from Nepal.

Configure the plugin to receive payments only users who select Nepal in payment information during checkout.

= Where is the eSewa payment option during checkout? =

You forgot to select the Nepal during registration at checkout. The eSewa payment option works only with Nepal.

= The request was paid and got the status of "processing" and not as "complete", that is right? =

Yes, this is absolutely right and means that the plugin is working as it should.

All payment gateway in WooCommerce must change the order status to "processing" when the payment is confirmed and should never be changed alone to "complete" because the request should go only to the status "finished" after it has been delivered.

For downloadable products to WooCommerce default setting is to allow access only when the request has the status "completed", however in WooCommerce settings tab Products you can enable the option "Grant access to download the product after payment" and thus release download when the order status is as "processing."

== Screenshots ==

1. Settings page.
2. Checkout page.

== Changelog ==

= 1.1.0 =
* Dev - Deploy corrections.

= 1.0.2 =
* Fix - Ensure coupon discount are applied when processing checkout.
* Dev - Included grunt-wp-plugin for deploying.
* Refactor - Subtotal amount values.

= 1.0.1 =
* Fix - When eSewa payment is on hold, reduce stock and empty cart.
* Tweak - Change method description for eSewa settings page.

= 1.0.0 =
* First stable release.

== Upgrade Notice ==

= 1.1.0 =
1.1.0 is a major update so it is important that you make backups, test extensions and your theme prior to updating. Developers should catch up with [develop.axisthemes.com](http://develop.axisthemes.com/) to see what has been happening in core.
