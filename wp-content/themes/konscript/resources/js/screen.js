jQuery.noConflict();
(function($) {

	// Base site url from WP html head
	var base = $("base").attr("href");
	
	// To be run when DOM is loaded. Initializes all effects and behaviours
	//$(window).load(function() {	
	$(document).ready(function() {
		// Masonry Grid effect
		initMasonryEffect();
		
		// Global stuff
		primaryMenuEvents();
		masterbarLinkHoverEvent();
		
		// Specific	stuff
		nivoProductImages();
		addToCartEvent();		
		emailFormFocusClear();
	
	});

	/**
	 * Removes the help-text when newsletter form is focused
	 */	
	function emailFormFocusClear() {
		$('form#mc-embedded-subscribe-form input[type="text"]').live('focus', function() {
			if($(this).val() == 'Fill in e-mail') {
				$(this).val('');
			}
		});
	}
	
	/**
	 * When item is added to cart, it will show shopping bag with incremented count and bounce links
	 */
	function addToCartEvent() {
		$("form.product_form .wpsc_buy_button").click(function() {
			
			var breakOut = false;
			var selectedVariations = $("select.wpsc_select_variation option:selected");
			
			selectedVariations.each(function() {
				if ($(this).val() == 0) {
					breakOut = true;
				}
			});
			if ($('.wpsc_buy_button').is(':disabled') == true) {
				breakOut = true;
			}			
			
			if (breakOut == false) {
			
				$(".masterbar-goshop").hide();
				$(".masterbar-shoppingbag").show();
				
				var bagCount = parseInt($(".masterbar-shoppingbag span").html());
				if(isNaN(parseInt($(".masterbar-shoppingbag span").html()))) { bagCount = 0; }
				
				$(".masterbar-shoppingbag span").html(bagCount + 1);
				bounceEffect('.masterbar-shoppingbag');
							
				$('.wpsc_buy_button_container input').val('Added to Shopping Bag!');
				bounceEffect('.wpsc_buy_button_container input');			
			
			} else {
				$('.wpsc_buy_button').attr('disabled', 'disabled');
			}
		});
	}
	
	/**
	 * Makes the masterbar links bounce when hovered
	 */
	function masterbarLinkHoverEvent() {
		$("#masterbar a").hover(
			function () {
				bounceEffect(this);			
			}, function () {});
	}
	
	/**
	 * Run a bounce effect on the supplied element
	 */
	function bounceEffect(element) {
    	if ( !$(element).is(':animated') ) { 		  	
	        $(element).css('position','relative');
	        $(element).animate({'top': '-=3px'}, 50, function(){
	            $(element).animate({'top': '+=6px'}, 100, function(){
	                $(element).animate({'top': '-=6px'}, 100, function(){
	                    $(element).animate({'top': '+=6px'}, 100, function(){
	                        $(element).animate({'top': '-=3px'}, 50);
	                    });
	                });
	            });
	        });
		}	
	}
	
	/**
	 * Make WPEC product pages feature a image gallery based on Nivo jQuery plugin
	 */
	function nivoProductImages() {

		$(window).load(function() {
			//var rand = Math.floor(Math.random()*total);			
			var total = $('#nivo_product_images img').length;
			if(total > 1) {
				$('#nivo_product_images').nivoSlider({
					effect: 'fade', //Specify sets like: 'fold,fade,sliceDown'
					animSpeed: 600, //Slide transition speed
					pauseTime: 6000,
					directionNav: true, //Next and Prev
					controlNav: false, //1,2,3...
					pauseOnHover: false, //Stop animation while hovering
					captionOpacity: 0, //Universal caption opacity
					startSlide: 0, //Set starting Slide (0 index)
					directionNavHide: false,
					keyboardNav: true,
					manualAdvance: false,
					prevText: '‹', // Prev directionNav text
					nextText: '›', // Next directionNav text
				});
			}	
		});
	}
	
	
	/**
	 * Bind the primary menu effects to events
	 */
	function primaryMenuEvents() {
		
		// Only run on masonry-enabled grid pages
		if ($('#grid').length != 0) {
			$("#primary-menu > li.menu-item-type-taxonomy > a, #site-title a").click(function() {
				primaryMenuEffect(this);
			});	
		}		
	
	}
	
	/**
	 * Run the primary menu effect on the supplied item
	 */
	function primaryMenuEffect(activateMenuItem) {
	
		// Only run if the clicked menu item is not already the current one
		if ($(activateMenuItem).next("ul.sub-menu.current").length == 0 && ($(activateMenuItem).next("ul.sub-menu").length != 0 || $(activateMenuItem).attr("href") == "#all")) {
			
			// Collapse the previous current if the previous current is not a parent of the current
			if ($(activateMenuItem).parents('.current').length == 0) {
    			$(".current").animate(
    				{width: "1", opacity: 0}, 
    				{queue: false, duration: 1000, complete: function() {
    					$(this).removeAttr('style');
    					$(this).removeClass("current");
    				}
    			});
    			alert("Menu debug: Level 1");
    		// If a sibling has an open sub-menu, collapse it.
		    } else if ($(activateMenuItem).parent('li').siblings('li').children('.current').length != 0) {
		        var parentSiblings = $(activateMenuItem).parent('li').siblings('li');
		        parentSiblings.children('.current').animate(
    				{width: "1", opacity: 0}, 
    				{queue: false, duration: 1000, complete: function() {
    					$(this).removeAttr('style');
    					parentSiblings.children('.current').removeClass('current');
    					parentSiblings.find('ul').css('opacity', '0');      // Important to reset this, otherwise fading won't happen the second time.
                		parentSiblings.find('ul').css('display', 'none');   // Important to reset this, otherwise sliding won't happen the second time.
    				}
    			});
    			alert("Menu debug: Level 2");
		    }
		    
		    var $current = $(activateMenuItem).next("ul.sub-menu");	
			// Expand the new current (that is clicked)
			$current.addClass("current").animate({width: "show", opacity: 1}, {queue: false, duration: 1000});
			// Add new CSS class to (sub-)*sub-menus
			addMenuLevelClass($current);
			// Hide sub-menus to current menu (otherwise, fade in and slide effects will not happen for these, as they have already been applied once)
			$current.find('ul').css('opacity', '0');
    		$current.find('ul').css('display', 'none');
		}
	
	}
	
	/**
	 * Adds a CSS class to currentMenu.
	 * A menu with depth 2 will get class 'sub-sub-menu', level 3 'sub-sub-sub-menu' and so forth.
	 */
	function addMenuLevelClass(currentMenu) {
	    var subMenuParents = currentMenu.parents('ul.sub-menu').length;
		if (subMenuParents > 0) {
		    var i = subMenuParents;
		    var classString = "sub-menu";
		    while (i > 0) {
		        classString = "sub-" + classString
		        i--;
		    }
		    currentMenu.addClass(classString);
		}
	}
	
	/**
	 * ------------------------------------------------------
	 * Masonry Grid Effect
	 * ------------------------------------------------------	 
	 */
	 
	// Global settings
	var gridContainer = "#grid";
	var gridElement = "div.box";
	var gridElementSpecific = gridContainer + " " + gridElement;
	var menuGridButton = "a.gridButton";
	var loadingIcon = "#loader";
	var gridColumnWidth = 40;
	var elmBusy = false;
	var gridFirstTime = true;
	
	// To be run first!!! Will initiate the effect and keep track of changes etc.
	function initMasonryEffect() {
	
		$allElm = $(gridElementSpecific); // Get all elements from DOM and set allElm variable
		$allElm.hide(); // Hide html elements prelimenary 
		$allElm.css('position', 'absolute'); // Positions elements absolutely
		
		// If the grid is present (#grid has elements), do masonry
		if ($allElm.length != 0) {
		
			// Check if site is accessed correctly through the hashes (for index pages). If not, redirect to home and set hash route.
			if ($('body.home').length == 0) { 
				var newUrl = hashizeUrl(window.location.href);
				setUrl(newUrl);
				
			// If no hash is set, initialize splash screen with all items
			} else if (getHash() == '') {
				setHash('all');
			
			// Page is ready for masonry
			} else if (getHash() == 'shop') { 
				setHash('category-shop');				
			} else {
				prepareMasonry();
			}
			
			// Change hash on click
			$('li.menu-item-type-taxonomy > a').click(function() {
				var category = hashizeUrl($(this).attr("href"), true);
//				category = category.replace(/\//g,"-");
				setHash(category);
				return false;
			});			
	
			// Watch for hash change and do masonry when changed
			$(window).hashchange(function() {
			
				// Alert Google Analytics that new async page has been called (converted to non-hashed url) and track with _gaq
				if (checkGoogleAnalyticsLoaded()) {
					var trackUrl = antiHashizeUrl(getHash(), true, false);
					if (trackUrl == "all") { trackUrl == ""; }
					var trackLocation = '/' + trackUrl;					
					_gaq.push(['_trackPageview', trackLocation]);
				}

				prepareMasonry();
			});		
			
		// The grid is not present, wait for clicks
		} else {
				
			// Change hash on click
			$('li.menu-item-type-taxonomy > a').click(function() {
				var category = hashizeUrl($(this).attr("href"), true);
//				category = category.replace(/\//g,"-");		
				var url = base + "#" + category;			
				setUrl(url);
				return false;
			});						
		}
	
	}
	
	// Performs pre-masonry actions before the effect is run by adding/removing necessary elements
	function prepareMasonry() {
		$(loadingIcon).fadeIn("fast"); // Show the loading icon
		elmBusy = true;
	
		// Get the current category and attempt to find the link in primary-menu that it should correspondingly open
		var category = getHash();
		var searchUrl = antiHashizeUrl(category, false, true);
		var possibleMenuItem = '#primary-menu li a[href="' + searchUrl + '"]';
		if($(possibleMenuItem).length != 0) {
			primaryMenuEffect(possibleMenuItem);	
		}
	
		// previous elements
		var $previousElm = $(gridElementSpecific);
		
		// If its the frontpage, impersonate the priority-frontpage category
		if (category == "all") {
			category = "priority-frontpage";
		}
		
		// remove elements which are not chosen from previous
		var $matchedElm = $(gridElement + "." + category);
		var $removeElm = $previousElm.not($matchedElm);

		// previous elements which are to be kept
		var $keptElm = $previousElm.filter($matchedElm);

		// get new elements - select all elm that have the corresponding
		// category and deselect all that are already there (from previous)
		var $newElm = $allElm.filter(gridElement + "." + category).not($keptElm);
		var $elmToBeRemoved = $previousElm.filter($removeElm);	
			
		// Check if there are any elements to remove
		if($elmToBeRemoved.size() > 0){		
			var counterRemoved = 0;				
			$elmToBeRemoved.fadeOut("slow", function() {
				$(this).remove();
				counterRemoved++;
				if ($elmToBeRemoved.length == counterRemoved) {
					bootstrapMasonry($newElm);																		
				}
			});
			
		// No elements to remove (e.g. going from subcat to topcat)
		} else {
			bootstrapMasonry($newElm);																		
		}
	}
		
	function bootstrapMasonry($newElm) {
		
		// Append new items and do masonry	
		$(gridContainer).prepend($newElm);
		
		if (gridFirstTime) {
			loadImages($(gridElementSpecific));
		} else {
			loadImages($newElm);
		}
		
		$(gridElementSpecific).tsort({attr:'rel', order:'desc'});	
		
		doMasonry();
	}
	
	function loadImages($elements){
//	$("#grid div.box a img").lazyload({ threshold : 1, failurelimit : 1, effect : "fadeIn" });
		$elements.each(function(){
			var $img = $(this).find('a img');
			if ($img.attr('data-original')) {
				$img.first().attr('src', $img.first().attr('data-original')).removeAttr('data-original');
			}
		});

	}	
	
	// Do masonry effect
	function doMasonry() {	
		$(gridContainer).masonry({
			columnWidth : gridColumnWidth,
			animate : true,
			itemSelector : gridElement,
			resizeable: true,
			animationOptions : {
				duration : 750,
				easing : 'swing',
				queue : true
			}
		});	
		
		//if there are no boxes we wont get a callback above
		if($(gridElementSpecific).size()==0){
			$(loadingIcon).fadeOut("fast");
		}

		// show the grid container again when masonry has been run
		$(gridContainer).css('opacity', 1);

		fadeInElm($(gridElementSpecific));	
	}
	
	// Fades in elements and removes the loader icon when done
	function fadeInElm($elm){
		var counterElm = 0;
		$elm.fadeIn("slow", function() {
			counterElm++;		
			if ($elm.length == counterElm) {
//				$elm.show();
				$(loadingIcon).fadeOut("fast");
				elmBusy = false;
			}			
		});
	}
	
	// Call to set a new route hash value
	function setHash(routeHash) {
		window.location.hash = routeHash; }
	// Returns the current hash value, i.e. current page to load
	function getHash() {
		var routeHash = window.location.hash;
		return routeHash.substring(1); }
	// Set the location to a specified full url
	function setUrl(url) {
		window.location = url; }
	
	// Gets a direct long url (typically to a cat or subcat) and hashize it from the base url
	// e.g. http://aquestionof.net/category/media/campaigns > http://aquestionof.net/#category-media-campaigns
	function hashizeUrl(url, relative) {
		if(!relative) { var relative = false }
		
		var currentUrl = url.replace(/\/$/,""); // Replaces the trailing slash if exists so it doesnt cause trouble when hashing
		var category = currentUrl.replace(base,"").replace(/\//g,"-"); // Make url relative and turn slashes to dashes			

		if (!relative) {
			return base + "#" + category; // Create new url with hash
		} else {
			return category; // Create new url with hash
		}
	}
	
	// If supplied by a hashed url (from hashizeUrl), it will attempt to convert it back to its static counterpart
	function antiHashizeUrl(url, relative, trailingslash) {
		if(!relative) { var relative = false }
		
		var currentUrl = url.replace(/\/$/,""); // Replaces the trailing slash if exists so it doesnt cause trouble when hashing
		
		if (trailingslash) {
			currentUrl = url + "/";
		}
		
		var category = currentUrl.replace(base,"").replace(/-/g,"/").replace(/#/g,""); // Make url relative and turn slashes to dashes			

		if (!relative) {
			return base + category; // Create new url with hash
		} else {
			return category; // Create new url with hash
		}
	}
	
	// Will check if site uses Google Analytics and return true/false
	function checkGoogleAnalyticsLoaded() {
		try {
			if (_gaq) {
				return true;
			} else {
				return false;
			}
		} catch(err) {
			return false;
		}
	}

})(jQuery)
