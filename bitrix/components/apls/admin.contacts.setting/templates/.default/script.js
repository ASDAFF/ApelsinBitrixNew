$(document).ready(function () {
    swapShopList();
    getAddShopForm();
    deleteShop();
    updateShop();
    drag_n_drop();
    getChangeRegionsCoords();

    function swapShopList() {
        $('.header-swap').click(function () {
            var cheker = $(this).find('.fa-minus').css('display');
            var parentId = $(this).parent('.contactsSortListElement').attr('regionid');
            if (cheker == 'none') {
                $(this).find('.fa-minus').css('display', 'flex');
                $(this).find('.fa-plus').css('display', 'none');
                $('#' + parentId).css('display', 'block');
            } else {
                $(this).find('.fa-minus').css('display', 'none');
                $(this).find('.fa-plus').css('display', 'flex');
                $('#' + parentId).css('display', 'none');
            }
        });
    }

    function getAddShopForm() {
        $('.addShop').click(function () {
            var data = [];
            data['regionId'] = $(this).parent('.shopsSortList').attr('id');
            data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
            BX.ajax({
                url: data["templateFolder"] + "/ajax/action/getAddShopForm.php",
                data: data,
                method: 'POST',
                dataType: 'json',
                onsuccess: function (rezult) {
                    $('.' + rezult.success.regionId + ' .addShop').css('display', 'none');
                    $('.' + rezult.success.regionId + ' .shopsSortList').prepend(rezult.success.html);
                    $('.addShopBlock .shopElementBtnSave').click(function () {
                        var data = [];
                        data['regionId'] = $('.addShopBlock').attr('redionid');
                        data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                        data['name'] = $('.addShopBlock .shop_element_title input').val();
                        data['address'] = $('.addShopBlock .shop_element_address input').val();
                        data['mail'] = $('.addShopBlock .shop_element_mail input').val();
                        data['phone1'] = $('.addShopBlock .shop_element_phone1 input').val();
                        data['addphone1'] = $('.addShopBlock .shop_element_addphone1 input').val();
                        data['phone2'] = $('.addShopBlock .shop_element_phone2 input').val();
                        data['addphone2'] = $('.addShopBlock .shop_element_addphone2 input').val();
                        if ($('.addShopBlock .featureCred input').prop("checked")) {
                            data['featureCred'] = '1';
                        }
                        if ($('.addShopBlock .featureW-sale input').prop("checked")) {
                            data['featureW-sale'] = '1';
                        }
                        if ($('.addShopBlock .featurer-of_goods input').prop("checked")) {
                            data['featurer-of_goods'] = '1';
                        }
                        if ($('.addShopBlock .featureR-the_clock input').prop("checked")) {
                            data['featureR-the_clock'] = '1';
                        }
                        data['monStart'] = $('#monStart').val();
                        data['monStop'] = $('#monStop').val();
                        data['tueStart'] = $('#tueStart').val();
                        data['tueStop'] = $('#tueStop').val();
                        data['wedStart'] = $('#wedStart').val();
                        data['wedStop'] = $('#wedStop').val();
                        data['thuStart'] = $('#thuStart').val();
                        data['thuStop'] = $('#thuStop').val();
                        data['friStart'] = $('#friStart').val();
                        data['friStop'] = $('#friStop').val();
                        data['satStart'] = $('#satStart').val();
                        data['satStop'] = $('#satStop').val();
                        data['sunStart'] = $('#sunStart').val();
                        data['sunStop'] = $('#sunStop').val();
                        data['lond'] = $('#long').val();
                        data['lat'] = $('#lat').val();
                        data['zoom'] = $('#zoom').val();
                        BX.ajax({
                            url: data["templateFolder"] + "/ajax/ui/addNewShop.php",
                            data: data,
                            method: 'POST',
                            dataType: 'json',
                            onsuccess: function (rezult) {
                                if (rezult.success.error == 'true') {
                                    alert('Не указан короткий адрес магазина');
                                } else {
                                    $('.addShopBlock').siblings('.addShop').css('display', 'block');
                                    $('.addShopBlock').remove();
                                    $('#' + rezult.success.regionId).append(rezult.success.html);
                                    alert('Магазин успешно добавлен');
                                }
                            },
                            onfailure: function (rezult) {
                                alert("Ошибка: addNewShop.php");
                            },
                        });
                    });
                    $('.addShopBlock .shopElementBtnCancel').click(function () {
                        $('.addShopBlock').siblings('.addShop').css('display', 'block');
                        $('.addShopBlock').remove();
                        updateShop();
                        deleteShop();
                    });
                },
                onfailure: function (rezult) {
                    alert("Ошибка: getAddShopForm.php)");
                },
            });
        });
    }

    function deleteShop() {
        $('.shopElementBtnDel').click(function () {
            if (confirm('Вы действительно хотите удалить контакты магазина?')) {
                var data = [];
                data['shopid'] = $(this).parents('.shopsSortListElement').attr('shopid');
                data['regionid'] = $(this).parents('.shopsSortList').attr('id');
                data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                BX.ajax({
                    url: data["templateFolder"] + "/ajax/ui/deleteShop.php",
                    data: data,
                    method: 'POST',
                    dataType: 'json',
                    onsuccess: function (rezult) {
                        $("." + rezult.success.shopId).remove();
                    },
                    onfailure: function (rezult) {
                        alert("Ошибка: deleteShop.php");
                    },
                });
            } else {
                alert('Осторожнее в следующий раз!');
            }
        });
    }

    function updateShop() {
        $('.shopElementBtnUpd').click(function () {
            var data = [];
            data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
            data['shopid'] = $(this).parents('.shopsSortListElement').attr('shopid');
            data['regionid'] = $(this).parents('.contactsCityElement .contactsSortListElement').attr('regionid');

            data['name'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_title').text();
            data['address'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_address').text();
            data['mail'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_mail').text();
            data['phone1'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_phone1').text();
            data['addphone1'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_addphone1 ').text();
            data['phone2'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_phone2').text();
            data['addphone2'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_addphone2').text();

            data['featureCred'] = $('.' + data['shopid'] + ' .shopElementFeature .featureCred').text();
            data['featureW-sale'] = $('.' + data['shopid'] + ' .shopElementFeature .featureW-sale').text();
            data['featurer-of_goods'] = $('.' + data['shopid'] + ' .shopElementFeature .featureR-of_goods').text();
            data['featureR-the_clock'] = $('.' + data['shopid'] + ' .shopElementFeature .featurer-the_clock').text();

            data['monStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockMon .elementTimeClockStart').text();
            data['monStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockMon .elementTimeClockStop').text();
            data['tueStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockTue .elementTimeClockStart').text();
            data['tueStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockTue .elementTimeClockStop').text();
            data['wedStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockWen .elementTimeClockStart').text();
            data['wedStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockWen .elementTimeClockStop').text();
            data['thuStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockThu .elementTimeClockStart').text();
            data['thuStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockThu .elementTimeClockStop').text();
            data['friStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockFri .elementTimeClockStart').text();
            data['friStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockFri .elementTimeClockStop').text();
            data['satStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSat .elementTimeClockStart').text();
            data['satStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSat .elementTimeClockStop').text();
            data['sunStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSun .elementTimeClockStart').text();
            data['sunStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSun .elementTimeClockStop').text();

            data['lond'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsLong .elementCoordsSetValue').text();
            data['lat'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsLat .elementCoordsSetValue').text();
            data['zoom'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsZoom .elementCoordsSetValue').text();
            data['b_img'] = $('.' + data['shopid'] + ' .shopElementImgs .shopElementB_Img').attr('imgValue');
            data['s_img'] = $('.' + data['shopid'] + ' .shopElementImgs .shopElementS_Img').attr('imgValue');
            console.log('123');
            BX.ajax({
                url: data["templateFolder"] + "/ajax/action/getUpdateShopForm.php",
                data: data,
                method: 'POST',
                dataType: 'json',
                onsuccess: function (rezult) {
                    $('.shopsSortListElement.' + rezult.success.shopid).addClass('addShopBlock');
                    $('.shopsSortListElement.' + rezult.success.shopid).addClass('updateShopBlock');
                    $('.shopsSortListElement.' + rezult.success.shopid).html(rezult.success.html);
                    $('.shop_element_img_save').click(function () {
                        var img_data = $(this).siblings('input').prop('files')[0];
                        if (img_data != undefined) {
                            var form_data = new FormData();
                            form_data.append('file', img_data);
                            form_data.append('shop_id', $(this).parents('.shopsSortListElement').attr('shopid'));
                            form_data.append('img_type', $(this).siblings('input').attr('imgType'));
                            var templateFolder = $('.AdminContactsWrapper').attr('templateFolder');
                            $.ajax({
                                url: templateFolder + "/ajax/ui/saveImages.php",
                                dataType: 'text',
                                cache: false,
                                contentType: false,
                                processData: false,
                                data: form_data,
                                type: 'post',
                                success: function (response) {
                                    alert("Картинка успешно добавлена с идентификатором " + response);
                                }
                            });
                        } else {
                            alert("Картинка не выбрана");
                        }
                    });
                    $('.updateShopBlock .shopElementBtnSave').click(function () {
                        var data = [];
                        data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                        data['shopid'] = $(this).parents('.shopsSortListElement').attr('shopid');
                        data['regionid'] = $(this).parents('.contactsCityElement .contactsSortListElement').attr('regionid');

                        data['name'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_title input').val();
                        data['address'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_address input').val();
                        data['mail'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_mail input').val();
                        data['phone1'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_phone1 input').val();
                        data['addphone1'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_addphone1 input').val();
                        data['phone2'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_phone2 input').val();
                        data['addphone2'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_addphone2 input').val();

                        if ($('.' + data['shopid'] + ' .shopElementFeature .featureCred input').prop("checked")) {
                            data['featureCred'] = '1';
                        } else {
                            data['featureCred'] = '0';
                        }
                        if ($('.' + data['shopid'] + ' .shopElementFeature .featureW-sale input').prop("checked")) {
                            data['featureW-sale'] = '1';
                        } else {
                            data['featureW-sale'] = '0';
                        }
                        if ($('.' + data['shopid'] + ' .shopElementFeature .featurer-of_goods input').prop("checked")) {
                            data['featurer-of_goods'] = '1';
                        } else {
                            data['featurer-of_goods'] = '0';
                        }
                        if ($('.' + data['shopid'] + ' .shopElementFeature .featureR-the_clock input').prop("checked")) {
                            data['featureR-the_clock'] = '1';
                        } else {
                            data['featureR-the_clock'] = '0';
                        }

                        data['monStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockMon .elementTimeClockStart input').val();
                        data['monStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockMon .elementTimeClockStop input').val();
                        data['tueStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockTue .elementTimeClockStart input').val();
                        data['tueStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockTue .elementTimeClockStop input').val();
                        data['wedStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockWed .elementTimeClockStart input').val();
                        data['wedStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockWed .elementTimeClockStop input').val();
                        data['thuStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockThu .elementTimeClockStart input').val();
                        data['thuStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockThu .elementTimeClockStop input').val();
                        data['friStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockFri .elementTimeClockStart input').val();
                        data['friStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockFri .elementTimeClockStop input').val();
                        data['satStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSat .elementTimeClockStart input').val();
                        data['satStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSat .elementTimeClockStop input').val();
                        data['sunStart'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSun .elementTimeClockStart input').val();
                        data['sunStop'] = $('.' + data['shopid'] + ' .shopElementTime .elementTimeClockSun .elementTimeClockStop input').val();
                        data['lond'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsLond .elementCoordsSetValue input').val();
                        data['lat'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsLat .elementCoordsSetValue input').val();
                        data['zoom'] = $('.' + data['shopid'] + ' .shopElementCoords .elementCoordsZoom .elementCoordsSetValue input').val();
                        BX.ajax({
                            url: data["templateFolder"] + "/ajax/ui/updateShop.php",
                            data: data,
                            method: 'POST',
                            dataType: 'json',
                            onsuccess: function (rezult) {
                                if (rezult.success.error == 'true') {
                                    alert('Не указан короткий адрес магазина');
                                } else {
                                    // $("#MainContactsWrapper").html("<pre>"+rezult.success.dump+"</pre>");
                                    $('.' + rezult.success.shopId).removeClass('addShopBlock');
                                    $('.' + rezult.success.shopId).removeClass('updateShopBlock');
                                    $('.' + rezult.success.shopId).html(rezult.success.html);
                                    updateShop();
                                    deleteShop();
                                }
                            },
                            onfailure: function (rezult) {
                                alert("Ошибка: updateShop-save.php");
                            },
                        });
                    });
                    $('.updateShopBlock .shopElementBtnCancel').click(function () {
                        var data = [];
                        data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                        data['shopid'] = $(this).parents('.shopsSortListElement').attr('shopid');
                        data['name'] = $('.' + data['shopid'] + ' .shopElementName .shop_element_title input').val();
                        BX.ajax({
                            url: data["templateFolder"] + "/ajax/action/cancelUpdateShop.php",
                            data: data,
                            method: 'POST',
                            dataType: 'json',
                            onsuccess: function (rezult) {
                                if (rezult.success.error == 'true') {
                                    alert('Не указан короткий адрес магазина');
                                } else {
                                    $('.' + rezult.success.shopId).empty();
                                    $('.' + rezult.success.shopId).removeClass('addShopBlock');
                                    $('.' + rezult.success.shopId).removeClass('updateShopBlock');
                                    $('.' + rezult.success.shopId).html(rezult.success.html);
                                    updateShop();
                                    deleteShop();
                                }
                            },
                            onfailure: function (rezult) {
                                alert("Ошибка: updateShop-cancel.php");
                            }
                        });
                    });
                },
                onfailure: function (rezult) {
                    alert("Ошибка: updateShop-form.php");
                },
            });
        });
    }

    function drag_n_drop() {
        var startSort = 100;
        $('.shopsSortListElement').each(function () {
            $(this).attr('sort', startSort);
            startSort = +startSort + 100;
        });

        $('.shopsSortListElement .sort-handle').mousedown(function () {
            var regionId = $(this).parents('.shopsSortList').attr('id');
            $('#' + regionId).sortable();
            $(this).mouseleave(function () {
                var startSort = 100;
                $('.shopsSortListElement').each(function () {
                    $(this).attr('sort', startSort);
                    startSort = +startSort + 100;
                });
                var updateData = '{';
                $('.shopsSortListElement').each(function () {
                    $('.shopsSortListElement').each(function () {
                        updateData += '"' + $(this).attr('shopid') + '":"' + $(this).attr('sort') + '",';
                    });
                });
                updateData = updateData.substring(0, updateData.length - 1) + '}';
                var data = [];
                data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                data['updateData'] = JSON.parse(updateData);
                BX.ajax({
                    url: data["templateFolder"] + "/ajax/ui/updateSortList.php",
                    data: data,
                    method: 'POST',
                    dataType: 'html',
                    onsuccess: function (rezult) {
                    }
                });
            });
        });
    }

    function getChangeRegionsCoords() {
        $('.regionChange').click(function () {
            var parentForm = $(this).closest('.contactsSortListElement');
            var data = [];
            data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
            data["longitudeValue"] = parentForm.find('.regionLongitude').attr('coordsValue');
            data["latitudeValue"] = parentForm.find('.regionLatitude').attr('coordsValue');
            data["zoomValue"] = parentForm.find('.regionZoom').attr('coordsValue');
            BX.ajax({
                url: data["templateFolder"] + "/ajax/action/addRegionCoords.php",
                data: data,
                method: 'POST',
                dataType: 'HTML',
                onsuccess: function (rezult) {
                    parentForm.find(".regionCoordsValues").html(rezult);
                    $('.regionSave').click(function () {
                        var newParentFormId = $(this).closest('.contactsSortListElement').attr('regionId');
                        var data = [];
                        data["templateFolder"] = $('.AdminContactsWrapper').attr('templateFolder');
                        data["regionId"] = newParentFormId;
                        if ($("#regionLongitudeValue").val() == "") {
                            data["longitudeValue"] = $("#regionLongitudeValue").attr('coordsValue');
                        } else {
                            data["longitudeValue"] = $("#regionLongitudeValue").val();
                        }
                        if ($("#regionLatitudeValue").val() == "") {
                            data["latitudeValue"] = $("#regionLatitudeValue").attr('coordsValue');
                        } else {
                            data["latitudeValue"] = $("#regionLatitudeValue").val();
                        }
                        if ($("#regionZoomValue").val() == "") {
                            data["regionZoom"] = $("#regionZoomValue").attr('coordsValue');
                        } else {
                            data["regionZoom"] = $("#regionZoomValue").val();
                        }
                        BX.ajax({
                            url: data["templateFolder"] + "/ajax/ui/updateRegionCoords.php",
                            data: data,
                            method: 'POST',
                            dataType: 'HTML',
                            onsuccess: function (rezult) {
                                $("." + newParentFormId + " .regionCoordsValues").html(rezult);
                                getChangeRegionsCoords();
                            }
                        });
                    });
                }
            });
        });
    }
});