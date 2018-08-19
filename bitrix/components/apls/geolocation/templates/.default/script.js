//CITY_CHANGE//
BX.CityChange = function() {
    var close;

    BX.CityChange.popup = BX.PopupWindowManager.create("cityChange", null, {
        autoHide: true,
        offsetLeft: 0,
        offsetTop: 0,
        overlay: {
            opacity: 100
        },
        draggable: false,
        closeByEsc: false,
        closeIcon: { right : "-10px", top : "-10px"},
        titleBar: {content: BX.create("span", {html: BX.message("GEOLOCATION_POPUP_WINDOW_TITLE")})},
        content: "<div class='popup-window-wait'><i class='fa fa-spinner fa-pulse'></i></div>",
        events: {
            onAfterPopupShow: function()
            {
                if(!BX.findChild(BX("cityChange"), {className: "bx-sls"}, true, false)) {
                    BX.ajax.post(
                        BX.message("GEOLOCATION_COMPONENT_TEMPLATE") + "/popup.php",
                        {
                            arParams: BX.message("GEOLOCATION_PARAMS")
                        },
                        BX.delegate(function(result)
                            {
                                this.setContent(result);
                                var windowSize =  BX.GetWindowInnerSize(),
                                    windowScroll = BX.GetWindowScrollPos(),
                                    popupHeight = BX("cityChange").offsetHeight;
                                BX("cityChange").style.top = windowSize.innerHeight/2 - popupHeight/2 + windowScroll.scrollTop + "px";
                            },
                            this)
                    );
                }
            }
        }
    });

    BX.addClass(BX("cityChange"), "pop-up city-change");
    close = BX.findChildren(BX("cityChange"), {className: "popup-window-close-icon"}, true);
    if(!!close && 0 < close.length) {
        for(i = 0; i < close.length; i++) {
            close[i].innerHTML = "<i class='fa fa-times'></i>";
        }
    }

    BX.CityChange.popup.show();
};