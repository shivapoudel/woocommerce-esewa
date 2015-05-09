=== WooCommerce eSewa ===
Contributors: axisthemes, shivapoudel
Donate link: http://axisthemes.com/donate/
Tags: woocommerce, esewa
Requires at least: 3.8
Tested up to: 4.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds eSewa as payment gateway in WooCommerce plugin.

== Description ==

### Add eSewa gateway to WooCommerce ###

This plugin adds eSewa gateway to WooCommerce.

Please notice that WooCommerce must be installed and active.

= Contribute =

You can contribute to the source code in our [GitHub](https://github.com/shivapoudel/woocommerce-esewa) page.

### Short Description ###

Add eSewa as a payment method in your WooCommerce store.

[eSewa](https://esewa.com.np/) is a Nepali Digital Payment Portal which allows you to make payments to various merchants.

The plugin WooCommerce eSewa was developed without any incentive or eSewa Company. None of the developers of this plugin with ties to these two companies.

= Compatibility =

Compatiable with WooCommerce 2.3+

= Installation =

Check out our installation guide and configuration of WooCommerce eSewa tab [Installation](http://wordpress.org/extend/plugins/woocommerce-esewa/installation/).

= Questions? =

You can answer you questions using:

* Our Session [FAQ](http://wordpress.org/extend/plugins/woocommerce-esewa/faq/).
* Creating a topic in the [WordPress support forum](http://wordpress.org/support/plugin/woocommerce-esewa) (English only).

== Installation ==

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to WooCommerce -> Settings -> Payment Gateways, choose eSewa and fill in your eSewa Service Code.

== Frequently Asked Questions ==

= What is the plugin license? =

* This plugin is released under a GPL license.

= What is needed to use this plugin? =

* WooCommerce version 2.3 or later installed and active.
* Have a Merchant account on [eSewa](https://www.esewa.com.np/).
* Ask/Get a **Service Code** for Merchant account from eSewa Digital Payment Portal.

= eSewa receives payments from which countries? =

At the moment the eSewa receives payments only from Nepal.

Configure the plugin to receive payments only users who select Nepal in payment information during checkout.

= I installed the plugin, but the eSewa payment option during checkout is invisible. What did I do wrong? =

You forgot to select the Nepal during registration at checkout. The eSewa payment option works only with Nepal.

= The request was paid and got the status of "processing" and not as "complete", that is right? =

Yes, this is absolutely right and means that the plugin is working as it should.

All payment gateway in WooCommerce must change the order status to "processing" when the payment is confirmed and should never be changed alone to "complete" because the request should go only to the status "finished" after it has been delivered.

For downloadable products to WooCommerce default setting is to allow access only when the request has the status "completed", however in WooCommerce settings tab Products you can enable the option "Grant access to download the product after payment" and thus release download when the order status is as "processing."

== Screenshots ==

1. Settings page.
2. Checkout page.

== Changelog ==

= 1.0 =

* Initial plugin version.

== License ==

WooCommerce eSewa is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

WooCommerce eSewa is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with WooCommerce eSewa. If not, see <http://www.gnu.org/licenses/>.
