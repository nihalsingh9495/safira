"use strict";
var safira_magnifier_vars;
var yith_magnifier_options = {
		sliderOptions: {
			responsive: safira_magnifier_vars.responsive,
			circular: safira_magnifier_vars.circular,
			infinite: safira_magnifier_vars.infinite,
			direction: 'left',
			debug: false,
			auto: false,
			align: 'left',
			height: 'auto',
			prev    : {
				button  : "#slider-prev",
				key     : "left"
			},
			next    : {
				button  : "#slider-next",
				key     : "right"
			},
			scroll : {
				items     : 1,
				pauseOnHover: true
			},
			items   : {
				visible: Number(safira_magnifier_vars.visible),
			},
			swipe : {
				onTouch:    true,
				onMouse:    true
			},
			mousewheel : {
				items: 1
			}
		},
		showTitle: false,
		zoomWidth: safira_magnifier_vars.zoomWidth,
		zoomHeight: safira_magnifier_vars.zoomHeight,
		position: safira_magnifier_vars.position,
		lensOpacity: safira_magnifier_vars.lensOpacity,
		softFocus: safira_magnifier_vars.softFocus,
		adjustY: 0,
		disableRightClick: false,
		phoneBehavior: safira_magnifier_vars.phoneBehavior,
		loadingLabel: safira_magnifier_vars.loadingLabel,
	};