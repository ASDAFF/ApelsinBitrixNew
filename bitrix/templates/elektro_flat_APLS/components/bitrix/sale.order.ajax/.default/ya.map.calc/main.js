var DELIVERY_PRICE = 0;
var DESTINATION_COORDINATE = "";
var DESTINATION_STRING = "";
var TEST = 0;
var CART_DATA = [];

function yaMapCalcInit(currentDelivery) {
    // console.log(soAjax);
    // console.log(soAjax.deliveryBlockNode);
    if(currentDelivery.ID == "20") {
        DESTINATION_STRING = $("#soa-property-7").val();
        $("input[name~='DELIVERY_EXTRA_SERVICES[20][15]']").parent().hide();
        $("#soa-property-7").hide();
        // setPrice();
        $(".bx-soa-customer-test div[data-property-id-row~='7']").append("<div id='soa-property-7-value'>"+ DESTINATION_STRING + "</div>");
        // $(".bx-soa-mainbar").append(
        //     "<div id='delivery-map-wrapper' style='display: none;'>" +
        //     "<div id='delivery-map' style='height: 400px;'></div>" +
        //     "<div id='delivery-map-apply'>Применить</div>" +
        //     "</div>"
        // );
        $(".bx-soa-customer-test div[data-property-id-row~='7']").append("" +
            "<div id='delivery-map-wrapper'>" +
            "<div id='delivery-map' style='height: 400px;'></div>" +
            // "<div id='delivery-map-apply'>Доставить по этому адресу</div>" +
            "</div>");

        // $("input[name~='DELIVERY_EXTRA_SERVICES[20][15]']").click(function () {
        //     $("#delivery-map-wrapper").show();
        // });
        // $("#delivery-map-wrapper").hide();
        // $("#delivery-map-wrapper").hide();
        // $("#soa-property-7-value").click(function () {
        //     $("#soa-property-7-value").hide();
        //     $("#delivery-map-wrapper").show();
        // });
        // $("#delivery-map-wrapper").show();
        // $("#delivery-map-apply").click(function () {
        //     // $("#delivery-map-wrapper").hide();
        //     // $("#soa-property-7-value").show();
        //     setPrice();
        // });
        ymaps.ready(init);
    }
}

