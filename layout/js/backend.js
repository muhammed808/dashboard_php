$(function () {

    'use strict';
    // dashbord

    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }else{
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        }
    });

    // inputs
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','')
    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    // deleation 
    $('.confirm').click(function() {
        return confirm('Are You Sure you want to Delete this');
    });
});


