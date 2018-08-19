$(document).ready(function () {
    getSwapLeftMenu();

    $('.menu-header').mouseover(function () {
        $('.menu-header-swap').css('color','#ef7f1a')
    });
    $('.menu-header').mouseout(function () {
        $('.menu-header-swap').css('color','#dee0ee')
    });

    function getSwapLeftMenu() {
        $('.menu-header-swap').click(function () {
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