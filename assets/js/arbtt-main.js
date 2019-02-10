/*global jQuery, $ */
jQuery("#arbtt_fi").parents('tr').hide();
jQuery("#arbtt_btnimg").parents('tr').hide();
jQuery("#arbtt_btntx").parents('tr').hide();
jQuery("#arbtt_btnimg_url").parents('tr').hide();
jQuery(document).ready(function ($) {
	"use strict";

	var x = $('#arbtt_btnst').val();
	selectedval(x);

	$('#arbtt_bgc, #arbtt_clr, #arbtt_bdclr').minicolors();

	$('#arbtt_btnst').on("change", function(){
		var abc = $(this).val();
		selectedval(abc);

	});

	function selectedval( abc ){
		if(abc==='fa'){
			jQuery("#arbtt_fi").parents('tr').show('blind');

			jQuery("#arbtt_btntx").parents('tr').hide();
			jQuery("#arbtt_btnimg").parents('tr').hide();

		}else if(abc==='txt'){
			jQuery("#arbtt_btntx").parents('tr').show('blind');

			jQuery("#arbtt_fi").parents('tr').hide();
			jQuery("#arbtt_btnimg").parents('tr').hide();
		}else if(abc==='img'){
			
			jQuery("#arbtt_btnimg").parents('tr').show('blind');
			// jQuery("#arbtt_btnimg_url").parents('tr').show('blind');

			jQuery("#arbtt_fi").parents('tr').hide();
			jQuery("#arbtt_btntx").parents('tr').hide();
		}else{
			jQuery("#arbtt_fi").parent().parent().hide();
			jQuery("#arbtt_btntx").parent().parent().hide();
			jQuery("#arbtt_btnimg").parent().parent().hide();
		}
	}




});