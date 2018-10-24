$( document ).ready(function() {
    $(function($) {
        function fixMenu() {
            if ($(window).scrollTop() > 120)
                $('.top-menu').addClass("top-menu-fixed");
            else
                $('.top-menu').removeClass("top-menu-fixed");
        }
        $(window).scroll(fixMenu);
        fixMenu();
    });
});
$( document ).ready(function() {
    function fixSerch() {
        if ($(window).scrollTop() > 80) {

            $('#altop_search').addClass("altop-search-fixed");
            $('.title-search-result').addClass("title-search-result-fixed");
        } else {
            $('#altop_search').removeClass("altop-search-fixed");
            $('.title-search-result').removeClass("title-search-result-fixed");
        }
    }
    $(function($) {
        $(window).scroll(fixSerch);
        fixSerch();
    });
});