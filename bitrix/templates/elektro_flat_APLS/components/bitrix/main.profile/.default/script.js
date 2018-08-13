$( document ).ready(function() {
    $( document ).ready(function() {
        Inputmask("64777[9]{7}").mask($( "input[name*='UF_CARD_NUMBER']"));
    });

    $('.personal_tab_title').click(function () {
        var checkedId = $(this).attr('id');
        $('.personal_tab_title').removeClass('checked');
        $('.personal-info').css('display','none');
        $(this).addClass('checked');
        $('.'+checkedId).css('display','block');
        if (checkedId == 'personal_delivery') {
            $('.workarea.personal .btn_buy.ppp').css('display','none');
            $('.workarea.personal .btn_buy.popdef.bt3').css('display','none');
        } else {
            $('.workarea.personal .btn_buy.popdef.bt3').css('display','block');
        }
    });
});