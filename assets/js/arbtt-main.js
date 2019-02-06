/*global jQuery, $ */
jQuery("#arbtt_fi").parent().parent().hide();
jQuery("#arbtt_btnimg").parent().parent().hide();
jQuery("#arbtt_btntx").parent().parent().hide();
jQuery("#arbtt_btnimg_url").parent().parent().hide();
jQuery(document).ready(function ($) {
	"use strict";
	$('#arbtt_bgc, #arbtt_clr, #arbtt_bdclr').minicolors();

	$('#arbtt_btnst').on("change", function(){
		var abc = $(this).val();
		if(abc==='fa'){
			jQuery("#arbtt_fi").parent().parent().show('blind');

			jQuery("#arbtt_btnimg").parent().parent().hide();
			jQuery("#arbtt_btntx").parent().parent().hide();

		}if(abc==='txt'){
			jQuery("#arbtt_btntx").parent().parent().show('blind');

			jQuery("#arbtt_fi").parent().parent().hide();
			jQuery("#arbtt_btnimg").parent().parent().hide();
		} if(abc==='img'){
			jQuery("#arbtt_btnimg").parent().parent().show('blind');

			jQuery("#arbtt_fi").parent().parent().hide();
			jQuery("#arbtt_btntx").parent().parent().hide();
		}
	});
});