
$(document).ready(function () {
	$('.personal_delivery .sale-profile-detail-form-btn button').click(function () {
		var data = [];
		data ["templateFolder"] = $('.personal_delivery .sale-profile-detail-block').attr('templateFolder');
		data ["user_prop_id"] = $('.personal_delivery .sale-profile-detail-block').attr('USER_PROPS_ID');
		$('.personal_delivery .sale-profile-detail-block .sale-profile-detail-form-property').each(function () {
			data[$(this).attr('name')] = {'order_prop_id':$(this).attr('id'),'value':$(this).find('input').val()};
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
});