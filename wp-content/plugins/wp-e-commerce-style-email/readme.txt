=== WP e-Commerce Style Email ===
Contributors: mightyturtle
Tags: e-commerce, wp e-commerce, email, style, theme template
Requires at least: 3.2
Tested up to: 3.4.2
Stable tag: 0.6.2

Style the emails that WP e-Commerce sends to your customers.

== Description ==

	This plugin lets you style the automated emails that WP e-Commerce sends to your customers, using a simple theme template file.
It's a little like gift wrapping all your communications. 
To use this plugin, you don't need to know how WP e-Commerce does its emailing, nor necessarily any php.
You DO need to know how to edit your Wordpress theme, including what a theme template file is.
The plugin's settings page walks you through the rest.
This plugin works with the WP e-Commerce customer emails for:

*	Purchase Reports
*	Purchase Receipts
*	Order Pending
*	Order Pending: Payment Required
*	Shipping Tracking Notifications
*	Unlocked Files (for download purchases)

This plugin can also be used to style the other emails that Wordpress sends out (blog subscriptions, user registrations, contact forms, etc).

This plugin has an admin page where you can activate live email styling (after your private design testing) or send yourself a test styled email.
In order to maintain readability, line breaks in any original content are automatically replaced with &lt;br /> tags.

Here's a simple example of what you might put in the wpsc-email_style.php theme template file:

	<html><body>
	<h2 style="color:#016E0F"><?php echo ecse_get_email_subject(); ?></h2>
	<?php echo ecse_get_email_content(); ?><br />
	</body></html>
  
  
Of course, you'll want to add a lot more html, css and maybe even php to achieve your own style.
You may want to consult the 'nets about how html works inside emails.  

Are you familiar with the Wordpress template hierarchy theming system? 
This plugin creates a template hierarchy just for emails, 
allowing you to get fancy and separately style each kind of email.
Or you can just stick to the single wpsc-email_style.php theme template file and keep it simple.

Do you want to style customer receipt content beyond what WP e-Commerce allows in it's admin settings tab?
This plugin allows you to template the content of customer receipts to make them beautiful, 
and even includes a sample set of theme template files for you to copy and adapt as you wish.


The plugin's other home on the web is over at <a href="http://schwambell.com/wp-e-commerce-style-email-plugin/">Schwambell</a>.



== Installation ==

1. Install through the Wordpress 'Plugin' menu in Wordpress, or upload this plugin's folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Create the 'wpsc-email_style.php' template file in your theme and start styling. You can do this from the options page's editor if you wish.
There is also a set of sample theme template files in this plugin's wp-e-commerce-style-email/theme subfolder, if you want to copy and adapt.
4. Go to the 'Store Email Style' options page and turn on the live customer styling when you're done testing.

You can use this plugin without already having the WP e-Commerce plugin running on a Wordpress website.


== Frequently Asked Questions ==

= I just activated the plugin, but the emails don't look any different =

If you haven't created the 'wpsc-email_style.php' template file and gone to work on it, this plugin will do nothing to your emails.
Also, don't forget to go to the 'Store Email Style' options page and turn on the live customer styling when you're done testing.

= Can you help me with this? =

I am happy assist with your web design work - for a fee. Head over to my website, http://schwambell.com

= Where can I find out about styling emails with html? =

I think these are helpful references:

http://www.campaignmonitor.com/css/

http://www.htmlgoodies.com/beyond/css/article.php/3679231/How-to-Create-Great-HTML-Emails-with-CSS.htm

http://kb.mailchimp.com/article/how-to-code-html-emails

Otherwise, I guess you could try this:
http://lmgtfy.com/?q=html+style+emails

= What's a good way to test my email styling with this plugin? =

After you've created your styling theme template file, you could:

*	Create a fake customer with your email address on your e-Commerce website, and go through all the motions; or
*	Go to the 'Store Email Style' options page and click to see a browser preview, or have a test styled email sent to you;
*	While you're in testing phase, store emails (including customer receipts) that are sent to an admin email address will automatically be styled, so you can see what actual customers will see before they do.

= Why are linebreaks being removed from my product list? =

This issue only seems to crop up when you're not templating the receipt content, you're on WPEC pre-3.8.9, and you've got some HTML in the WPEC layout option for that content.

Why might it happen? When you turn on this plugin and apply style, the plugin will do its best job to make the plain text content look good in HTML.
It does so by converting plain text linebreaks to HTML tags.
But the plugin will only do that if it doesn't detect HTML in the content already.

So let's say you've put some HTML tags before or after your product list in the WPEC admin settings tab, where you layout the purchase receipt content.
My plugin will detect some HTML, and then it won't add any HTML of its own to the content. As a result the plain text linebreaks remain, and they don't mean much in an HTML email.

