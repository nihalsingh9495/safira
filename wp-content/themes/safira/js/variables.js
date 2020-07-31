		"use strict";
		var safira_brandnumber = 6,
			safira_brandscrollnumber = 1,
			safira_brandpause = 3000,
			safira_brandanimate = 2000;
		var safira_brandscroll = false;
							safira_brandscroll = true;
					var safira_categoriesnumber = 6,
			safira_categoriesscrollnumber = 2,
			safira_categoriespause = 3000,
			safira_categoriesanimate = 700;
		var safira_categoriesscroll = 'false';
					var safira_blogpause = 3000,
			safira_bloganimate = 700;
		var safira_blogscroll = false;
					var safira_testipause = 3000,
			safira_testianimate = 2000;
		var safira_testiscroll = false;
							safira_testiscroll = false;
					var safira_catenumber = 6,
			safira_catescrollnumber = 2,
			safira_catepause = 3000,
			safira_cateanimate = 700;
		var safira_catescroll = false;
					var safira_menu_number = 11;
		var safira_sticky_header = false;
							safira_sticky_header = true;
					jQuery(document).ready(function(){
			jQuery(".ws").on('focus', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".ws").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".wsearchsubmit").on('click', function(){
				if(jQuery("#ws").val()=="" || jQuery("#ws").val()==""){
					jQuery("#ws").focus();
					return false;
				}
			});
			jQuery(".search_input").on('focus', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".search_input").on('focusout', function(){
				if(jQuery(this).val()==""){
					jQuery(this).val("");
				}
			});
			jQuery(".blogsearchsubmit").on('click', function(){
				if(jQuery("#search_input").val()=="" || jQuery("#search_input").val()==""){
					jQuery("#search_input").focus();
					return false;
				}
			});
		});
		