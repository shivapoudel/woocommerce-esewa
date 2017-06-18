=== WooCommerce eSewa ===
Contributors: shivapoudel
Tags: woocommerce, esewa
Requires at least: 4.0
Tested up to: 4.8
Stable tag: 1.7.0
License: GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Adds eSewa as payment gateway in WooCommerce plugin.

== Description ==

= Add eSewa gateway to WooCommerce =

This plugin adds eSewa gateway to WooCommerce.

Please notice that [WooCommerce](https://wordpress.org/plugins/woocommerce/) must be installed and active.

= Introduction =

Add eSewa as a payment method in your WooCommerce store.

[eSewa](https://esewa.com.np/) is a Nepali Digital Payment Portal developed by fonepay. This means that if your store doesn't accept payment in NPR, you really do not need this plugin!!!

The plugin WooCommerce eSewa was developed without any incentive or eSewa Company. None of the developers of this plugin have ties to any of these two companies.

= Installation =

Check out our installation guide and configuration of WooCommerce eSewa tab [Installation](https://wordpress.org/plugins/woocommerce-esewa/installation/).

= Questions? =

You can answer your questions using:

* Our Session [FAQ](https://wordpress.org/plugins/woocommerce-esewa/faq/).
* Creating a topic in the [WordPress support forum](https://wordpress.org/support/plugin/woocommerce-esewa) (English only).

= Contribute =

You can contribute to the source code in our [GitHub](https://github.com/shivapoudel/woocommerce-esewa/) page.

== Installation ==

= Minimum Requirements =

* WordPress 4.0 or greater.
* WooCommerce 2.3 or greater.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of WooCommerce eSewa, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “WooCommerce eSewa” and click Search Plugins. Once you’ve found our payment gateway plugin you can view details about it such as the the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading our woocommerce esewa plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Updating =

Automatic updates should work like a charm; as always though, ensure you backup your site just in case.

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

= Where can I report bugs or contribute to the project? =

Bugs can be reported either in our support forum or preferably on the [WooCommerce eSewa GitHub repository](https://github.com/shivapoudel/woocommerce-esewa/issues).

= WooCommerce eSewa is awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](http://github.com/shivapoudel/woocommerce-esewa/) :)

== Screenshots ==

1. Settings page.
2. Checkout page.

== Changelog ==

= 1.6.0 =
* Tweak - Tested upto WP 4.8 & WooCommerce 3.0 :)

= 1.6.0 =
* Fix - Limit lengths of eSewa Args.
* Fix - Variable for logging eSewa args.
* Dev - Plugin Authorship to @shivapoudel.

= 1.5.0 =
* Fix - Plugins action settings link.
* Tweak - More logging for request args.
* Tweak - Define full path for includes.
* Tweak - Add support for WC_Logger pluggable via wc_get_logger function.

= 1.4.0 =
* Deprecated - PDT Check as IPN is sufficient for processing order.

= 1.3.0 =
* Tweak - Method description for gateway.
* Refactor - Correctly validated the IPN and PDT once again.
* Deprecated - Remove `plugin_path()` method in main plugin class.

= 1.2.2 =
* Fix - Payment description typo.
* Dev - PHP_CodeSniffer standard tweaks.

= 1.2.1 =
* Fix - Typo in readme file.
* Tweak - Improve load_plugin_textdomain method.
* Tweak - Escape success and failure using `esc_url_raw` for query args.

= 1.2.0 =
* Fix - 500 response on IPN fail
* Fix - Class reference in eSewa IPN handler
* Tweak - Improve the check for class single instance
* Tweak - Changed all requests with wp_remote_* to wp_safe_remote_*

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
