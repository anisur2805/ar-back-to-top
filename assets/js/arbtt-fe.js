jQuery( document ).ready(function ( $ ) {
'use strict';
var sctop = object_name.a_value;
var sctoptime = object_name.sctoptime;

    $(window).scroll(function () {
        if ($(this).scrollTop() > sctop) {
            $('.arbtt').fadeIn();
        } else {
            $('.arbtt').fadeOut();
        }
    });

    //console.log(sctoptime);
    $('.arbtt').click(function () {
        $('html , body').animate({scrollTop: 0}, sctoptime);
        return false;
    });
} );