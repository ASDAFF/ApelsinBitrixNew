$(document).ready(function () {
    var thisCity = $('.geolocation__value').html();
    thisCity = thisCity.replace(/\s+/g,'');

    /*Закрытие уже открытой вкладки*/
    $('.cityHeaderBlock_text').each(function () {
        if( $(this).html() == thisCity ) {
            console.log($(this).html());
            $(this).siblings(".cityHeaderBlock_icon").find('i').removeClass("fa-plus").addClass("fa-minus");
            var parentBlock = $(this).closest(".cityHeaderBlock");
            var cityBlock = $(this).closest(".cityBlock");
            parentBlock.find(".catalog-detail-store.header").show();
            cityBlock.find(".store_row").show();
        }
    });

    /*Открытие закрытие табов*/
    $('.cityHeaderBlock_icon').click(function () {
        var parentBlock = $(this).closest(".cityHeaderBlock");
        var cityBlock = $(this).closest(".cityBlock");
        if ($(this).find("i").hasClass("fa-minus")) {
            $(this).find("i").removeClass("fa-minus").addClass("fa-plus");
            parentBlock.find('.catalog-detail-store.header').hide("fast", function () {
                $(this).next().hide("fast", arguments.callee);
                cityBlock.find('.store_row').hide("fast", function () {
                    $(this).next().hide("fast", arguments.callee);
                });
            });
        } else if ($(this).find("i").hasClass("fa-plus")) {
            $('.catalog-detail-store.header').hide("fast", function () {
                $(this).next().hide("fast", arguments.callee);
                $('.store_row').hide("fast", function () {
                    $(this).next().hide("fast", arguments.callee);
                });
            });
            $(".cityHeaderBlock_icon").find("i").removeClass("fa-minus").addClass("fa-plus");
            $(this).find("i").removeClass("fa-plus").addClass("fa-minus");
            parentBlock.find('.catalog-detail-store.header').show("fast", function () {
                $(this).next().show("fast", arguments.callee);
                cityBlock.find('.store_row').show("fast", function () {
                    $(this).next().show("fast", arguments.callee);
                });
            });
        }
    });
});