// $(document).ready(function () {
//     addUserCardInfo();
// });

function addUserCardInfo() {
    if(USER_DATA_INFO.REGISTERED) {
        var html = "";
        if(USER_DATA_INFO.UF_CARD_NUMBER == "") {
            var html =
                "<div id='user_card_info'>" +
                USER_DATA_INFO.LAST_NAME + " " +
                USER_DATA_INFO.NAME +
                ",<br>для оформления заказа со скидкой, <a href='/personal/private/'>укажите</a> дисконтную карту." +
                // ",<br>вы можете <b>привязать свою карту</b> в <a href='/personal/private/'>личном кабинете</a>, чтобы оформить заказ со скидкой." +
                "</div>";
        } else {
            var html =
                "<div id='user_card_info'>" +
                USER_DATA_INFO.LAST_NAME + " " +
                USER_DATA_INFO.NAME +
                ",<br>у вас привязана карта<br>№: <b>" +
                USER_DATA_INFO.UF_CARD_NUMBER +
                "</b><br>Тип цен: <b>" +
                USER_DATA_INFO.PRICE_TYPE +
                "</b></div>";
        }
        $(".bx-soa-cart-total").append(html);
    }
}