(function($) {
	$(function() {
		var $body = $('body'),
			$header = $('#header'),
			$nav = $('#nav'),
			$nav_a = $nav.find('a'),
			$wrapper = $('#wrapper');
		$('form').placeholder();
		skel.on('+medium -medium', function() {
			$.prioritize('.important\\28 medium\\29', skel.breakpoint('medium').active)
		});
		$('<div id="titleBar">' + '<a href="#header" class="toggle"></a>' + '<span class="title">' + $('#name').html() + '</span>' + '</div>').appendTo($body);
		$('#header').panel({
			delay: 500,
			hideOnClick: true,
			hideOnSwipe: true,
			resetScroll: true,
			resetForms: true,
			side: 'right',
			target: $body,
			visibleClass: 'header-visible'
		});
		if (skel.vars.os == 'wp' && skel.vars.osVersion < 10) $('#titleBar, #header, #main').css('transition', 'none')
	})
})(jQuery);