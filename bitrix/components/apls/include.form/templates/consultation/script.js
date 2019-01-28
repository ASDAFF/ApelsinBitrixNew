$(document).ready(function () {
    $('.headerCallBack').click(function () {
        var data = [];
        data["templateFolder"] = $('.headerCallBack').attr('templateFolder');
        BX.ajax({
            url: data["templateFolder"] + "/ajax/action_getModal.php",
            data : data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (result) {
                $('body').prepend(result);
                window.scrollTo(0, 0);
                $('html').css('overflow','hidden');
                if ($("#callBack_Phone").attr('value') == '') {
                    Inputmask('+7 ([9]{3}) [9]{3}-[9]{2}-[9]{2}').mask($("#callBack_Phone"));
                }
                $("#callBack_Phone").click(function () {
                    var oldValue = $("#callBack_Phone").attr('value');
                    $("#callBack_Phone").attr('value','');
                    Inputmask('+7 ([9]{3}) [9]{3}-[9]{2}-[9]{2}').mask($("#callBack_Phone"));
                    $("#callBack_Phone").focusout(function () {
                        if ($("#callBack_Phone").val() == '') {
                            Inputmask('[9]{11}').mask($("#callBack_Phone"));
                            $("#callBack_Phone").attr('value',oldValue);
                        }
                    });
                });
                $('.callBackModal_content_btn').click(function () {
                    $('.callBackModal_content_input input').css("border-color", "#dee0ee");
                    var data = [];
                    data["templateFolder"] = $('.headerCallBack').attr('templateFolder');
                    data['name'] = $('#callBack_Name').val();
                    data['phone'] = $('#callBack_Phone').val();
                    data['email'] = $('#callBack_Email').val();
                    if (data['name'] == '') {
                        $('#callBack_Name').css("border", "2px solid #cc3333");
                    } else if (data['phone'] == '') {
                        $('#callBack_Phone').css("border", "2px solid #cc3333");
                    } else if ($('input[type=checkbox]').is(':checked') === false) {
                        alert('Не проставлен пункт о согласии на обработку персональных данных');
                    } else {
                        BX.ajax({
                            url: data["templateFolder"] + "/ajax/action_sendMail.php",
                            data : data,
                            method: 'POST',
                            dataType: 'html',
                            onsuccess: function (result) {
                                $('.callBackOverflow').remove();
                                $('.callBackModal').remove();
                                $('html').css('overflow','auto');
                            }
                        });
                    }
                });
                $('.callBackOverflow').click(function () {
                    $('.callBackOverflow').remove();
                    $('.callBackModal').remove();
                    $('html').css('overflow','auto');
                });
                $('.closeBtn').click(function () {
                    $('.callBackOverflow').remove();
                    $('.callBackModal').remove();
                    $('html').css('overflow','auto');
                });
            }
        });
    });
});