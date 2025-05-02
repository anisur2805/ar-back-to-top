;(function ($) {
	"use strict";

	var visibleAfter = parseInt(arbtt_obj.btn_visible_after),
		fadeDuration = parseInt(arbtt_obj.fade_in);

	$(window).on("scroll", function () {
		if ($(this).scrollTop() > visibleAfter) {
			$(".arbtt").fadeIn();
		} else {
			$(".arbtt").fadeOut();
		}
	});

	$(".arbtt").on("click", function (e) {
		e.preventDefault();
		$("html, body").animate({ scrollTop: 0 }, fadeDuration);
	});
})(jQuery);
