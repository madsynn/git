/*-----------------------------------------------------------------------------
Matrix Responsive WordPress Theme - Javascript Functions
-------------------------------------------------------------------------------
Version : 1.1
Date : 15 / 11 / 2012
Author : Billy Foo
-----------------------------------------------------------------------------*/
jQuery(document).ready(function($){
		
	//Navigation
	jQuery("ul#nav li").hoverIntent(function(){
		jQuery(this).children('ul').slideDown(300);
	},function(){
		jQuery(this).children('ul').slideUp(300);
	});
		// Create the dropdown base
		jQuery("<select />").appendTo("nav");
		
		// Create default option "Go to..."
		jQuery("<option />", {
		   "selected": "selected",
		   "value"   : "",
		   "text"    : "Go to..."
		}).appendTo("nav select");
		
		// Populate dropdown with menu items
		jQuery("nav a").each(function() {
		 var el = jQuery(this);
		 jQuery("<option />", {
			 "value"   : el.attr("href"),
			 "text"    : el.text()
		 }).appendTo("nav select");
		});
		
		jQuery("nav select").change(function() {
		  window.location = jQuery(this).find("option:selected").val();
		});
	
	
	//Create a function for tiles
	function matrix_tiles_init() {
	//Flexslider	
    jQuery('.flexslider').flexslider();
	
	//Fix Masonry container width error
	function matrix_fix_masonry() {
	var screenWidth = jQuery(window).width();
	if (screenWidth < 960) {
		jQuery('#mainpage-mos').width(screenWidth);
	} else {
		jQuery('#mainpage-mos').width(960);
	}
	
	if (screenWidth > 970){
		jQuery('#bloglist-left').width(640);
	} else if (screenWidth < 640) {
		jQuery('#bloglist-left').width(screenWidth);
	} else {
		jQuery('#bloglist-left').width(0.66 * screenWidth);
	}
	
	};
	matrix_fix_masonry();
	jQuery(window).resize(matrix_fix_masonry);
	
	//Masonry Settings
    jQuery('#content-mos').masonry({
      itemSelector : '.tile',
      columnWidth : 160,
	  isAnimated: true,
	  isFitWidth: true
    });
	
  	//Allow effects when hovering on tiles
    jQuery('.tile').not('.exclude').hover(function(){  
        jQuery('.tile').not(this).addClass('fade');
    },     
    function(){    
        jQuery('.tile').removeClass('fade');     
    });
	jQuery('.tile').append('<img class="tilehover" src="'+templateURL+'/images/tilehover.png" alt=" "/>');
		
	//Live-tile effects
	jQuery(".live").liveTile({pauseOnHover: true});
	
	//Lightbox
	var lbSRC ="";
	jQuery("a.lightbox").fancybox({
		'margin' : 0 ,
		'overlayColor' : '#000',
		'transitionIn': 'elastic',
		'transitionOut': 'elastic',
		'speedOut': 100,
		'cyclic' : true,
		//Lightbox iframe fix
		'onComplete': function() {
		lbSRC = jQuery('#fancybox-content').find('iframe').attr('src');
		postID = jQuery('#fancybox-content').find('article').attr('id');
		jQuery('#fancybox-content > div').css('overflow','hidden');
		},
		'onClosed': function() {
		jQuery('#'+postID).find('iframe').attr('src',lbSRC);
		}
	});
	
	}//end matrix_tiles_init();
	
	matrix_tiles_init(); //run the function when page is ready
	
	//Toggle
	jQuery(".toggle-button").click(function(){
		jQuery(this).next("div.toggle-content").slideToggle("slow");
		jQuery(this).children('.toggle-indicator').text(jQuery(this).children('.toggle-indicator').text() == '+' ? '-' : '+');
	});
	
	//Accordion
	jQuery(".accordion .ac-tab").click(function(){
		jQuery(this).next("div.toggle-content").slideToggle("slow").siblings("div.toggle-content").slideUp("slow");
		jQuery(this).children('.toggle-indicator').text('-')
		jQuery(this).siblings().children('.toggle-indicator').text('+');
	});
	
	//Table
	jQuery(".table-info ul li").hoverIntent(function(){
		jQuery(this).next("li.table-details").slideDown("slow").siblings("li.table-details").slideUp("slow");
		jQuery(this).children('.toggle-indicator').text('-')
		jQuery(this).siblings().children('.toggle-indicator').text('+');
	},function(){
		
	});
	jQuery(".table-info ul").mouseleave(function(){
		jQuery(this).children('li.table-details').slideUp('slow');
		jQuery(this).find('.toggle-indicator').text('+');
	});
	
	//Post Meta
	var pathname = window.location.protocol + "//" + window.location.host + "" + window.location.pathname;
	// Get Number of Facebook Shares
    jQuery.getJSON('http://graph.facebook.com/?id='+pathname+'',
        function(data) {
			if( data.shares == undefined){
            jQuery('.meta2 .count').prepend('0');
			}else{
			jQuery('.meta2 .count').prepend(data.shares);
			}
    });

    // Get Number of Tweet Count
    jQuery.getJSON('http://urls.api.twitter.com/1/urls/count.json?url='+pathname+'&callback=?',
        function(data) {
            jQuery('.meta3 .count').prepend(data.count);
    });
	
	
	//Bloglist
	jQuery('.bloglist').hover(function(){
		jQuery(this).css({'margin-top' : '-5px'});
	},
	function(){
		jQuery(this).css({'margin-top' : '0'});
	});
		
	//Organic Tabs
	jQuery("#demo-button").organicTabs();
	jQuery("#demo-tab").organicTabs();
	jQuery(".orgTab").organicTabs();
    
	//Check for placeholder
	// Released under MIT license: http://www.opensource.org/licenses/mit-license.php
	jQuery('[placeholder]').focus(function() {
	  var input = jQuery(this);
	  if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	  }
	}).blur(function() {
	  var input = jQuery(this);
	  if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	  }
	}).blur().parents('form').submit(function() {
	  jQuery(this).find('[placeholder]').each(function() {
		var input = jQuery(this);
		if (input.val() == input.attr('placeholder')) {
		  input.val('');
		}
	  })
	});
	
	// Contact Page Validation
	jQuery("#contactForm").validate();
	
	//AJAX Pagination
	jQuery('.ajax-pagination a').live('click', function(e){ //check when pagination link is clicked and stop its action.
		e.preventDefault();
		var link = jQuery(this).attr('href'); //Get the href attribute
		jQuery("#loader").fadeIn(300); // show the loader animation
		jQuery.ajax({
            url: link,
			dataType: "html",
            context: document.body,
			cache: "false",
			beforeSend: function(){jQuery('#mainpage-mos').fadeOut(500)},
            success: function(html) {
				var newhtml = jQuery('#mainpage-mos', jQuery(html));
				jQuery('script', newhtml).each(function() {
					jQuery.globalEval(jQuery(this).text());
				});
				jQuery('#mainpage-mos').html(newhtml);
				jQuery('#mainpage-mos').fadeIn(500);
				matrix_tiles_init(); // run this function first
				var screenheight = jQuery(window).height();
				jQuery('#container').css('min-height', screenheight);
				// Remove .lb-video style for iframe elements
				jQuery('iframe').parent('.lb-video').removeAttr("style");
				jQuery("#loader").fadeOut(300); //hide the loader

            },
			error: function() {
				alert('Error');
			}
        });
		});
 	
	// jplayer hide buttons on resize
	jQuery('a.jp-full-screen').click(function(){
		jQuery('a#fancybox-left, a#fancybox-close, a#fancybox-right').hide();
	});
	jQuery('a.jp-restore-screen').click(function(){
		jQuery('a#fancybox-left, a#fancybox-close, a#fancybox-right').show();
	});
	
	// Fix background pattern
	var screenheight = jQuery(window).height();
	jQuery('#container').css('min-height', screenheight-50);
	
	// Remove .lb-video style for iframe elements
	jQuery('iframe').parent('.lb-video').removeAttr("style");
	
});