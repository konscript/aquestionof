<?php
/**
 * Header Template
 *
 * Master template for head-section and body opening with generic elements
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>	
		<base href="<?php echo get_bloginfo( 'wpurl' ); ?>/" />
		<title><?php custom_document_title(); ?></title>
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=520,maximum-scale=1.0" />
		
		<link rel="shortcut icon" type="image/ico" href="<?php echo THEME_URI .'/resources/images/favicon.ico'; ?>" />
		<link rel="apple-touch-icon" href="<?php echo THEME_URI . '/resources/images/apple-touch-icon.png'; ?>" />
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo THEME_URI . '/resources/images/apple-touch-icon-72.png'; ?>" />
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo THEME_URI . '/resources/images/apple-touch-icon-114.png'; ?>" />		
		
		<?php wp_head(); // WP head hook ?>
				
		<?php customColors(); ?>
		<?php metaOpenGraph(); ?>
			
	</head>

	<body class="<?php hybrid_body_class(); ?> no-js">
	
	<script type="text/javascript">
		// will remove the no-js if javascript is enabled (if this snippet can run)		
		function removeClass(ele,cls) {
			if (ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'))) {
				var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
				ele.className=ele.className.replace(reg,' ');
			}
		}
		removeClass(document.body, "no-js")
	</script>
	
	<div id="masterbar-container" class="custom-color-1">
		<div id="masterbar">
				
			<div class="master-shoppingbag">
				<?php 
					$show_bag = false;
					if (wpsc_cart_item_count() > 0) { $show_bag = true; }
				?>
				<a href="#category-shop" class="masterbar-goshop custom-color-2" <?php if ($show_bag == true) { echo 'style="display: none;"'; } ?>>Go shopping!</a>
				<a href="<?php echo get_option('shopping_cart_url'); ?>" class="masterbar-shoppingbag custom-color-2" <?php if ($show_bag == false) { echo 'style="display: none;"'; } ?>>Shopping Bag ( <span><?php echo wpsc_cart_item_count(); ?></span> )</a>					
			</div>
		
			<?php wp_nav_menu(array(
				'theme_location'=> 'masterbar', 
				'container' 	=> false,
				'menu_class' 	=> 'masterbar-menu custom-color-2',
				'menu_id' 		=> 'masterbar-menu',								
				'link_before' 	=> ''
				)); ?>
				
			<div class="master-social">
				<a href="https://twitter.com/#!/a_question_of" target="_blank"><img src="<?php echo THEME_URI . '/resources/images/social/twitter_box.png'; ?>" alt="Twitter" /></a>
				<a href="http://consciousapparel.tumblr.com" target="_blank"><img src="<?php echo THEME_URI . '/resources/images/social/tumblr_box.png'; ?>" alt="Tumblr" /></a>								
				<a href="https://www.facebook.com/AQUESTIONOF" target="_blank"><img src="<?php echo THEME_URI . '/resources/images/social/facebook_box.png'; ?>" alt="Facebook" /></a>
			</div>				
				
		</div>
	</div>
	
	<div id="body-container">
		
		<div id="header-container">

			<img id="loader" style="display:none;" src="<?php echo THEME_URI . '/resources/images/loader.gif'; ?>"/>		
		
			<div id="header">
						
				<div id="site-title">
					<a class="gridButton" href="#all">
						<img src="<?php echo THEME_URI . '/resources/images/logo-new.png'; ?>" alt="A Question Of" />
						<h2><?php echo get_bloginfo( 'name' ); ?></h2>
					</a>
				</div>	
				
				<?php wp_nav_menu(array(
					'theme_location'=> 'primary',
					'container' 	=> false,
					'menu_class' 	=> 'primary-menu custom-color-2',
					'menu_id' 		=> 'primary-menu',
					'fallback_cb' 	=> false,
					'link_before' 	=> '/ '
					)); ?>
																					
			</div><!-- #header -->
		</div><!-- #header-container -->
		
		<div id="content-container">
