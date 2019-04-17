$(document).ready(function () {
    getMap();
    changeTypeList();
    getBigImage();

    function getMap() {
        var regionCenter = [];
        regionCenter [0] = $(".contacts_wrapper").attr("longitude");
        regionCenter [1] = $(".contacts_wrapper").attr("latitude");
        var regionZoom = $(".contacts_wrapper").attr("zoom");
        var shopsCoords = '{';
        $('.contactsShopElement').each(function () {
            shopsCoords += '"' + $(this).attr("id") + '": {"name":"' + $(this).find('.contactsShopElement_name_header').text() + '", "address":"' + $(this).find('.contactsShopElement_name_address').text() + '", "long":"' + $(this).attr("longitude") + '", "lat":"' + $(this).attr("latitude") + '"},';
        });
        shopsCoords = shopsCoords.substr(0, shopsCoords.length - 1) + "}";

        ymaps.ready(init);
        var myMap, myPlacemark;
        var folder = $('.contacts_wrapper').attr('templatefolder');
        function init() {
            myMap = new ymaps.Map("ymap_contacts", {
                center: regionCenter,
                zoom: regionZoom
            });
            myCollection = new ymaps.GeoObjectCollection({}, {});
            var counter = 0;
            $.each(JSON.parse(shopsCoords), function (key, value) {
                var coords = [];
                coords[counter] = value.long;
                coords[counter+1] = value.lat;
                myCollection.add(new ymaps.Placemark(coords,{
                    hintContent:value.name,
                    balloonContent:value.address
                }, {
                    iconLayout: 'default#image',
                    iconImageHref: folder+'/icon/label.svg',
                    iconImageSize: [40, 52]
                }));
            });
            myMap.geoObjects.add(myCollection);
        }

        $('.shop_element_feedback_map_scheme').click(function () {
            var parantElement = $(this).closest('.contactsShopElement');
            var coords = [];
            coords[0] = parantElement.attr('longitude');
            coords[1] = parantElement.attr('latitude');
            var zoom = parantElement.attr('zoom');
            if (myMap) {
                myMap.setCenter(coords,zoom,{
                    checkZoomRange: true
                });
            }
            $('.contacts_settings').removeClass('active');
            $('#mapBlock').addClass('active');
            $('.contactsShopTable').hide();
            $('#ymap_contacts').show();
        });

        $('.contacts_settings_map').click(function () {
            var regionCenter = [];
            regionCenter [0] = $(".contacts_wrapper").attr("longitude");
            regionCenter [1] = $(".contacts_wrapper").attr("latitude");
            var regionZoom = $(".contacts_wrapper").attr("zoom");
            if (myMap) {
                myMap.setCenter(regionCenter,regionZoom,{
                    checkZoomRange: true
                });
            }
        });
    }
    
    function changeTypeList() {
        $('.contacts_settings').click(function () {
            $('.contacts_settings').removeClass('active');
            $(this).addClass('active');
            var thisId = $(this).attr('id');
            var subId = $(this).siblings('.contacts_settings').attr('id');
            console.log(thisId+' - '+subId);
            $('.'+subId).hide();
            $('.'+thisId).show();
        });
    }
    
    function getBigImage() {
        $('.contactsShopElement_img').click(function () {
            var data = [];
            data["templateFolder"] = $('.contacts_wrapper').attr('templatefolder');
            data["bigImg"] = $(this).attr('bigimg');
            BX.ajax({
                url: data["templateFolder"] + "/ajax/GetBigImage.php",
                data: data,
                method: 'POST',
                dataType: 'html',
                onsuccess: function (data) {
                    $('.page-wrapper').prepend(data);
                    $('body, html').css('overflow','hidden');

                    $('.feedback_mail_close').click(function () {
                        $('#feedback').remove();
                        $('#feedback_mail').remove();
                        $('body, html').css('overflow','auto');
                    });
                }
            });
        });
    }

    /**
     * Вывод и отправка функционала "Написать управляющему магазина"
     */
    $('.shop_element_feedback_dir').click(function () {
        var data = [];
        data["templateFolder"] = $('.contacts_wrapper').attr('templatefolder');
        data["shopName"] = $(this).attr('shopName');
        data['mail'] = $(this).attr('mail');
        console.log(data);
        BX.ajax({
            url: data["templateFolder"] + "/ajax/InputFeedbackForm.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (data) {
                $('.page-wrapper').prepend(data);
                $('body, html').css('overflow','hidden');
                var feedbackData = [];
                $('#feedback_button').click(function () {
                    feedbackData["templateFolder"] = ContactsTemplateFolder();
                    feedbackData['formName'] = $('.feedback_mail_name_text input').val();
                    feedbackData['formPhone'] = $('.feedback_mail_phone_text input').val();
                    feedbackData['formText'] = $('.feedback_mail_text_text textarea').val();
                    feedbackData["shopName"] = $(this).attr('shopName');
                    feedbackData["mail"] = $(this).attr('mail');
                    feedbackData['formAgreement'] = $('.feedback_mail_agreement_checkbox input').is(':checked');
                    BX.ajax({
                        url: feedbackData["templateFolder"] + "/ajax/SendFeedbackForm.php",
                        data: feedbackData,
                        method: 'POST',
                        dataType: 'JSON',
                        onsuccess: function (feedbackData) {
                            if (feedbackData.success.close !== 'true') {
                                alert('Ошибка отправки');
                                $('.feedback_mail_name').before(feedbackData.success.error);
                                $('#feedback').remove();
                                $('#feedback_mail').remove();
                            } else {
                                $('#feedback').remove();
                                $('#feedback_mail').remove();
                                $('body, html').css('overflow','auto');
                            }
                        }
                    });
                });
                $('.feedback_mail_close').click(function () {
                    $('#feedback').remove();
                    $('#feedback_mail').remove();
                    $('body, html').css('overflow','auto');
                });
            }
        });
    });
});
