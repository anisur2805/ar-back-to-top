/*global jQuery, $, window */
jQuery(document).ready(function ($) {
'use strict';
var sctop = object_name.a_value;
var sctoptime = object_name.sctoptime;
    //Check to see if the window is top if not then display button
    $(window).scroll(function () {
        if ($(this).scrollTop() > sctop) {
            $('.arbtt').fadeIn();
        } else {
            $('.arbtt').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.arbtt').click(function () {
        $('html , body').animate({scrollTop: 0}, sctoptime);
        return false;
    });
});