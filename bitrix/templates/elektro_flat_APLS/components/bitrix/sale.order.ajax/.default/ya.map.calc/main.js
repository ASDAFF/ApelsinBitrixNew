var DELIVERY_PRICE = 0;
var DESTINATION_COORDINATE = "";
var DESTINATION_STRING = "";
var TEST = 0;

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
    // Стоимость за километр.
    var DELIVERY_TARIFF = ORDER_AJAX_DELIVERY_MAP.DELIVERY_TARIFF,
        // цена по городу
        CITY_COST = ORDER_AJAX_DELIVERY_MAP.CITY_COST,
        // цена за городом
        WAY_OUTSIDE_COST = ORDER_AJAX_DELIVERY_MAP.WAY_OUTSIDE_COST,
        // Включенный трафик за городом.
        MINIMUM_OUTSIDE = ORDER_AJAX_DELIVERY_MAP.MINIMUM_OUTSIDE,
        // Минимальная стоимость.
        MINIMUM_COST = ORDER_AJAX_DELIVERY_MAP.MINIMUM_COST,
        // Основная карта
        myMap = new ymaps.Map('delivery-map', {
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
                title: 'Расчёт доставки'
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
    var myPolygon = new ymaps.Polygon([ORDER_AJAX_DELIVERY_MAP.MAP_POLYGON]);
    // Делаем полигон видимым
    myPolygon.options.set('visible', true);
    // Добавялем полигон на карту
    myMap.geoObjects.add(myPolygon);

    // Пользователь сможет построить только автомобильный маршрут.
    routePanelControl.routePanel.options.set({
        types: {auto: true},
        avoidTrafficJams: true
    });
    // Неизменная точка откуда
    // console.log(DESTINATION_STRING);
    routePanelControl.routePanel.state.set({
        fromEnabled: false,
        from: ORDER_AJAX_DELIVERY_MAP.MAP_FROM,
        to: DESTINATION_STRING,
        // to: 'Россия, Рязань, улица Есенина, 13'
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
                                    var price = calculate(wayOutsideKm),
                                        balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                                            '<span>Расстояние: ' + length.text + '.</span><br/>' +
                                            '<span>Расстояние за городом: ' + wayOutsideKm + 'км.</span><br/>' +
                                            '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>'
                                        );
                                    route.options.set('routeBalloonContentLayout', balloonContentLayout);
                                    activeRoute.balloon.open();
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
                            var price = calculate(wayOutsideKm),
                                balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                                    '<span>Расстояние: ' + length.text + '.</span><br/>' +
                                    '<span>Расстояние за городом: ' + wayOutsideKm + 'км.</span><br/>' +
                                    '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>'
                                );
                            route.options.set('routeBalloonContentLayout', balloonContentLayout);
                            activeRoute.balloon.open();
                            setPrice();
                        }
                    }
                });
                // // Получим протяженность маршрута.
                // var length = route.getActiveRoute().properties.get("distance"),
                //     // Вычислим стоимость доставки.
                //     price = calculate(wayOutside),
                //     // Создадим макет содержимого балуна маршрута.
                //     balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                //         '<span>Расстояние: ' + length.text + '.</span><br/>' +
                //         '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>'
                //     );
                // // Зададим этот макет для содержимого балуна.
                // route.options.set('routeBalloonContentLayout', balloonContentLayout);
                // // Откроем балун.
                // activeRoute.balloon.open();
            }
        });
        // $("#delivery-map-wrapper").hide();
    });

    // Функция, вычисляющая стоимость доставки.
    function calculate(outsideLength) {
        console.log("outsideLength",outsideLength);
        var price = 0;
        if(outsideLength == 0) {
            price = CITY_COST;
        } else if (outsideLength <= MINIMUM_OUTSIDE) {
            price = WAY_OUTSIDE_COST;
        } else {
            price = Math.max(WAY_OUTSIDE_COST + ((outsideLength - MINIMUM_OUTSIDE) * DELIVERY_TARIFF), MINIMUM_COST);
        }
        DELIVERY_PRICE = price;
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