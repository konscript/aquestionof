<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('admin.css',__FILE__) ?>" />

<div class="style-emails wrap">

	<h2><?php _e("WP e-Commerce Style Emails"); ?></h2>
	<br />
	<div style="width:420px; border-bottom:1px solid #CCCCCC;">
		<div class='list-item create-instructions'>
			<?php _e("1. <b> Create </b> the <i>wpsc-email_style.php</i> wrapper template file in your theme:"); ?><br />
			<?php 
			$stylesheet_directory = get_bloginfo('stylesheet_directory'); 
			$stylesheet_directory = explode('wp-content',$stylesheet_directory);
			echo 'wp-content'.$stylesheet_directory[1];
			?>/<br />
			<br />
			<?php _e('Or copy a sample <i>wpsc-email_style.php</i> from this plugin\'s "theme" subfolder:') ?><br />
			<?php 
			$stylesheet_directory = plugins_url('theme/',__FILE__);
			$stylesheet_directory = explode('wp-content',$stylesheet_directory);
			echo 'wp-content'.$stylesheet_directory[1];
			//TODO: provide way to quickly copy sample files from this page
			?><br />
			<br />
			<?php echo __("Click ")."<a id='ecse_theme_editor_link' href='#'>".__("here")."</a>".__(" to use the in-page coding editor."); ?>
			<div id="expandable_tips_show" class="expandable_tips_control"><?php _e("Show docs"); ?><img src="<?php echo plugins_url('images/down_arrow_blue.gif',__FILE__) ?>" /></div>
			<div id="expandable_tips_hide" class="expandable_tips_control"><?php _e("Hide docs"); ?><img src="<?php echo plugins_url('images/up_arrow_blue.gif',__FILE__) ?>" /></div><br /><br />
			<div id="expandable_tips">
				<?php _e("This is a simple example of what you could put in your template file:"); ?><br />
				<pre id="default_theme_content">
&lt;html>&lt;body>
&lt;h2 style="color:#016E0F">&lt;?php echo ecse_get_email_subject(); ?>&lt;/h2>
&lt;?php echo ecse_get_email_content(); ?>
&lt;/body>&lt;/html>
				</pre>
				<?php _e('In addition, these are some'); ?> <a href="http://codex.wordpress.org/Conditional_Tags" target="_new"><?php _e('conditional tags');?></a> <?php _e('you can use'); ?>:<br />
				<pre>
