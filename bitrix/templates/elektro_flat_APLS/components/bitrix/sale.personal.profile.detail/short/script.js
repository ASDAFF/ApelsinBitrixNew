
$(document).ready(function () {
	$('.personal_delivery .sale-profile-detail-form-btn button').click(function () {
		var data = [];
		data ["templateFolder"] = $('.personal_delivery .sale-profile-detail-block').attr('templateFolder');
		data ["user_prop_id"] = $('.personal_delivery .sale-profile-detail-block').attr('USER_PROPS_ID');
		$('.personal_delivery .sale-profile-detail-block .sale-profile-detail-form-property').each(function () {
		    if ($(this).attr('name') == 'Контактный телефон') {
		        var newVal;
		        newVal = $(this).find('input').val().replace(/ /g,'');
		        newVal = newVal.replace('(','');
		        newVal = newVal.replace(')','');
		        newVal = newVal.replace(/-/g,'');
                data[$(this).attr('name')] = {'order_prop_id':$(this).attr('id'),'value':newVal};
            } else {
                data[$(this).attr('name')] = {'order_prop_id':$(this).attr('id'),'value':$(this).find('input').val()};
            }
        });
		BX.ajax({
            url: data["templateFolder"] + "/ajax.php",
            data: data,
            method: 'POST',
            dataType: 'html',
            onsuccess: function (rezult) {
                $('.rezultBlock').html(rezult);
            },
        });
    });
	$('input').each(function () {
        if ($(this).parent().attr('name') == 'Контактный телефон') {
            Inputmask('8 ([9]{3}) [9]{3}-[9]{2}-[9]{2}').mask($(this));
        }
    });
});