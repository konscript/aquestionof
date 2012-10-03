=== WP e-Commerce - Printable Invoices ===

Contributors: Michael Visser
Tags: e-commerce, wp-e-commerce, shop, cart, invoices
Requires at least: 2.9.2
Tested up to: 3.3.2
Stable tag: 1.6.2

== Description ==

Provide detailed sale invoices from WP e-Commerce to your customers in printed or electronic form.

For more information visit: http://www.visser.com.au/wp-ecommerce/

== Installation ==

1. Upload the folder 'wp-e-commerce-printable-invoices' to the '/wp-content/plugins/' directory

2. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

To customise the Printable Invoices template.

==== In WP e-Commerce 3.7 ====

1. Open Store > Printable Invoices from the WordPress Administration

==== In WP e-Commerce 3.8 ====

1. Open Settings > Printable Invoice from the WordPress Administration

====

To view the Printable Invoice for a Sale.

==== In WP e-Commerce 3.7 ====

1. Open Store > Sales from the WordPress Administration

==== In WP e-Commerce 3.8 ====

1. Open Dashboard > Store Sales from the WordPress Administration

====

2. Open the Sale detail view by clicking on the quantity of a given Sale

3. Under the Actions options click Print Invoice

That's it!

== Support ==

If you have any problems, questions or suggestions please join the members discussion on my WP e-Commerce dedicated forum.

http://www.visser.com.au/wp-ecommerce/forums/

== Changelog ==

= 1.6.2 =
* Added: Template tag wpsc_pi_get_checkout_field_value( $title, $type ) for outputting individual Checkout field values
* Added: Invoice Number field to Sale detail page
* Fixed: Previous Sale always linking to Order ID: #1
* Added: Controls to show/hide Checkout fields
* Added: Show/Hide Session ID
* Added: Replace Purchase ID with Invoice Number option
* Added: Class names to Cart columns
* Changed: Moved Next/Previous switch buttons to right side
* Added: Return to Sale button when viewing via WP Admin
* Fixed: Footer logo not showing
* Added: Order Status to template

= 1.6.1 =
* Fixed: Duplicate Purchase Receipt e-mails (thanks WASP Digital)
* Fixed: Limit Checkout form fields to default Checkout Set

= 1.6 =
* Added: Compatibility with WP e-Commerce 3.8.8

= 1.5.9 =
* Fixed: Tax not calculating for tax exclusive stores
* Added: Print button to Manage Sales

= 1.5.8 =
* Added: Filters for Theme/Plugin developers to extend Printable Invoices
* Added: Payment Method added to template
* Added: Session ID added to template
* Changed: Store Address now accepts multiple-lines
* Changed: Added example date format to Printed On date format

= 1.5.7 =
* Fixed: Checkout Headers dissapearing to the bottom
* Added: Side-by-side layout for Checkout form fields
* Added: Checkout form field label width
* Fixed: Tax column and Tax Total incorrect when using Tax Included
* Fixed: Purchase Receipt replacement uses Theme invoice file where available
* Changed: Removed unneccesary markup from Purchase Receipt template
* Added: Compatibility with WP e-Commerce Style Email
* Added: Print Invoice button to Manage Sales page (pending WPEC patch)
* Added: Next/Previous Sale buttons when viewing via WordPress Administration
* Added: print.css stylesheet when viewing via WordPress Administration

= 1.5.6 =
* Fixed: Turning off 'Replace Purchase Receipt' no longer overrides WP e-Commerce default

= 1.5.5 =
* Fixed: Formatting affecting Admin Report

= 1.5.4 =
* Fixed: WP e-Commerce 3.7 errors

= 1.5.3 =
* Added: Colour controls for background and content region

= 1.5.2 =
* Added: E-mail integration for Purchase Receipt
* Added: Link to Website link in HTML footer
* Fixed: Update notice appears when removing header logo from Settings
* Added: Adjust height/width of header logo
* Fixed: Hide shipping details if no shipping method is used
* Fixed: WP e-Commerce Plugins widget markup

= 1.5.1 =
* Fixed: Styling issue within Plugins Dashboard widget
* Added: Alt. switch to wpsc_get_action()

= 1.5 =
* Fixed: Issue introduced with wpsc_get_action()

= 1.4.9 =
* Fixed: First time activation not firing
* Fixed: Performance improvements for WP e-Commerce Plugins widget
* Added: wpsc_get_action() to common.php
* Added: Uninstall.php

= 1.4.8 =
* Changed: Reformatted readme.txt
* Changed: Moved settings template into /templates/admin/
* Added: Integrated Version Monitor into Plugin

= 1.4.7 =
* Overhauled support for logo and footer images
* Logo images are now stored in /wp-content/uploads/ with redundant support

= 1.4.6 =
* Moved ..._check_plugin_version() to functions.php
* Migrated Plugin prefix from 'vl_wpscpe' to 'wpsc_pi'
* Added common.php and template.php to separate Plugin and template functions

= 1.4.5 =
* Clean slashes from the header and footer texts

= 1.4.4 =
* Added support for show/hide Show Printed On..., Show Total Tax, Show Total Shipping
* Added support for Invoice Header, Header Text

= 1.4.3 =
* Added support for default Footer text on Plugin activation
* Fixed issue affecting Plugin update notification

= 1.4.2 =
* Added support for non-standard WordPress install directories

= 1.4.1 =
* Fixed missing Print Invoice link from Actions

= 1.4 =
* Added support for deleting header and footer logo
* Added footer text field with store template options
* Cleaned up Printable Invoices template
* Added SKU, Price and Quantity columns to cart table

= 1.3.4 =
* Replaced discount name with discount amount on invoice template

= 1.3.3 =
* Added support for child-WordPress themes
* Added support for per-Product tax pricing (Tax included/Tax)

= 1.3.2 =
* Fixed 'printable_invoices' directory creation within /wp-content/uploads/wpsc/upgrades/...
* Added support for WP e-Commerce 3.8
* Cleaned up source markup

= 1.3.1 =
* Added switch to change Admin menu placement in WP e-Commerce 3.7/3.8

= 1.3 =
* Added footer logo

= 1.2 =
* Switched hard-coded $ (dollar) currency symbol for default WP e-Commerce currency

= 1.1 =
* Added notes to invoice template

= 1.0 =
* Added notification of available updates on Plugin page
* First working release of the modification

== Disclaimer ==

This Plugin does not claim to be a PCI-compliant solution. It is not responsible for any harm or wrong doing this Plugin may cause. Users are fully responsible for their own use. This Plugin is to be used WITHOUT warranty.