ecse_is_purchase_report_email()
ecse_is_out_of_stock_email()
ecse_is_purchase_receipt_email()
ecse_is_order_pending_email()
ecse_is_order_pending_payment_required_email()
ecse_is_tracking_email()
ecse_is_unlocked_file_email()
ecse_is_test_email()
ecse_is_other_email()
				</pre>
				<?php _e('Want to get really fancy? This plugin now supports a') ?> <a href="http://codex.wordpress.org/Template_Hierarchy" target="_new"><?php _e('template hierarchy');?></a> <?php _e('for the wrapper, and content replacement for improved receipt emails!'); ?><br />
				<a href="<?php echo plugins_url('images/template_heirarchy.gif',__FILE__) ?>" target="_new"><img src="<?php echo plugins_url('images/template_heirarchy_thumb.gif',__FILE__) ?>" alt="Wrapper Template hierarchy" title="Wrapper Template hierarchy" /></a>
				<a href="<?php echo plugins_url('images/content_templating.gif',__FILE__) ?>" target="_blank"><img src="<?php echo plugins_url('images/content_templating_thumb.gif',__FILE__) ?>" alt="Content Templating" title="Content Templating" /></a>
				<br />
				<?php _e('Here are good references for designing html/css emails:'); ?> <a href="http://www.campaignmonitor.com/css/" target="_new">CampaignMonitor</a> | <a href="http://mailchimp.com/resources/guides/html/email-marketing-field-guide/" target="_new">MailChimp</a>
			</div>
			<?php do_action('ecse_options_page_create_instructions'); ?>
		</div>
		<div class='list-item preview-options'>
			<?php _e("2. <b>Preview</b> your styling. ") ?><br /><br /><?php _e("Live WPEC emails sent to an admin will automatically get styled, even when live styling is turned off. So feel free to run a test purchase with your admin email address, and preview exactly what customers will see."); ?><br /><br /><?php _e("You can also have a very simple styled email sent to you:"); ?><br />
			<?php 
				global $current_user;
				get_currentuserinfo();
			?>
			<form action="" method="post" id="email-style-testing-form" name="email-style-testing-form">
				<input type="text" value="<?php echo $current_user->user_email; ?>" name="send_email_address" />
				<input type="submit" value="Send a test styled email" name="send-email-submit" /><div id="ecse_preview_email_loading" class="esce_loading"></div> <br /><br />
				<?php _e('Or preview the email from your browser') ?>: &nbsp; <a href="<?php echo add_query_arg('ecse_preview','true'); ?>" id="ecse_activate_preview" target="_new"><?php _e('general email'); ?></a> | <a href="<?php echo add_query_arg(array('ecse_preview'=>'true','ecse_type'=>'receipt')); ?>" target="_new"><?php _e('purchase receipt'); ?></a>.<br />
				<?php _e('(Good for quick previews, but don\'t only rely on this. Email clients can render your html differently!)') ?>
				<?php if(!empty($_POST['send-email-submit'])) echo '<span class="sent-message">Sent.</span>'; ?>
			</form>
			<?php do_action('ecse_options_page_preview_options'); ?>
		</div>
		<div class='list-item turn-on-options'>
			<?php _e("3. <b>Turn on</b> the live email styling when you're satisfied."); ?><br />
			<form action="" method="post" id="email-style-options-form" name="email-style-options-form">
				<input type="checkbox" name="style_emails_store" id="style_emails_store" <?php if(get_option('ecse_is_active')) { echo 'checked="checked"';  }; ?> /><label for="style-emails-active"><?php _e("Style live customer emails"); ?></label><br />
				<input type="checkbox" name="style_emails_other" id="style_emails_other" <?php if(get_option('ecse_is_other_active')) { echo 'checked="checked"';  }; ?> /><label for="style-other-emails-active"><?php _e("Style all other website emails (contact forms, user registrations, etc)"); ?></label><br />
				<br />
				<?php _e('Exclude emails by subject. Enter each subject on its\' own line:') ?><br />
				<textarea class='ignore-subjects'><?php 
					function ecse_get_ignore_list() {
						$subjects = get_option('ecse_subjects_to_ignore');
						if( !empty($subjects) ) {
							foreach(unserialize(get_option('ecse_subjects_to_ignore')) as $row) {
								if(!empty($row)) {
									echo $row."\n";
								}
							}
						} 
					}
					ecse_get_ignore_list();
				?></textarea>
				<input type="submit" value="Save" name="save-options-submit" /><div id="ecse_options_loading" class="esce_loading"></div> 
				<?php if(!empty($_POST['save-options-submit'])) echo '<span class="sent-message">'.__('Saved').'.</span>'; ?>
			</form>
			<?php do_action('ecse_options_page_turn_on_options'); ?>
		</div>
	</div>
	
	
	<form action="" method="post" id="email-style-wish-form" name="email-style-wish-form">
		<h3><?php _e("Contact this plugin's developer"); ?></h3>
		<?php if(!empty($_POST['send-wish-submit'])) echo '<div class="sent-message">Sent.</div>'; ?>
		<textarea name="send_wish_content"><?php _e("Write some constructive criticism or positive feedback"); ?></textarea>
		<input type="submit" value="Send" name="send-wish-submit" id="send-wish-submit" /><div id="ecse_feedback_loading" class="esce_loading"></div> 
		<?php _e("or visit"); ?> <a href="http://schwambell.com"><?php _e("his website"); ?></a> | <a href="http://wordpress.org/extend/plugins/wp-e-commerce-style-email/">WP.org <?php _e('plugin page') ?></a> | <a href="http://wordpress.org/tags/wp-e-commerce-style-email">WP.org <?php _e('forums') ?></a>
	</form>
	
	<div id="ecse_theme_editor_container" class="ecse_overlay">
		<div id="ecse_theme_editor_inner" class="inner">
			<h2><?php _e('Theme Template File'); ?></h2>
			<textarea id="ecse_theme_editor"></textarea><br />
			<div id="ecse_theme_editor_controls">
				<input type="submit" id="ecse_theme_editor_submit" value="<?php _e('save'); ?>" class="submit_button"><div id="ecse_theme_editor_loading" class="esce_loading"></div> 
				<a href="#" id='ecse_theme_editor_revert'><?php _e('Revert'); ?></a> | 
				<a href="#" id='ecse_theme_editor_cancel'><?php _e('Cancel'); ?></a>
			</div>
		</div>
	</div>
	
	
	<div id="ecse_notifications">
		<div id="esce_notifications_inner"></div>
	</div>
	
	<script type="text/javascript">


	// GUI  //
	
	jQuery(document).ready(function(){

		//hide all the loading indicators. This cant be done with load-time CSS because they need to have display:inline-block before being hidden by jQuery
		jQuery('.esce_loading').hide();
		//set the loading GIF. This is done here so the page doesn't look weird if javascript or jQuery is not functioning. Probably unneccessary, but I'm fancy like that.
		jQuery('.esce_loading').css('background-image','url("<?php echo plugins_url('images/loading-publish.gif',__FILE__); ?>")');
		//copy the tips into the editor sidebar (in the future)
		//jQuery('#ecse_theme_editor_inner .col2').html( jQuery('#expandable_tips').html() );
		
		//fancify the feedback box
		setFeedbackBean(jQuery('#email-style-wish-form textarea').val());
		jQuery('#email-style-wish-form textarea').focus(function() {
		    if(jQuery(this).val()==getFeedbackTempBean()) jQuery(this).val('');
		});
		jQuery('#email-style-wish-form textarea').blur(function() {
		    if(jQuery(this).val()=='') jQuery(this).val(getFeedbackTempBean());
		});

		//AJAXify the feedback
		jQuery('#email-style-wish-form').submit(function() {
			var myVal = jQuery('#email-style-wish-form textarea').val();
			SendFeedback(myVal);
			return false;//so the page doesn't reload
		});

		//AJAXify the settings
		jQuery('#email-style-options-form').submit(function() {
			var option_values = Array();
			option_values['style_active'] = jQuery('#style_emails_store:checked').val();
			option_values['style_other'] = jQuery('#style_emails_other:checked').val();
			option_values['ignore_list'] = jQuery('.turn-on-options textarea.ignore-subjects').val();
			SaveOptions(option_values);
			return false;//so the page doesn't reload
		});

		//AJAXify the preview email sender
		jQuery('#email-style-testing-form').submit(function() {
			var email_address = jQuery('#email-style-testing-form input:text').val();
			SendPreview(email_address);
			return false;//so the page doesn't reload
		});

		//links to expand or contract the editing tips
		jQuery('#expandable_tips_show').click(function() {
			jQuery('#expandable_tips').slideDown();
			jQuery('#expandable_tips_show').hide();
			jQuery('#expandable_tips_hide').css('display', 'inline-block');
		});
		jQuery('#expandable_tips_hide').click(function() {
			jQuery('#expandable_tips').slideUp();
			jQuery('#expandable_tips_show').show();
			jQuery('#expandable_tips_hide').hide();
		});
		
		//link to activate the editor
		jQuery('#ecse_theme_editor_link').click(function() {
			jQuery('#ecse_theme_editor_loading').hide();
			jQuery('#ecse_theme_editor').val('<?php _e('loading'); ?>...');
			getThemeFile();
			jQuery('#ecse_theme_editor_container').fadeIn('slow');
		});

		//editor's cancel link or click outside
		jQuery('#ecse_theme_editor_container').click(function(event) { //clicking outside the editor
			if( event.target == this ) {
				if( hasChanges('<?php _e("Are you sure you want to close the editor? You will lose any unsaved changes"); ?>') ) jQuery(this).fadeOut('slow');
			}
		});
		jQuery('#ecse_theme_editor_cancel').click(function() { //clicking the cancel link
			if( hasChanges('<?php _e("Are you sure you want to close the editor? You will lose any unsaved changes"); ?>') ) jQuery('#ecse_theme_editor_container').fadeOut('slow');
		});

		//editor's revert link
		jQuery('#ecse_theme_editor_revert').click(function() {
			if( hasChanges('<?php _e("Are you sure you want to revert? You will lose any unsaved changes"); ?>') ) {
				jQuery('#ecse_theme_editor').val('<?php _e('loading'); ?>...');
				getThemeFile();
			}
		});

		//editor's save button
		jQuery('#ecse_theme_editor_submit').click(function() {
			saveThemeFile();
		});

		
	});

	//general 3-second notifications
	function display_notification(msg) {
		jQuery('#ecse_notifications').hide();
		jQuery('#esce_notifications_inner').html(msg);
		jQuery('#ecse_notifications').stop(true,true).slideDown().delay(3000).slideUp()
	}



	

	// SERVER INTERFACE //
	
	function saveThemeFile() {
		jQuery('#ecse_theme_editor_loading').show();
		var data = {
			action: "ecse_save_theme_file",
			file_content: jQuery('#ecse_theme_editor').val()
		};
		setThemeTempBean(data.file_content);
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			if(response=='false') {
				display_notification('<?php _e("There was a problem saving your theme file");?>');
			} else {
				display_notification('saved');
				var temp_content = getThemeTempBean();
				if(temp_content!=false) {
					setThemeDataBean(temp_content);
					setThemeTempBean(false);
				}
			}
			jQuery('#ecse_theme_editor_loading').hide();
		});
	}

	function getThemeFile() {
		var data = { action: "ecse_get_theme_file" };
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			if(response=='false') { //theme file does not yet exist
				response = html_decode(jQuery('#default_theme_content').html());
				display_notification('<?php _e("Click SAVE to create your theme file");?>');
			}
			response = html_decode(response);
			setThemeDataBean(response);
			jQuery('#ecse_theme_editor').val(response);
			//alert(jQuery('#ecse_theme_editor').val());
		});
	}

	function SendFeedback(feedback) {
		jQuery('#ecse_feedback_loading').show();
		var data = {
			action: "ecse_send_feedback",
			send_wish_content: feedback
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			var notification = 'Message sent';
			if(response=='false') notification='<?php _e("There was a problem sending that message");?>';
			display_notification(notification);
			jQuery('#ecse_feedback_loading').hide();
		});
	}

	function SaveOptions(option_values) {
		jQuery('#ecse_options_loading').show();
		var data = {
			action: "ecse_save_settings",
			style_emails_store: option_values['style_active'],
			style_emails_other: option_values['style_other'],
			style_emails_ignore: option_values['ignore_list']
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			var notification = 'Settings saved';
			if(response=='false') notification='<?php _e("There was a problem saving the settings");?>';
			display_notification(notification);
			jQuery('#ecse_options_loading').hide();
		});
	}

	function SendPreview(email_address) {
		jQuery('#ecse_preview_email_loading').show();
		var data = {
			action: "ecse_send_preview",
			send_email_address: email_address
		};
		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			var notification = 'Preview sent';
			if(response=='false') notification='<?php _e("There was a problem sending that preview");?>';
			display_notification(notification);
			jQuery('#ecse_preview_email_loading').hide();
		});
	}
	

	

	// DATA IN PAGE //
	
	function setThemeDataBean(myData) { // store the theme template file's saved state (string) for in-page comparison against edited content
		var myDiv = jQuery('#ecse_theme_editor')[0];
		jQuery.data(myDiv,'saved_data',myData);
	}

	function getThemeDataBean() { // return (string) data stored via previous function.
		var myDiv = jQuery('#ecse_theme_editor')[0];
		return jQuery.data(myDiv,'saved_data');
	}

	function hasChanges(msg) { //check to see if there are changes to the saved version of the theme file. If there are, confirm that it is ok to abandon the changes.
		var proceed=true;
		if( getThemeDataBean() != jQuery('#ecse_theme_editor').val() ) proceed = confirm(msg);
		return proceed;
	}

	function setThemeTempBean(myData) { // store the theme template file's saved state (string) for in-page comparison against edited content
		var myDiv = jQuery('#ecse_theme_editor')[0];
		jQuery.data(myDiv,'temp_data',myData);
	}

	function getThemeTempBean() { // return (string) data stored via previous function.
		var myDiv = jQuery('#ecse_theme_editor')[0];
		return jQuery.data(myDiv,'temp_data');
	}

	function setFeedbackBean(myData) { // store the theme template file's saved state (string) for in-page comparison against edited content
		var myDiv = jQuery('#email-style-wish-form textarea')[0];
		jQuery.data(myDiv,'initial_data',myData);
	}

	function getFeedbackTempBean() { // return (string) data stored via previous function.
		var myDiv = jQuery('#email-style-wish-form textarea')[0];
		return jQuery.data(myDiv,'initial_data');
	}


	

	// UTILITY //
	
	function html_decode(text) { // undoes the effect of the php htmlentities() function
		return text.replace(/&lt;/gi,'<').replace(/&gt;/gi,'>').replace(/&quot;/gi,'"');
	}


	

	
	
	</script>
	
	
	
	
</div>