How might you overcome this issue? Try removing the HTML from the WPEC content. Or even better, use the content templating that this plugin enables. 
It's easy (you can copy sample templates from this plugin's folder) and your receipts will look better anyway.

This is only an issue before WPEC 3.8.9 though, because WPEC has started generating HTML product lists of its own, using tables.
And obviously if you're templating the content, you can put your own HTML layout on the product list.

= What's this about a fancy template hierarchy? =

This plugin allows you to be as complicated or as simple with your email wrapper templates as you wish.
You can have different styling for manager emails than from customer emails, 
and you can even have different styling just for particular kinds of customer emails.

A template hierarchy diagram can be found through this plugin's settings page.
The simplest way to style your emails is just to do it all in the 'wpsc-email_style.php' template file.

A sample 'wpsc-email_style.php' can be copied from this plugin's "theme" subfolder into your theme.

= And what's this about fancy content for customer receipts? =

This only applies to customer receipt emails (including order pending and payment required):

You can now structure the content of receipts using theme template files. 
This replaces what WP e-Commerce enables through it's settings->admin tab.
Content for all non-receipt emails still comes from WP e-Commerce, Wordpress or other plugins as appropriate.

To see what it's all about, copy the sample template files from this plugin's "theme" subfolder into your theme.
The sample files that I provide are a vast improvement over what WP e-Commerce gives you for receipts, 
and you may be happy just leaving them as they are. If you want to stay with what WP e-Commerce creates for receipts,
just remove my sample files from your theme.

Further explanation of the receipt templating system is found on this plugin's settings page.

= How is a wrapper template different to a content template? =

Wrapper templates create the gift wrapping around your content. The template hierarchy exists to allow
different kinds of emails to look completely different. For example, you may want a sidebar or a certain kind of
footer for customer emails, but a much simpler email layout for admin/management emails. That's what the wrapper template
heirarchy allows you to do. And even though the wrapper template heirarchy allows you to be very specific to certain kinds of emails,
wrapper templates are still very different to content templates.

Wrapper template files DO NOT HAVE ACCESS to structured content data - that's what content templates are for.

Content templating is very specific to purchase receipts (at least for now), and within a content template you
have access to detailed purchase data.

The sample template files bundled in this plugin's "theme" subfolder will point you in the right direction.

= I have some constructive criticism or pleasant feedback =

Head over to my website, http://schwambell.com and send me an email. Or use the Wordpress Plugin forums.
I'll try to get back to you in a businesslike fashion, but I can't promise immediate plugin revisions.


== Screenshots ==

1. The admin/settings page steps you through the process.


== Changelog ==

= 0.6.2 =
* Update subject-line detection for transaction reports / purchase reports (changed in WPEC 3.8.9)

= 0.6.1 =
* Fix bug that caused a rogue less-than sign to appear on purchase receipts.
* Fix content-type incompatibility with WPEC 3.8.9's new HTML email generation system

= 0.6 =
* Add template hierarchy to support separate styling of each kind of email;
* Add content templating for customer receipts;
* Add sample template files in plugin's "theme" subfolder;
* Add ability to exempt specific email subjects from styling (useful when using the style-all option);
* Add ability to preview general & purchase receipt email styles from within the browser;
* Improve detection of HTML within email contents, so linebreaks aren't added mistakenly;
* Jumping version numbers because it's a giant release!

= 0.4.8 =
* Fix incompatibility with wp-testimonials plugin.

= 0.4.1 =
* Fix charset mangling, which caused email subjects containing accented characters to not encode properly;
* Add theme template file editing and creating from the admin settings page;
* Add ability to apply styling to all other emails sent from a website (subscriptions, user registrations, etc);
* Test and all other emails eing styled are now sent from the same email address as WPEC emails;
* Improve the starter example;
* Refine Admin settings page content and formatting;
* AJAXify and jQuerify the admin settings page;
* Cheers to the several users who contacted me with ideas, tips and usability feedback.

= 0.3 =
* Add conditional tags to indicate what kind of WPEC email is being sent;
* Style WPEC emails sent to admins during testing phase;
* Internationalize the admin settings page for non-english speaking users;
* Remove charset specification (WP automatically sets the charset that is used on the website anyway) because it may impact non-english character sets. Cheers to Oscar Byhlinder for his help.

= 0.2 =
* Improve admin settings page.

= 0.1 =
* Initial public release.


== Upgrade Notice ==

= 0.6.1 =
A small update to 0.6. If you're on that version, this version doesn't introduce any changes - it just fixes a couple small bugs that impact purchase receipt emails.

= 0.6.2 =
A small update to 0.6. If you're on that version, this version doesn't introduce any changes - it just fixes a small bug that impacts style detection for purchase report emails.

