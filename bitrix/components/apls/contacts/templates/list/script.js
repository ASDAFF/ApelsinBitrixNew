function ContactsTemplateFolder() {
    return $('.contacts_wrapper').attr('templatefolder');
}

$(document).ready(function () {
    var checkMap = false;
    var checkList = true;

    ContactsSetting($('.contacts_edit_list'));
    APLS_contacts_show_region($('.APLS_contacts_regions_block').first());

    $('.APLS_contacts_regions_block').click(function () {
        APLS_contacts_show_region(this);
    });

    $('.contacts_settings_map').click(function () {
        getRegionAjax();
        if (checkList) {
            $(this).addClass('activeSetting');
            $('.contacts_edit_list').removeClass('activeSetting');
            $('.cities_list').hide();
            $('.contacts_map').show();
            checkList = false;
            checkMap = true;
        }
    });

    $('.contacts_edit_list').click(function () {
        if (checkMap) {
            $(this).addClass('activeSetting');
            $('.contacts_settings_map').removeClass('activeSetting');
            $('.contacts_map').hide();
            $('.cities_list').show();
            checkMap = false;
            checkList = true;
        }
    });

    $('.shop_element_feedback_map_scheme').click(function () {
        var shopId = $(this).attr('shopid');
        getShopAjax(shopId);
        $('.contacts_edit_list').removeClass('activeSetting');
        $('.contacts_settings_map').addClass('activeSetting');
        $('.cities_list').hide();
        $('.contacts_map').show();
        checkList = false;
        checkMap = true;
    });

    function APLS_contacts_show_region(obj) {
        var regionID = $(obj).attr("regionid");
        $(".APLS_contacts_regions_block").removeClass("ActiveBlockRegions");
        $(obj).addClass("ActiveBlockRegions");
        $('.shop_element').hide();
        $("[regionid = " + regionID + "]").show();
        getRegionAjax();
    }

    function ContactsSetting(obj) {
        $('.contact_settings').children().removeClass('activeSetting');
        $(obj).addClass('activeSetting');

    }

    function getRegionAjax() {
        var data = [];
        data["templateFolder"] = ContactsTemplateFolder();
        data["regionHLName"] = $('.contacts_wrapper').attr('highload_region_name');
        data["shopHLName"] = $('.contacts_wrapper').attr('highload_shops_name');
        data["regionId"] = $('.ActiveBlockRegions').attr('regionid');
        BX.ajax({
            url: data["templateFolder"] + "/ajax/InsertRegionMap.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (data) {
                // $('.contacts_map').html(data);
            }
        });
    }
    
    function getShopAjax(shopId) {
        var data = [];
        data["templateFolder"] = ContactsTemplateFolder();
        data["regionHLName"] = $('.contacts_wrapper').attr('highload_region_name');
        data["shopHLName"] = $('.contacts_wrapper').attr('highload_shops_name');
        data["shopID"] = shopId;
        BX.ajax({
            url: data["templateFolder"] + "/ajax/InsertRegionMap.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (data) {
                // $('.contacts_map').html(data);
            }
        });
    }

    $('.shop_element').mouseenter(function () {
        $(this).find('.shop_element_title').css('color', '#ef7f1a');
        $(this).find('.APLS_contacts_buildings_time_Y').css('color', '#189900');
        $(this).find('.APLS_contacts_buildings_time_N').css('color', '#ef2b29');
    });

    $('.shop_element').mouseleave(function () {
        $(this).find('.shop_element_title').css('color', '#8184a1');
        $(this).find('.APLS_contacts_buildings_time_Y').css('color', '#8184a1');
        $(this).find('.APLS_contacts_buildings_time_N').css('color', '#8184a1');
    });

    /**
     * Вывод и отправка функционала "Написать управляющему магазина"
     */
    $('.shop_element_feedback_dir').click(function () {
        var data = [];
        data["templateFolder"] = ContactsTemplateFolder();
        data["shopName"] = $(this).attr('shopName');
        data['mail'] = $(this).attr('mail');
        BX.ajax({
            url: data["templateFolder"] + "/ajax/InputFeedbackForm.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (data) {
                $('.page-wrapper').append(data);
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
                                alert ('Спасибо за обращение!');
                                $('#feedback').remove();
                                $('#feedback_mail').remove();
                            }
                        }
                    });
                });
                $('.feedback_mail_close').click(function () {
                    $('#feedback').remove();
                    $('#feedback_mail').remove();
                });
            }
        });
    });

    /**
     * Вывод и отправка функционала "Карта магазина"
     */
    // $('feedback_shopmap_1').click(function () {
    //     var data = [];
    //     data["templateFolder"] = ContactsTemplateFolder();
    // });

});