function init() {
    getCartInfo();
    var myMap = new ymaps.Map('delivery-map', {
            center: ORDER_AJAX_DELIVERY_MAP.MAP_CENTER,
            // zoom: ORDER_AJAX_DELIVERY_MAP.MAP_ZOOM,
            zoom: "10",
            controls: ['trafficControl'],
        }),

        // Создадим панель маршрутизации.
        routePanelControl = new ymaps.control.RoutePanel({
            options: {
                maxWidth: "400px",
                // Добавим заголовок панели.
                showHeader: false,
                title: 'Расчёт доставки',
            }
        }),
        // Элемент управленяи зумом
        zoomControl = new ymaps.control.ZoomControl({
            options: {
                size: 'small',
                float: 'none',
                position: {
                    bottom: 145,
                    right: 10
                }
            }
        });
    // Получим доступ к элементу управления.
    control = myMap.controls.get('trafficControl');
    // Покажем пробки на карте.
    control.showTraffic();
    // Добавляем полигон
    var myPolygon = new ymaps.Polygon(
            [ORDER_AJAX_DELIVERY_MAP.MAP_POLYGON],{},
            {
                fillColor: '#ef7f1a',
                strokeColor: '#ef7f1a',
                opacity: 0.3
            }
        );
    // Делаем полигон видимым
    myPolygon.options.set('visible', true);
    // Добавялем полигон на карту
    myMap.geoObjects.add(myPolygon);

    // Пользователь сможет построить только автомобильный маршрут.
    routePanelControl.routePanel.options.set({
        types: {auto: true},
        avoidTrafficJams: true,
        autofocus: false,
        allowSwitch: false,
    });
    // Неизменная точка откуда
    routePanelControl.routePanel.state.set({
        fromEnabled: false,
        from: ORDER_AJAX_DELIVERY_MAP.MAP_FROM,
        to: DESTINATION_STRING,
    });
    // Добавляем на карту управление зумом
    myMap.controls.add(routePanelControl).add(zoomControl);
    // Получим ссылку на маршрут.
    routePanelControl.routePanel.getRouteAsync().then(function (route) {
        // Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
        route.model.setParams({results: 1}, true);
        // Повесим обработчик на событие построения маршрута.
        route.model.events.add('requestsuccess', function () {
            var activeRoute = route.getActiveRoute();
            var destinationCoordinate = route.getWayPoints().get(1).properties.get("coordinates")
            DESTINATION_COORDINATE = [destinationCoordinate[1],destinationCoordinate[0]];
            DESTINATION_STRING = route.getWayPoints().get(1).properties.get("address");
            // console.log(route.getWayPoints().get(1).properties);
            if (activeRoute) {
                var segments = ymaps.geoQuery(activeRoute.getPaths().get(0).getSegments());
                var allPoints = [];
                segments.each(function (segment) {
                    var coordinates = segment.geometry.getCoordinates();
                    $.merge(allPoints, coordinates);
                });
                var startPoint = null;
                var stopPoint = null;
                var wayOutside = 0;
                var wayOutsideKm = 0;
                var allPointsLength = allPoints.length;
                $.each(allPoints, function(index, point ) {
                    // если встречаем первую точку за границей то добавляем ее в маршрут
                    if(startPoint == null && !myPolygon.geometry.contains(point)) {
                        startPoint = allPoints[index-1];
                    }
                    if(!myPolygon.geometry.contains(point)) {
                        stopPoint = point;
                    }
                    if(
                        startPoint != null &&
                        stopPoint != null &&
                        (
                            myPolygon.geometry.contains(point) ||
                            index + 1 == allPoints.length
                        )
                    ) {
                        ymaps.route([startPoint,stopPoint], {
                            avoidTrafficJams: true
                        }).then(
                            function (thisRoute) {
                                var returnVal = thisRoute.getLength();
                                wayOutside += returnVal;
                                wayOutsideKm = Math.round(wayOutside / 1000);
                                if(allPointsLength == index+1) {
                                    var length = route.getActiveRoute().properties.get("distance");
                                    var allKm = Math.round(length.value / 1000);
                                    var price = calculate(wayOutsideKm, allKm),
                                        balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                                            '<span>Расстояние: ' + allKm + ' км.</span><br/>' +
                                            '<span style="font-weight: bold; font-style: italic">Стоимость доставки: <span class="mapBalloonPrice">' + price + '</span> р.</span>'
                                        );
                                    route.options.set('routeBalloonContentLayout', balloonContentLayout);
                                    activeRoute.balloon.open();
                                    var data = [];
                                    data["itCity"] = false;
                                    BX.ajax({
                                        url: "/bitrix/templates/elektro_flat_APLS/components/bitrix/sale.order.ajax/.default/ya.map.calc/ajax/itCity.php",
                                        data: data,
                                        method: 'POST',
                                        dataType: 'HTML',
                                        onsuccess: function (result) {
                                        }
                                    });
                                    // BX.Sale.OrderAjaxComponent.result.IT_SITY = false;
                                    setPrice();
                                }
                            },
                            function (error) {
                                alert("Возникла ошибка: " + error.message);
                            }
                        );
                        startPoint = null;
                        stopPoint = null;
                    } else {
                        if(allPointsLength == index+1) {
                            var length = route.getActiveRoute().properties.get("distance");
                            var allKm = Math.round(length.value / 1000);
                            var price = calculate(wayOutsideKm, allKm),
                                balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                                    '<span>Расстояние: ' + allKm + ' км.</span><br/>' +
                                    '<span style="font-weight: bold; font-style: italic">Стоимость доставки: <span class="mapBalloonPrice">' + price + '</span> р.</span>'
                                );
                            route.options.set('routeBalloonContentLayout', balloonContentLayout);
                            activeRoute.balloon.open();
                            var data = [];
                            data["itCity"] = true;
                            BX.ajax({
                                url: "/bitrix/templates/elektro_flat_APLS/components/bitrix/sale.order.ajax/.default/ya.map.calc/ajax/itCity.php",
                                data: data,
                                method: 'POST',
                                dataType: 'HTML',
                                onsuccess: function (result) {
                                }
                            });
                            // BX.Sale.OrderAjaxComponent.result.IT_SITY = true;
                            if(ORDER_AJAX_DELIVERY_MAP.CITY_FREE_DELIVERY_PRICE) {
                                var atr = {
                                    "allKm" : allKm,
                                    "route" : route,
                                };
                                getPropertyValues(['0685b3bb-d2bc-46ab-a5db-2507c9eb8324'],freeDeliveryCallback, atr);
                            } else {
                                setPrice();
                            }
                        }
                    }
                });
            }
        });
    });

    // Функция, вычисляющая стоимость доставки.
    function calculate(outsideKm, allKm) {
        console.log("outsideKm",outsideKm);
        console.log("allKm",allKm);
        var price = 0;
        if(outsideKm == 0) {
            price = calculateCityPrice(allKm);
        } else {
            price = calculateOutsidePrice(outsideKm, allKm);
        }
        price = changePrice(price);
        DELIVERY_PRICE = price;
        BX.Sale.OrderAjaxComponent.options.totalPriceChanged = true;
        return price;
    }

    function changePrice(price) {
        return price;
    }

    function calculateOutsidePrice(km,allKm) {
        return calculatePrice(
            km,
            allKm,
            ORDER_AJAX_DELIVERY_MAP.OUTSIDE_MIN_PRICE,
            ORDER_AJAX_DELIVERY_MAP.OUTSIDE_MAX_PRICE,
            ORDER_AJAX_DELIVERY_MAP.OUTSIDE_CONDITIONS
        );
    }

    function calculateCityPrice(km) {
        return calculatePrice(
            km,
            km,
            ORDER_AJAX_DELIVERY_MAP.CITY_MIN_PRICE,
            ORDER_AJAX_DELIVERY_MAP.CITY_MAX_PRICE,
            ORDER_AJAX_DELIVERY_MAP.CITY_CONDITIONS
        );
    }

    function calculatePrice(km, allKm, minPrice, maxPrice, conditions) {
        if(km == allKm && BX.Sale.OrderAjaxComponent.result.TOTAL.ORDER_PRICE <= ORDER_AJAX_DELIVERY_MAP.CITY_PROMO_LIMIT_ORDER_COST) {
            minPrice = ORDER_AJAX_DELIVERY_MAP.CITY_PROMO_PRICE;
        }
        var price = minPrice; // устанавливаем минимальную стоимость
        // перебираем условия стоимости
        $.each(conditions, function (key, condition) {
            // проверяем на обязательыне значения условия
            if(
                typeof(condition.KM_MIN) != "undefined" && typeof(condition.KM_MAX) != "undefined" &&
                typeof(condition.KM_PRICE) != "undefined" && typeof(condition.FIX_PRICE) != "undefined" &&
                typeof(condition.FULL_PATH_CALC) != "undefined"
            ) {
                if(
                    (
                        condition.KM_MAX >= condition.KM_MIN &&
                        condition.KM_MAX && km >= condition.KM_MIN &&
                        km <= condition.KM_MAX
                    ) || (
                        km >= condition.KM_MIN
                    )
                ) {
                    if(condition.KM_PRICE) { // если стоимость за киллометраж
                        if(condition.FULL_PATH_CALC) { // считаем киллометраж по полному пути
                            price = condition.FIX_PRICE + (allKm * condition.KM_PRICE);
                        } else { // если считаем киллометраж по доп киллометрам
                            price = condition.FIX_PRICE + ((km + 1 - condition.KM_MIN) * condition.KM_PRICE);
                        }
                    } else { // если стоимость фиксированная
                        price = condition.FIX_PRICE;
                    }
                }
            }
        });
        // проверяем не привышает ли текущая стоимость максимума
        if(maxPrice && price > maxPrice) {
            // если привышает устаналваием стоимость как максимально доступную
            price = maxPrice;
        }
        return price;
    }
}


