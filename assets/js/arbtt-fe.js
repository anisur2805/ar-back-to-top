/*global jQuery, $ */
jQuery(document).ready(function ($){

    //Check to see if the window is top if not then display button
    $(window).scroll(function(){
        if ($(this).scrollTop() > 100) {
            $('.arbtt').fadeIn();
        } else {
            $('.arbtt').fadeOut();
        }
    });

    //Click event to scroll to top
    $('.arbtt').click(function(){
        $('html, body').animate({scrollTop : 0},800);
        return false;
    });

});