BX.PromotionsCityChange = function() {
    var close;

    BX.PromotionsCityChange.popup = BX.PopupWindowManager.create("PromotionsCityChange", null, {
        autoHide: true,
        offsetLeft: 0,
        offsetTop: 0,
        overlay: {
            opacity: 100
        },
        draggable: false,
        closeByEsc: false,
        closeIcon: { right : "-10px", top : "-10px"},
        titleBar: {content: BX.create("span", {html: BX.message("CITY_CHANGE_WINDOW_TITLE")})},
        content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",
        events: {
            onAfterPopupShow: function()
            {
                if(!BX.findChild(BX("PromotionsCityChange"), {className: "bx-sls"}, true, false)) {
                    BX.ajax.post(
                        BX.message("CITY_CHANGE_COMPONENT_TEMPLATE") + "/popup.php",
                        {
                            arParams: BX.message("CITY_CHANGE_URL")
                        },
                        BX.delegate(function(result)
                            {
                                this.setContent(result);
                                var windowSize =  BX.GetWindowInnerSize(),
                                    windowScroll = BX.GetWindowScrollPos(),
                                    popupHeight = BX("PromotionsCityChange").offsetHeight;
                                BX("PromotionsCityChange").style.top = windowSize.innerHeight/2 - popupHeight/2 + windowScroll.scrollTop + "px";
                            },
                            this)
                    );
                }
            }
        }
    });

    BX.addClass(BX("PromotionsCityChange"), "pop-up city-change");
    close = BX.findChildren(BX("PromotionsCityChange"), {className: "popup-window-close-icon"}, true);
    if(!!close && 0 < close.length) {
        for(i = 0; i < close.length; i++) {
            close[i].innerHTML = "<i class='fa fa-times'></i>";
        }
    }

    BX.PromotionsCityChange.popup.show();
};
/*----------------------*/
/* AFTER LOAD DOCUMENTS */
/*----------------------*/
$(document).ready(function () {
    BX.bind(BX("CITY_CHANGE_BUTTON"), "click", BX.delegate(BX.PromotionsCityChange, BX));

    if ($("div").is(".PromotionBannerBlock")) {
        $("#pagetitle").addClass('imgTitle');
    }
});