function setPrice() {
    $("#soa-property-7").val(DESTINATION_STRING);
    $("#soa-property-7-value").html(DESTINATION_STRING);
    // console.log(DELIVERY_PRICE);
    var oldPrice = $("input[name~='DELIVERY_EXTRA_SERVICES[20][15]']").val();
    if(oldPrice != DELIVERY_PRICE) {
        $("input[name~='DELIVERY_EXTRA_SERVICES[20][15]']").val(DELIVERY_PRICE);
        var event = new Event('change');
        $("input[name~='DELIVERY_EXTRA_SERVICES[20][15]']")[0].dispatchEvent(event);
    }
}

function freeDeliveryCallback(result, atr) {
    var freeDeliveryItem = [];
    var notFreeDeliveryItem = [];
    var counterFree = 0;
    var counterNotFree = 0;
    var freeDelivery = false;
    $.each(result.values, function (property,value) {
        $.each(value,function (key, val) {
            if(val == "Да") {
                freeDeliveryItem[counterFree++] = key;
            } else {
                notFreeDeliveryItem[counterNotFree++] = key;
            }
        });
    });
    if(freeDeliveryItem.length > 0 && freeDeliveryItem.length >= notFreeDeliveryItem.length) {
        freeDelivery = true;
        if(BX.Sale.OrderAjaxComponent.result.TOTAL.ORDER_PRICE >= ORDER_AJAX_DELIVERY_MAP.CITY_FREE_DELIVERY_LIMIT_ORDER_COST) {
            DELIVERY_PRICE = 0;
        } else {
            DELIVERY_PRICE = ORDER_AJAX_DELIVERY_MAP.CITY_FREE_DELIVERY_PRICE;
        }
    }
    // $('.mapBalloonPrice').html(DELIVERY_PRICE);
    console.log(atr);
    var CITY_FREE_DELIVERY_TEXT = "";
    if(freeDelivery) {
        CITY_FREE_DELIVERY_TEXT = '<br/><span style="font-weight: bold; font-style: italic">' + ORDER_AJAX_DELIVERY_MAP.CITY_FREE_DELIVERY_TEXT + '</span>';
    }
    var balloonContentLayout = ymaps.templateLayoutFactory.createClass(
        '<span>Расстояние: ' + atr.allKm + ' км.</span><br/>' +
        '<span style="font-weight: bold; font-style: italic">Стоимость доставки: <span class="mapBalloonPrice">' + DELIVERY_PRICE + '</span> р.</span>' +
        CITY_FREE_DELIVERY_TEXT
    );
    atr.route.options.set('routeBalloonContentLayout', balloonContentLayout);
    setPrice();
}


function getPropertyValues(array, callback, atr) {
    var data = [];
    var counter = 1;
    array.forEach(function (item) {
        data["item_"+counter] = item;
        counter++;
    });
    return BX.ajax({
        url: "/bitrix/templates/elektro_flat_APLS/components/bitrix/sale.order.ajax/.default/ya.map.calc/ajax/getPropertyValues.php",
        data: data,
        method: 'POST',
        dataType: 'JSON',
        onsuccess: function (result) {
            callback(result, atr);
        }
    });
}

function getCartInfo() {
    return BX.ajax({
        url: "/bitrix/templates/elektro_flat_APLS/components/bitrix/sale.order.ajax/.default/ya.map.calc/ajax/getCartInfo.php",
        // data: data,
        method: 'POST',
        dataType: 'JSON',
        onsuccess: function (result) {
            CART_DATA = result;
        }
    })
}