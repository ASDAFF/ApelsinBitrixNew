$(document).ready(function () {
    var searchRow = $('#pagetitle').html();
    $('.noSearch_result').html(searchRow.replace('Поиск: ',''));

    if ($("#noSearch_Phone").attr('value') == '') {
        Inputmask('+7 ([9]{3}) [9]{3}-[9]{2}-[9]{2}').mask($("#callBack_Phone"));
    }
    $("#noSearch_Phone").click(function () {
        var oldValue = $("#noSearch_Phone").attr('value');
        $("#noSearch_Phone").attr('value','');
        Inputmask('+7 ([9]{3}) [9]{3}-[9]{2}-[9]{2}').mask($("#noSearch_Phone"));
        $("#noSearch_Phone").focusout(function () {
            if ($("#noSearch_Phone").val() == '') {
                Inputmask('[9]{11}').mask($("#noSearch_Phone"));
                $("#noSearch_Phone").attr('value',oldValue);
            }
        });
    });

    $('.noSearch_btn').click(function () {
        var searchString = $('#pagetitle').html();
        var data = [];
        data['templateFolder'] = $('.noSearch_wrapper').attr('templateFolder');
        data['searchGood'] = searchString.replace('Поиск: ','');
        data['clientName'] = $('#noSearch_Name').val();
        data['clientPhone'] = $('#noSearch_Phone').val();
        data['comment'] = $('#noSearch_text').val();
        console.log(data);
        if (data['clientName'] == '') {
            $('#noSearch_Name').after('<div class="ErrMsg">Поле обязательно для заполнения</div>');
            $('#noSearch_Name').css('border-color','red');
        } else if (data['clientPhone'] == '') {
            $('#noSearch_Phone').after('<div class="ErrMsg">Поле обязательно для заполнения</div>');
            $('#noSearch_Phone').css('border-color','red');
        } else {
            BX.ajax({
                url: data["templateFolder"] + "/ajax/action_sendMail.php",
                data : data,
                method: 'POST',
                dataType: 'html',
                onsuccess: function (result) {
                    $('.noSearch_message').remove();
                    $('.noSearch_feedBack').remove();
                    $('.noSearch_btn').remove();
                    $('.noSearch_wrapper').html('<div class="successMsg">Спасибо за обращение, наши менеджеры скоро свяжутся с Вами!</div>');
                }
            });
        }
    });
});
