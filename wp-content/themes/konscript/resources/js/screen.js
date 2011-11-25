jQuery.noConflict();
(function($) {

	// Base site url from WP html head
	var base = $("base").attr("href");
	
	// To be run when DOM is loaded. Initializes all effects and behaviours
	$(document).ready(function() {
		
		// Masonry Grid effect
		initMasonryEffect();
		
		// Global stuff
		primaryMenuEvents();
		//applyCufon();	
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
			}, function () {}
		);
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
			var total = $('#nivo_product_images img').length;
			var rand = Math.floor(Math.random()*total);
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
	    	    prevText: '<', // Prev directionNav text
    	    	nextText: '>', // Next directionNav text
			});
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
					
			// Collapse the previous current
			$("ul.sub-menu.current").removeClass("current").animate(
				{width: "1", opacity: 0}, 
				{queue: false, duration: 1000, complete: function() {
					$(this).removeAttr('style')
				}
			});
			
			// Expand the new current (that is clicked)
			$current = $(activateMenuItem).next("ul.sub-menu");
			$current.addClass("current").animate({width: "show", opacity: 1}, {queue: false, duration: 1000});
		}	
	
	}
			
	/**
	 * Will apply custom font with cufon for supplied texts
	 * DEPRECATED, Cufon is not used anymore, all font-face solution
	 */
	function applyCufon() {
	    if(isFontFaceSupported() != true) {
			Cufon.replace('#primary-menu li a');
			Cufon.replace('#masterbar-menu li a');
			Cufon.replace('.master-shoppingbag a');
			Cufon.replace('.entry-content h1');
			Cufon.replace('.entry-content .related-post-title');
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
				// Append new items and do masonry
				if ($elmToBeRemoved.length == counterRemoved) {
					$(gridContainer).prepend($newElm);				
					sortMasonry($newElm);								
					fadeInElm($(gridElementSpecific));
				}
			});
			
		// No elements to remove (e.g. going from subcat to topcat)
		} else {
			// Append new items and do masonry	
			$(gridContainer).prepend($newElm);		
			sortMasonry($newElm);
			fadeInElm($(gridElementSpecific));	
		}
	}
	
	// Fades in elements and removes the loader icon when done
	function fadeInElm($elm){
		var counterElm = 0;
		$elm.fadeIn("slow", function() {
			counterElm++;		
			if ($elm.length == counterElm) {
				$(loadingIcon).fadeOut("fast");
				elmBusy = false;
			}			
		});
	}
	
	function sortMasonry($elements) {
		
		$("#grid > div").tsort({attr:'rel', order:'desc'});	
/*
		var $previousElm = $("#grid > div:first");
		$("#grid > div.priority-featured").each(function(){
			var t = $(this);
			$previousElm.insertBefore(t);
			console.log(t);
		});
			
		$("#grid > div.priority-featured").tsort({attr:'rel', order:'desc'});	
				
		$("#grid > div").tsort('',{attr:'rel', order:'desc',sortFunction:function(a,b){
			return a.e.hasClass('priority-featured') < b.e.hasClass('priority-featured') ? 1 : 0;
			var iCalcA = parseInt(a.s)%16;
			var iCalcB = parseInt(b.s)%16;
			return iCalcA === iCalcB ? 0 : (iCalcA > iCalcB ? 1 : -1);
		}});	
*/	
		doMasonry();
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

	/**
	 * jQuery.fn.sortElements
	 * --------------
	 * @param Function comparator:
	 *   Exactly the same behaviour as [1,2,3].sort(comparator)
	 *   
	 * @param Function getSortable
	 *   A function that should return the element that is
	 *   to be sorted. The comparator will run on the
	 *   current collection, but you may want the actual
	 *   resulting sort to occur on a parent or another
	 *   associated element.
	 *   
	 *   E.g. $('td').sortElements(comparator, function(){
	 *      return this.parentNode; 
	 *   })
	 *   
	 *   The <td>'s parent (<tr>) will be sorted instead
	 *   of the <td> itself.
	 */
	jQuery.fn.sortElements = (function(){

	    var sort = [].sort;

	    return function(comparator, getSortable) {

	        getSortable = getSortable || function(){return this;};

	        var placements = this.map(function(){

	            var sortElement = getSortable.call(this),
	                parentNode = sortElement.parentNode,

	                // Since the element itself will change position, we have
	                // to have some way of storing its original position in
	                // the DOM. The easiest way is to have a 'flag' node:
	                nextSibling = parentNode.insertBefore(
	                    document.createTextNode(''),
	                    sortElement.nextSibling
	                );

	            return function() {

	                if (parentNode === this) {
	                    throw new Error(
	                        "You can't sort elements if any one is a descendant of another."
	                    );
	                }

	                // Insert before flag:
	                parentNode.insertBefore(this, nextSibling);
	                // Remove flag:
	                parentNode.removeChild(nextSibling);

	            };

	        });

	        return sort.call(this, comparator).each(function(i){
	            placements[i].call(getSortable.call(this));
	        });

	    };

	})();

})(jQuery)
