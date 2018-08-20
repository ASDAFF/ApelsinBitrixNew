$(document).ready(function () {
    getSwapLeftMenu();

    $('.menu_header_block').mouseover(function () {
        $('.menu_header_block .h3').css('color','#ef7f1a');
        $('.menu_header_block').css('background','transparent');
        $('.menu_header_block .menu-header-swap').css('color','#ef7f1a');
    });
    $('.menu_header_block').mouseout(function () {
        $('.menu_header_block').css('background','#ef7f1a');
        $('.menu_header_block .h3').css('color','#ffffff');
        $('.menu_header_block .menu-header-swap').css('color','#ffffff');
    });

    function getSwapLeftMenu() {
        $('.menu-header').click(function () {
            var checker = $('.menu-header-swap .fa-plus').css('display');
            if (checker == 'none') {
                $('.menu-header-swap .fa-plus').css('display','block');
                $('.menu-header-swap .fa-minus').css('display','none');
                $('.left-column .left-menu').hide('300');
            } else {
                $('.menu-header-swap .fa-plus').css('display','none');
                $('.menu-header-swap .fa-minus').css('display','block');
                $('.left-column .left-menu').show('300');
            }
        });
    }
});